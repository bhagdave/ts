<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateReminderAbilities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $abilityId = DB::table('abilities')->insertGetId([
            'name' => 'createreminders',
            'title' => 'Create Reminders',
            'only_owned' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $roleIds = DB::table('roles')->whereIn('name', ['agent', 'tenant'])->pluck('id');

        $permissions = $roleIds->map(fn($id) => [
            'ability_id'  => $abilityId,
            'entity_id'   => $id,
            'entity_type' => 'roles',
            'forbidden'   => 0,
        ])->values()->all();

        if (!empty($permissions)) {
            DB::table('permissions')->insert($permissions);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $abilitiesTable = DB::table('abilities');
        $abilityId = $abilitiesTable->where('name', 'createreminders')->value('id');
        $permissionsTable = DB::table('permissions');
        $permissionsTable->where('ability_id', $abilityId)->delete();
        $abilitiesTable->where('id', $abilityId)->delete();
    }
}
