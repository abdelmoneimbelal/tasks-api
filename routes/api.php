<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api', 'throttle:10,1')->prefix('user')->group(function(){

    Route::post('update/password', [UserController::class, 'updatePassword']);
    Route::post('update/profile', [UserController::class, 'updateProfile']);

});