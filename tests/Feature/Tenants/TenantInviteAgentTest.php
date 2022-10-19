<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Tenant;
use App\Properties;
use Invite;
use Tests\Traits\CoreFeatureTest;
use Auth;
use App\Invitaion;

class TenantInviteAgentTest extends TestCase
{

    use WithFaker;
    use CoreFeatureTest;
    use DatabaseTransactions;

    private $property;
    private $fakeEmail;
    private $inviteCode;

    public function testInvite(){
        $this->createLoggedInUser('Tenant', 1);
        $this->createTenantRecord();
        $this->createProfile();
        $this->createProperty();
        $this->createInvite();
        $this->getInvitecode();
        $this->checkForRegistration();
    }

    private function createTenantRecord(){
        $this->assertAuthenticatedAs($this->user);
        $this->tenant = factory(Tenant::class)->create([
            'sub' => $this->user->sub,
            'email' => $this->user->email,
        ]);
        $this->assertDatabaseHas('tenants',[
            'sub' => $this->user->sub,
            'email' => $this->user->email
        ]);
    }

    private function createProfile(){
        $this->actingAs($this->user)->get('/');
        $this->actingAs($this->user)->post('/profile/create')->assertRedirect('http://127.0.0.1:8000/property/create');
        $this->actingAs($this->user)->get('/property/create')->assertSuccessful();
    }

    private function createProperty(){
        $this->actingAs($this->user)->post('/property/store',[
            'propertyName' => 'New Tenant Property',
            'propertyType' => 'Single Let - Standard Residential Let',
            'inputAddress' => $this->faker->streetAddress,
            'inputAddress2' => $this->faker->secondaryAddress,
            'inputCity' => $this->faker->city,
            'inputRegion' => $this->faker->country,
            'inputPostCode' => $this->faker->postcode,
        ])->assertRedirect('http://127.0.0.1:8000');
    }
    private function createInvite(){
        $this->actingAs($this->user)->get('/agent/invite')->assertSuccessful();
        $this->fakeEmail = $this->faker->unique()->safeEmail;
        $response = $this->actingAs($this->user)->post('/agent/create',[
            'name' => $this->faker->company,
            'email' => $this->fakeEmail
        ]);
        $response->assertRedirect();
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
