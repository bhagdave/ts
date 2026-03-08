<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsForContractors extends Migration
{
    private $roleId;

    public function up()
    {
        DB::table('roles')->insertOrIgnore([
            'name' => 'contractor',
            'title' => 'Contractor',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $this->getRoleId();

        // Ability names corresponding to original hardcoded IDs (6,13,20,23,25,26,29,30)
        $abilityNames = [
            'viewProperty', 'viewLandlord', 'viewTenant',
            'indexIssue', 'editIssue', 'viewIssue', 'updateIssue', 'viewStream',
        ];
        $abilities = DB::table('abilities')->whereIn('name', $abilityNames)->pluck('id');

        $permissions = $abilities->map(fn($id) => [
            'ability_id'  => $id,
            'entity_id'   => $this->roleId,
            'entity_type' => 'roles',
            'forbidden'   => 0,
        ])->values()->all();

        if (!empty($permissions)) {
            DB::table('permissions')->insert($permissions);
        }
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
