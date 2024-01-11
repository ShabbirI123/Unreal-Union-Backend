<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('unreal-union/v1')->group(function () {

    // USERS
    Route::post('/users', [UserController::class, 'register']);
    Route::post('/users/login', [UserController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {

        //USERS
        Route::delete('/users/logout/{userId}', [UserController::class, 'invalidToken']);

        Route::post('/users/events', [UserController::class, 'registerForEvent']);
        Route::get('/users/events/{userId}', [UserController::class, 'getRegisteredEvents']);

        // EVENTS
        Route::post('/events', [EventController::class, 'createEvent']);
        Route::get('/events/search/{searchString?}', [EventController::class, 'getEventList']);
        Route::get('/events/{eventId}', [EventController::class, 'getEvent']);
    });
});
