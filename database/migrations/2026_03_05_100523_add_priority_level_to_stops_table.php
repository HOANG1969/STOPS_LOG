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
        Schema::table('stops', function (Blueprint $table) {
            $table->tinyInteger('priority_level')
                  ->after('issue_category')
                  ->nullable()
                  ->default(null)
                  ->comment('Mức độ quan trọng: 0=Cao nhất, 1=Cao, 2=Trung bình, 3=Thấp. NULL=Chưa chấm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stops', function (Blueprint $table) {
            $table->dropColumn('priority_level');
        });
    }
};
