<?php
// routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Route utilisateur de base
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Routes d'authentification publiques (pour maintenir la compatibilité)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Routes de vérification d'email
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['signed'])
    ->name('verification.verify');
Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail']);

// Alternative avec préfixe auth (si vous voulez changer vos URLs)
// Route::prefix('auth')->group(function () {
//     Route::post('/register', [AuthController::class, 'register']);
//     Route::post('/login', [AuthController::class, 'login']);
// });

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