<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTenant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties_tenant', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->string('tenant_id', 40);
            $table->string('properties_id', 40);
            $table->timestamps();
            $table->index(['tenant_id', 'properties_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties_tenant');
    }
}
