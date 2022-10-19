<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Agent;
use App\User;

class AddNewAgentToExistingProperties implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $agencyId;
    protected $agencyStream;

    public function __construct(User $user,$agencyId, $agencyStream)
    {
        $this->user = $user;
        $this->agencyId = $agencyId;
        $this->agencyStream = $agencyStream;
    }

    public function handle()
    {
        $streams = $this->getStreamsToAttach();
        $this->insertStreamUserRecord($this->agencyStream); // private agency stream
        foreach($streams as $streamId){
            $this->insertStreamUserRecord($streamId); // property streams
        }
    }

    private function getStreamsToAttach(){
        return DB::table('properties')
            ->pluck('stream_id')
            ->where('agent_id', '=', $this->agencyId)
            ->all();
    }

    private function insertStreamUserRecord($streamId){
        DB::table('stream_user')->insert(
            [
                'user_id' => $this->user->id,
                'stream_id' => $streamId
            ]
        );
    }
}
