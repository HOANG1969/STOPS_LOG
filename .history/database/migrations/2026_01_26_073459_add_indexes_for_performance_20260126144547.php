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
            // Index cho status - được sử dụng rất nhiều trong queries
            $table->index('status');
            
            // Index cho user_id - FK thường xuyên được join
            $table->index('user_id');
            
            // Index cho requester_department - được dùng trong filter
            $table->index('requester_department');
            
            // Index cho department - được dùng trong filter
            $table->index('department');
            
            // Composite index cho trạng thái và ngày duyệt
            $table->index(['status', 'approved_at']);
            
            // Composite index cho department và status
            $table->index(['requester_department', 'status']);
            
            // Index cho created_at để sort
            $table->index('created_at');
            
            // Index cho approved_at để sort
            $table->index('approved_at');
            
            // Index cho tchc_checked_at để sort
            $table->index('tchc_checked_at');
            
            // Index cho approved_by 
            $table->index('approved_by');
            
            // Index cho tchc_checker_id
            $table->index('tchc_checker_id');
            
            // Index cho tchc_manager_id
            $table->index('tchc_manager_id');
        });

        Schema::table('request_items', function (Blueprint $table) {
            // Index cho request_id - FK được join liên tục
            $table->index('request_id');
            
            // Index cho office_supply_id - FK được join liên tục  
            $table->index('office_supply_id');
            
            // Composite index cho request_id và office_supply_id
            $table->index(['request_id', 'office_supply_id']);
        });

        Schema::table('office_supplies', function (Blueprint $table) {
            // Index cho name - có thể được dùng trong search
            $table->index('name');
        });

        Schema::table('users', function (Blueprint $table) {
            // Index cho department - được dùng trong filter
            $table->index('department');
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
            $table->dropIndex(['status']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['requester_department']);
            $table->dropIndex(['department']);
            $table->dropIndex(['status', 'approved_at']);
            $table->dropIndex(['requester_department', 'status']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['approved_at']);
            $table->dropIndex(['tchc_checked_at']);
            $table->dropIndex(['approved_by']);
            $table->dropIndex(['tchc_checker_id']);
            $table->dropIndex(['tchc_manager_id']);
        });

        Schema::table('request_items', function (Blueprint $table) {
            $table->dropIndex(['request_id']);
            $table->dropIndex(['office_supply_id']);
            $table->dropIndex(['request_id', 'office_supply_id']);
        });

        Schema::table('office_supplies', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['department']);
        });
    }
};
