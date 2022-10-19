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
use Illuminate\Support\Facades\Log;

class AgentInviteUserTest extends DuskTestCase
{
    use WithFaker;
    use CoreFeatureTest;
    use DatabaseTransactions;

    private $inviteCode;
    private $fakeEmail;
    private $streetAddress;

    public function testAgentInviteUser()
    {
        $this->browse(function (Browser $browser) {
            Log::info("AgentINviteUserTest - Started");
            $browser->visit('/login');
            $browser->type('email', env('TEST_AGENT_USERNAME'));
            $browser->type('password', env('TEST_AGENT_PASSWORD'));
            $browser->press('Login');
            $browser->assertPathIs('/');
            $browser->clickLink('Profile');
            $browser->visit('/profile/edit');
            $browser->clickLink('Invite User');
            $browser->visit('/invite/user');
            Log::info("About to create the invite");
            $name =  $this->faker->name;
            $email = $this->faker->unique()->safeEmail;
            Log:info('Creating for : name ' . $name . ' and  email ' . $email);
            $browser->type('name[]', $name);
            $this->fakeEmail =  $email;
            $browser->type('email[]',$this->fakeEmail);
            $browser->press('Send Invites');
            $browser->pause(1000);
            $browser->press('Send Invites');
            $browser->screenshot('after-invite-send');
            $browser->assertPathIs('/invite/user');
            $browser->clickLink('Profile');
            $browser->visit('/profile/edit');
            $browser->clickLink('Logout');
            $browser->visit('/login');
            $browser->assertPathIs('/login');
            Auth::logout();

            $this->inviteCode = \App\Invitation::where('email', $this->fakeEmail)
                ->first()->code;
            $url = '/invite/' . $this->inviteCode;
            $browser->visit($url);
            $browser->type('firstName', $this->faker->firstName);
            $browser->type('lastName', $this->faker->lastName);
            $browser->type('password', 'Password1');
            $browser->type('password_confirmation', 'Password1');
            $browser->press('Register');
            $browser->assertPathIs('/welcome');
            $browser->visit('/property')
                ->waitForText('Your Properties')
                ->assertSee('153 Alvis Walk');
            Log::info("AgentINviteUserTest - Ended");
        }
        );
    }

}
