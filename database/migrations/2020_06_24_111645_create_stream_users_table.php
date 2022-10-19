<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\User;

class CreateStreamUsersTable extends Migration
{
    public function up()
    {
        Schema::create('stream_user', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->string('user_id', 40);
            $table->string('stream_id', 40);
            $table->timestamp('last_visited')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->index(['user_id', 'stream_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('stream_user');
    }
}
