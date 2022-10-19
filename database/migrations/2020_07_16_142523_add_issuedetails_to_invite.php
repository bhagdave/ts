<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIssuedetailsToInvite extends Migration
{
    public function up()
    {
        Schema::table('user_invitations', function (Blueprint $table) {
            $table->char('agency_id', 40)->nullable()->default(null);
        });
    }

    public function down()
    {
        Schema::table('user_invitations', function (Blueprint $table) {
            $table->dropColumn('agency_id');
        });
    }
}
