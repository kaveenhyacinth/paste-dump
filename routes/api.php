<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

//region Public Routes
Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});
//endregion


//region Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});
//endregion

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');
