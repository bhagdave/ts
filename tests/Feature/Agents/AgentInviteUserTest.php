<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Properties;
use Invite;
use Tests\Traits\CoreFeatureTest;
use Auth;
use App\Invitaion;

class AgentInviteUserTest extends TestCase
{

    use WithFaker;
    use CoreFeatureTest;
    use DatabaseTransactions;

    private $property;
    private $fakeEmail;
    private $inviteCode;

    public function testInvite(){
        $this->createLoggedInUser('Agent', 1);
        $this->createAgent();
        $this->createInvite();
        $this->getInvitecode();
        $this->checkForRegistration();
    }

    private function createInvite(){
        $this->actingAs($this->user)->get('/invite/user')->assertSuccessful();
        $this->fakeEmail = $this->faker->unique()->safeEmail;
        $response = $this->actingAs($this->user)->post('/invite/user',[
            [
                'name' => $this->faker->name,
                'email' => $this->fakeEmail
            ]
        ]);
        $response->assertOk();
        $this->assertDatabaseHas('user_invitations',[
            'email' => $this->fakeEmail
        ]); 
    }

    private function getInviteCode(){
        $this->inviteCode = \App\Invitation::where('email', $this->fakeEmail)->first()->code;
    }

    private function checkForRegistration(){
        Auth::logout();
        $this->get('/invite/'.$this->inviteCode)->assertSuccessful();
        $response = $this->post('/invite/register',[
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'email' => $this->fakeEmail,
            'password' => 'Password1',
            'password_confirmation' => 'Password1',
            'code' => $this->inviteCode
        ]);
        $this->assertDatabaseHas('user_invitations',[
            'code' => $this->inviteCode,
            'email' => $this->fakeEmail,
            'status' => 'successful'
        ]); 
    }



}
