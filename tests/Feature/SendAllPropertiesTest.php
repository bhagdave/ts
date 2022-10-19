<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Auth;
use App\User;

class SendAllPropertiesMessageTest extends TestCase
{

    use DatabaseTransactions;

    private $user;

    public function testMessageSend(){
        $this->getUser();
        $this->login();
    }

    private function getUser(){
        echo("\nTestUser:" .  env('TEST_AGENT_USERNAME'). "\n");
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

    private function sendMessage(){
        $response = $this->post('/sendMessageToAllProperties', ['message', 'FEATURE test']);
        $response->assertOk();
    }
}
