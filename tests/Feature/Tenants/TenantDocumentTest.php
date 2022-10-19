<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\CoreFeatureTest;
use App\Properties;
use App\Tenant;
use Invitation;
use Auth;

class TenantDocumentTest extends TestCase
{

    use CoreFeatureTest; 
    use WithFaker;
    use DatabaseTransactions;

    private $property;
    private $inviteCode;

    public function testTenantCreation(){
        $this->createLoggedInUser();
        $this->createAgent(); 
        $this->createProfile();
        $this->createProperty();
        $this->createTenant();
        $this->getTenantData();
        $this->checkDocuments();
        $this->checkUpload();
   }

    private function createProperty(){
        $this->property = factory(Properties::class)->create([
            'agent_id' => $this->agency->id,
            'created_by_user_id' => $this->user->sub
        ]);
    }

    private function createTenant(){
        $this->tenantToCreate = array(
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'property_id' => $this->property->id
        );
        $response = $this->actingAs($this->user)->post('/tenant/store',$this->tenantToCreate);
        $this->assertDatabaseHas('tenants',[
            'property_id' => $this->property->id,
            'email' => $this->tenantToCreate['email']
        ]);
    }

    private function getTenantData(){
        $this->tenantToCreate['inviteCode'] = \App\Invitation::where('email', $this->tenantToCreate['email'])->first()->code;
        $this->tenantToCreate['id'] = \App\Tenant::where('email', $this->tenantToCreate['email'])->first()->id;
   }

    private function checkDocuments(){
        $response = $this->actingAs($this->user)->get('/tenant/' . $this->tenantToCreate['id'] . '/documents');
        $response->assertOk();
    }

    private function checkUpload(){
        Storage::fake('testing');
        $response = $this->actingAs($this->user)->post('/tenant/' . $this->tenantToCreate['id'] . '/document/upload',
            [
                'fileType' => 'Feature Test',
                'document' => Uploadedfile::fake()->create('document.pdf', 25)
            ]
        );        
        $this->assertDatabaseHas('document_storage', 
            [
                'type' => 'tenant',
                'linked_to' => $this->tenantToCreate['id']
            ]
        );
    }

}
