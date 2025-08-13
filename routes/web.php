<?php
use App\Http\Controllers\PropertyController; 
use App\Http\Controllers\BookingController; 
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PropertyController::class, 'index'])->name('/');
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');

Route::middleware('auth')->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/cancel', [BookingController::class, 'showCancelForm'])
         ->name('bookings.cancel.show');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])
         ->name('bookings.cancel');
});

require __DIR__.'/auth.php';
