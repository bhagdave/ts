<?php

namespace App\Http\Controllers;

use App\Agent;
use App\Stream;
use App\Landlord as Landlord;
use App\Properties;
use App\Traits\MultitenantableProperty;
use App\Traits\UploadTrait;
use App\User;
use App\Tenant;
use App\Issue;
use Auth;
use Bouncer;
use Geocoder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use App\Jobs\AddUsersToNewPropertyStream;
use App\Events\StreamUpdated;
use DataTables;
use Illuminate\Support\Facades\Schema;

class PropertiesController extends Controller {

    use UploadTrait;
    use MultitenantableProperty;

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $propertiesQuery = Properties::orderBy('propertyName')->get();
        $user = Auth::user();

        if (is_null($propertiesQuery)) {
            return redirect()->back()->with('message', 'You do not have access to that part of the site.')->withInput();
        } else {
            $properties = (new Collection($propertiesQuery))->paginate(7);
            return view('properties.index', compact('properties'));
        }
    }

    public function indexData() {
        $propertiesQuery = Properties::orderBy('propertyName')->get();

        if (is_null($propertiesQuery)) {
            abort(403);
        } else {
            return DataTables::of($propertiesQuery)->toJson();
        }
    }

    public function indexProperties($landlordId) {
        $sub = \App\Landlord::where('id', $landlordId)->first()->user_id;
        $propertiesQuery = \App\Properties::where('created_by_user_id', $sub)->get();

        if (is_null($propertiesQuery)) {
            return redirect()->back()->with('message', 'You do not have access to that part of the site.')->withInput();
        } else {
            $properties = (new Collection($propertiesQuery))->paginate(20);
            return view('properties.landlordProperties', compact('properties'));
        }
    }

    public function create() {
        $user = User::current();
        if (!$user->subscribed){
            return redirect('payment');
        }
        $agentOwned = false;
        $canTenantCreate = $this->checkIfTenantCanCreateAProperty();
        if (!is_null($canTenantCreate)) {
            return $canTenantCreate;
        }
        $landlords = $this->getLandlordsRecordsForDropdown();
        if (Bouncer::is($user)->a('landlord')) {
            $landlords = "";
            if(!empty(Landlord::where('user_id', $user->sub)->first()->agent_id)) {
                $agentOwned = true;
            }
        }
        return view('properties.create', compact('user','landlords','agentOwned' ));
    }

    private function checkIfTenantCanCreateAProperty(){
        $user = User::current();
        if (Bouncer::is($user)->a('tenant')) {
            if ($user->tenant->property_id) {
                return redirect()->back()->with('message', 'As a tenant you can only create one property.')->withInput();
            }
        }
        return null;
    }

    private function getLandlordsRecordsForDropdown(){
        $landlords = '';
        $user = User::current();
        if (Bouncer::is($user)->a('agent')) {
            $landlords = Landlord::where('agent_id', $user->agent->agency_id)
                ->whereNotNull('user_id')    
                ->get();
        }
        
        return $landlords;
    }

    public function store(Request $request) {
        $user = User::current();
        if (!$user->subscribed){
            return redirect('payment');
        }
        $validator = $this->createValidator($request);
        if ($validator->fails()) {
            return redirect()->back()->with('message', 'Update could not be saved, please retry.')->withInput();
        } 
        try {
            Schema::disableForeignKeyConstraints();
            $this->getAddressLatLang();
            $this->getAgentIdToAddToProperty($user);
            $this->getCreatorsSub($user, $request);
            $newProperty = $this->createNewPropertyRecord($user);
            $this->addPropertyToTenant($newProperty, $user);
        } catch (\Exception $e) {
            return redirect()->back()->with('message',  'There was a problem creating the property. Please contact Tenancy Stream with the issue no:PC-01')->withInput();
        } finally {
            Schema::enableForeignKeyConstraints();
        }
        $streamId = $this->createNewPropertyStream($newProperty->id);
        AddUsersToNewPropertyStream::dispatch($user, $streamId);
        if ($user->userType == 'Agent'){
            $privateStreamId = $this->createPrivatePropertyStream($newProperty->id);
            AddUsersToNewPropertyStream::dispatch($user, $privateStreamId);
        }
        if ($user->userType == 'Tenant') {
            return redirect('/');
        }        
        return redirect('/tenant/create?property_id=' . $newProperty->id)->with('message', 'Property Created - Now Add A Tenant');
    }

    private function createValidator($request){
        $validator = Validator::make($request->all(), [
            'propertyName' => 'required',
            'propertyType' => 'required',
            'totalRent' => 'nullable',
            'tenantsTotal' => 'nullable',
            'inputAddress' => 'required',
            'inputAddress2' => 'nullable',
            'inputCity' => 'required',
            'inputRegion' => 'required',
            'inputPostCode' => 'required',
        ]);
        return $validator;
    }

    private function getAddressLatLang(){
        $this->address = Geocoder::getCoordinatesForAddress(request('inputAddress') . ',' . request('inputCity') . ',' . request('inputPostCode'));

        if (isset($this->address['lat']) && isset($this->address['lng'])) {
            $this->lat = $this->address['lat'];
            $this->lng = $this->address['lng'];
        } else {
            $this->lat = 0;
            $this->lng = 0;
        }
    }
    
    private function getAgentIdToAddToProperty($user){
        if (Auth::user()->userType == "Agent") {
            $this->agent_id = $user->agent->agency_id;
        } else {
            $this->agent_id = 0;
        }

        if(!empty(\App\Landlord::where('user_id', $user->sub)->first()->agent_id)){
            $this->agent_id = \App\Landlord::where('user_id', $user->sub)->first()->agent_id;
        }
    }

    private function getCreatorsSub($user){
        if (request()->has('addToLandlordAccount')) {
            $this->creator = request('addToLandlordAccount');
        } else {
            $this->creator = $user->sub;
        }
    }

    private function createNewPropertyRecord($user){
        $newProperty = new Properties(
            [
            'propertyName' => request('propertyName'),
            'propertyType' => request('propertyType'),
            'totalRent' => request('totalRent'),
            'tenantsTotal' => request('tenantsTotal'),
            'inputAddress' => request('inputAddress'),
            'inputAddress2' => request('inputAddress2'),
            'inputCity' => request('inputCity'),
            'inputRegion' => request('inputRegion'),
            'inputPostCode' => request('inputPostCode'),
            'agent_id' => $this->agent_id,
            'propertyLat' => $this->lat,
            'propertyLng' => $this->lng,
            'created_by_user_id' => $this->creator,
            ]
    );
        $newProperty->save();
        return $newProperty;
    }

    private function addPropertyToTenant($property, $user){
        if ($user->userType == 'Tenant') {
            if (!isset($user->tenant->property_id)) {
                $tenant = $user->tenant;
                if (!is_null($tenant)) {
                    $tenant->property_id = $property->id;
                    $tenant->save();
                }
            }
        }
    }

    private function createNewPropertyStream($propertyId){
        $newStream = new Stream();
        $newStream->extra_attributes->property_id = $propertyId;
        $newStream->save();
        $this->updatePropertyWithStream($propertyId, $newStream->id); 
        $message = "Welcome to your new property stream.  This is a place for you to communicate with everyone connected to your property.";
        $this->createInitialStreamMessage($newStream->id, $propertyId, $message);
        return $newStream->id;
    }

    private function updatePropertyWithStream($propertyId, $streamId){
        $property = Properties::find($propertyId);
        if ($property){
            $property->stream_id = $streamId;
            $property->save();
       }
    }

    private function createPrivatePropertyStream($propertyId){
        $newStream = new Stream();
        $newStream->extra_attributes->broadcastOnly = 'true';
        $newStream->extra_attributes->property_id = $propertyId;
        $newStream->private = true;
        $newStream->save();
        $this->updatePropertyWithPrivateStream($propertyId, $newStream->id); 
        $message = "Welcome to your private property stream.  This is a place for you to communicate without the tenants seeing.";
        $this->createInitialStreamMessage($newStream->id, $propertyId, $message);
        return $newStream->id;
    }

    private function updatePropertyWithPrivateStream($propertyId, $streamId){
        $property = Properties::find($propertyId);
        if ($property){
            $property->private_stream_id = $streamId;
            $property->save();
       }
    }
    private function createInitialStreamMessage($streamId, $propertyId, $message){
            activity($streamId)
                ->causedBy(User::current()->sub)
                ->withProperties(['propertyId' => $propertyId, 'messageType' => 'Event' ])
                ->log($message);
    }


    public function update(Request $request, $id) {
        $property = Properties::find($id); 
        $streamId = $property->stream_id;
        $this->getAddressLatLang($request);

        $validator = $this->createValidatorForUpdate($request);
        if ($validator->fails()) {
            return redirect()->back()->with('message', 'Update could not be saved, please retry.')->withErrors($validator)->withInput();
        }
        if ($request->has('addToLandlordAccount')) {
            $creator = request('addToLandlordAccount');
        } else {
            $creator = Auth::user()->sub;
        }
        $profileImage = $this->uploadPropertyImage($request, $property);

        try {
            $property = Properties::where('id', $id)
                ->update(
                    ['propertyName' => request('propertyName'),
                        'totalRent' => request('totalRent'),
                        'tenantsTotal' => request('tenantsTotal'),
                        'inputAddress' => request('inputAddress'),
                        'inputAddress2' => request('inputAddress2'),
                        'inputCity' => request('inputCity'),
                        'inputRegion' => request('inputRegion'),
                        'propertyNotes' => request('propertyNotes'),
                        'inputPostCode' => request('inputPostCode'),
                        'profileImage' => $profileImage,
                        'propertyLat' => $this->lat,
                        'propertyLng' => $this->lng,
                        'created_by_user_id' => $creator,
                    ]
                );
        } catch (\Exception $e) {
            abort(404);
        }
        return redirect('/stream/' . $streamId)->with('message', 'Property updated successfully');
    }

    private function createValidatorForUpdate($request){
         $validator = Validator::make($request->all(), [
            'propertyName' => 'required',
            'totalRent' => 'nullable',
            'tenantsTotal' => 'nullable',
            'inputAddress' => 'required',
            'inputAddress2' => 'nullable',
            'inputCity' => 'required',
            'inputPostCode' => 'required',
        ]);
        return $validator;
   }

    private function uploadPropertyImage(Request $request, $property){
        if ($request->has('profileImage')) {
            $image = $request->file('profileImage');
            $name = str_slug($request->input('name')) . '_' . time();
            $profileImage =  $this->uploadOne($image, 'public', $name);
        } else {
            $profileImage = $property->profileImage;
        }
        return $profileImage;
    }

    public function show($id) {
        $property = \App\Properties::find($id);

        if (is_null($property)) {
            abort(404);
        } else {
            return view('properties.show', compact('property'));
        }
    }

    public function edit($id) {
        $property = \App\Properties::find($id);
        $user = User::current();

        if ($user->isAn('agent')) {
            $landlords = $this->getLandlordsRecordsForDropdown();
            $stream = $property->stream;
            return view('properties.edit', compact('stream', 'property', 'landlords'));
        } 
        $landlords = null;
        return view('properties.edit', compact('property', 'landlords'));
    }

    public function delete($id) {
        $user = Auth::user();
        try {
            $property = \App\Properties::where('id', $id)->delete();
            $this->deleteIssuesForProperty($id);
        } catch (\Exception $e) {
            abort(404);
        }
        
        $message ="Property was deleted";
        $this->createDeleteActivityRecord($user, $id, $message);
        event(new StreamUpdated($user, $id, $message));
         
        return redirect('/property/')->with('message', $message);
    }

    private function deleteIssuesForProperty($propertyId){
        $issuesQuery = \App\Issue::where('property_id',$propertyId)->get();
        foreach($issuesQuery as $issue) 
        {
            $issue->delete();
        }
    }
    
    private function createDeleteActivityRecord($user, $propertyId, $message){
        activity("DeletedProperty")
            ->causedBy($user->sub)
            ->withProperties(['propertyId' => $propertyId])
            ->log($message);
    }

}
