<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Agency;
use App\Jobs\TrialEndingJob;

class TrialEnding extends Command
{
    protected $signature = 'email:trialending';

    protected $description = 'Send email for those agencies whose trial is coming to an end.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $agencies = Agency::whereBetween('trial_ends_at', [
            Carbon::today()->addDays(2)->endOfDay(), 
            Carbon::today()->addDays(4)])
            ->get();
        foreach($agencies as $agency){
            if (!$agency->subscribed('standard')){
                $this->info('Sending reminder for ' . $agency->company_name);
                TrialEndingJob::dispatch($agency);
            }
        }
    }

}
