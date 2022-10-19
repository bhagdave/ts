<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MarkExistingAgenciesAsExempt extends Migration
{
    public function up()
    {
        Schema::table('agency', function (Blueprint $table) {
            $table->boolean('founder')->default(0);
        });
        DB::table('agency')->update(['founder' => 1]);
    }

    public function down()
    {
        Schema::table('agency', function (Blueprint $table) {
            $table->dropColumn([
                'founder'
            ]);
        });
    }
}
