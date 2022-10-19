<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Properties;
use App\Stream;

class AddStreamToProperty extends Migration
{
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->char('stream_id', 36)->nullable()->default(null);
        });
        // Cant use the model because of the stupid bloody trait on it that is not even in a globalscope
        $properties = $this->getAllProperties();
        foreach($properties as $property){
            $stream = Stream::withExtraAttributes('property_id', $property->id)->first();
            if (isset($stream)){
                $this->updateProperty($property->id, $stream->id);
            }
        }
    }

    private function getAllProperties(){
        $properties = \DB::select("Select * from properties");
        return collect ($properties);
    }


    private function updateProperty($id, $streamId){
        \DB::table('properties')
            ->where('id', $id)
            ->update(['stream_id' => $streamId]);
    }

    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('stream_id');
        });
    }
}
