<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\Stream;
use App\Agent;
use Illuminate\Support\Facades\Schema;

class AddPrivateStreamIdToPropertyTable extends Migration
{
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->char('private_stream_id', 40)->nullable()->default(null);
        });
        $properties = $this->getAllProperties();
        foreach($properties as $property){
            if (isset($property->agent_id)){
                $streamId = $this->createNewPrivateStream($property->id);
                $this->updateProperty($property->id, $streamId);
                $this->attachAgentsToStream($property->agent_id, $streamId);
            }
        }
    }

    private function getAllProperties(){
        $properties = \DB::select("Select * from properties where propertyName != 'Test Property'");
        return collect ($properties);
    }

    private function createNewPrivateStream($propertyId){
        $newStream = new Stream();
        $newStream->extra_attributes->broadcastOnly = 'true';
        $newStream->private = true;
        $newStream->save();
        return $newStream->id;
    }

    private function updateProperty($id, $streamId){
        \DB::table('properties')
            ->where('id', $id)
            ->update(['private_stream_id' => $streamId]);
    }
    
    private function attachAgentsToStream($agencyId, $streamId){
        $agents = Agent::where('agency_id', '=', $agencyId)->get();
        foreach($agents as $agent){
            $this->insertStreamUserRecord($agent->user_id, $streamId);
        }
    }

    private function insertStreamUserRecord($userId, $streamId){
        DB::table('stream_user')->insert(
            [
                'user_id' => $userId,
                'stream_id' => $streamId
            ]
        );
    }

    public function down()
    {
        Schema::table('property', function (Blueprint $table) {
            $table->dropColumn('private_stream_id');
        });
    }
}
