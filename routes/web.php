<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Trainer\TrainerDashboardController;
use App\Http\Controllers\Trainee\TraineeDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

// CO3 - Auth routes (default Laravel)
require __DIR__.'/auth.php';

// CO3 - Authenticated routes group with middleware
Route::middleware(['auth'])->group(function () {
    
    // CO3 - Named route for dashboard redirect
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
    
    // CO3 - Resource Controller with only specific methods
    Route::resource('bookings', BookingController::class)->only(['index', 'update']);
    
    // CO3 - Named routes for booking payment
    Route::get('/book-trainer/{id}', [BookingController::class, 'create'])->name('book.trainer.create');
    Route::post('/initiate-payment/{trainer_id}', [BookingController::class, 'initiatePayment'])->name('initiate.payment');
    Route::post('/payment-success', [BookingController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment-failed', [BookingController::class, 'paymentFailed'])->name('payment.failed');
    
    // Alternative route for my-bookings (if you prefer this name)
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.index');
    
    // CO3 - Route group with prefix and name for Admin
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
        Route::get('/trainers', [AdminDashboardController::class, 'trainers'])->name('trainers');
        Route::post('/trainers/{id}/verify', [AdminDashboardController::class, 'verifyTrainer'])->name('trainers.verify');
        Route::get('/bookings', [AdminDashboardController::class, 'bookings'])->name('bookings');
    });
    
    // CO3 - Route group with prefix and name for Trainer
    Route::middleware(['role:trainer'])->prefix('trainer')->name('trainer.')->group(function () {
        Route::get('/dashboard', [TrainerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/clients', [TrainerDashboardController::class, 'clients'])->name('clients');
        Route::get('/schedule', [TrainerDashboardController::class, 'schedule'])->name('schedule');
        Route::post('/profile/update', [TrainerDashboardController::class, 'updateProfile'])->name('profile.update');
    });
    
    // CO3 - Route group with prefix and name for Trainee
    Route::middleware(['role:trainee'])->prefix('trainee')->name('trainee.')->group(function () {
        Route::get('/dashboard', [TraineeDashboardController::class, 'index'])->name('dashboard');
        Route::get('/trainers', [TraineeDashboardController::class, 'trainers'])->name('trainers');
        Route::get('/trainers/{id}/book', [TraineeDashboardController::class, 'bookTrainer'])->name('book-trainer');
    });
});