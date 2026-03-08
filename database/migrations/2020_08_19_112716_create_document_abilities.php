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
        $abilityId = DB::table('abilities')->insertGetId([
            'name' => 'documentStorage',
            'title' => 'Document Storage',
            'only_owned' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $roleIds = DB::table('roles')->whereIn('name', ['agent', 'landlord', 'tenant'])->pluck('id');

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

    public function down()
    {
        Schema::dropIfExists('document_abilities');
    }
}
