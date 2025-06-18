<?php
// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Routes d'authentification publiques
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Routes protégées par Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
    
    // Exemples de routes protégées par rôle
    Route::middleware('role:Admin')->group(function () {
        // Routes réservées aux admins
    });
    
    Route::middleware('role:Client')->group(function () {
        // Routes réservées aux clients
    });
    
    Route::middleware('role:Agent commercial,Responsable production')->group(function () {
        // Routes pour agents et responsables production
    });
});