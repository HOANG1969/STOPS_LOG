<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supply_requests', function (Blueprint $table) {
            // Kiểm tra và thêm các cột TCHC workflow nếu chưa tồn tại
            if (!Schema::hasColumn('supply_requests', 'tchc_checker_id')) {
                $table->unsignedBigInteger('tchc_checker_id')->nullable()->after('approved_by');
                $table->foreign('tchc_checker_id')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('supply_requests', 'tchc_checked_at')) {
                $table->timestamp('tchc_checked_at')->nullable()->after('approved_at');
            }
            if (!Schema::hasColumn('supply_requests', 'tchc_check_notes')) {
                $table->text('tchc_check_notes')->nullable()->after('approval_notes');
            }
            if (!Schema::hasColumn('supply_requests', 'tchc_manager_id')) {
                $table->unsignedBigInteger('tchc_manager_id')->nullable()->after('tchc_checker_id');
                $table->foreign('tchc_manager_id')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('supply_requests', 'tchc_approved_at')) {
                $table->timestamp('tchc_approved_at')->nullable()->after('tchc_checked_at');
            }
            if (!Schema::hasColumn('supply_requests', 'tchc_approval_notes')) {
                $table->text('tchc_approval_notes')->nullable()->after('tchc_check_notes');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            // Thêm cột boolean cho TCHC roles
            if (!Schema::hasColumn('users', 'is_tchc_checker')) {
                $table->boolean('is_tchc_checker')->default(false)->after('role');
            }
            if (!Schema::hasColumn('users', 'is_tchc_manager')) {
                $table->boolean('is_tchc_manager')->default(false)->after('is_tchc_checker');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supply_requests', function (Blueprint $table) {
            //
        });
    }
};
