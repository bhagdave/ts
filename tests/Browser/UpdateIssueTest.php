<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UpdateIssueTest extends DuskTestCase
{
    use DatabaseTransactions;
    public function testIssueCreaetion()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');
            $browser->type('email', env('TEST_AGENT_USERNAME'));
            $browser->type('password', env('TEST_AGENT_PASSWORD'));
            $browser->press('Login');
            $browser->waitForText('Dashboard');
            $browser->clickLink('Issues');
            $browser->visit('/issue/' . env('TEST_ISSUE_REFERENCE'));
            $browser->type('description', 'DuskTestUpdate');
            $browser->press('Submit');
            $browser->assertPathIs('/issue/' . env('TEST_ISSUE_REFERENCE'));
        });
    }
}
