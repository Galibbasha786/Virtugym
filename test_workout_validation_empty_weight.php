<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$trainer = App\Models\User::where('role', 'trainer')->first();
$trainee = App\Models\User::where('role', 'trainee')->first();
$exercise = App\Models\Exercise::first();

Auth::login($trainer);

$request = Illuminate\Http\Request::create('/workouts', 'POST', [
    'trainee_id' => $trainee->id,
    'title' => 'Test Workout Empty Weight',
    'type' => 'Strength',
    'difficulty' => 'Beginner',
    'duration_minutes' => "45",
    'exercises' => [
        [
            'exercise_id' => $exercise->id,
            'sets' => "3",
            'reps' => "10",
            'target_weight' => "" // empty string
        ]
    ]
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
