<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Laravel\Cashier\Subscription;
use Mail;
use App\Agency;
use App\Mail\PaymentFailure;

class SubscriptionDeleted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $payload;

    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    public function handle()
    {
        $subscription = Subscription::where('stripe_id', '=', $this->payload['id'])->first();
        if (isset($subscription)){
            $agency = Agency::where('id', '=', $subscription->agency_id)->first(); 
            if (isset($agency)){
                $agent = $agency->mainAgent();
                if (isset($agent)){
                    $user = $agent->user;
                    $mailData = $this->getMailData($user);
                    Mail::to($user->email)->queue(new PaymentFailure($mailData));
                }
            }
        }
    }

    private function getMailData($user){
        $mailData['link'] = url('payment');
        $mailData['firstName'] = $user->firstName;
        return $mailData;
    }
}
