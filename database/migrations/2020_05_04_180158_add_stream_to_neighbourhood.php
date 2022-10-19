<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Stream;
use App\Neighbourhood;

class AddStreamToNeighbourhood extends Migration
{
    public function up()
    {
        Schema::table('neighbourhoods', function (Blueprint $table) {
            $table->char('stream_id', 36)->nullable()->default(null);
        });
        $neigbourhoods = Neighbourhood::all();
        foreach($neigbourhoods as $neighbourhood){
            $stream = Stream::withExtraAttributes('neighbourhood_id', $neighbourhood->id)->first();
            if (isset($stream)){
                $neighbourhood->stream_id = $stream->id;
                $neighbourhood->save();
            }
        }
    }

    public function down()
    {
        Schema::table('neighbourhoods', function (Blueprint $table) {
            $table->dropColumn('stream_id');
        });
    }
}
