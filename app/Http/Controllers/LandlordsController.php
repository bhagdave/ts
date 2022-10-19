<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use App\User;
use App\Agent;
use App\Tenant;
use App\Landlord;
use App\Issue;
use App\Rent;
use App\Properties;
use App\Traits\UploadTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Traits\inviteUser;
use PDF;
use Carbon\Carbon;

class LandlordsController extends Controller
{

    use UploadTrait;
    use inviteUser;

    public function __construct() {
        $this->middleware('auth');
    }

    public function delete($id) 
    {
        $landlordQuery = \App\Landlord::where('agent_id', User::current()->agent->agency_id)->where('id',$id)->first();

        if(is_null($landlordQuery)){
             return redirect()->back()->with('message',  'You do not have permissions to update that landlord.')->withInput();
        }
        $landlords = \App\Landlord::find($id)->delete();
        return redirect('/landlords/')->with('message',  'Deleted successfully');
    }

    public function store(Request $request) 
    {
        $validator = $this->validateStoreRequest($request);
        if ($validator->fails()) { 
            return redirect()->back()->with('message',  'Landlord could not be created, please retry.')->withInput();
        } 

        $email = request('email');
        if ($this->checkIfLandlordExists($email)) {
            return redirect()->back()->with('message',  'Landlord could not be created as they already exist, please retry.')->withInput();
        }

        try{
            $this->createLandlord();
            $emailData = $this->createDataForEmail();
            $this->inviteNewLandlord($emailData);
        } catch (\Exception $e){
            return redirect()->back()->with('message',  'There was a problem creating the invite. Please contact Tenancy Stream with the issue no:LC-21')->withInput();
        }
        if (User::current()->userType == 'Tenant'){
            return redirect('/')->with('message', 'Landlord Invited');    
        } 
        return redirect()->action('LandlordsController@index')->with('message',  'Updated successfully');
    }


    private function validateStoreRequest($request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
        ]);
        return $validator;
    }

    private function checkIfLandlordExists($email){
        return User::where('email',$email)->exists(); 
    }

    private function createLandlord(){
        $user = User::current();
        $landlordRecord = [
            'name' => request('name'),
            'email' => request('email'),
        ];
        if ($user->userType == 'Agent'){
            $landlordRecord['agent_id'] = $user->agent->agency_id;
        }
        if ($user->userType == 'Tenant'){
            $landlordRecord['agent_id'] = null;
        }

        $newLandlord = new Landlord($landlordRecord);
        $newLandlord->save();
    }

    private function createDataForEmail(){
        $user = User::current();
        $agent = $user->agent;
        $userName = $user->firstName;
        $emailData = array(
            'name' => request('name'),
            'email' => request('email'),
            'agencyName' => null,
            'agentName' => $userName,
            'subject' => $userName . " has invited you to join them in TenancyStream.com"
        );
        if ($user->userType == 'Tenant'){
            $tenant = $user->tenant;
            if (!is_null($tenant)){
                $emailData['property_id'] = $tenant->property_id;    
            }
        }
        if ($user->userType == 'Agent'){
            $emailData['agencyName'] = $agent->name;
            $emailData['subject'] = $userName . " from " . $agent->name . " has invited you to join them in TenancyStream.com";
        }
        return $emailData;
    }
    
    public function update(Request $request, $id) 
    {
        $landlordQuery = \App\Landlord::where('agent_id', User::current()->agent->agency_id)->where('id',$id)->first();

        if(is_null($landlordQuery)){
             return redirect()->back()->with('message',  'You do not have permissions to update that landlord.')->withInput();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'nullable', 
            'notes'=> 'nullable',
            'moveInDate'=>'nullable',
            'profileImage' => 'nullable',

        ]);


        if ($validator->fails()) {
            return redirect()->back()->with('message',  'Landlord could not be updated, please retry')->withInput();
        } 
               
            try{
                $updateLandlord = \App\Landlord::where('id',$id)->update(
                    ['name' => request('name'),
                    'email' => request('email'),
                    'phone' => request('phone'),
                    'notes' => request('notes')
                ]);
            } catch (\Exception $e){
                return redirect()->back()->with('message',  'There was a problem updating the landlord. Please contact Tenancy Stream with the issue no:LC-01')->withInput();
            }
        
        return redirect()->action('LandlordsController@index')->with('message',  'Updated successfully');
    

    }

    public function edit($id) 
    {
        $landlord = \App\Landlord::where('id',$id)->first();
        if ($landlord){
            return view('landlords.edit', compact('landlord'));
        }
            return abort(404);
    }

    public function index() 
    {
        //Is handled by a trait, can be removed
        $landlordsQuery = \App\Landlord::get();
        $landlords = (new Collection($landlordsQuery))->paginate(20);
        return view('landlords.index', compact('landlords'));
    }

    public function create() 
    {
        return view('landlords.create');
    }

    public function show($id) 
    {
        $landlord = \App\Landlord::where('agent_id', User::current()->agent->agency_id)->where('id',$id)->first();
        if ($landlord) {
            return view('landlords.show', compact('landlord'));
        }
        return abort(404);
    }

    public function getContactsForMenu(){
        $user = Auth::user();
        $contacts= collect();
        if ($user->userType == 'Landlord'){
            if ($user->landlord->agent){
                $contacts= $this->getAgentsForLandlord($user->landlord->agent);
            }
            $tenants = $this->getTenantsForLandlord($user);
            $contacts = $contacts->concat($tenants);
            $sorted = $contacts->sortBy('name');
            return $sorted->values()->toJson();
        }
        return 'Err';
    }

    private function getAgentsForLandlord($agency){
        $data =  Agent::where('agency_id', '=', $agency->id)
            ->whereNull('agents.deleted_at')
            ->select('id', 'name');
        $data->addSelect(DB::raw("'Agent' as type"));
        return $data->get();
    }

    private function getTenantsForLandlord($user){
        $tenants = Tenant::where('created_by_user_id', '=', $user->sub)
            ->whereNotNull('tenants.sub')
            ->whereNull('tenants.deleted_at')
            ->join('properties', 'property_id', '=', 'properties.id')
            ->select('tenants.id', 'tenants.name');
        $tenants->addSelect(DB::raw("'Tenant' as type"));
        return $tenants->get();
    }
    public function report($landlordId){
        $landlord = Landlord::where('agent_id', User::current()->agent->agency_id)->where('id',$landlordId)->first();
        if ($landlord) {
            return view('landlords.report', compact('landlord'));
        }
        return abort(404);
    }

    public function generateReport(Request $request,$landlordId){
        $month = $request->input('month') . '-01';
        $date = Carbon::parse($month);
        $firstOfMonth = $date->firstOfMonth()->toDateTimeString();
        $endOfMonth = $date->endOfMonth()->toDateTimeString();
        $landlord = Landlord::where('agent_id', User::current()->agent->agency_id)
           ->where('id',$landlordId)
           ->first();
        if ($landlord->property) {
            $pdf = PDF::loadView('landlords.monthly-report', compact('landlord', 'date', 'firstOfMonth', 'endOfMonth'));
            $name = str_replace(' ', '', $landlord->name);
            return $pdf->download($name . '.pdf');
        } else {
            return redirect()->back()->with('message',  'That landlord has no properties attached.')->withInput();
        }
        return redirect()->back()->with('message',  'Something went wrong generating the report')->withInput();
    }
}
