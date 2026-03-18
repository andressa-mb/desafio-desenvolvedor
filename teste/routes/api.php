<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Files\FileController;

Route::prefix('auth')->group(function () {
    //login e register não precisam autenticação
    Route::post('/create', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user/{user}', [AuthController::class, 'show']);
    Route::get('/users', [AuthController::class, 'index']);
});

Route::prefix('docs')->middleware('auth:sanctum')->group(function () {
    Route::post('/save', [FileController::class, 'store']);
    Route::get('/history', [FileController::class, 'history']);
    Route::get('/search/{text}', [FileController::class, 'search']);
});
