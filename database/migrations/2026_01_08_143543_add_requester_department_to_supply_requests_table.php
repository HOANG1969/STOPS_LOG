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
            // Thêm column requester_department, copy data từ department column
            $table->string('requester_department')->nullable()->after('department');
        });
        
        // Update dữ liệu từ column department sang requester_department
        \DB::statement('UPDATE supply_requests SET requester_department = department');
        
        // Sau khi copy xong, set NOT NULL
        Schema::table('supply_requests', function (Blueprint $table) {
            $table->string('requester_department')->nullable(false)->change();
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
            $table->dropColumn('requester_department');
        });
    }
};
