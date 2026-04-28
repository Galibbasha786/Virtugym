<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$trainee = App\Models\User::where('role', 'trainee')->first();
$query = App\Models\Workout::where('trainee_id', $trainee->id)
                ->orWhere('user_id', $trainee->id)
                ->orderBy('created_at', 'desc');

echo "Query: " . print_r($query->toMql(), true) . "\n";
echo "Count: " . $query->count() . "\n";
