<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hints', function (Blueprint $table) {
            $table->id();
            $table->integer('day');
            $table->string('title',30);
            $table->text('content');
            $table->timestamps();
        });
        // fill up some records
        $hints = DB::table('hints');
        for($i=0; $i<30; $i++){
            $hints->insert([
                'day' => $i,
                'title' => "Day $i",
                'content' => "Day $i",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hints');
    }
}
