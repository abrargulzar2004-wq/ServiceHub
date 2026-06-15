<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Models\Service;

Route::get('/', function () {
    $services = Service::with('worker')->latest()->take(6)->get();
    return view('home', compact('services'));
})->name('home');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// OTP Verification Route
Route::get('/otp-verify', [AuthController::class, 'showOtp'])->name('otp.verify');
Route::post('/otp-verify', [AuthController::class, 'verifyOtp']);

// Protected Routes
Route::middleware(['auth', 'verified.otp'])->group(function () {
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Admin Dashboard
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        Route::get('/customers', [AdminController::class, 'customers'])->name('customers');
        Route::get('/workers', [AdminController::class, 'workers'])->name('workers');
        Route::get('/workers/create', [AdminController::class, 'createWorker'])->name('workers.create');
        Route::post('/workers', [AdminController::class, 'storeWorker'])->name('workers.store');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::post('/users/{user}/toggle', [AdminController::class, 'toggleUserStatus'])->name('users.toggle');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        
        Route::get('/services', [AdminController::class, 'services'])->name('services');
        Route::get('/services/create', [AdminController::class, 'createService'])->name('services.create');
        Route::post('/services', [AdminController::class, 'storeService'])->name('services.store');
        Route::get('/services/{service}/edit', [AdminController::class, 'editService'])->name('services.edit');
        Route::put('/services/{service}', [AdminController::class, 'updateService'])->name('services.update');
        Route::delete('/services/{service}', [AdminController::class, 'destroyService'])->name('services.destroy');
        
        Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
        
        Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews');
        Route::delete('/reviews/{review}', [AdminController::class, 'destroyReview'])->name('reviews.destroy');
    });

    // Worker Dashboard
    Route::middleware('role:worker')->prefix('worker')->name('worker.')->group(function () {
        Route::get('/dashboard', [WorkerController::class, 'dashboard'])->name('dashboard');
        Route::post('/booking/{booking}/status', [WorkerController::class, 'updateStatus'])->name('booking.status');
    });

    // Customer Dashboard
    Route::middleware('role:customer')->prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
        Route::get('/services', [CustomerController::class, 'services'])->name('services');
        Route::post('/book/{service}', [CustomerController::class, 'book'])->name('book');
        Route::post('/review/{booking}', [CustomerController::class, 'review'])->name('review');
        Route::delete('/booking/{booking}', [CustomerController::class, 'cancel'])->name('booking.cancel');
    });

});
