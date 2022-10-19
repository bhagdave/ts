<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;
use App\Mail\TrialEnding;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TrialEndingJob implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $agency;

    public function __construct($agency)
    {
        $this->agency = $agency;
    }

    public function handle()
    {
        $agent = $this->agency->mainAgent();
        if (isset($agent)){
            $user = $agent->user;
            if (isset($user)){
                if ($user->email_notifications){
                    $mailData = $this->getMailData($user);
                    Mail::to($user->email)->queue(new TrialEnding($mailData));
                }
            }
        }
    }

    private function getMailData($user){
        $messages = $this->getStoredMessagesForAgency();
        $mailData['link'] = url('payment');
        $mailData['firstName'] = $user->firstName;
        $mailData['storedMessages'] = $messages[0]->Messages;
        return $mailData;
    }

    private function getStoredMessagesForAgency(){
        $agencyId = $this->agency->id;
        $result = DB::select(DB::raw(
            "SELECT
                COUNT(*) AS Messages
            FROM
                activity_log
            WHERE
                log_name IN (SELECT
                                stream_id AS streamId
                            FROM
                                properties
                            WHERE
                                agent_id IN (SELECT
                                                id
                                            FROM
                                                agency
                                            WHERE
                                                id = :agencyId1) 
                                        UNION 
                                            SELECT
                                                private_stream_id AS streamId
                                            FROM
                                                properties
                                            WHERE
                                                agent_id IN (SELECT
                                                                id
                                                            FROM
                                                                agency
                                                            WHERE
                                                                id = :agencyId2))"

        ), array(
            'agencyId1' => $agencyId, 
            'agencyId2' => $agencyId, 
        ));
        return $result;
    }
}
