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
        Schema::table('supply_requests', function (Blueprint $table) {
            // Thêm các cột cho TCHC workflow
            $table->unsignedBigInteger('tchc_checker_id')->nullable()->after('approved_by');
            $table->timestamp('tchc_checked_at')->nullable()->after('approved_at');
            $table->text('tchc_check_notes')->nullable()->after('approval_notes');
            $table->unsignedBigInteger('tchc_manager_id')->nullable()->after('tchc_checker_id');
            $table->timestamp('tchc_approved_at')->nullable()->after('tchc_checked_at');
            $table->text('tchc_approval_notes')->nullable()->after('tchc_check_notes');

            // Thêm foreign keys
            $table->foreign('tchc_checker_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('tchc_manager_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('users', function (Blueprint $table) {
            // Cập nhật enum role để bao gồm TCHC roles
            $table->enum('role', ['employee', 'approver', 'admin', 'tchc_checker', 'tchc_manager'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supply_requests', function (Blueprint $table) {
            $table->dropForeign(['tchc_checker_id']);
            $table->dropForeign(['tchc_manager_id']);
            $table->dropColumn([
                'tchc_checker_id',
                'tchc_checked_at', 
                'tchc_check_notes',
                'tchc_manager_id',
                'tchc_approved_at',
                'tchc_approval_notes'
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['employee', 'approver', 'admin'])->change();
        });
    }
};