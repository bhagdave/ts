<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DuskTestCase;

class RegisterTenantTest extends DuskTestCase
{
    use WithFaker;
    use DatabaseTransactions;

    public function testTenant()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register/tenant');
            $browser->type('email', $this->faker->unique()->safeEmail);
            $browser->press('Get Started');
            $browser->assertPathIs('/register/tenantStep2');
            $browser->type('firstName', $this->faker->firstName);
            $browser->type('lastName',  $this->faker->lastName);
            $browser->press('Continue');
            $browser->assertPathIs('/register/tenantStep3');
            $browser->type('password', 'Password1');
            $browser->type('password_confirmation', 'Password1');
            $browser->press('Continue');
            $browser->visit('/welcome');
            $browser->assertPathIs('/welcome');
            $browser->press('Add where you live');
            $browser->assertPathIs('/property/create');
        }
        );
    }
}
