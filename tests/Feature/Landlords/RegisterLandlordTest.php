<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Auth;

class RegisterLandlordTest extends TestCase
{
    use WithFaker;
    use DatabaseTransactions;
    private $session;
    private $fakeEmail;

    public function testRegisterLandlord(){
        $this->checkStep1();
        $this->checkStep2();
        $this->checkStep3();
        $this->gotoWelcome();
        $this->createProperty();
    } 

    private function checkStep1(){
        $this->fakeEmail = $this->faker->unique()->safeEmail;
        $response = $this->post('/register/landlord',[
            'email'=>$this->fakeEmail
        ]);
        $response->assertSessionHas('register');
        $this->session =  $response->getSession();
    }

    private function checkStep2(){
        $response = $this->withSession((array) $this->session)->post('/register/landlordStep2',[
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName
        ]);
        $response->assertRedirect('http://127.0.0.1:8000/register/landlordStep3');
        $this->session =  $response->getSession();
    }  

    private function checkStep3(){
        $response = $this->withSession((array) $this->session)->post('/register/createLandlord',[
             'password' => 'Password12345!',
            'password_confirmation' => 'Password12345!'    
        ]);
        $this->session = $response->getSession();
        $response->assertRedirect('http://127.0.0.1:8000/welcome');
        $this->assertDatabaseHas('users', ['email' => $this->fakeEmail]);
    } 

    private function gotoWelcome(){
        $response = $this->withSession((array) $this->session)->get('welcome');
        $response->assertSeeText('View your property');
        $this->user = Auth::user();
    }

    private function createProperty(){
        $this->actingAs($this->user)->get('/property/create')->assertSuccessful();
        $this->actingAs($this->user)->post('/property/store',[
            'propertyName' => 'Feature test property',
            'propertyType' => 'Other',
            'broadcastOnly'=> FALSE,
            'inputAddress' => $this->faker->streetAddress,
            'inputAddress2' => $this->faker->streetName,
            'inputCity' => $this->faker->city,
            'inputRegion' => $this->faker->country,
            'inputPostCode' => $this->faker->postcode,
            'created_by_user_id' => $this->user->sub
        ]);
        $this->assertDatabaseHas('properties', [
            'propertyType' => 'Other', 
            'created_by_user_id' => $this->user->sub
        ]);
   }

}
