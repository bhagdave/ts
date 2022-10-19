<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Createrentpaidtable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rent_paid', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('property_id');
            $table->uuid('tenant_id');
            $table->string('status',20)->nullable();
            $table->string('paid_date')->nullable();
            $table->decimal('amount',9,2)->nullable();
            $table->timestamps();

            $table->index(['property_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rent_paid');
    }
}
