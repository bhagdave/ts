<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DuskTestCase;

class InviteTenantTest extends DuskTestCase
{
    use WithFaker;
    use DatabaseTransactions;

    public function testInvite()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login');
            $browser->type('email', env('TEST_AGENT_USERNAME'));
            $browser->type('password', env('TEST_AGENT_PASSWORD'));
            $browser->press('Login');
            $browser->assertPathIs('/');
            $browser->clickLink('Tenants');
            $browser->clickLink('Add Tenants');
            $browser->type('name', $this->faker->name);
            $browser->type('email', $this->faker->unique()->safeEmail);
            $browser->press('Submit');
            $browser->pause(1000);
            $browser->waitForText("Edit Tenant", 10);
        });
    }
}
