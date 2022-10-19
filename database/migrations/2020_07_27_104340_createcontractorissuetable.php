<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Createcontractorissuetable extends Migration
{
    public function up()
    {
        Schema::create('contractor_issue', function (Blueprint $table) {
            $table->string('contractor_id', 40);
            $table->string('issue_id', 40);
            $table->index(['contractor_id', 'issue_id']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contractor_issue');
    }
}
