<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\CoreFeatureTest;

class PropertyCreateTest extends TestCase
{

    use CoreFeatureTest; 
    use DatabaseTransactions;

    public function testPropertyCreation(){
        $this->createLoggedInUser();
        $this->createAgent(); 
        $this->createProfile();
        $this->createProperty();
   }

    private function createProperty(){
        $this->actingAs($this->user)->get('/property/create')->assertSuccessful();
        $this->actingAs($this->user)->post('/property/store',[
            'propertyName' => 'Test Property',
            'propertyType' => 'Other',
            'broadcastOnly'=> FALSE,
            'inputAddress' => 'ATestAddress Road',
            'inputAddress2' => 'Somepartoftown',
            'inputCity' => 'SomeCity',
            'inputRegion' => 'England',
            'inputPostCode' => 'X00 0XX',
            'created_by_user_id' => $this->user->sub
        ])->assertRedirect();
    }

   
}
