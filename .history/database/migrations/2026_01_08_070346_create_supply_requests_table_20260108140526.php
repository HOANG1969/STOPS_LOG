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
        Schema::create('supply_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_code')->unique(); // VP001, VP002
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('requester_name');
            $table->string('requester_email');
            $table->string('department');
            $table->enum('priority', ['Normal', 'High', 'Urgent'])->default('Normal');
            $table->date('needed_date');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'forwarded', 'approved', 'rejected'])->default('pending');
            $table->timestamp('forwarded_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->text('approval_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supply_requests');
    }
};
