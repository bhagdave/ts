<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Stream;

class AddLastMessageToStream extends Migration
{
    public function up()
    {
        Schema::table('stream', function (Blueprint $table) {
            $table->timestamp('last_message')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
        });
        $streams = Stream::all();
        foreach ($streams as $stream) {
            $this->addLastMessageDateToStream($stream);
        }
    }

    private function addLastMessageDateToStream($stream){
        $lastMessage = DB::table('activity_log')->where('log_name', '=', $stream->id)
            ->select(DB::raw('max(created_at) as last_message'))
            ->value('last_message');
        $stream->last_message = $lastMessage;
        $stream->save();
    }

    public function down()
    {
        Schema::table('stream', function (Blueprint $table) {
            $table->dropColumn('last_message');
        });
    }
}
