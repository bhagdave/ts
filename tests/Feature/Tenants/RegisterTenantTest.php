<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class RegisterTenantTest extends TestCase
{
    use WithFaker;
    use DatabaseTransactions;
    private $session;

    public function testRegisterAgent(){
        $this->checkStep1();
        $this->checkStep2();
        $this->checkStep3();
        $this->gotoWelcome();
    } 

    private function checkStep1(){
        $response = $this->post('/register/tenant',[
            'email'=>$this->faker->unique()->safeEmail
        ]);
        $response->assertSessionHas('register');
        $this->session =  $response->getSession();
    }

    private function checkStep2(){
        $response = $this->withSession((array) $this->session)->post('/register/tenantStep2',[
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName
        ]);
        $response->assertRedirect('http://127.0.0.1:8000/register/tenantStep3');
        $this->session =  $response->getSession();
    }  

    private function checkStep3(){
        $response = $this->withSession((array) $this->session)->post('/register/step3',[
             'password' => 'Password12345!',
            'password_confirmation' => 'Password12345!'    
        ]);
        $this->session = $response->getSession();
        $response->assertRedirect('http://127.0.0.1:8000');
    } 

    private function gotoWelcome(){
        $response = $this->withSession((array) $this->session)->get('welcome');
        $this->session = $response->getSession();
    }

}
