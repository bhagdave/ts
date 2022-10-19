<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrivateToIssuesTable extends Migration
{
    public function up()
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->boolean('private')->default(0);
            $table->char('creator_id', 40)->nullable()->default(null);
        });
    }

    public function down()
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->dropColumn([
                'private',
                'creator_id'
            ]);
        });
    }
}
