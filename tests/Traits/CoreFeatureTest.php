<?php


namespace Tests\Traits;

use App\User;
use App\Agent;
use App\Tenant;
use App\Agency;
use Illuminate\Support\Facades\Auth;

trait CoreFeatureTest
{
    protected $user;
    protected $agent;
    protected $agency;
    protected $tenant;

    public function createLoggedInUser($userType = 'Agent', $registered = 0){
        $this->user = factory(User::class)->create([
            'registered' => $registered,
            'userType' => $userType,
        ]);
        $this->logIn($this->user);
        return $this->user;
    }

    public function createAgent($agencyId = null){
        if (!isset($agencyId)){
            $this->createAgency();
            $agencyId = $this->agency->id;
        }
        $agentUser = $this->user;
        if(!$agentUser){
            $agentUser = $this->createLoggedInUser();
        }
        $this->agent = factory(Agent::class)->create(
            [
                'user_id' => $agentUser->sub,
                'agency_id' => $agencyId
            ]
        );
        return (object) ['user' => $agentUser, 'agent' => $this->agent];
    }

    public function createAgency(){
        $this->agency = factory(Agency::class)->create();
    }

    public function createTenant($propertyId){
        $tenantUser = factory(User::class)->create(['userType' => 'Tenant']);
        $this->tenant = factory(Tenant::class)->create([
            'sub' => $tenantUser->sub,
            'property_id' => $propertyId
        ]);
        return (object) ['user' => $tenantUser, 'tenant' => $this->tenant];
    }


    public function createProfile(){
        $this->actingAs($this->user)->get('/')->assertRedirect('http://127.0.0.1:8000/welcome');
        $response = $this->actingAs($this->user)->post('/profile/create')->assertRedirect('http://127.0.0.1:8000');
        $this->actingAs($this->user)->get('/property')->assertSuccessful();
    }

    protected function logIn(User $user)
    {
        if(Auth::check()){
            Auth::logout();
        }
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertRedirect(env('APP_URL'));
        $this->assertAuthenticatedAs($user);
    }

}
