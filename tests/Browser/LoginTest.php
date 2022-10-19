<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');
            $app = $browser->element('#app');
            $this->assertNotNull($browser->element('#app'));
            $browser->type('email', env('TEST_AGENT_USERNAME'));
            $browser->type('password', env('TEST_AGENT_PASSWORD'));
            $browser->press('Login');
            $browser->waitForText('Dashboard');
            $browser->assertSee('Dashboard');
        });
    }
}
