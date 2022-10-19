<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractorsTable extends Migration
{
    public function up()
    {
        Schema::create('contractors', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->uuid('id')->primary();
            $table->string('phone')->nullable();
            $table->string('notes')->nullable();
            $table->string('name')->nullable();
            $table->string('company')->nullable();
            $table->string('email')->nullable();
            $table->string('profileImage')->nullable(); 
            $table->string('sub'); 

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contractors');
    }
}
