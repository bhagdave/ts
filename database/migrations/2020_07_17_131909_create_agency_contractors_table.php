<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgencyContractorsTable extends Migration
{
    public function up()
    {
        Schema::create('agency_contractors', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->string('agency_id', 40);
            $table->string('contractor_id', 40);
            $table->index(['agency_id', 'contractor_id']);
            $table->index('agency_id');
            $table->index('contractor_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('agency_contractors');
    }
}
