<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$users = DB::table('users')->select('id', 'name', 'email', 'role')->get();

echo "=== DANH SÁCH USERS VÀ ROLES ===" . PHP_EOL;
foreach ($users as $user) {
    echo "ID: {$user->id} | Name: {$user->name} | Email: {$user->email} | Role: {$user->role}" . PHP_EOL;
}
