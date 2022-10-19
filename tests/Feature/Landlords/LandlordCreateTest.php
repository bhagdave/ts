<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\CoreFeatureTest;
use App\Properties;

class LandlordCreateTest extends TestCase
{

    use CoreFeatureTest; 
    use WithFaker;
    use DatabaseTransactions;

    private $property;

    public function testLandlordCreation(){
        $this->createLoggedInUser();
        $this->createAgent(); 
        $this->createProfile();
        $this->createProperty();
        $this->createLandlord();
   }

    private function createProperty(){
        $this->property = factory(Properties::class)->create([
            'agent_id' => $this->agent->id,
            'created_by_user_id' => $this->user->sub
        ]);
    }

    private function createLandlord(){
        $landlordToCreate = array(
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
        );
        $response = $this->actingAs($this->user)->post('/landlord/store',$landlordToCreate);
        $this->assertDatabaseHas('landlords',[
            'email' => $landlordToCreate['email']
        ]); 
    }
}
