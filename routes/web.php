<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Trainer\TrainerDashboardController;
use App\Http\Controllers\Trainee\TraineeDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    
    // Role-based redirect
    Route::get('/dashboard', function () {
        $role = auth()->user()->role ?? 'trainee';
        
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'trainer') {
            return redirect()->route('trainer.dashboard');
        } else {
            return redirect()->route('trainee.dashboard');
        }
    })->name('dashboard');
    
    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
        Route::get('/trainers', [AdminDashboardController::class, 'trainers'])->name('trainers');
        Route::post('/trainers/{id}/verify', [AdminDashboardController::class, 'verifyTrainer'])->name('trainers.verify');
        Route::get('/bookings', [AdminDashboardController::class, 'bookings'])->name('bookings');
    });
    
    // Trainer Routes
    Route::middleware(['role:trainer'])->prefix('trainer')->name('trainer.')->group(function () {
        Route::get('/dashboard', [TrainerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/clients', [TrainerDashboardController::class, 'clients'])->name('clients');
        Route::get('/schedule', [TrainerDashboardController::class, 'schedule'])->name('schedule');
        Route::post('/profile/update', [TrainerDashboardController::class, 'updateProfile'])->name('profile.update');
    });
    
    // Trainee Routes
    Route::middleware(['role:trainee'])->prefix('trainee')->name('trainee.')->group(function () {
        Route::get('/dashboard', [TraineeDashboardController::class, 'index'])->name('dashboard');
        Route::get('/trainers', [TraineeDashboardController::class, 'trainers'])->name('trainers');
        Route::get('/trainers/{id}/book', [TraineeDashboardController::class, 'bookTrainer'])->name('book-trainer');
        Route::get('/my-bookings', [TraineeDashboardController::class, 'myBookings'])->name('my-bookings');
        Route::get('/my-workouts', [TraineeDashboardController::class, 'myWorkouts'])->name('my-workouts');
    });
});

require __DIR__.'/auth.php';