<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\ReviewController;


Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{user}', [UserController::class, 'show']);
Route::delete('/users/{user}', [UserController::class, 'destroy']) -> middleware('auth:sanctum');
Route::put('/users/{user}', [UserController::class, 'update']) -> middleware('auth:sanctum');

Route::get('/sessions', [SessionController::class, 'index']);
Route::get('/sessions/{session}', [SessionController::class, 'show']);
Route::delete('/sessions/{user}', [SessionController::class, 'destroy']) -> middleware('auth:sanctum');


Route::get('/user', function (Request $request) {
    return response()->json(
        [
            'id' => $request->user()->id,
            'first_name' => $request->user()->first_name,
            'last_name' => $request->user()->last_name,
            'email' => $request->user()->email,
            'role' => $request->user()->role,
        ]);
    })->middleware('auth:sanctum');

    // Restaurants
    Route::get('/restaurants', [RestaurantController::class, 'index']);
    Route::get('/restaurants/{restaurant}', [RestaurantController::class, 'show']);
    Route::post('/restaurants', [RestaurantController::class, 'store'])->middleware('auth:sanctum');
    Route::delete('/restaurants/{restaurant}', [RestaurantController::class, 'destroy'])->middleware('auth:sanctum');


    // Reviews
    Route::get('/restaurants/{restaurant}/reviews', [App\Http\Controllers\Api\ReviewController::class, 'index']);
    Route::post('/restaurants/{restaurant}/reviews', [App\Http\Controllers\Api\ReviewController::class, 'store'])->middleware('auth:sanctum');

    // Reservations
    Route::get('/restaurants/{restaurant}/available-slots', [App\Http\Controllers\ReservationController::class, 'availableTimeSlots']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/reservations', [App\Http\Controllers\ReservationController::class, 'index']);
        Route::post('/reservations', [App\Http\Controllers\ReservationController::class, 'store']);
        Route::get('/reservations/{reservation}', [App\Http\Controllers\ReservationController::class, 'show']);
        Route::put('/reservations/{reservation}', [App\Http\Controllers\ReservationController::class, 'update']);
        Route::delete('/reservations/{reservation}', [App\Http\Controllers\ReservationController::class, 'destroy']);
        Route::get('/reservations/{reservation}/pdf', [App\Http\Controllers\ReservationController::class, 'downloadPdf']);
        Route::get('/restaurants/{restaurant}/reservations', [App\Http\Controllers\ReservationController::class, 'restaurantReservations']);
        
        Route::post('/reservations/{reservation}/accept', [App\Http\Controllers\ReservationController::class, 'accept']);
        Route::post('/reservations/{reservation}/decline', [App\Http\Controllers\ReservationController::class, 'decline']);
    });
