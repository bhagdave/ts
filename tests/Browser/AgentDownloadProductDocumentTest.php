<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DuskTestCase;

class AgentDownloadPropertyDocumentTest extends DuskTestCase
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
            $browser->visit('/stream/a8e9e9f9-c203-401b-adf2-48a59d9371ba');
            $browser->visit('/property/4007c80d-0298-461e-b4de-c5355b0b31f3/documents');
            $browser->pause(2000);
            $browser->clickLink('EPC');

        });
    }
}





