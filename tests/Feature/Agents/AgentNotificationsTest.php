<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Properties;
use Auth;
use App\User;

class AgentNotificationTest extends TestCase
{

    use DatabaseTransactions;

    private $user;

    public function testInvite(){
        $this->getUser();
        $this->login();
        $this->notifications();
    }

    private function getUser(){
        $this->user = User::where('email', '=', env('TEST_AGENT_USERNAME'))->get()->first();
    }

    private function login(){
        $response = $this->post(route('login'), [
            'email' => $this->user->email,
            'password' => env('TEST_AGENT_PASSWORD')
        ]);
        $response->assertRedirect('http://127.0.0.1:8000');
        $this->assertAuthenticatedAs($this->user);
    }

    private function notifications(){
        $response = $this->actingAs($this->user)->get('/notifications/');
        $response->assertOk();
    }
}
