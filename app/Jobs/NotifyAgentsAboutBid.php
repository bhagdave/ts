<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Notifications\ContractorBidNotification;
use App\Agency;

class NotifyAgentsAboutBid implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $issue;
    private $contractor;
    private $property;

    public function __construct($issue, $property, $contractor)
    {
        $this->issue = $issue;
        $this->property = $property;
        $this->contractor = $contractor;
    }

    public function handle()
    {
        Log::info("Handling Contractor Bid");
        $emailData = [];
        $emailData['propertyAddress'] = $this->property->inputAddress;
        $emailData['description'] = $this->issue->description;
        $emailData['link'] = URL::to('/issue/' . $this->issue->id );
        $emailData['contractorEmail'] = $this->contractor->email;
        $emailData['contractorName'] = $this->contractor->name .' at ' . $this->contractor->company;
        $agents = $this->getAgentsFromProperty();
        if (isset($agents)){
            foreach($agents as $agent){
                $agent->user->notify(new ContractorBidNotification($emailData) );
            }
        }
    }

    private function getAgentsFromProperty(){
        $agency = Agency::find($this->property->agent_id);
        if (isset($agency)){
            $agents = $agency->agents;
            return $agents;
        }
        return null;
    }
}
