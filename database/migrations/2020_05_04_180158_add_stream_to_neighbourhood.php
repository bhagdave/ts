<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Stream;

class AddStreamToNeighbourhood extends Migration
{
    public function up()
    {
        Schema::table('neighbourhoods', function (Blueprint $table) {
            $table->char('stream_id', 36)->nullable()->default(null);
        });
        $neighbourhoods = DB::table('neighbourhoods')->get();
        foreach ($neighbourhoods as $neighbourhood) {
            $stream = Stream::withExtraAttributes('neighbourhood_id', $neighbourhood->id)->first();
            if (isset($stream)) {
                DB::table('neighbourhoods')->where('id', $neighbourhood->id)->update(['stream_id' => $stream->id]);
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
