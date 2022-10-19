<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->uuid('id')->primary();
            
            $table->foreign('property_id')->references('id')->on('properties');
            $table->string('property_id')->nullable();
            $table->string('location')->nullable();
            $table->string('description')->nullable();
            $table->string('attributes')->nullable();

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
        Schema::dropIfExists('issues');
    }
}
