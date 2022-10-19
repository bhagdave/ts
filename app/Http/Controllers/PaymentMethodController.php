<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class PaymentMethodController extends Controller
{

    public function index(){
        $user = Auth::user();
        // check if already subscribed
        if ($user->userType == "Agent"){
            if ($user->agent->main){
                $agency = $user->agent->agency;
                if ($agency->subscribed('standard')){
                    return redirect()->back()->with('message', 'You are already subscribed.');
                }
                $paymentMethod = $agency->defaultPaymentMethod();
                if (isset($paymentMethod)){
                    $paymentMethod = $paymentMethod->id;
                }
                $planId = config('stripe.stripe_product');
                $intent = null;
                $stripeKey = config('stripe.stripe_psk');
                if (!isset($paymentMethod)){
                    $intent = $agency->createSetupIntent();
                }
                return view('profiles.payment', compact('stripeKey','user', 'agency', 'paymentMethod', 'intent', 'planId'));
            }
        }
        return redirect()->back()->with('message', 'You are not authorised to access the payment methods screen.');
    }

    public function subscribe(Request $request){
        $user = Auth::user();
        $plan = $request->input('plan');
        $paymentMethod = $request->input('payment_method');
        $subscription = $user->agent->agency->newSubscription('standard', $plan)->create($paymentMethod);
        if (isset($subscription)){
            return redirect('/profile/edit')->with('message', 'Thank you for subscribing');
        } 
        return redirect()->back()->with('message', 'There was a problem with your subscription. Please try again or contact Tenancy Stream');
    }
}
