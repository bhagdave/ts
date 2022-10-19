<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rules', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40);
            $table->string('rule');
            $table->string('link')->nullable();
            $table->timestamps();
        });
        $rules = DB::table('rules');
        $rules->insert([
            'name' => 'kind',
            'rule' => 'Be Kind and Courteous',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $rules->insert([
            'name' => 'no hate',
            'rule' => 'No hate speech or bullying',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $rules->insert([
            'name' => 'spam',
            'rule' => 'No promotion of spam',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $rules->insert([
            'name' => 'privacy',
            'rule' => 'Respect members privacy',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rules');
    }
}
