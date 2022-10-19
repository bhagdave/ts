<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;
use App\Agent;
use App\Mail\UpdateEmail;

class UpdateNotice extends Command
{
    protected $signature = 'email:updateagents';

    protected $description = 'Send all agents an application update.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info("Starting the update");
        $update = \DB::table('update')->first();
        $agents = Agent::all();
        foreach($agents as $agent){
            if ($agent->user->email_notifications){
                $name = $agent->user->firstName ?? $agent->name;
                $mailData = [
                    'subject' => 'Tenancy Stream Update',
                    'email' => $agent->user->email,
                    'message' => $update->message,
                    'name' => $name
                ];
                Mail::to($mailData['email'])->queue(new UpdateEmail($mailData));
            }
        }
    }
}
