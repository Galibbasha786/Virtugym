<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Trainer\TrainerDashboardController;
use App\Http\Controllers\Trainee\TraineeDashboardController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\TrainerAvailabilityController;
use App\Http\Controllers\VideoCallController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

require __DIR__.'/auth.php';

// ============ AUTHENTICATED ROUTES ============
Route::middleware(['auth'])->group(function () {
    
    // Dashboard Redirect
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
    
    // Bookings
    Route::resource('bookings', BookingController::class)->only(['index', 'update']);
    Route::get('/book-trainer/{id}', [BookingController::class, 'create'])->name('book.trainer.create');
    Route::post('/initiate-payment/{trainer_id}', [BookingController::class, 'initiatePayment'])->name('initiate.payment');
    Route::post('/payment-success', [BookingController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment-failed', [BookingController::class, 'paymentFailed'])->name('payment.failed');
    
    // Chat
    Route::get('/chat/{trainer_id?}', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/messages/{trainer_id}', [ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/unread/count', [ChatController::class, 'getUnreadCount'])->name('chat.unread');
    
    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    
    // Profile
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
    
    // ============ ADMIN ROUTES ============
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
        Route::get('/trainers', [AdminDashboardController::class, 'trainers'])->name('trainers');
        Route::post('/trainers/{id}/verify', [AdminDashboardController::class, 'verifyTrainer'])->name('trainers.verify');
        Route::get('/bookings', [AdminDashboardController::class, 'bookings'])->name('bookings');
    });
    
    // ============ TRAINER ROUTES ============
    Route::prefix('trainer')->name('trainer.')->middleware(['role:trainer'])->group(function () {
        Route::get('/dashboard', [TrainerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/clients', [TrainerDashboardController::class, 'clients'])->name('clients');
        Route::get('/schedule', [TrainerDashboardController::class, 'schedule'])->name('schedule');
        Route::post('/profile/update', [TrainerDashboardController::class, 'updateProfile'])->name('profile.update');
        
        // TRAINER AVAILABILITY ROUTES
        Route::get('/availability', [TrainerAvailabilityController::class, 'index'])->name('availability.index');
        Route::post('/availability', [TrainerAvailabilityController::class, 'store'])->name('availability.store');
        Route::delete('/availability/{id}', [TrainerAvailabilityController::class, 'destroy'])->name('availability.destroy');
    });
    
    // ============ TRAINEE ROUTES ============
    Route::prefix('trainee')->name('trainee.')->middleware(['role:trainee'])->group(function () {
        Route::get('/dashboard', [TraineeDashboardController::class, 'index'])->name('dashboard');
        Route::get('/trainers', [TraineeDashboardController::class, 'trainers'])->name('trainers');
        Route::get('/trainers/{id}/book', [TraineeDashboardController::class, 'bookTrainer'])->name('book-trainer');
    });
    
    // ============ AVAILABLE SLOTS API ============
    Route::get('/trainer/available-slots/{trainer_id}/{date}', [TrainerAvailabilityController::class, 'getAvailableSlots'])->name('trainer.available-slots');
    
    // ============ VIDEO CALL ROUTES ============
    Route::prefix('video-call')->name('video-call.')->group(function () {
        Route::get('/join/{booking_id}', [VideoCallController::class, 'join'])->name('join');
        Route::post('/start/{booking_id}', [VideoCallController::class, 'startMeeting'])->name('start');
        Route::post('/end/{booking_id}', [VideoCallController::class, 'endMeeting'])->name('end');
    });
});
