<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Tests\Traits\CoreFeatureTest;
use App\Properties;
use App\Tenant;
use Invitation;
use Auth;

class TenantCreateTest extends TestCase
{

    use CoreFeatureTest; 
    use WithFaker;
    use DatabaseTransactions;

    private $property;
    private $inviteCode;

    public function testTenantCreation(){
        $this->createLoggedInUser();
        $this->createAgent(); 
        $this->createProfile();
        $this->createProperty();
        $this->createTenant();
        $this->completeTenantInvite();
        $this->deleteTenant();
   }

    private function createProperty(){
        $this->property = factory(Properties::class)->create([
            'agent_id' => $this->agency->id,
            'created_by_user_id' => $this->user->sub
        ]);
    }

    private function createTenant(){
        $this->tenantToCreate = array(
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'property_id' => $this->property->id
        );
        $response = $this->actingAs($this->user)->post('/tenant/store',$this->tenantToCreate);
        $this->assertDatabaseHas('tenants',[
            'property_id' => $this->property->id,
            'email' => $this->tenantToCreate['email']
        ]);
        $this->tenantToCreate['inviteCode'] = \App\Invitation::where('email', $this->tenantToCreate['email'])->first()->code; 
    }

    private function completeTenantInvite(){
        Auth::logout();
        $response = $this->post('/invite/register',[
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'email' => $this->tenantToCreate['email'],
            'password' => 'Password1',
            'password_confirmation' => 'Password1',
            'code' => $this->tenantToCreate['inviteCode']
        ]);
        $response->assertRedirect("http://127.0.0.1:8000/welcome");
        $user = Auth::user();
        $response = $this->actingAs($user)->get('/welcome');
        $response->assertSee('started');
        $this->assertDatabaseHas('user_invitations',[
            'code' => $this->tenantToCreate['inviteCode'],
            'email' => $this->tenantToCreate['email'],
            'status' => 'successful'
        ]); 
    }

    private function deleteTenant(){
        Auth::logout();
        $this->login($this->user);
        $tenant = $this->getTenant();
        $response = $this->post('/tenant/' . $tenant->id . '/delete',['_method' => 'delete']);
        $response->assertRedirect('http://127.0.0.1:8000/tenants');
        $this->assertDatabaseHas('tenants',[
            'email' => $this->tenantToCreate['email'],
            'property_id' => null
        ]); 
    }

    private function getTenant(){
        return DB::table('tenants')->where('email', '=', $this->tenantToCreate['email'])->first();
    }

}
