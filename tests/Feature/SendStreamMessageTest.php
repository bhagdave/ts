<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Stream;
use App\Properties;
use Tests\Traits\CoreFeatureTest;

class SendStreamMessageTest extends TestCase
{
    use WithFaker;
    use CoreFeatureTest;
    use DatabaseTransactions;
    
    private $city;
    private $postcode;
    private $country;
    private $property;
    private $streamId;

    public function testStreamMessageSend()
    {
        $this->createLoggedInUser();
        $this->createAgent();
        $this->createProfile();
        $this->createProperty();
        $this->getStreamForProperty();
        $this->checkStreamShows();
        $this->postNewMessageInStream();
    }

    private function createProperty(){
        $this->actingAs($this->user)->get('/property/create')->assertSuccessful();
        $this->city =  $this->faker->city;
        $this->postcode = $this->faker->postcode;
        $this->country = $this->faker->country;
        $this->actingAs($this->user)->post('/property/store',[
            'propertyName' => 'Feature Test Property',
            'propertyType' => 'Other',
            'broadcastOnly'=> FALSE,
            'inputAddress' => $this->faker->streetAddress,
            'inputAddress2' => $this->faker->secondaryAddress,
            'inputCity' => $this->city,
            'inputRegion' => $this->country,
            'inputPostCode' => $this->postcode,
            'created_by_user_id' => $this->user->sub
        ])->assertRedirect();
        $this->property = \App\Properties::where([
            'inputCity' => $this->city,
            'inputRegion' => $this->country,
            'inputPostcode' => $this->postcode
        ])->first(); 
    }

    private function getStreamForProperty(){
        $this->streamId = Stream::withExtraAttributes('property_id',$this->property->id )->first()->id;
    }

    private function checkStreamShows(){
        $this->actingAs($this->user)->get('/stream/'.$this->streamId)->assertSuccessful();
    }

    private function postNewMessageInStream(){
        $message = [
            'user' => (array) $this->user,
            'message' => ['message' => 'A test message']
        ];
        $this->actingAs($this->user)->postJson(
            'messages/' . $this->streamId,
            $message
        );
        $this->assertDatabaseHas('activity_log',[
            'log_name' => $this->streamId,
            'description' => 'A test message'
        ]);
    }

}
