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

class RegisterLandlordController extends RegisterController
{

    protected $redirectTo = '/home';
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function createLandlord(Request $request){
        $validatedData = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $this->addRegisterToSession($validatedData, $request);
        $register = $request->session()->get('register');
        $user = $this->createNewUser($register, 'Landlord');
        Auth::login($user);

        Mail::to(["dave@tenancystream.com","james@tenancystream.com", "natalie@tenancystream.com", "phil@tenancystream.com"])->queue(new WelcomeMail($user, "Landlord"));
        return redirect("/welcome");
    }

    public function landlord(Request $request){
        if ($request->session()->exists('invitation')){
            return redirect('/invite/' . $request->session()->get('invitation'));
        }
        $request->session()->forget('register');
        $register = $request->session()->get('register');
        return view('auth.multi-page.landlord', compact('register'));
    }

    public function postLandlordStep1(Request $request){
        if ($this->validateStep1($request)) {
            return redirect()->back()->with('message',  'Account could not be created, please retry.')->withInput()->withErrors($this->validator);
        } 
        $validatedData = $this->validator->valid();
        $this->addRegisterToSession($validatedData, $request);
        return redirect('/register/landlordStep2');
    }


    private function validateStep1(Request $request){
        $this->createValidator($request,['email' => 'required']);
        $this->validator->after(function () {
            if ($this->checkIfEmailInUse(request('email'))) {
                $this->validator->errors()->add('email', 'Email address already in use!');
            }
        });
        return $this->validator->fails(); 
    }

    public function landlordStep2(Request $request)
    {
        return $this->showView('auth.multi-page.landlordStep2', $request);
    }

    public function postLandlordStep2(Request $request)
    {
        $validatedData = $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
        ]);
        $this->addRegisterToSession($validatedData, $request);
        return redirect('/register/landlordStep3');
    }

    public function landlordStep3(Request $request)
    {
        return $this->showView('auth.multi-page.landlordStep3', $request);
    }

}
