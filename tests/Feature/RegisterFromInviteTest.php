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

class RegisterFromInviteTest extends TestCase
{

    use WithFaker;
    use CoreFeatureTest;
    use DatabaseTransactions;

    private $property;
    private $fakeEmail;
    private $inviteCode;

    public function testInvite(){
        $this->createLoggedInUser();
        $this->createInvite();
        $this->checkForErrors();
        $this->checkForRegistration();
    }

    public function testInviteWithProperty(){
        $this->createLoggedInUser();
        $this->createAgent();
        $this->createProperty();
        $this->createInviteWithProperty();
        $this->checkForErrors();
        $this->checkForRegistration();
    }

    private function createInvite(){
        $this->fakeEmail = $this->faker->unique()->safeEmail;
        $this->inviteCode = Invite::invite($this->fakeEmail, $this->user->id);
        $this->assertDatabaseHas('user_invitations',[
            'code' => $this->inviteCode,
            'email' => $this->fakeEmail
        ]); 
    }

    private function checkForErrors(){
        Auth::logout();
        $this->post('/invite/register',[
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'email' => $this->fakeEmail,
            'password' => '',
            'code' => $this->inviteCode
        ]);
        $this->assertDatabaseHas('user_invitations',[
            'code' => $this->inviteCode,
            'email' => $this->fakeEmail,
            'status' => 'pending'
        ]); 
        $response = $this->post('/invite/register',[
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'email' => $this->fakeEmail,
            'password' => '',
            'code' => 'poiwur9r08' 
        ]);
        $response->assertRedirect();
   }

    private function checkForRegistration(){
        Auth::logout();
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

    private function createInviteWithProperty(){
        $this->fakeEmail = $this->faker->unique()->safeEmail;
        $this->inviteCode = Invite::invite($this->fakeEmail, $this->user->id);
        \App\Invitation::where('code', $this->inviteCode)->update(['property_id' => $this->property->id]);
        $this->assertDatabaseHas('user_invitations',[
            'code' => $this->inviteCode,
            'email' => $this->fakeEmail
        ]); 
    }

    private function createProperty(){
        $this->property = factory(Properties::class)->create([
            'created_by_user_id' => $this->user->sub,
            'agent_id' => $this->agent->id
        ]);
        $this->assertDatabaseHas('properties',[
            'created_by_user_id' => $this->user->sub,
        ]); 
   }


}
