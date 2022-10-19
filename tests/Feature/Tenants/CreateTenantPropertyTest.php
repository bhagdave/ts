<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Tenant;
use App\User;

class CreateTenantPropertyTest extends TestCase
{

    use WithFaker;
    use DatabaseTransactions;
    
    public function testCreateTenantProperty(){
        $this->createLoggedInTenant();
        $this->createProfile();
        $this->createProperty();
    }

    private function createLoggedInTenant(){
        $this->user = factory(User::class)->create(
            ['userType' => 'Tenant', 'registered' => 1]
        );
       
        $response = $this->post(route('login'), [
            'email' => $this->user->email,
            'password' => 'password'
        ]);
        $response->assertRedirect('http://127.0.0.1:8000');
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
}
