<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Properties;
use Tests\Traits\CoreFeatureTest;

class PropertyUpdateTest extends TestCase
{

    use WithFaker;
    use CoreFeatureTest; 
    use DatabaseTransactions;

    private $propertyName;
    private $properytId;

    public function testPropertyUpdate(){
        $this->createLoggedInUser();
        $this->createAgent(); 
        $this->createProfile();
        $this->createProperty();
        $this->updateProperty();
        //$this->deleteProperty();
    }   

    private function createProperty(){
        $this->actingAs($this->user)->get('/property/create')->assertSuccessful();
        $this->propertyName = "TEST:" . $this->faker->name;
        $this->actingAs($this->user)->post('/property/store',[
            'propertyName' => $this->propertyName,
            'propertyType' => 'Other',
            'broadcastOnly'=> FALSE,
            'inputAddress' => $this->faker->streetAddress,
            'inputAddress2' => $this->faker->citySuffix,
            'inputCity' => $this->faker->city,
            'inputRegion' => $this->faker->country,
            'inputPostCode' => $this->faker->postcode,
            'created_by_user_id' => $this->user->sub
        ])->assertRedirect();
    }

    private function updateProperty(){
        $this->propertyId = \App\Properties::where('propertyName', $this->propertyName)->first()->id;
        $this->actingAs($this->user)->post('/property/' . $this->propertyId. '/update', 
            [
            'propertyName' => $this->propertyName,
            'propertyType' => 'Other',
            'broadcastOnly'=> FALSE,
            'inputAddress' => $this->faker->streetAddress,
            'inputAddress2' => $this->faker->citySuffix,
            'inputCity' => $this->faker->city,
            'inputRegion' => $this->faker->country,
            'inputPostCode' => $this->faker->postcode,
            'created_by_user_id' => $this->user->sub
           ]
       )->assertRedirect();
        $this->assertDatabaseHas('properties', 
            [
                'propertyName' => $this->propertyName
            ]
        );
    }

    private function deleteProperty(){
        echo("\nURL:/property/". $this->propertyId . '/delete');
        $this->actingAs($this->user)->post('/property/' . $this->properytId . '/delete');
        $property = \App\Properties::where('id', $this->propertyId)->first();
        if (isset($property)){
            assert(false);
        }
    }
   
}
