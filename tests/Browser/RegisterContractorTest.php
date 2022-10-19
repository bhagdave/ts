<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DuskTestCase;

class RegisterContractorTest extends DuskTestCase
{
    use WithFaker;
    use DatabaseTransactions;

    public function testTenant()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register/contractor');
            $browser->type('email', $this->faker->unique()->safeEmail);
            $browser->press('Get Started As A Contractor');
            $browser->assertPathIs('/register/contractorstep2');
            $browser->type('companyName', 'Daves Contracting');
            $browser->press('Continue');
            $browser->assertPathIs('/register/contractorstep3');
            $browser->type('firstName', $this->faker->firstName);
            $browser->type('lastName',  $this->faker->lastName);
            $browser->press('Continue');
            $browser->assertPathIs('/register/contractorstep4');
            $browser->select('categories[]', 2);
            $browser->select('categories[]', 4);
            $browser->select('categories[]', 6);
            $browser->press('Continue');
            $browser->assertPathIs('/register/contractorstep5');
            $browser->type('password', 'Password1');
            $browser->type('password_confirmation', 'Password1');
            $browser->press('Continue');
            $browser->visit('/welcome');
            $browser->assertPathIs('/welcome');
        }
        );
    }
}



