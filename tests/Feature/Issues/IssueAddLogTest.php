<?php

namespace Tests\Feature\Issues;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Feature\Issues\IssueCreateTest;

class IssueAddLogTest extends IssueCreateTest
{
    use DatabaseTransactions;

    private $agentToMock;
    private $issueToUpdate;

    /**
     * @group issue
     */
    public function testIssueUpdate(){
        $this->testIssueCreation();
        $this->agentToMock = $this->getUserCreated();
        $this->issueToUpdate = $this->getIssueCreated();
        $this->assertNotNull($this->agentToMock);
        $this->updateIssue();
    }

    private function updateIssue(){
        $this->actingAs($this->user)->post('/issue/' . $this->issueToUpdate->id . '/add-log-entry',[
            'description' => 'IssueUpdateTest',
            'status' => 'Closed'
        ]);
        $this->assertDatabaseHas('issues',[
            'id' => $this->issueToUpdate->id,
            'description' => 'IssueUpdateTest'
        ]);
    }

}
