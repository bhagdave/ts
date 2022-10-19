<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLandlords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('landlords', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->uuid('id')->primary();
            $table->string('agent_id')->nullable();
            $table->foreign('agent_id')->references('id')->on('agents');

            $table->string('phone')->nullable();
            $table->string('notes')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('profileImage')->nullable(); 

            $table->softDeletes();
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
        Schema::dropIfExists('landlords');
    }
}
