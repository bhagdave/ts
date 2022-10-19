<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\User;

class CreateStreamUserRecords extends Migration
{
    public function up()
    {
        $users = DB::table('users')->get();
        foreach ($users as $user){
            $this->attachUserToStreams($user);
        }
    }

    private function attachUserToStreams($user){
        if ($user->userType == 'Agent'){
            $agencyId = DB::table('agents')->where('user_id', '=', $user->sub)->value('agency_id');
            $properties = DB::table('properties')->where('agent_id', '=', $agencyId)->get();
            $this->loopThroughPropertiesAndAttachToStream($user, $properties);
        }
        if ($user->userType == 'Landlord'){
            $properties = DB::table('properties')->where('created_by_user_id', '=', $user->sub)->get();
            $this->loopThroughPropertiesAndAttachToStream($user, $properties);
        }
        if ($user->userType == 'Tenant'){
            $streamId = DB::table('tenants')->where('sub', '=', $user->sub)
                ->join('properties','properties.id', '=', 'tenants.property_id')
                ->value('stream_id');
            if (isset($streamId)){
                $this->addPivotRecordToDatabase($user->id, $streamId);
            }
        }
    }

    private function addPivotRecordToDatabase($userId, $streamId){
        DB::table('stream_user')->insert(['user_id' => $userId, 'stream_id' => $streamId]);
    }

    private function loopThroughPropertiesAndAttachToStream($user, $properties){
        foreach ($properties as $property){
            if (isset($property->stream_id)){
                $this->addPivotRecordToDatabase($user->id, $property->stream_id);
            }
        }
    }

    public function down()
    {
    }
}
