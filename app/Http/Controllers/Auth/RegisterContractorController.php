<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Contractor;
use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
use Invite;
use Auth;
use App\Http\Controllers\Auth\RegisterController;

class RegisterContractorController extends RegisterController
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
        $user = $this->createNewUser($register, 'Contractor');
        $contractor = $this->createContractorRecord($user);
        $categories = $request->session()->get('categories');
        $this->createCategoriesRecords($contractor, $categories);
        Auth::login($user);

        Mail::to(["dave@tenancystream.com","james@tenancystream.com", "natalie@tenancystream.com", "phil@tenancystream.com"])->queue(new WelcomeMail($user));
        return redirect("/welcome");
    }

    private function createContractorRecord($user){
        $contractor = new Contractor;
        $contractor->sub = $user->sub;
        $contractor->name = $user->firstName . ' ' . $user->lastName;
        $contractor->company = $user->companyName;
        $contractor->email = $user->email;
        $contractor->save();
        return $contractor;
    }

    private function createCategoriesRecords($contractor, $categories){
        $contractor->categories()->attach($categories);
    }

    public function PostStep1(Request $request)
    {
        if ($this->validateStep1($request)) {
            return redirect()->back()->with('message',  'Account could not be created, please retry.')->withInput()->withErrors($this->validator);
        } 
        $validatedData = $this->validator->valid();
        $this->addRegisterToSession($validatedData, $request);
        return redirect('/register/contractorstep2');
    }


    private function validateStep1(Request $request){
        $this->createValidator($request,['email' => 'required']);
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

    public function Step2(Request $request)
    {
        return $this->showView('auth.multi-page.contractorStep2',$request);
    }

    public function PostStep2(Request $request)
    {
        $validatedData = $request->validate([
            'companyName' => 'required',
        ]);
       
        $this->addRegisterToSession($validatedData, $request);
        return redirect('/register/contractorstep3');
    }

    public function Step3(Request $request)
    {  
        $register = $request->session()->get('register');

        return view('auth.multi-page.contractorStep3',compact('register'));
    }

    public function PostStep3(Request $request)
    {
        $validatedData = $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
        ]);
        $this->addRegisterToSession($validatedData, $request);
        return redirect('/register/contractorstep4');
    }
    public function Step4(Request $request)
    {  
        $register = $request->session()->get('register');
        $categories = Category::all();
        return view('auth.multi-page.contractorStep4',compact('register', 'categories'));
    }

    public function PostStep4(Request $request){
        $validatedData = $request->validate([
            'categories' => 'required',
        ]);
        $this->addRegisterToSession($validatedData, $request);
        $request->session()->put('categories', $request->get('categories'));
        return redirect('/register/contractorstep5');
    }

    public function Step5(Request $request)
    {  
        $register = $request->session()->get('register');
        return view('auth.multi-page.contractorStep5',compact('register'));
    }

    public function register(Request $request){
        if ($request->session()->exists('invitation')){
            return redirect('/invite/' . $request->session()->get('invitation'));
        }
        $request->session()->forget('register');
        $register = $request->session()->get('register');
        return view('auth.multi-page.contractor', compact('register'));
    }
}
