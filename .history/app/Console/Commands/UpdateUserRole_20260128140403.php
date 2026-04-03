<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UpdateUserRole extends Command
{
    protected $signature = 'user:update-role {email} {role}';
    protected $description = 'Update user role';

    public function handle()
    {
        $email = $this->argument('email');
        $role = $this->argument('role');
        
        $user = User::where('email', $email)->first();
        
        if ($user) {
            $user->role = $role;
            $user->save();
            $this->info("✅ Updated {$email} role to {$role}");
            $this->line("Name: {$user->name}");
            $this->line("Email: {$user->email}");
            $this->line("Role: {$user->role}");
            $this->line("Position: {$user->position}");
        } else {
            $this->error("❌ User {$email} not found");
            
            // Show existing users
            $users = User::all(['name', 'email', 'role']);
            $this->line("\nExisting users:");
            foreach ($users as $u) {
                $this->line("- {$u->name} ({$u->email}) - {$u->role}");
            }
        }
    }
}