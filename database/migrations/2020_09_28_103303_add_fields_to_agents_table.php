<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToAgentsTable extends Migration
{
    public function up()
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->integer('property_count')->nullable();
            $table->string('country', 30)->nullable();
        });
    }

    public function down()
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn('property_count');
            $table->dropColumn('country');
        });
    }
}
