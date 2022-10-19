<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\Agency;
use Illuminate\Support\Facades\Schema;

class AddMainToAgentTable extends Migration
{
    public function up()
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->boolean('main')->default(0);
        });
        $agencies = Agency::all();
        foreach($agencies as $agency){
            $mainAgent = $agency->agents()->orderBy('created_at')->first();
            if (isset($mainAgent)){
                $mainAgent->main = 1;
                $mainAgent->save();
            }
        }
    }

    public function down()
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn([
                'main'
            ]);
        });
    }
}
