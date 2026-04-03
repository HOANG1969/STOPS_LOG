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
            // Add request_date column
            $table->date('request_date')->nullable()->after('requester_position');
            
            // Update status enum to include 'draft'
            \DB::statement("ALTER TABLE supply_requests MODIFY COLUMN status ENUM('draft', 'pending', 'forwarded', 'approved', 'rejected') DEFAULT 'pending'");
            
            // Update priority enum to match controller validation
            \DB::statement("ALTER TABLE supply_requests MODIFY COLUMN priority ENUM('low', 'normal', 'high', 'urgent') DEFAULT 'normal'");
            
            // Make needed_date nullable since it's optional in controller
            $table->date('needed_date')->nullable()->change();
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
            // Remove request_date column
            $table->dropColumn('request_date');
            
            // Revert status enum
            \DB::statement("ALTER TABLE supply_requests MODIFY COLUMN status ENUM('pending', 'forwarded', 'approved', 'rejected') DEFAULT 'pending'");
            
            // Revert priority enum
            \DB::statement("ALTER TABLE supply_requests MODIFY COLUMN priority ENUM('Normal', 'High', 'Urgent') DEFAULT 'Normal'");
            
            // Make needed_date required again
            $table->date('needed_date')->nullable(false)->change();
        });
    }
};
