<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CreateIssueTest extends DuskTestCase
{
    use DatabaseTransactions;
    /**
     * A Dusk test example.
     *
     * @return void
     *
     */
    public function testIssueCreaetion()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');
            $browser->type('email', env('TEST_AGENT_USERNAME'));
            $browser->type('password', env('TEST_AGENT_PASSWORD'));
            $browser->press('Login');
            $browser->waitForText('Dashboard');
            $browser->clickLink('Issues');
            //$browser->visit('/issues');
            $browser->clickLink('Add Issue');
            //$browser->visit('/issues/create');
            $browser->type('@property', 'A property');
            $browser->keys('#title', 'A problem');
            $browser->keys('#description', 'There is a problem in this test that I am ashameoofdsfdsofdsofoskdfpokposkfpokfsdpokfpsokfpoksdpfoksdpfoksdpofkoidkjfoijdfsoijfoijfsdoifjoijsdfoijfoisjdfoijodisjfoijoisdjfjoijfdsiojfoijdsfoijfoisdjfoijdsoifjoidsfjoisdjfoijsdoifosdijfiofjdsoifjds to admit!');
            $browser->press('Submit');
            $browser->assertSee('A problem');        
        });
    }
}
