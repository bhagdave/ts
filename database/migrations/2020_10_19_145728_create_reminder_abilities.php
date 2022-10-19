<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        $abilitiesTable = DB::table('abilities');
        $abilityId = $abilitiesTable->insertGetId([
            'name' => 'createreminders',
            'title' => 'Create Reminders',
            'only_owned' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        $permissionsTable = DB::table('permissions');
        $permissionsTable->insert([
            [
                'ability_id' => $abilityId,
                'entity_id' => 1,
                'entity_type' => 'roles',
                'forbidden' => 0
            ],[
                'ability_id' => $abilityId,
                'entity_id' => 3,
                'entity_type' => 'roles',
                'forbidden' => 0
            ],
        ]);

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
