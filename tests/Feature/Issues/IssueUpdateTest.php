<?php

namespace Tests\Feature\Issues;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Feature\Issues\IssueCreateTest;
use App\Issue;
use App\User as AppUser;

class IssueUpdateTest extends IssueCreateTest
{
    use DatabaseTransactions;

    public function testIssueUpdate(){
        $this->testIssueCreation();
        $agentToMock = $this->getUserCreated();
        $this->assertNotNull($agentToMock);

        $updateStatus = 'Closed';
        $updateConfidential = false;

        $this->updateIssue($this->user, $updateStatus, $updateConfidential);

        $this->assertDatabaseHas('issues',[
            'id' => $this->issue->id,
            'attributes' => $updateStatus
        ]);
    }

    /**
     * @group issue
     */
    private function updateIssue(AppUser $actor, $updateStatus, $updateConfidential, $issue = null){
        $updateTitle = 'TitleUpdated';
        $updateMainDescription = 'ThisMainDescriptionIsUpdatedTest';
        $updateDueDate = "2020-05-19 22:31:00";
        $updatePriority = 5;

        if(!$issue){
            $issue = $this->issue;
        }

        $this->actingAs($actor)->post('/issue/' . $issue->id . '/updateIssue',[
            'status' => $updateStatus,
            'mainDescription' => $updateMainDescription,
            'title' => $updateTitle,
            'confidential' => $updateConfidential,
            'duedate' => $updateDueDate,
            'priority' => $updatePriority
        ]);
    }

    /**
     * @group issue
     */
    public function testAccessToConfidential()
    {
        $agent = $this->createAgent();
        $this->createProfile();
        $property = $this->createProperty($agent->user->sub, $agent->agent->agency_id);
        $tenant = $this->createTenant($property->id);
        $this->createStream();
        $issue = $this->createIssue();
        $this->logIn($tenant->user);
        $this->updateIssue($tenant->user, 'Open', 'this should not update', $issue);

        //assert the issue has not been updated
        $this->assertDatabaseHas('issues',[
            'id' => $issue->id,
            'description' => $issue->description,
            'attributes' => $issue->attributes
        ]);
    }

}
