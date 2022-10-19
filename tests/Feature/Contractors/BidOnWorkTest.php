<?php

namespace Tests\Feature\Contractors;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Contractor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Traits\CoreFeatureTest;

class BidOnWorkTest extends TestCase
{
    use CoreFeatureTest;
    use DatabaseTRansactions;

    private $contractor;

    public function testBid()
    {
        $this->createLoggedInUser('Contractor', 1);
        $this->createContractor();
        $this->createContractorProfile();
        $this->editProfile();
        $this->bidOnIssue();
    }
    private function createContractor(){
        $contractorUser = $this->user;
        if(!$contractorUser){
            $contractorUser = $this->createLoggedInUser();
        }
        $this->contractor = factory(Contractor::class)->create([
            'sub' => $this->user->sub
        ]);
    }
    
    private function createContractorProfile(){
        $this->actingAs($this->user)->get('/')->assertRedirect('http://127.0.0.1:8000/welcome');
        $response = $this->actingAs($this->user)->post('/profile/create')->assertRedirect('http://127.0.0.1:8000');
    }

    private function bidOnIssue(){
        $response = $this->actingAs($this->user)->followingRedirects()->get('/issue/' . env('TEST_ISSUE_BID') . '/bid');
        $this->assertDatabaseHas('contractor_issue',[
            'contractor_id' => $this->contractor->id,
            'issue_id' => env('TEST_ISSUE_BID')
        ]); 
    }

    private function editProfile(){
        $this->actingAs($this->user)->get('/profile/edit')->assertSeeText('Categories of work');
    }
}
