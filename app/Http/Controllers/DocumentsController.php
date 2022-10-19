<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Properties;
use App\Tenant;
use App\Documents;

class DocumentsController extends BaseDocumentController
{
    public function index(){
        // Please note that the properties returned are restricted by a global scope define in the trait - MultiTenatedProperty
        $properties = Properties::all()->loadMissing('tenants');
        return view('documents.index', compact('properties'));
    }
    public function getDocumentCounts(){
        // Please note that both tenants and property queries are restyircyed by traits 
        $properties = Properties::all()->pluck('id');
        $propertyCount = Documents::where('type', 'property')->whereIn('linked_to', $properties)->count();
        $tenants = Tenant::all()->pluck('id');
        $tenantCount = Documents::where('type', 'tenant')->whereIn('linked_to', $tenants)->count();
        return ['propertyCount' => $propertyCount, 'tenantCount' => $tenantCount];
    }
}
