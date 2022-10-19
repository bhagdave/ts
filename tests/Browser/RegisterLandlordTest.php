<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DuskTestCase;

class RegisterLandlordTest extends DuskTestCase
{
    use WithFaker;
    use DatabaseTransactions;

    public function testLandlordRegister()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register/landlord');
            $browser->type('email', $this->faker->unique()->safeEmail);
            $browser->press('Get Started');
            $browser->assertPathIs('/register/landlordStep2');
            $browser->type('firstName', $this->faker->firstName);
            $browser->type('lastName',  $this->faker->lastName);
            $browser->press('Continue');
            $browser->assertPathIs('/register/landlordStep3');
            $browser->type('password', 'Password1');
            $browser->type('password_confirmation', 'Password1');
            $browser->press('Continue');
            $browser->visit('/welcome');
            $browser->assertPathIs('/welcome');
            $browser->visit('/property/create');
            $browser->type('propertyName', 'DUSK TEST');
            $browser->type('inputAddress', $this->faker->streetAddress);
            $browser->type('inputAddress2', $this->faker->streetName);
            $browser->type('inputCity', $this->faker->city);
            $browser->type('inputPostCode', $this->faker->postcode);
            $browser->press('Submit');
            $browser->assertPathIs('/tenant/create');            
            $this->tenantEmail = $this->faker->unique()->safeEmail;
            $browser->type('name', $this->faker->name);
            $browser->type('email', $this->tenantEmail);
            $browser->press('Submit');
            $this->assertDatabaseHas('tenants',['email' => $this->tenantEmail]);        
        });
    }
}
