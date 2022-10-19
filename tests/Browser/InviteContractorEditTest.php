<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use App\Invitation;
use Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class InviteContractorEditTest extends DuskTestCase
{
    use WithFaker;

    private $emailAddress;
    private $inviteCode;

    public function testInvite()
    {
        $this->setUpInvite();
        $this->browse(function (Browser $browser) {
            $browser->visit('/login');
            $browser->type('email', env('TEST_AGENT_USERNAME'));
            $browser->type('password', env('TEST_AGENT_PASSWORD'));
            $browser->press('Login');
            $browser->assertPathIs('/');
            $browser->clickLink('Issues');
            $browser->visit('/issues');
            $browser->visit('/issue/' . env('TEST_ISSUE_REFERENCE'));
            $browser->clickLink('Edit Issue');
            $browser->visit('/issue/' . env('TEST_ISSUE_REFERENCE') . '/edit');
            $this->emailAddress = $this->faker->unique()->safeEmail;
            $browser->type('invite', $this->emailAddress);
            $browser->press('Submit');

            $browser->assertPathIs('/issue/' . env('TEST_ISSUE_REFERENCE'));
            $browser->clickLink('Profile');
            $browser->visit('/profile/edit');
            $browser->clickLink('Logout');
            $browser->visit('/login');
            $browser->assertPathIs('/login');
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

    private function setUpInvite() :void{
        DB::table('issues')
            ->where('id', '=', env('TEST_ISSUE_REFERENCE'))
            ->update(
                [
                    'contractors_id' => null,
                    'invite' => null
                ]
            );
    }
}
