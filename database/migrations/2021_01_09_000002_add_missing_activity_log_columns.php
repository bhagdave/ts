<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(config('activitylog.table_name', 'activity_log'), function (Blueprint $table) {
            if (!Schema::hasColumn(config('activitylog.table_name', 'activity_log'), 'event')) {
                $table->string('event')->nullable()->after('subject_type');
            }
            if (!Schema::hasColumn(config('activitylog.table_name', 'activity_log'), 'batch_uuid')) {
                $table->uuid('batch_uuid')->nullable()->after('properties');
            }
        });
    }

    public function down(): void
    {
        Schema::table(config('activitylog.table_name', 'activity_log'), function (Blueprint $table) {
            $table->dropColumn(['event', 'batch_uuid']);
        });
    }
};
