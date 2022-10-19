<?php

namespace Tests\Feature\Issues;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Properties;
use App\Stream;
use App\Issue;
use Tests\Traits\CoreFeatureTest;


class IssueCreateTest extends TestCase
{

    use CoreFeatureTest;
    use DatabaseTransactions;

    private $property;
    private $coreTestFeatures;
    protected $issue;

    public function getUserCreated(){
        return $this->user;
    }

    public function getPropertyCreated(){
        return $this->property;
    }

    public function getIssueCreated(){
        return $this->issue;
    }

    public function testIssueCreation(){
        $this->createLoggedInUser();
        $this->createAgent();
        $this->createProfile();
        $this->createProperty();
        $this->createTenant($this->property->id);
        $this->createStream();
        $this->createIssue();
    }

    protected function createProperty($userId = null, $agencyId = null){
        $this->property = factory(Properties::class)->create([
            'created_by_user_id' => $userId ? : $this->user->sub,
            'agent_id' => $agencyId ? : $this->agency->id
        ]);
        return $this->property;
    }

    protected function createStream(){
        //Create a Stream
	   	$newStream = new Stream();
        $newStream->extra_attributes->broadcastOnly = 'false';
	   	$newStream->extra_attributes->property_id = $this->property->id;
		$newStream->save();
    }

    protected function createIssue(){
        $description = 'IssueCreateTest';
        $response = $this->actingAs($this->user)->post('/issue/store',[
            'property_id' => $this->property->id,
            'title' => 'IssueCreateTest',
            'description' => $description
        ]);
        $this->issue = Issue::where('property_id', $this->property->id)
            ->where('description', $description)
            ->first();

        $this->assertDatabaseHas('issues',[
            'property_id' => $this->property->id,
            'description' => $description
        ]);
        return $this->issue;
    }

}
