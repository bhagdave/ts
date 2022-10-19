<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Auth;

class RegisterContractorTest extends TestCase
{
    use WithFaker;
    use DatabaseTransactions;
    private $session;
    private $fakeEmail;

    public function testRegisterContractor(){
        $this->checkStep1();
        $this->checkStep2();
        $this->checkStep3();
        $this->checkStep4();
    } 

    private function checkStep1(){
        $this->fakeEmail = $this->faker->unique()->safeEmail;
        $response = $this->post('/register/contractor',[
            'email'=>$this->fakeEmail
        ]);
        $response->assertSessionHas('register');
        $this->session =  $response->getSession();
    }

    private function checkStep2(){
        $response = $this->withSession((array) $this->session)->post('/register/contractorstep2',[
            'companyName' => $this->faker->company,
        ]);
        $response->assertRedirect('http://127.0.0.1:8000/register/contractorstep3');
        $this->session = $response->getSession();
    }

    private function checkStep3(){
        $response = $this->withSession((array) $this->session)->post('/register/contractorstep3',[
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName
        ]);
        $response->assertRedirect('http://127.0.0.1:8000/register/contractorstep4');
        $this->session =  $response->getSession();
    }  

    private function checkStep4(){
        $response = $this->withSession((array) $this->session)->post('/register/contractorstep4',[
            'categories' => [2,3]
        ]);
        echo("Status:" . $response->status());
        $response->assertRedirect('http://127.0.0.1:8000/register/contractorstep5');
        $this->session =  $response->getSession();
    }

    private function checkStep5(){
        $response = $this->withSession((array) $this->session)->post('/register/contractorcreate',[
            'password' => 'Password12345!',
            'password_confirmation' => 'Password12345!'    
        ]);
        $this->session = $response->getSession();
        $response->assertRedirect('http://127.0.0.1:8000/welcome');
        $this->assertDatabaseHas('users', ['email' => $this->fakeEmail]);
    } 


   }

