<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Dusk\Browser;
use App\Invitation;
use Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Log;

class InviteContractorCreateTest extends DuskTestCase
{
    use WithFaker;
    use DatabaseTransactions;

    private $emailAddress;
    private $inviteCode;

    public function testInvite()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login');
            $browser->type('email', env('TEST_AGENT_USERNAME'));
            $browser->type('password', env('TEST_AGENT_PASSWORD'));
            $browser->press('Login');
            $browser->assertPathIs('/');
            $browser->clickLink('Issues');
            $browser->visit('/issues');
            $this->emailAddress = $this->faker->unique()->safeEmail;
            $browser->clickLink('Add Issue');
            $browser->visit('/issues/create');
            $browser->type('@property', 'A property');
            $browser->type('title', $this->faker->realText(20));
            $browser->type('description', $this->faker->realText(100));
            $browser->type('invite', $this->emailAddress);
            $browser->press('Submit');
            $browser->clickLink('Profile');
            $browser->visit('/profile/edit');
            $browser->clickLink('Logout');
            $browser->visit('/login');
            $invitation = Invitation::where('email', $this->emailAddress)->first();
            Auth::logout();
            $this->inviteCode = Invitation::where('email', $this->emailAddress)
                ->first()->code;
            $url = '/invite/' . $this->inviteCode;
            $browser->visit($url);
            $browser->type('firstName', $this->faker->firstName);
            $browser->type('lastName', $this->faker->lastName);
            $browser->type('password', 'Password1');
            $browser->type('password_confirmation', 'Password1');
            $browser->press('Register');
            $browser->assertPathIs('/welcome');
       });
    }
}
