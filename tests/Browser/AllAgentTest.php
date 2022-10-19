<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DuskTestCase;

class AgentBigTest extends DuskTestCase
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
            $browser->visit('/stream/' . env('TEST_STREAM_REFERENCE'));
            $browser->type('message', 'A new message');
            $browser->visit('/property/' . env('TEST_PROPERTY_REFERENCE'));
            $browser->clickLink('Edit');
            $browser->visit('/property/' . env('TEST_PROPERTY_REFERENCE').'/edit');
            $browser->type('propertyNotes', $this->faker->realText(50));
            $browser->press('Submit');
            $browser->clickLink('Tenants');
            $browser->visit('/tenants');
            $browser->visit('/tenant/'. env('TEST_TENANT_REFERENCE')  .'/edit');
            $browser->type('notes', $this->faker->realText(50));
            $browser->press('Submit');
            $browser->assertPathIs('/tenants');
            $browser->clickLink('Landlords');
            $browser->visit('/landlords');
            $browser->visit('/landlord/'. env('TEST_LANDLORD_REFERENCE')  .'/edit');
            $browser->type('notes', $this->faker->realText(50));
            $browser->press('Submit');
            $browser->clickLink('Issues');
            $browser->visit('/issues');
            $browser->visit('/issue/'. env('TEST_ISSUE_REFERENCE'));
            $browser->visit('/issue/'. env('TEST_ISSUE_REFERENCE')  .'/edit');
            $browser->type('mainDescription', $this->faker->realText(100));
            $browser->press('Submit');
            $browser->assertPathIs('/issue/'. env('TEST_ISSUE_REFERENCE') );
            $browser->clickLink('Profile');
            $browser->visit('/profile/edit');
            $browser->clickLink('Invite User');
            $browser->visit('/invite/user');
            $browser->type('name[]', $this->faker->name);
            $browser->type('email[]', $this->faker->unique()->safeEmail);
            $browser->press('Send Invites');
            $browser->assertPathIs('/invite/user');
        });
    }
}





