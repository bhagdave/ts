<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatageoriesContractorsTable extends Migration
{
    public function up()
    {
        Schema::create('catageory_contractor', function (Blueprint $table) {
            $table->string('contractor_id', 40);
            $table->bigInteger('category_id', 40);
            $table->index(['contractor_id', 'category_id']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('catageory_contractor');
    }
}
