<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterIssuesTableToAddContractorsAndCategories extends Migration
{
    public function up()
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->string('contractors_id', 40)->nullable();
            $table->bigInteger('categories_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->dropColumn('contractors_id');
            $table->dropColumn('categories_id');
        });
    }
}
