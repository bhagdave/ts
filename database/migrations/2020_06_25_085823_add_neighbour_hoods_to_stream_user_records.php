<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\User;

class AddNeighbourHoodsToStreamUserRecords extends Migration
{
    public function up()
    {
        $users = DB::table('users')->where('userType', '=', 'Tenant')->get();
        foreach ($users as $user){
            $this->attachTenantToNeigbourhoodStream($user);
        }
    }

    private function attachTenantToNeigbourhoodStream($user){
        $streamId = DB::table('tenants')->where('sub', '=', $user->sub)
            ->join('properties','properties.id', '=', 'tenants.property_id')
            ->join('neighbourhoods', 'neighbourhoods.id', '=', 'properties.neighbourhood_id')
            ->value('neighbourhoods.stream_id');
        if (isset($streamId)){
            DB::table('stream_user')->insert(['user_id' => $user->id, 'stream_id' => $streamId]);
        }
    }

    public function down()
    {
    }
}
