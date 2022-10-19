<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Agency;
use App\Agents;
use App\Stream;

class Createagencystreams extends Migration
{
    public function up()
    {
        $agencies = Agency::all();
        foreach($agencies as $agency){
            $agencyStream = $this->createStreamForAgency($agency->company_name);
            $agency->stream_id = $agencyStream->id;
            $agency->save();
            $agents = $agency->agents;
            foreach($agents as $agent){
                $this->attachUserToStream($agent->user->sub, $agencyStream->id);
            }
        }
    }

    private function createStreamForAgency($companyName){
        $newStream = new Stream();
        $newStream->private = true;
        $newStream->streamName = $companyName;
        $newStream->extra_attributes->broadcastOnly = 'false';
        $newStream->save();
        return $newStream;
    }
    private function attachUserToStream($userId, $streamId){
        DB::table('stream_user')->insert(['user_id' => $userId, 'stream_id' => $streamId]);
    }

    public function down()
    {
        //
    }
}
