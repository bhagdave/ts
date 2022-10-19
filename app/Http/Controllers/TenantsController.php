<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use App\User;
use App\Stream;
use App\Jobs\RemoveTenantFromProperty;
use App\Tenant;
use App\Properties;
use App\Traits\UploadTrait;
use App\Traits\inviteUser;
use Illuminate\Support\Facades\Validator;
use Auth;
use Mail;
use App\Mail\ExistingTenantPropertyAttached;
use App\Mail\ExistingTenantPropertyInvite;

use App\Events\StreamUpdated;


class TenantsController extends Controller
{

    use UploadTrait;
    use inviteUser;

    public function __construct() {
        $this->middleware('auth');
    }

    public function getTenants($filter = null){
        // Please note that the tenants returned are restricted by a global scope define in the trait - MultiTenatedTenants
        $tenantsQuery = Tenant::orderBy('name');
        if ($filter){
            if ($filter == 'pending'){
                $tenantsQuery->whereNull('sub');
            }
            if ($filter == 'confirmed'){
                $tenantsQuery->whereNotNull('sub');
            }
        }
        return $tenantsQuery->get();
    }

    public function getTenantCounts(){
        $pending = Tenant::whereNull('sub')->count();
        $confirmed = Tenant::whereNotNull('sub')->count();
        return ['pendingTenants' => $pending, 'confirmedTenants' => $confirmed];
    }

    public function getProperties(){
       $properties = Properties::get();
        return $properties;
    }


    public function show($id)
    {
        $tenant = Tenant::find($id);
        if(is_null($tenant)){
            abort(404);
        } else{
            return view('tenants.show', compact('tenant'));
        }
    }

    public function delete($id) 
    {
        $user = User::current();

        $tenant = $this->getTenants()->find($id);
        if (!isset($tenant->property)){
            return redirect()->back()->with('message',  'That tenant deletion is being processed already.');
        }
        $streamId = null;
        if (isset($tenant->property->stream_id)){
            $streamId = $tenant->property->stream_id;
            $stream = Stream::find($streamId);

            $deleteMessage = $tenant->name . " was removed from the stream";        

            activity($streamId)
                    ->withProperties(['propertyId' => $tenant->property->id, 'messageType' => 'Event'])
                    ->log($deleteMessage);
                    event(new StreamUpdated($user, $tenant->property->id, $deleteMessage));
        }

        $tenant->notes = "Deletion Requested";
        $tenant->save();
        RemoveTenantFromProperty::dispatch($tenant->id, $tenant->property_id, $streamId);
        if(is_null($tenant)){
            abort(403);
        } else{
            return redirect('/tenants/')->with('message',  'The Tenant delete request has been received and will be processed shortly.');
        }        
    }

    public function store(Request $request) 
    {
        $user = User::current();
        if (!$user->subscribed){
            return redirect('payment');
        }
        $validator = $this->doValidationForStore($request);
        if ($validator->fails()) {
            return redirect()->back()->with('message',  'Tenant could not be created, please retry.')->withInput()->withErrors($validator);
        } 

        $property = $this->getProperty(request('property_id'));
        if(is_null($property)){
            return redirect()->back()->with('message',  'You do not have permissions to update tenants at that property.')->withInput();
        }

        $sub = $this->getTenantUserSub(request('email'));
        if (isset($sub)){
            return $this->attachExistingTenant($sub);
        } else {
            try{
                $newTenant = $this->createNewTenant([
                    'name' => request('name'),
                    'email' => request('email'),
                    'property_id' => $property->id,
                    'sub' => $sub,
                    'phone' => request('phone'),
                    'moveInDate' => request('moveInDate'),
                    'rentAmount' => request('rentAmount'),
                    'rentDueInterval' => request('rentDueInterval'),
                    'notes' => request('notes')
                ]);
                $emailData = $this->buildDataForInviteEmail($property);
                $this->inviteNewTenant($emailData);
            } catch (\Exception $e){
                return redirect()->back()->with('message',  'There was a problem creating the invite. Please contact Tenancy Stream with the issue no:TC-01')->withInput();
            }
        }
        return redirect('/tenant/'.$newTenant->id)->with('message',  'Tenant created successfully');
    }

