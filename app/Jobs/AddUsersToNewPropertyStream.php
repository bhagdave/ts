<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use App\Agent;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddUsersToNewPropertyStream implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $streamId;

    public function __construct(User $user, $streamId)
    {
        $this->user = $user;
        $this->streamId = $streamId;
    }

    public function handle()
    {
        if ($this->user->userType == 'Agent'){
            $this->attachAgentsToStream($this->user->agent->agency_id);
        } else {
            $this->insertStreamUserRecord($this->user->id);
        }
        
    }

    private function insertStreamUserRecord($userId){
        DB::table('stream_user')->insert(
            [
                'user_id' => $userId,
                'stream_id' => $this->streamId
            ]
        );
    }

    private function attachAgentsToStream($agencyId){
        $agents = Agent::where('agency_id', '=', $agencyId)->get();
        foreach($agents as $agent){
            $this->insertStreamUserRecord($agent->user->id);
        }
    }
}
