<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$trainer = App\Models\User::where('role', 'trainer')->first();
$trainee = App\Models\User::where('role', 'trainee')->first();

Auth::login($trainer);

$request = Illuminate\Http\Request::create('/workouts', 'POST', [
    'trainee_id' => $trainee->id,
    'title' => 'Test Workout 3',
    'type' => 'Strength',
    'difficulty' => 'Beginner',
    'duration_minutes' => "45",
]);

$controller = app(App\Http\Controllers\WorkoutController::class);

try {
    $response = $controller->store($request);
    echo "Success! \n";
} catch (\Illuminate\Validation\ValidationException $e) {
    echo "Validation failed: \n";
    print_r($e->errors());
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
