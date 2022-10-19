<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('recurrence')->default('none'); // none/weekly/monthly/annually
            $table->string('type');
            $table->string('type_id', 40);
            $table->bigInteger('reminder_id')->unsigned()->nullable(); // foreign key to itself
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('reminders', function($table) {
            $table->foreign('reminder_id')->references('id')->on('reminders');
            $table->index(['type','type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reminders');
    }
}
