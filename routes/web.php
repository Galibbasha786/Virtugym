<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\ProgressController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('exercises', ExerciseController::class)->only(['index', 'show']);
    Route::resource('workouts', WorkoutController::class);
    Route::resource('progress', ProgressController::class);
});

require __DIR__.'/auth.php';