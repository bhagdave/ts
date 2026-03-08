<?php

namespace Tests\Feature\Contractors;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Contractor;
use App\Properties;
use App\Issue;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Traits\CoreFeatureTest;

class BidOnWorkTest extends TestCase
{
    use CoreFeatureTest;
    use DatabaseTransactions;

    private $contractor;
    private $issueId;

    public function testBid()
    {
        $this->createLoggedInUser('Contractor', 1);
        $this->createContractor();
        $this->createContractorProfile();
        $this->editProfile();
        $this->createTestIssue();
        $this->bidOnIssue();
    }
    private function createContractor(){
        $contractorUser = $this->user;
        if(!$contractorUser){
            $contractorUser = $this->createLoggedInUser();
        }
        $this->contractor = Contractor::factory()->create([
            'sub' => $this->user->sub
        ]);
    }

    private function createContractorProfile(){
        $this->actingAs($this->user)->get('/')->assertRedirect('http://127.0.0.1:8000/welcome');
        $response = $this->actingAs($this->user)->post('/profile/create')->assertRedirect('http://127.0.0.1:8000');
    }

    private function createTestIssue(){
        $property = Properties::factory()->create([
            'created_by_user_id' => $this->user->sub,
        ]);
        $issue = Issue::factory()->create([
            'property_id' => $property->id,
        ]);
        $this->issueId = $issue->id;
    }

    private function bidOnIssue(){
        $response = $this->actingAs($this->user)->followingRedirects()->get('/issue/' . $this->issueId . '/bid');
        $this->assertDatabaseHas('contractor_issue',[
            'contractor_id' => $this->contractor->id,
            'issue_id' => $this->issueId
        ]);
    }

    private function editProfile(){
        $this->actingAs($this->user)->get('/profile/edit')->assertSeeText('Categories of work');
    }
}
