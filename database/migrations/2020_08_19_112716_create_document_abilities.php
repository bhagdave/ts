<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateDocumentAbilities extends Migration
{
    public function up()
    {        
        $abilitiesTable = DB::table('abilities');
        $abilityId = $abilitiesTable->insertGetId([
            'name' => 'documentStorage',
            'title' => 'Document Storage',
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
                'entity_id' => 2,
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

    public function down()
    {
        Schema::dropIfExists('document_abilities');
    }
}
