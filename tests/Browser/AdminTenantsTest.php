<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminTenants extends DuskTestCase
{
    use DatabaseTransactions;
    public function testSeeUsers()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');
            $app = $browser->element('#app');
            $this->assertNotNull($browser->element('#app'));
            $browser->type('email', env('ADMIN_USERNAME','local'));
            $browser->type('password', env('ADMIN_PASSWORD'));
            $browser->press('Login');
            $browser->clickLink('Admin');
            $browser->visit('/admin');
            $browser->assertSee('Users');
            $browser->visit('/tenants');
            $browser->assertSee('Tenants');
        });
    }
}
