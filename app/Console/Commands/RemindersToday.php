<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Agency;
use App\Mail\ReminderEmail;
use Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RemindersToday extends Command
{
    protected $signature = 'email:reminders';

    protected $description = 'Send Emails to agencies for reminders due today';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $agencies = Agency::all();
        foreach($agencies as $agency){
            if ($agency->reminders()->exists()){
                Log::info("Reminders for " . $agency->company_name);
                $reminders = $agency->reminders()
                    ->where('start_date',Carbon::today()->toDateString())
                    ->get();
                Log::info("Reminders for today = " . count($reminders));
                if (count($reminders) > 0){
                    $this->sendRemindersEmail($agency,$reminders);
                }
            }
        }
    }

    /*
     * Put alll the data together and send the agent reminder emails
     */
    private function sendRemindersEmail($agency, $reminders){
        $toEmailAddresses = [];
        $agents = $agency->agents;
        foreach($agents as $agent){
            if ($agent->user->email_notifications){
                $toEmailAddresses[] = $agent->user->email;
            }
        }
        Log::debug(print_r($toEmailAddresses, true));
        Mail::to($toEmailAddresses)->queue(new ReminderEmail($reminders));
    }
}
