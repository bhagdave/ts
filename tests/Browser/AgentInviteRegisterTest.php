<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DuskTestCase;
use Tests\Traits\CoreFeatureTest;
use DataTables;
use App\Invitation;
use Auth;
use App\Properties;
use Illuminate\Support\Facades\Schema;

class AgentInviteRegistertTest extends DuskTestCase
{
    use WithFaker;
    use CoreFeatureTest;
    use DatabaseTransactions;

    private $inviteCode;
    private $fakeEmail;
    private $streetAddress;

    public function testShowInviteForm()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register/tenant');
            $browser->type('email', $this->faker->unique()->safeEmail);
            $browser->press('Get Started');
            $browser->assertPathIs('/register/tenantStep2');
            $browser->type('firstName', $this->faker->firstName);
            $browser->type('lastName', $this->faker->lastName);
            $browser->press('Continue');
            $browser->assertPathIs('/register/tenantStep3');
            $browser->type('password', 'Password1');
            $browser->type('password_confirmation', 'Password1');
            $browser->press('Continue');
            $browser->assertPathIs('/welcome');
            $browser->press('Add where you live');
            $browser->assertPathIs('/property/create');
            $this->streetAddress =  $this->faker->streetAddress;
            $browser->type('propertyName', 'Dusk test case property name');
            $browser->type('inputAddress', $this->faker->streetAddress);
            $browser->type('inputAddress2', $this->streetAddress);
            $browser->type('inputCity', $this->faker->city);
            $browser->type('inputPostCode', $this->faker->postcode);
            $browser->press('Submit');
            $browser->assertPathIs('/');
            $browser->clickLink('Add Agent');
            $browser->visit('/agent/invite');
            $browser->type('name', $this->faker->company);
            $this->fakeEmail =  $this->faker->unique()->safeEmail;
            $browser->type('email',$this->fakeEmail);
            $browser->press('Submit');
            $browser->assertPathIs('/');
            $this->inviteCode = \App\Invitation::where('email', $this->fakeEmail)->first()->code;
            $browser->clickLink('Profile');
            $browser->visit('/profile/edit');
            $browser->clickLink('Logout');
            $browser->visit('/login');
            $browser->assertPathIs('/login');
            Auth::logout();
            $url = '/invite/' . $this->inviteCode;
            $browser->visit($url);
            $browser->type('firstName', $this->faker->firstName);
            $browser->type('lastName', $this->faker->lastName);
            $browser->type('password', 'Password1');
            $browser->type('password_confirmation', 'Password1');
            $browser->press('Register');
            $browser->assertPathIs('/welcome');
        }
        );
    }

}
