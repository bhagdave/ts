<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Mail;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Agent;
use App\Agency;
use App\Invitation;
use App\Properties;
use App\Jobs\AddNewAgentToExistingProperties;
use App\Mail\EmailReminder;
use Illuminate\Support\Str;
use App\Traits\inviteUser;

class InviteAgent extends Controller
{
    use inviteUser;
    
    public function __invoke(Request $request){
        $user = User::current(); 
        if ($user->userType != 'Tenant'){
            return redirect()->back()->with('message', 'You cannot invite an agent.')->withInput();
        }
        if (!isset($user->tenant->property_id)){
            return redirect()->back()->with('message', 'You cannot invite an agent until you have created a property.')->withInput();
        }
        $property = Properties::find($user->tenant->property_id);
        if ($property->agent_id){
            return redirect()->back()->with('message', 'You already have an agent attached to the property.')->withInput();
        }
        return view('agents.invite', compact('user'));
    }

    public function createInvite(Request $request){
        $validator = $this::validateRequest($request);
        if (!is_null($validator)){
            return $validator;
        }
        $userForAgent = $this->createUserRecord();
        $agency = $this->createAgencyRecord();
        $this->createAgentRecord($userForAgent->sub, $agency->id);
        $this->createInviteToAgent();
        return redirect('/')->with('message',  'Invite sent successfully');   
    }

    private function validateRequest($request){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('message', 'Invite could not be sent, please retry.')
                ->withInput()->withErrors($validator);
        }
        return null;
    }

    private function createUserRecord(){
        $user = new User([
            'name' => request('name'),
            'companyName' => request('name'),
            'email' => request('email'),
            'userType' => 'Agent',
            'sub' => 'Agent_'.Str::random(24),
            'registered' => 0
        ]);
        $user->save();
        return $user;
    }

    private function createAgencyRecord(){
        $agency = new Agency(
            [
                'company_name' => request('name')
            ]
        );
        $agency->save();
        return $agency;
    }
    private function createAgentRecord($userSub, $agencyId){
        $agent = New Agent([
            'name' => request('name'),
            'user_id' => $userSub,
            'agency_id' => $agencyId
        ]);
        $agent->save();
    }

    private function createInviteToAgent(){
        $mailData = $this->createDataForEmail(); 
        $this->inviteNewAgent($mailData);   
    }

    private function createDataForEmail(){
        $user = User::current();
        $userName = $user->firstName;
        $emailData = array(
            'name' => request('name'),
            'email' => request('email'),
            'subject' => $userName . " has invited you to join them in TenancyStream.com",
            'userName' => $userName
        );
        if ($user->userType == 'Tenant'){
            $tenant = $user->tenant;
            if (!is_null($tenant)){
                $emailData['property_id'] = $tenant->property_id;    
            }
        }
        return $emailData;
   }
    public function index(Request $request){
        $user = Auth::user();
        if ($user->userType != 'Agent') {
            return redirect()->back()
                ->with('message', 'You do not have privileges to add users');
        }
        return view('users.invite', compact('user'));
    }

    public function store(Request $request){
        $user = Auth::user();
        if ($user->userType != 'Agent') {
            return redirect()->back()
                ->with('message', 'You do not have privileges to add users');
        }
        $invites = $request->input();
        $agencyId = $user->agent->agency_id;
        foreach($invites as $invite){
            // Check if already invited and send reminder
            $existingInvite = $this->getExistingInvite($invite);
            if (isset($existingInvite)){
                $this->sendInviteReminder($existingInvite);
                continue;
            }
            $newUser = $this->createInvitedAgent($invite, $user, $agencyId);
            if (isset($user->agent->agency->stream_id)){
                AddNewAgentToExistingProperties::dispatch($newUser, $agencyId, $user->agent->agency->stream_id);
            }
            $this->sendInviteToAgent($invite, $user);
        }
        return response()->json(null, 200);
    }
    
    private function getExistingInvite($invite){
        return Invitation::where('email', $invite['email'])->first();
    }

    private function sendInviteReminder($invitation){
        if ($invitation->email) {
            $mailData = [
                'subject' => 'Tenancy Stream Invite',
                'email' => $invitation->email,
                'refCode' => $invitation->code,
            ];
            Mail::to($mailData['email'])->queue(new EmailReminder($mailData));
        }
    }

    private function createInvitedAgent($invite, $user, $agencyId){
        $user = new User([
            'name' => $invite['name'],
            'companyName' => $user->companyName,
            'email' => $invite['email'],
            'userType' => 'Agent',
            'sub' => 'AGCOL'.Str::random(24),
            'registered' => 0
        ]);
        $user->save();
        $agent = New Agent([
            'name' => $invite['name'],
            'user_id' => $user->sub,
            'agency_id' => $agencyId
        ]);
        $agent->save();
        return $user;
    }

    private function sendInviteToAgent($invite, $user){
        $emailData = array(
            'name' => $invite['name'],
            'email' => $invite['email'],
            'subject' => $user->firstName . " has invited you to join them on TenancyStream.app",
            'userName' => $user->firstName
        );
        $this->inviteNewAgent($emailData);   
    }
}
