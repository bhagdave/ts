<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class LoginTest extends TestCase
{
    use DatabaseTransactions;
    public function testLogin(){
        $this->loginFormShows();
        $this->loginShowsErrorsOnEmptyPost();
        $this->loginAuthenticates();
   } 
    
   private function loginFormShows()
   {
        $response = $this->get('/login');
        $response->assertStatus(200);
   }

    private function loginShowsErrorsOnEmptyPost(){
        $response = $this->post('/login',[]);
        $response->assertStatus(302);
    }

    private function loginAuthenticates(){
        $user = factory(User::class)->create();
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertRedirect('http://127.0.0.1:8000');
        $this->assertAuthenticatedAs($user);
    }

}
