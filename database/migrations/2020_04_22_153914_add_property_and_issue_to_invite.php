<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPropertyAndIssueToInvite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_invitations', function (Blueprint $table) {
            $table->char('property_id', 36)->nullable()->default(null);
            $table->char('issue_id', 36)->nullable()->default(null);
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
            $table->dropColumn('property_id');
            $table->dropColumn('issue_id'); 
        });
    }
}
