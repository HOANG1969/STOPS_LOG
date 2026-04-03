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
        Schema::create('stops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('Người ghi nhận');
            $table->string('observer_name')->comment('Tên người quan sát');
            $table->date('observation_date')->comment('Ngày quan sát');
            $table->time('observation_time')->nullable()->comment('Giờ quan sát');
            $table->string('location')->comment('Vị trí');
            $table->string('equipment_name')->nullable()->comment('Tên thiết bị');
            $table->text('issue_description')->comment('Vấn đề ghi nhận');
            $table->text('corrective_action')->comment('Hành động khắc phục');
            $table->enum('status', ['open', 'in-progress', 'completed'])->default('open')->comment('Trạng thái');
            $table->date('completion_date')->nullable()->comment('Ngày hoàn thành');
            $table->text('notes')->nullable()->comment('Ghi chú');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stops');
    }
};
