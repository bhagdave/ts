<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Agency;
use App\User;
use App\Agent;


class CopyAgentDetailsToAgency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $usersModel = new User();
        $agents = $usersModel
            ->where('userType', 'Agent')->get();
        foreach ($agents as $agent) {
            $agentToUpdate = Agent::where('user_id', '=', $agent->sub)->first();
            $companyName = $agent->companyName;
            if ($agentToUpdate){
                if ($agentToUpdate->name){
                    $companyName = $agentToUpdate->name;
                }
            }
            $agency = new Agency([
                'company_name' => $companyName ? $companyName : 'An Agency' ,
                'phone' => $agent->phone
            ]);
            $agency->save();
            if ($agentToUpdate) {
                $agentToUpdate->agency_id = $agency->id;
                $agentToUpdate->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('agency')->truncate();
    }
}
