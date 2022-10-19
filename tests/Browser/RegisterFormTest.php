<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DuskTestCase;

class RegisterFormTest extends DuskTestCase
{
    use WithFaker;
    use DatabaseTransactions;

    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register');
            $browser->type('firstName', $this->faker->firstName);
            $browser->type('lastName',  $this->faker->lastName);
            $browser->press('Get Started As An Agent');
            $browser->assertPathIs('/register/step2');
            $browser->type('email', $this->faker->unique()->safeEmail);
            $browser->type('telephone', $this->faker->phoneNumber);
            $browser->press('Continue');
            $browser->assertPathIs('/register/step3');
            $browser->type('companyName', $this->faker->company);
            $browser->type('property_count', $this->faker->randomDigitNot(0));
            $browser->press('Continue');
            $browser->assertPathIs('/register/step4');
            $browser->type('password', 'password1');
            $browser->type('password_confirmation', 'password1');
            $browser->check('terms');
            $browser->press('Continue');
            $browser->assertPathIs('/welcome');
            $browser->press('Get Started');
            $browser->assertPathIs('/');
        });
    }
}
