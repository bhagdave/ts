<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Agency;
use Mail;
use App\Mail\TrialOverMail;

class TrialOver extends Command
{
    protected $signature = 'email:trialover';

    protected $description = 'Send email for users whos trial has finished';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $agencies = Agency::whereBetween('trial_ends_at', [
            Carbon::yesterday()->startOfDay(), 
            Carbon::yesterday()->endOfDay()])
            ->get();
        foreach($agencies as $agency){
            if (!$agency->subscribed){
                $agent = $agency->mainAgent();
                if (isset($agent)){
                    $user = $agent->user;
                    if (isset($user)){
                        if ($user->email_notifications){
                            $mailData = $this->getMailData($user);
                            Mail::to($user->email)->queue(new TrialOverMail($mailData));
                        }
                    }
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
