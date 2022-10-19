<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Properties;
use Auth;
use App\User;

class AgentDownloadDocumentsTest extends TestCase
{

    use DatabaseTransactions;

    private $user;

    public function testInvite(){
        $this->getUser();
        $this->login();
        $this->viewTenantDownloads();
        $this->downloadFile();
    }

    private function getUser(){
        echo("\nTestUser:" .  env('TEST_AGENT_USERNAME'). "\n");
        $this->user = User::where('email', '=', env('TEST_AGENT_USERNAME'))->get()->first();
    }

    private function login(){
        $response = $this->post(route('login'), [
            'email' => $this->user->email,
            'password' => env('TEST_AGENT_PASSWORD')
        ]);
        $response->assertRedirect('http://127.0.0.1:8000');
        $this->assertAuthenticatedAs($this->user);
    }

    private function viewTenantDownloads(){
        $response = $this->actingAs($this->user)->get('tenant/'. env('TEST_TENANT_REF')  .'/documents');
        $response->assertOk();
    }

    private function downloadFile(){
        $response = $this->actingAs($this->user)->get('tenant/'. env('TEST_TENANT_REF')  .'/document/download/'. env('TEST_DOC_REF'));
        $response->assertOk();
    }

}
