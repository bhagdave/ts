<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Properties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->uuid('id')->primary();
            $table->string('propertyName');
            $table->string('propertyType')->nullable();
            $table->string('totalRent')->nullable();
            $table->string('tenantsTotal')->nullable();
            $table->string('propertyNotes')->nullable();
            $table->string('profileImage')->nullable(); 
            $table->string('inputAddress')->nullable();
            $table->string('inputAddress2')->nullable();
            $table->string('inputCity')->nullable();
            $table->string('inputRegion')->nullable();
            $table->string('inputPostCode')->nullable();
            $table->string('propertyLat')->nullable();
            $table->string('propertyLng')->nullable();
            $table->timestamps(); 

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::drop('properties');
    }
}
