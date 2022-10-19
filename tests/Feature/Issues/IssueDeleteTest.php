<?php

namespace Tests\Feature\Issues;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Properties;
use App\Stream;
use App\Issue;
use Tests\Traits\CoreFeatureTest;


class IssueDeleteTest extends IssueCreateTest
{

    use DatabaseTransactions;

    public function testIssueDeletion(){
        $this->createLoggedInUser();
        $this->createAgent();
        $this->createProfile();
        $this->createProperty();
        $property = $this->getPropertyCreated();
        $this->createTenant($property->id);
        $this->createStream();
        $this->createIssue();
        $this->deleteIssue();
    }

    public function deleteIssue(){
        $issue = $this->getIssueCreated();
        $mockUser = $this->getUserCreated();
        $this->actingAs($mockUser)->delete('/issue/' . $issue->id . '/delete');
        $this->assertSoftDeleted('issues', ['id' => $issue->id]);
    }

}
