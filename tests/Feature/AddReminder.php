<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\CoreFeatureTest;
use App\Properties;

class AddReminder extends TestCase
{
    use CoreFeatureTest;
    use DatabaseTransactions;

    private $property;

    public function testAddReminder()
    {
        $this->createLoggedInUser();
        $this->createAgent();
        $this->createProfile();
        $this->createProperty();
        $this->createReminder();
    }

    private function createProperty($userId = null, $agencyId = null){
        $this->property = factory(Properties::class)->create([
            'created_by_user_id' => $userId ? : $this->user->sub,
            'agent_id' => $agencyId ? : $this->agency->id
        ]);
        return $this->property;
    }

    private function createReminder(){
        $response = $this->actingAs($this->user)->post('/reminders/create/post',[
            'type' => 'property',
            'type_id' => $this->property->id,
            'name' => 'TestReminder',
            'start_date' => now(),
            'end_date' => now(),
            'recurrence' => 'monthly'
        ]);
        $this->assertDatabaseHas('reminders',[
            'type_id' => $this->property->id,
            'type' => 'property',
            'name' => 'TestReminder'
        ]);
    }
}
