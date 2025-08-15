<?php
use App\Http\Controllers\PropertyController; 
use App\Http\Controllers\BookingController; 
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminPropertyController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [PropertyController::class, 'index'])->name('/');
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');

// Simple user routes 
Route::middleware('auth')->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/cancel', [BookingController::class, 'showCancelForm'])
         ->name('bookings.cancel.show');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])
         ->name('bookings.cancel');
});
// Admin routes 
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Properties management
    Route::get('/properties', [AdminPropertyController::class, 'index'])->name('properties.index');
    Route::post('/properties', [AdminPropertyController::class, 'store'])->name('properties.store');
    Route::put('/properties/{property}', [AdminPropertyController::class, 'update'])->name('properties.update');
    Route::delete('/properties/{property}', [AdminPropertyController::class, 'destroy'])->name('properties.destroy');
    
    // Bookings management
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');
});

require __DIR__.'/auth.php';
