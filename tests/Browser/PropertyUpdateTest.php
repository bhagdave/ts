<?php
 
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class Property0c35e22b218844bea092f8658ea57220Test extends DuskTestCase
{

    /**
     * My test implementation
     */
    public function testLeverageIsErgonomic()
    {

        $this->browse(function (Browser $browser) {
            $browser->visit('/login');
            $browser->type('email', env('TEST_AGENT_USERNAME'));
            $browser->type('password', env('TEST_AGENT_PASSWORD'));
            $browser->press('Login');
            $browser->assertPathIs('/');
            $browser->visit('/property/388fed7a-a7d4-4c89-a45d-f1b1e3ebe782');
            $browser->clickLink('Edit');
//            $browser->visit('/property/388fed7a-a7d4-4c89-a45d-f1b1e3ebe782/edit');
            $browser->type('propertyNotes', 'Somenotes');
            $browser->press('Submit');
        });

    }
}

?>
