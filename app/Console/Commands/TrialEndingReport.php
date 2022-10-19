<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;
use App\Mail\TrialEndingReportEmail;
Use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TrialEndingReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:trialendingreport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Report of Agencies within two weeks of the end of their trial';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($today->dayOfWeek == Carbon::MONDAY){ // Heroku scheduler does not allow a weekly job. Hence this
            $agencies = $this->getAgentsTrialEnding();
            Mail::to([ 'natalie@tenancystream.com', 'jenny@tenancystream.com'])->queue(New TrialEndingReportEmail($agencies));
        }
    }

    private function getAgentsTrialEnding(){
        return DB::select(DB::raw("SELECT company_name, firstName, lastName, email, telephone, trial_ends_at 
                            FROM tenancystream.agency
                                JOIN agents on agents.agency_id = agency.id and agents.main = 1
                                JOIN users on users.sub = agents.user_id
                                WHERE founder = 0 
                                    AND trial_ends_at > NOW() 
                                    AND trial_ends_at < DATE_ADD(NOW(), INTERVAL 14 DAY) ORDER BY trial_ends_at;"
                    ));
    }
}
