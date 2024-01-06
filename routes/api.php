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

    //TODO: add middleware for methods that should only be possible when authenticated
    Route::get('/users/events/{eventId}', [UserController::class, 'getRegisteredEvents']);
    
    // EVENTS
    Route::post('/events', [EventController::class, 'createEvent']);
    Route::get('/events/search/{searchString?}', [EventController::class, 'getEventList']);
    Route::get('/events/{eventId}', [EventController::class, 'getEvent']);
});
