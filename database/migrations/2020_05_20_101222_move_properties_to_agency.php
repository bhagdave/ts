<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MovePropertiesToAgency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function($table)
        {
            $table->dropForeign('properties_agent_id_foreign');
        });
        $properties = DB::table('properties')
            ->where('agent_id', '!=', '0')->get();
        foreach($properties as $property){
            if ($property->agent_id) {
                $agencyId = DB::table('agents')->where('id', '=', $property->agent_id )->value('agency_id');
                if ($agencyId){
                    $this->changeToAgencyOnProperty($property->id, $agencyId);
                }
            }
        }    
    }


    private function changeToAgencyOnProperty($propertyId, $agencyId){
        DB::table('properties')
            ->where('id', '=', $propertyId)
            ->update(
                [
                    'agent_id' => $agencyId
                ]
            );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