    private function doValidationForStore($request){
         $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => ['required', 'string', 'email', 'max:255'],
            'property_id' => 'required',
        ]);
        return $validator;
    }

    private function getProperty($propertyId){
        return $this->getProperties()->where('id', $propertyId)->first();
    }

    private function getTenantUserSub($email){
        if (User::where('email',$email)->exists()) {
            $sub = User::where('email',$email)->first()->sub;
            return $sub;
        }
        return null;
    }

    private function attachExistingTenant($userSub){
        // DB Facade used to bypass the multitenanttenancy code - DG
        //
        $user = User::where('sub', '=', $userSub)->first();
        if ($user->userType != 'Tenant'){
            return redirect()->back()->with('message',  'That email address already exists on the system.')->withInput();
        }
        $tenant = DB::table('tenants')->where('email', '=', $user->email)->first();
        if (isset($tenant)){
            if (isset($tenant->property_id)){
                $this->sendEmailToTenantAttemptedInvite(request('property_id'));
                return redirect()->back()->with('message',  'That user is already attached to an existing property on Tenancy Stream.')->withInput();
            }
            $this->sendEmailToTenantInvitedProperty(request('property_id'));
            DB::table('tenants')->where('id', '=', $tenant->id)
                ->update(['property_id' => request('property_id')]);
            return redirect()->back()->with('message',  'Tenant already on system and invited to property.');
        }
    }

    private function sendEmailToTenantAttemptedInvite($propertyId){
        $property = Properties::find($propertyId);
        if ($property){
            $mailData = $this->buildDataForInviteEmail($property);
            $mailData['subject'] = "Tenancy Stream Attempt to change property";
            $emailToSend = new ExistingTenantPropertyInvite($mailData);
            Mail::to($mailData['email'])->queue($emailToSend);
        }
    }

    private function sendEmailToTenantInvitedProperty($propertyId){
        $property = Properties::find($propertyId);
        if ($property){
            $mailData = $this->buildDataForInviteEmail($property);
            $mailData['subject'] = "Tenancy Stream Invited to new Property";
            $emailToSend = new ExistingTenantPropertyAttached($mailData);
            Mail::to($mailData['email'])->queue($emailToSend);
        }
    }

    private function createNewTenant($tenantData){
        $newTenant = new Tenant($tenantData);
        $newTenant->save();
        return $newTenant;
    }

    private function buildDataForInviteEmail($property){
        $agency = $property->agency;
        $userName = User::current()->firstName;
        if (isset($agency)){
            $subject = $userName . " from " . $agency->company_name . " has invited you to join them in TenancyStream.com";
            $agencyName = $agency->company_name;
        } else {
            $subject = $userName . " has invited you to join them in TenancyStream.com";
            $agencyName = '';
        }
        $emailData = array(
            'name' => request('name'),
            'email' => request('email'),
            'property_address' => $property->inputAddress,
            'agencyName' => $agencyName,
            'agentName' => $userName,
            'subject' => $subject,
        );
        return $emailData;
   }

    public function update(Request $request, $id) 
    {

        $redirect = $this->redirectIfNoPermission();
        if ($redirect){
            return $redirect;
        }
        $validator = $this->createValidatorForUpdate($request);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('message', 'Tenant could not be updated, please retry.')
                ->withInput()
                ->withErrors($validator);
        } 

               
        try{
            $originalEmail = Tenant::where('id', $id)->value('email');
            $tenant = $this->updateTenant($id, $request);
            if ($request->has('email')) {
                if (request('email') != $originalEmail) {
                    $this->resendInviteEmail($tenant);
                }
            }
        } catch (\Exception $e){
            return redirect()->back()->with('message',  'There was a problem updating the tenant. Please contact Tenancy Stream with the issue no:TC-02')->withInput();
        }
        return redirect()->action('TenantsController@index')
            ->with('message',  'Updated successfully');

    }

    private function redirectIfNoPermission(){
        $tenantsQuery = $this->getTenants();

        if(is_null($tenantsQuery)){
            return redirect()->back()
                ->with('message',  'You do not have permissions to update that tenant.')
                ->withInput();
        }
        $property = $this->getProperties()->where('id',request('property_id'))->first();
        if(is_null($property)) {
            return redirect()->back()
                ->with('message',  'You do not have permissions to update tenants at that property.')
                ->withInput();
         }
        return null;
   }


    private function createValidatorForUpdate(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'nullable',
            'email' => 'nullable',
            'property_id' => 'required',
            'phone' => 'nullable', 
            'rentAmount' => 'nullable', 
            'rentDueInterval' => 'nullable', 
            'notes'=> 'nullable',
            'moveInDate'=>'nullable',
            'profileImage' => 'nullable',
            ]
        );
        return $validator;
    }


    private function updateTenant($id, $request){
        $tenantToUpdate = [
            'property_id' => request('property_id'),
            'phone' => request('phone'),
            'rentAmount' => request('rentAmount'),
            'notes' => request('notes'),
            'rentDueInterval' => request('rentDueInterval'),
            'moveInDate' => request('moveInDate'),
        ];
        if ($request->has('email')) {
            $tenantToUpdate['email'] = request('email');
        }
        $updatedTenant = Tenant::where('id', $id)->update($tenantToUpdate);
        return $updatedTenant;
    }

    private function resendInviteEmail($tenant){
        $property = $this->getProperties()->where('id',request('property_id'))->first();
        $emailData = $this->buildDataForInviteEmail($property);
        $this->inviteNewTenant($emailData);
    }

    public function edit($id) 
    {
        $tenant = Tenant::find($id);

        if(is_null($tenant)){
            abort(404);
        } else{
            $properties = $this->getProperties();
            return view('tenants.edit', compact('tenant','properties'));
        } 
    }

    public function index(Request $request) 
    {
        $properties = $this->getProperties();
        $filter = $request->query('filter');
        $tenantsQuery = $this->getTenants($filter);

        $tenants = (new Collection($tenantsQuery))->paginate(7);
        return view('tenants.index', compact('tenants','filter','properties'));
    }

    public function search(Request $request){
        $search = $request->input('search');
        $properties = $this->getProperties();
        $tenantQuery = Tenant::where('name', 'like','%' . $search . '%' )
            ->orWhere('email', 'like', '%' . $search . '%')->get();
        $tenants = (new Collection($tenantQuery))->paginate(7);
        return view('tenants.index', compact('tenants','properties', 'search'));
    }

    public function indexByProperty(Request $request, $id){
        //Only index tenants which belong to properties owned by the landlord
        $tenantsQuery = Tenant::where('property_id',$id)->orderBy('name')->get();

        $filter = $request->query('filter');
        $property = Properties::find($id);
        if(is_null($property)){
            abort(404);
        } 
        if (count($tenantsQuery) == 0){
            return redirect()->back()->with('message',  'There are no tenants on that property');
        }

        $tenants = (new Collection($tenantsQuery))->paginate(20);
        return view('tenants.index', compact('tenants','filter','property'));
    }

    public function create(Request $request) 
    {
        $user = User::current();
        if (!$user->subscribed){
            return redirect('payment');
        }
        $properties = $this->getProperties();
        $property = Properties::find($request->input('property_id'));

        return view('tenants.create',compact('properties', 'property'));
    }

}
