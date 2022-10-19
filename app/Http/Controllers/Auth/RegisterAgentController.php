<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
use Bouncer;
use Invite;
use Auth;
use App\Http\Controllers\Auth\RegisterController;

class RegisterAgentController extends RegisterController
{

    protected $redirectTo = '/home';
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $this->addRegisterToSession($validatedData, $request);
        $register = $request->session()->get('register');
        if (!isset($register['email'])){
            return redirect('/register')->with('message',  'We seem to have misplaced your email address..')->withInput();
        }
        $user = $this->createNewUser($register, 'Agent');
        Auth::login($user);

        Mail::to(["dave@tenancystream.com", "natalie@tenancystream.com", "phil@tenancystream.com"])->queue(new WelcomeMail($user, "Agent"));
        return redirect("/welcome");
    }

    public function PostStep1(Request $request)
    {
        $validatedData = $request->validate(['firstName' => 'required', 'lastName' => 'required']);
        $this->addRegisterToSession($validatedData, $request);
        return redirect('/register/step2');
    }



    public function Step2(Request $request)
    {
        $register = $request->session()->get('register');
        return view('auth.multi-page.step2',compact('register'));
    }

    public function PostStep2(Request $request)
    {
        if ($this->validateStep2($request)) {
            return redirect()->back()->with('message',  'Account could not be created, please retry.')->withInput()->withErrors($this->validator);
        } 
        $validatedData = $this->validator->valid();
        $this->addRegisterToSession($validatedData, $request);
        return redirect('/register/step3');
    }
    private function validateStep2(Request $request){
        $this->createValidator($request,['email' => 'required', 'telephone' => 'required']);
        $this->validator->after(function () {
            if ($this->checkforInvite(request('email'))) {
                $this->validator->errors()->add('email', 'You already have an invite.  Please use the link in the email to register!');
            }
            if ($this->checkIfEmailInUse(request('email'))) {
                $this->validator->errors()->add('email', 'Email address already in use!');
            }
        });
        return $this->validator->fails(); 
    }

    public function Step3(Request $request)
    {  
        $register = $request->session()->get('register');
        if (!isset($register['email'])){
            return redirect('/registerForm')->with('message',  'We seem to have misplaced your email address..')->withInput();
        }
        return view('auth.multi-page.step3',compact('register'));
    }

    public function PostStep3(Request $request)
    {
        $validatedData = $request->validate([
            'companyName' => 'required',
        ]);
        $this->addRegisterToSession($validatedData, $request);
        return redirect('/register/step4');
    }

    public function Step4(Request $request)
    {  
        $register = $request->session()->get('register');
        if (!isset($register['email'])){
            return redirect('/register')->with('message',  'We seem to have misplaced your email address..')->withInput();
        }
        return view('auth.multi-page.step4',compact('register'));
    }


}
