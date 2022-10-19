<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentStorageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_storage', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('type',['property', 'tenant', 'landlord', 'contractor', 'agent']);
            $table->string('linked_to', 36);
            $table->string('path');
            $table->string('file_type');
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
        Schema::dropIfExists('document_storage');
    }
}
