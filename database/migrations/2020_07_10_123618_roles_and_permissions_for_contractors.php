<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsForContractors extends Migration
{
    private $roleId;

    public function up()
    {
        DB::table('roles')->insert([
            'name' => 'contractor',
            'title' => 'Contractor',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $this->getRoleId();
        DB::table('permissions')->insert([
            [
                'ability_id' => 6,
                'entity_id' => $this->roleId,
                'entity_type' => 'roles',
                'forbidden'=> 0
            ],
            [
                'ability_id' => 13,
                'entity_id' => $this->roleId,
                'entity_type' => 'roles',
                'forbidden'=> 0
            ],
            [
                'ability_id' => 20,
                'entity_id' => $this->roleId,
                'entity_type' => 'roles',
                'forbidden'=> 0
            ],
            [
                'ability_id' => 23,
                'entity_id' => $this->roleId,
                'entity_type' => 'roles',
                'forbidden'=> 0
            ],
            [
                'ability_id' => 25,
                'entity_id' => $this->roleId,
                'entity_type' => 'roles',
                'forbidden'=> 0
            ],
            [
                'ability_id' => 26,
                'entity_id' => $this->roleId,
                'entity_type' => 'roles',
                'forbidden'=> 0
            ],
            [
                'ability_id' => 29,
                'entity_id' => $this->roleId,
                'entity_type' => 'roles',
                'forbidden'=> 0
            ],
            [
                'ability_id' => 30,
                'entity_id' => $this->roleId,
                'entity_type' => 'roles',
                'forbidden'=> 0
            ]
        ]);
    }

    private function getRoleId(){
        $this->roleId = DB::table('roles')->where('name', '=', 'contractor')->value('id');
    }

    public function down()
    {
        $this->getRoleId();
        DB::table('roles')->where('id', '=', $this->roleId)->delete();
        DB::table('permissions')->where('entity_id', '=', $this->roleId )->delete();
    }
}
