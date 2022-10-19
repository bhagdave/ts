<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DuskTestCase;

class UpdateTenantTest extends DuskTestCase
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
            $browser->visit('/tenant/'. env('TEST_PENDING_TENANT')  .'/edit');
            $browser->type('notes', $this->faker->realText(50));
            $browser->type('email', $this->faker->unique()->safeEmail);
            $browser->press('Submit');
            $browser->assertPathIs('/tenants');
            $browser->visit('/tenant/'. env('TEST_CONFIRMED_TENANT')  .'/edit');
            $browser->type('notes', $this->faker->realText(50));
            $browser->press('Submit');
            $browser->assertPathIs('/tenants');
       });
    }
}





