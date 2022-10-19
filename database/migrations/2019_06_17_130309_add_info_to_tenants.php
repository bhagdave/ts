<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInfoToTenants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenants', function (Blueprint $table) {
             $table->string('phone')->nullable();
             $table->string('rentAmount')->nullable();
             $table->string('notes')->nullable();
             $table->string('rentDueInterval')->nullable();
             $table->string('moveInDate')->nullable();
             $table->string('profileImage')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenants', function (Blueprint $table) {
            //
        });
    }
}
