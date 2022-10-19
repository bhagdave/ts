<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Properties;
use App\Agent;
use App\Tenant;
use App\Contractor;
use App\Landlord;
use App\Issue;
use App\Invitation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\AddUsersToNewPropertyStream;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
use Bouncer;
use Invite;
use Auth;

class RegisterController extends Controller
{

    protected $redirectTo = '/home';
    protected $validator;
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'companyName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function createUserFromInvite(Request $request)
    {
        $validatedRequest = $this->validateInvitation($request);
        if (!is_null($validatedRequest)){
            return $validatedRequest;
        }
        $code = request('code');
        $email = request('email');
        $invitation = Invite::get($code); 
        $user = $this->registerUserFromInvite($request);
        if (is_null($user)){
            return redirect()->back()->with('message', 'Invalid code, please retry.')->withInput();
        }
        Auth::loginUsingId($user->id, true);
        $user = User::current();
        $this->updateTenantRecord($user);
        $this->updateLandlordRecord($user, $invitation);
        $this->updatePropertyRecord($user, $invitation);
        $this->createContractorRecord($user, $invitation);
        return redirect('/welcome');
    }

    private function validateInvitation(Request $request){
        $validator = Validator::make($request->all(), [
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'code' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('message', 'Update could not be saved, please retry.')
                ->withInput()->withErrors($validator);
        }
        return null;
    }

    private function registerUserFromInvite(Request $request){
        $email = request('email');
        if( Invite::isAllowed(request('code'),$email) ){
            $user = User::where('email',$email)->first();
            if (is_null($user)){
                $user = new User();
                $user->sub = 'invited_'.Str::random(24);
                $user->userType = "undefined"; 
           }
            $invitation = Invite::get(request('code'));
            $user->password = Hash::make(request('password'));
            $user->email = $email;
            $user->firstName = request('firstName');
            $user->lastName = request('lastName');
            $user->save();
            Invite::consume(request('code'));
            return $user;
        }
        return null;
    }

    private function updateTenantRecord($user){
        if(Tenant::withoutGlobalScopes()->where('email','=',$user->email)->count() > 0){
            $user->userType = "Tenant"; 
            Tenant::withoutGlobalScopes()->where('email','=',$user->email)->update(['sub' =>  $user->sub]);
            $user->save();
            $tenant = Tenant::withoutGlobalScopes()->where('sub', '=', $user->sub)->first();
            if (isset($user->tenant)){
                if (isset($user->tenant->property)){
                    $streamId = $tenant->property->stream_id;
                    if (isset($streamId)){
                        AddUsersToNewPropertyStream::dispatch($user, $streamId);
                    }
                }
            }
        }
    }


    private function updateLandlordRecord($user, $invitation){
        if (Landlord::withoutGlobalScopes()->where('email','=',$user->email)->count() > 0){
            $user->userType = "Landlord"; 
            Landlord::withoutGlobalScopes()->where('email','=',$user->email)->update(['user_id' => $user->sub]);
            $user->save();
        }
    }
    private function updatePropertyRecord($user, $invitation){
        Auth::logout();
        Auth::login($invitation->user);
        if (!is_null($invitation->property_id)){
            $updatedRecord = Array('created_by_user_id' => $user->sub);
            if ($user->userType == 'Agent'){
                $agent = Agent::where('user_id', $user->sub)->first();
                if (!is_null($agent)){
                    $updatedRecord['agent_id'] = $agent->id;
                }
            }
            Properties::withoutGlobalScopes()->where('id', $invitation->property_id)->update($updatedRecord);
        }
        Auth::logout();
        Auth::loginUsingId($user->id, true);
    }

    private function createContractorRecord($user, $invitation){
        if (isset($invitation->issue_id)){
            $contractor = new Contractor();
            $contractor->sub = $user->sub;
            $contractor->name = $user->firstName . ' ' . $user->lastName;
            $contractor->email = $user->email;
            $contractor->save();
            $this->updateIssueWithContractor($invitation->issue_id, $contractor->id);
            $user->userType = "Contractor";
            $user->save();
            if ($invitation->agency_id){
                $contractor->agencies()->attach($invitation->agency_id);
            }
        }
    }

    private function updateIssueWithContractor($issueId, $contractorId){
        $issue = Issue::find($issueId);
        if (isset($issue)){
            $issue->contractors_id = $contractorId;
            $issue->save();
        }
    }
    protected function showInviteForm($refCode)
    {
        if( Invite::isValid($refCode))
        {
            $invitation = Invite::get($refCode); 
            $invited_email = $invitation->email;
            $referral_user = $invitation->user;
            $property = $this->getPropertyFromInvite($invitation);
            session(['invitation' => $refCode]);
            return view('auth.invite', compact('refCode','invited_email','referral_user', 'property'));
        }
        $status = Invite::status($refCode);
        abort(403, 'This invitation link is no longer valid. Please contact the sender and request a new invitation.');
   }

    private function getPropertyFromInvite($invite){
        $property = null;
        Auth::login($invite->user);
        if (!is_null($invite->property_id)){
            $property = Properties::withoutGlobalScopes()->where('id', $invite->property_id)->first();
        }
        Auth::logout();            
        return $property;
    }


    protected function addRegisterToSession($validatedData, $request){
        if(empty($request->session()->get('register'))){
            $register = new User();
            $register->fill($validatedData);
            $request->session()->put('register', $register);
        }else{
            $register = $request->session()->get('register');
            $register->fill($validatedData);
            $request->session()->put('register', $register);
        }
        if(empty($request->session()->get('agent'))){
            $agent = new Agent();
            $agent->fill($validatedData);
            $request->session()->put('agent', $agent);
        }else{
            $agent = $request->session()->get('agent');
            $agent->fill($validatedData);
            $request->session()->put('agent', $agent);
        }
    }

    protected function createNewUser($register, $userType){
         $user = User::create([
            'firstName' => $register->firstName,
            'lastName' => $register->lastName,
            'companyName' => $register->companyName,
            'email' => $register->email,
            'password' => Hash::make($register->password),
            'sub' => $userType.'_'.Str::random(24),
            'userType' => $userType,
            'registered' => 1,
            'telephone' => $register->telephone,
        ]);
        return $user;
   }

    public function showRegistrationForm(Request $request)
    {
        if ($request->session()->exists('invitation')){
            return redirect('/invite/' . $request->session()->get('invitation'));
        }
        $request->session()->forget('register');
        $register = $request->session()->get('register');
        return view('auth.multi-page.step1', compact('register'));
    }

    protected function checkForInvite($email){
        return Invitation::where('email',$email)->exists(); 
    }

    protected function createValidator($request, $validationArray){
        $this->validator = Validator::make($request->all(),$validationArray);
    }

    protected function checkIfEmailInUse($email){
        return User::where('email',$email)->exists(); 
    }

    protected function showView($view, Request $request)
    {
        $register = $request->session()->get('register');
        return view($view,compact('register'));
    }

}
