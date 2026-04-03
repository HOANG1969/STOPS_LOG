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
            $table->unsignedBigInteger('priority_scored_by')->nullable()->after('priority_level');
            $table->timestamp('priority_scored_at')->nullable()->after('priority_scored_by');
            
            $table->foreign('priority_scored_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stops', function (Blueprint $table) {
            $table->dropForeign(['priority_scored_by']);
            $table->dropColumn(['priority_scored_by', 'priority_scored_at']);
        });
    }
};
