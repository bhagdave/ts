<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SendMessageOnStreamTest extends DuskTestCase
{
    use DatabaseTransactions;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login');
            $browser->type('email', env('TEST_AGENT_USERNAME'));
            $browser->type('password', env('TEST_AGENT_PASSWORD'));
            $browser->press('Login');
            $browser->assertPathIs('/');
            $browser->visit('/stream/' . env('TEST_STREAM_REFERENCE'));
            $browser->type('message', 'DUSK:This is a test');
            $browser->keys('#btn-input', '{enter}');
            $browser->waitForText("DUSK:", 5);
    });
    }
}
