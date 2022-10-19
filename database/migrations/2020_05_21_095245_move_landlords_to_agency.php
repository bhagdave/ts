<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MoveLandlordsToAgency extends Migration
{
    public function up()
    {
        Schema::table('landlords', function (Blueprint $table) {
            $table->dropForeign('landlords_agent_id_foreign');
        });
        $landlords = DB::table('landlords')
            ->whereNotNull('agent_id')->get();
        foreach ($landlords as $landlord) {
            $this->updateLandlordToAgency($landlord->id, $landlord->agent_id);
        }
    }

    private function updateLandlordToAgency($landlordId, $agentId){
        $agencyId = DB::table('agents')->where('id', '=', $agentId)->value('agency_id');
        DB::table('landlords')
            ->where('id', '=', $landlordId)
            ->update(
                [
                    'agent_id' => $agencyId
                ]
            );
    }

    public function down()
    {
    }
}
