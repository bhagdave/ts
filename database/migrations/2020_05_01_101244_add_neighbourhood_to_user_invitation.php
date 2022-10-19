<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNeighbourhoodToUserInvitation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_invitations', function (Blueprint $table) {
            $table->char('neighbourhood_id', 36)->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_invitations', function (Blueprint $table) {
            $table->dropColumn('neighbourhood_id');
        });
    }
}
