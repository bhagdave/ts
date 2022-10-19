<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class RegisterAgentTest extends TestCase
{
    use WithFaker;
    use DatabaseTransactions;
    private $session;

    public function testRegisterAgent(){
        $this->checkStep1();
        $this->checkStep2();
        $this->checkStep3();
        $this->checkStep4();
    } 

    private function checkStep1(){
        $response = $this->post('/register/step1',[
            'firstName'=>$this->faker->firstName,
            'lastName' => $this->faker->lastName
        ]);
        $response->assertSessionHas('register');
        $response->assertRedirect('http://127.0.0.1:8000/register/step2');
        $this->session =  $response->getSession();
    }

    private function checkStep2(){
        $response = $this->withSession((array) $this->session)->post('/register/step2',[
            'email' => $this->faker->unique()->safeEmail,
            'telephone' => $this->faker->phoneNumber
        ]);
        $response->assertRedirect('http://127.0.0.1:8000/register/step3');
        $this->session =  $response->getSession();
    }  

    private function checkStep3(){
        $response = $this->withSession((array) $this->session)->post('/register/step3',[
            'companyName' => $this->faker->firstName,
            'country' => $this->faker->countryCode,
            'property_count' => $this->faker->randomDigitNot(0)
        ]);
        $response->assertRedirect('http://127.0.0.1:8000/register/step4');
        $this->session = $response->getSession();
    } 

    private function checkStep4(){
        $response = $this->WithSession((array) $this->session)->post('/register/create',[
            'password' => 'Password12345!',
            'password_confirmation' => 'Password12345!'    
        ]);
        $response->assertRedirect('http://127.0.0.1:8000/welcome');
    }
}
