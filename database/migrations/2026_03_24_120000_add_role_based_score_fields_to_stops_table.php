<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stops', function (Blueprint $table) {
            if (!Schema::hasColumn('stops', 'shift_leader_scored_by')) {
                $table->foreignId('shift_leader_scored_by')->nullable()->after('priority_scored_at')->constrained('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('stops', 'shift_leader_scored_at')) {
                $table->timestamp('shift_leader_scored_at')->nullable()->after('shift_leader_scored_by');
            }

            if (!Schema::hasColumn('stops', 'shift_leader_priority_level')) {
                $table->unsignedTinyInteger('shift_leader_priority_level')->nullable()->after('shift_leader_scored_at');
            }

            if (!Schema::hasColumn('stops', 'shift_leader_note')) {
                $table->text('shift_leader_note')->nullable()->after('shift_leader_priority_level');
            }

            if (!Schema::hasColumn('stops', 'safety_officer_scored_by')) {
                $table->foreignId('safety_officer_scored_by')->nullable()->after('shift_leader_note')->constrained('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('stops', 'safety_officer_scored_at')) {
                $table->timestamp('safety_officer_scored_at')->nullable()->after('safety_officer_scored_by');
            }

            if (!Schema::hasColumn('stops', 'safety_officer_priority_level')) {
                $table->unsignedTinyInteger('safety_officer_priority_level')->nullable()->after('safety_officer_scored_at');
            }

            if (!Schema::hasColumn('stops', 'safety_officer_note')) {
                $table->text('safety_officer_note')->nullable()->after('safety_officer_priority_level');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stops', function (Blueprint $table) {
            if (Schema::hasColumn('stops', 'safety_officer_note')) {
                $table->dropColumn('safety_officer_note');
            }

            if (Schema::hasColumn('stops', 'safety_officer_priority_level')) {
                $table->dropColumn('safety_officer_priority_level');
            }

            if (Schema::hasColumn('stops', 'safety_officer_scored_at')) {
                $table->dropColumn('safety_officer_scored_at');
            }

            if (Schema::hasColumn('stops', 'safety_officer_scored_by')) {
                $table->dropConstrainedForeignId('safety_officer_scored_by');
            }

            if (Schema::hasColumn('stops', 'shift_leader_note')) {
                $table->dropColumn('shift_leader_note');
            }

            if (Schema::hasColumn('stops', 'shift_leader_priority_level')) {
                $table->dropColumn('shift_leader_priority_level');
            }

            if (Schema::hasColumn('stops', 'shift_leader_scored_at')) {
                $table->dropColumn('shift_leader_scored_at');
            }

            if (Schema::hasColumn('stops', 'shift_leader_scored_by')) {
                $table->dropConstrainedForeignId('shift_leader_scored_by');
            }
        });
    }
};
