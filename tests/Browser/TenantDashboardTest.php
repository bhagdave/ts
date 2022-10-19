<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TenantDashboardTest extends DuskTestCase
{
    public function testDashboard()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');
            $app = $browser->element('#app');
            $this->assertNotNull($browser->element('#app'));
            $browser->type('email', env('TEST_TENANT_USERNAME'));
            $browser->type('password', env('TEST_TENANT_PASSWORD'));
            $browser->press('Login');
            $browser->waitForText('Dashboard');
            $browser->assertSee('Dashboard');
            $browser->assertSee('Issues');
            $browser->assertSee('RegisteredTenantProperty');
        });
    }
}
