<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->boot();

use App\Models\User;

// Update mai.ptn role to admin
$user = User::where('email', 'mai.ptn@pvgas.com.vn')->first();

if ($user) {
    $user->role = 'admin';
    $user->save();
    echo "✅ Updated mai.ptn role to admin\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "Role: {$user->role}\n";
    echo "Position: {$user->position}\n";
} else {
    echo "❌ User mai.ptn@pvgas.com.vn not found\n";
    
    // Show all users for debugging
    $users = User::all(['name', 'email', 'role']);
    echo "\nExisting users:\n";
    foreach ($users as $u) {
        echo "- {$u->name} ({$u->email}) - {$u->role}\n";
    }
}