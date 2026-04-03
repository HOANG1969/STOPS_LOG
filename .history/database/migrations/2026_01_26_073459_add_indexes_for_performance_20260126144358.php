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
            
            // Index cho area - được dùng trong filter
            $table->index('area');
            
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
        //
    }
};
