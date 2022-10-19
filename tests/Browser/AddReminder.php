<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AddReminder extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');
            $browser->type('email', env('TEST_AGENT_USERNAME'));
            $browser->type('password', env('TEST_AGENT_PASSWORD'));
            $browser->press('Login');
            $browser->waitForText('Dashboard');
            $browser->visit('/reminders/manage/property/0c35e22b-2188-44be-a092-f8658ea57220');
            $browser->clickLink('Add Reminder');
            $browser->visit('/reminders/create/property/0c35e22b-2188-44be-a092-f8658ea57220');
            $browser->type('name', 'zxcvbnm');
            $browser->type('start_date', '01/12/2021');
            $browser->press('Create Reminder');
            $browser->assertSee('zxcvbnm');    
        });
    }
}
