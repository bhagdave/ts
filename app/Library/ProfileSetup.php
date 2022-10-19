<?php

namespace App\Library;

use App\Jobs\AddNewAgentToExistingProperties;
use App\Stream;
use App\Properties;
use App\Tenant;
use App\Reminder;
use App\User;
use App\Issue;
use Szapatie\Activitylog\Models\Activity;

class ProfileSetup
{
    /*
     * Check which type of user to create instatiate the relevant class and fire
     * it off to create the profile
     */
    public function setupProfileForUser($user, $agentFromSession = null){
        if ($user->userType == "Agent") {
            (new AgentProfileSetup())->setupProfileForAgent($user, $agentFromSession);
        }

        if ($user->userType == "Landlord") {
            (new LandlordProfileSetup())->setupProfileForLandlord($user);
        }

        if ($user->userType == "Tenant") {
            (new TenantProfileSetup())->setupProfileForTenant($user);
        }

        if ($user->userType == "Contractor") {
            (new ContractorProfileSetup())->setupProfileForContractor($user);
        }
    }
    protected function createTestProperty($user, $agencyId = null){
        $newProperty = new Properties(
            [
            'propertyName' => 'Test Property',
            'propertyType' => 'Other',
            'broadcastOnly'=> FALSE,
            'inputAddress' => 'ATestAddress Road',
            'inputAddress2' => 'Somepartoftown',
            'inputCity' => 'SomeCity',
            'inputRegion' => 'England',
            'inputPostCode' => 'X00 0XX',
            'agent_id' => $agencyId ? $agencyId : 0,
            'created_by_user_id' => $user->sub,
            ]
        );
        $newProperty->save();
        $stream = $this->createStreamForTestProperty($newProperty->id);
        $newProperty->stream_id = $stream->id;
        $newProperty->save();
        return $newProperty;
    }
    private function createStreamForTestProperty($propertyId){
        $newStream = new Stream();
        $newStream->extra_attributes->broadcastOnly = 'false';
        $newStream->extra_attributes->property_id = $propertyId;
        $newStream->save();
        $this->createInitialStreamMessage($newStream->id, $propertyId);
        return $newStream;
    }
    private function createInitialStreamMessage($streamId, $propertyId){
        $message = "Welcome to your test property stream.  This is a place for you to communicate with everyone connected to the property. Add some test tenants to get started.";
        activity($streamId)
            ->causedBy(User::current()->sub)
            ->withProperties(['propertyId' => $propertyId, 'messageType' => 'Event' ])
            ->log($message);
    }

    protected function createTestTenant($property){
        $tenant = new Tenant([
            'name' => "The Test Tenant",
            'email' => "testtenant@example.com",
            'property_id' => $property->id,
            'phone' => '07777777777',
            'rentAmount' => '400',
            'notes' => 'A test tenant',
            'rentDueInterval' => 'Monthly',
            'moveInDate' => '2020-12-03'
        ]);
        $tenant->save();
        return $tenant;
    }

    protected function createTestIssue($property, $user){
        $issue = new Issue([
            'property_id' => $property->id,
            'description' => 'This is a test issue for you to amend and check and test.',
            'attributes' => 'Open',
            'creator_id' => $user->sub
        ]);
        $issue->save();
        return $issue;
    }

    protected function createReminder($property){
        $reminder = new Reminder([
            'name' => 'Test reminder',
            'start_date' => now(),
            'end_date' => now(),
            'type' => 'property',
            'type_id' => $property->id,
            'recurrence' => 'monthly'
        ]);
        $reminder->save();
        return $reminder;
    }
}
