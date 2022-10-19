<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DuskTestCase;

class AgentDownloadTenantTest extends DuskTestCase
{

    use WithFaker;
    use DatabaseTransactions;

    public function testAll()
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
            $browser->clickLink('Tenants');
            $browser->visit('/tenants');
            $browser->visit('/tenant/9f97fcdd-837f-4192-b82c-2548673b356a/documents');
            $browser->pause(2000);
            $browser->clickLink('Tenancy agreement');
        });
    }
}





