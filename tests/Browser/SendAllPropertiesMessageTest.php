<?php

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SendAllPropertiesMessageTest extends DuskTestCase
{

    use DatabaseTransactions;
    
    public function testSendAllMessages()
    {

        $this->browse(function (Browser $browser) {
            $browser->visit('/login');
            $browser->type('email', env('TEST_AGENT_USERNAME'));
            $browser->type('password', env('TEST_AGENT_PASSWORD'));
            $browser->press('Login');
            $browser->pause(2);
            $browser->assertPathIs('/');
            $browser->type('messageall', 'This is a DUSK test message');
            $browser->press('Send to all');
            $browser->pause(9);
            $browser->assertPathIs('/');
            $browser->waitForText("Message sent!", 10);
        });

    }
}
