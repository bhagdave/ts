<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id('id')->autoIncrement();
            $table->string('name');
            $table->string('notes')->nullable();
            $table->timestamps(0);
        });
        DB::table('categories')->insert([
            [
                'name' => 'Electrician',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Plumber',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Handyman',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Decorator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gas Engineer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cleaner',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gardener',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Window Cleaner',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pest Control',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Carpenter',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Glazier',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Locksmith',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Alarm Engineer',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
