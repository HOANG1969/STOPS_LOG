<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stop_score_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stop_id')->constrained('stops')->cascadeOnDelete();
            $table->foreignId('scored_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('scorer_type', 50);
            $table->string('scorer_role', 50)->nullable();
            $table->unsignedTinyInteger('previous_priority_level')->nullable();
            $table->unsignedTinyInteger('priority_level')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('scored_at');
            $table->timestamps();

            $table->index(['stop_id', 'scored_at'], 'stop_score_histories_stop_scored_at_idx');
            $table->index(['scorer_type', 'scored_at'], 'stop_score_histories_type_scored_at_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stop_score_histories');
    }
};
