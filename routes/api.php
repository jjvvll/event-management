<?php

use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

// Public routes
Route::apiResource('events', EventController::class)
    ->only(['index', 'show']);

// Protected routes
Route::apiResource('events', EventController::class)
    ->only(['store', 'update', 'destroy'])
    ->middleware(['auth:sanctum', 'throttle:api']);

// Protected routes
Route::apiResource('events.attendees', AttendeeController::class)
    ->scoped()
    ->only(['store', 'destroy'])
    ->middleware(['auth:sanctum', 'throttle:api']);


// Public routes
Route::apiResource('events.attendees', AttendeeController::class)
    ->scoped()
    ->only(['index', 'show']);




// Route::apiResource('events', EventController::class);
// Route::apiResource('events.attendees', AttendeeController::class)
//     ->scoped()
//     ->only(['index', 'show']);

// // Route::apiResource('events.attendees', AttendeeController::class)
// //     ->scoped()->except(['update']); // if you would use route model binding to get an attendee, Laravel will automatically load it by looking only for the attendees of a parent event.
// //     // ->scoped(['attendee' => 'event']);

//     // Protected routes
// Route::apiResource('events', EventController::class)
// ->only(['store', 'update', 'destroy'])
// ->middleware(['auth:sanctum', 'throttle:api']);

// Route::apiResource('events.attendees', AttendeeController::class)
//     ->scoped()
//     ->only(['store', 'destroy'])
//     ->middleware(['auth:sanctum', 'throttle:api']);
