<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProcedureController;
use App\Http\Controllers\Admin\QuestionController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/chat/send', [ChatController::class, 'sendMessage']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::apiResource('/procedures', ProcedureController::class);
        Route::get('/questions', [QuestionController::class, 'index']);
        Route::patch('/questions/{question}/resolve', [QuestionController::class, 'resolve']);
        Route::post('/questions/{question}/convert', [QuestionController::class, 'convert']);
    });
});
