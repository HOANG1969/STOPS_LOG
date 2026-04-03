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
        // Thay đổi enum role để bao gồm tchc_checker và tchc_manager
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('employee', 'approver', 'admin', 'tchc_checker', 'tchc_manager') DEFAULT 'employee'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert về enum cũ
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('employee', 'approver', 'admin') DEFAULT 'employee'");
    }
};
