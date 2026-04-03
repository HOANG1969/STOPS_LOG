<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stops', function (Blueprint $table) {
            $table->index('observer_phone', 'stops_observer_phone_idx');
            $table->index('issue_category', 'stops_issue_category_idx');
            $table->index('status', 'stops_status_idx');
            $table->index('observation_date', 'stops_observation_date_idx');
            $table->index('created_at', 'stops_created_at_idx');
            $table->index('priority_level', 'stops_priority_level_idx');
            $table->index(['observer_phone', 'observation_date'], 'stops_shift_date_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stops', function (Blueprint $table) {
            $table->dropIndex('stops_observer_phone_idx');
            $table->dropIndex('stops_issue_category_idx');
            $table->dropIndex('stops_status_idx');
            $table->dropIndex('stops_observation_date_idx');
            $table->dropIndex('stops_created_at_idx');
            $table->dropIndex('stops_priority_level_idx');
            $table->dropIndex('stops_shift_date_idx');
        });
    }
};
