<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\TimeSlotController;


// 🔹 rota de teste
Route::get('/health', function () {
    return response()->json([
        'ok' => true,
        'time' => now(),
    ]);
});

Route::get('/teste-octane', function () {
    return [
        'worker_pid' => getmypid(),
        'server' => 'Swoole',
        'time' => now(),
    ];
});

// 🔹 rotas versionadas
Route::prefix('v1')->group(function () {

    // Auth
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Protegidas por Sanctum
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // CRUD Users
        Route::apiResource('users', UserController::class);

        //TIME SLOT
        // ADMIN: gerar slots
        Route::post('slots/generate', [TimeSlotController::class, 'generate']);

        // Cliente: ver slots disponíveis
        Route::get('slots/available', [TimeSlotController::class, 'available']); // ?date=2025-09-01

        // Cliente: reservar
        Route::post('slots/reserve/{id}', [TimeSlotController::class, 'reserve'])->middleware('auth:sanctum');

        // Listar todos slots (admin)
        Route::get('slots', [TimeSlotController::class, 'index']);

    });
});
