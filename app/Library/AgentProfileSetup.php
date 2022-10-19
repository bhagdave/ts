<?php

namespace App\Library;

use App\Library\ProfileSetup;
use App\Jobs\AddNewAgentToExistingProperties;
use Bouncer;
use App\Agent;
use App\Agency;
use App\Documents;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\User;
use App\Stream;
use Spatie\Activitylog\Models\Activity;

class AgentProfileSetup extends ProfileSetup
{

    public function setupProfileForAgent($user, $agentFromSession = null){
        $this->assignRolesToAgents($user);
        $agent = $this->createAgentRecord($user, $agentFromSession);
        if (isset($agent->agency->stream_id)){
            AddNewAgentToExistingProperties::dispatch($user, $agent->agency_id, $agent->agency->stream_id);
        }
    }
    private function assignRolesToAgents($user){
        if ($user->userType == "Agent"){
            Bouncer::assign('agent')->to($user);
            Bouncer::retract('landlord')->from($user);
            Bouncer::retract('tenant')->from($user);
            Bouncer::retract('admin')->from($user);
            Bouncer::retract('contractor')->from($user);
            Bouncer::retract('undefined')->from($user);
            Bouncer::refreshFor($user);
        }
    }
    private function createAgentRecord($user, $agentFromSession){
        $user->userType= "Agent";
        $agent = Agent::where('user_id', $user->sub)->first();
        if (is_null($agent)) { // This excludes agents invited as a new colleague
            $newStream = $this->createStreamForAgency($user->companyName);
            $newStream->users()->attach($user->id);
            $agency = Agency::createAgencyFromCompanyName($user->companyName, $newStream->id);
            $agent = new Agent;
            $agent->user_id=$user->sub;
            $agent->name=$user->companyName;
            $agent->agency_id = $agency->id;
            $agent->main = 1;
            if (isset($agentFromSession)){
                $agent->property_count = $agentFromSession['property_count'];
            }
            $agent->save();
            $property = $this->createTestProperty($user, $agency->id);
            $tenant = $this->createTestTenant($property);
            $this->createTestIssue($property, $user);
            $this->createReminder($property);
            $this->createDocuments($property->id, $tenant->id);
        }
        return $agent;
    }
    private function createStreamForAgency($companyName){
        $newStream = new Stream();
        $newStream->private = true;
        $newStream->streamName = $companyName;
        $newStream->extra_attributes->broadcastOnly = 'false';
        $newStream->save();
        $this->createInitialStreamMessageForAgency($newStream->id);
        return $newStream;
    }
    private function createInitialStreamMessageForAgency($streamId){
        $message = "Welcome to your agency stream.  This is a place for you to communicate with everyone in your team.";
        activity($streamId)
            ->causedBy(User::current()->sub)
            ->withProperties([ 'messageType' => 'Event' ])
            ->log($message);
    }
    private function createDocuments($propertyId, $tenantId){
        $this->createS3Folder($propertyId);
        $this->createS3Folder($tenantId);
        $file = Uploadedfile::fake()->create('document.pdf', 25);
        $tenantPath = $this->storeFile($file, $tenantId);
        $propertyPath = $this->storeFile($file, $tenantId);
        $this->storePathInDatabase($tenantId, $tenantPath, 'tenant');
        $this->storePathInDatabase($propertyId, $propertyPath, 'property');
    }
    private function createS3Folder($id){
        $folderExists = Storage::disk('s3')->exists('docs/'. $id);
        if (!$folderExists) {
            Storage::disk('s3')->makeDirectory( 'docs/'.$id);
        }
    }
    private function storeFile($file, $id){
        $path = Storage::disk('s3')->put('docs/'.$id, $file, 'protected');
        return $path; 
    }
    private function storePathInDatabase($id,$path, $type){
        $document = new Documents([
            'type' => $type,
            'linked_to' => $id,
            'path' => $path,
            'file_type' => 'TEST DOC'
        ]);
        $document->save();
    }
}
