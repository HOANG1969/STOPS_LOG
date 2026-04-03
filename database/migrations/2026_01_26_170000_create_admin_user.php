<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        User::create([
            'name' => 'admin',
            'full_name' => 'Quản trị viên hệ thống',
            'email' => 'admin@company.com',
            'password' => bcrypt('123456'),
            'department' => 'IT',
            'position' => 'Quản trị viên',
            'role' => 'admin',
            'phone' => '0901234000',
            'is_active' => true,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        User::where('email', 'admin@company.com')->delete();
    }
};