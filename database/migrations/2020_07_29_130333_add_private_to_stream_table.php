<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrivateToStreamTable extends Migration
{
    public function up()
    {
        Schema::table('stream', function (Blueprint $table) {
            $table->boolean('private')->nullable()->deafult('false');
        });
    }

    public function down()
    {
        Schema::table('stream', function (Blueprint $table) {
            $table->dropColumn('private');
        });
    }
}
