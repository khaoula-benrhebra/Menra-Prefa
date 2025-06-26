<?php
// routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;

// Route utilisateur de base
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Routes d'authentification publiques
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Routes de vérification d'email
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['signed'])
    ->name('verification.verify');
Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail']);

// Routes protégées par Sanctum
Route::middleware(['auth:sanctum', 'auth.gates'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
    
    // Routes pour la gestion des utilisateurs (Admin uniquement)
    Route::prefix('admin')->middleware('role:Admin')->group(function () {
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users', [UserController::class, 'index']);
    });
    
    // Routes pour la gestion des catégories
    Route::prefix('categories')->group(function () {
        // Routes publiques (lecture) - accessibles à tous les utilisateurs connectés
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        
        // Routes protégées (écriture) - réservées au Responsable production
        Route::middleware('role:Responsable production')->group(function () {
            Route::post('/', [CategoryController::class, 'store']);
            Route::put('/{id}', [CategoryController::class, 'update']);
            Route::delete('/{id}', [CategoryController::class, 'destroy']);
        });
    });
    
    // Exemples de routes protégées par rôle
    Route::middleware('role:Admin')->group(function () {
        // Autres routes réservées aux admins
    });
    
    Route::middleware('role:Client')->group(function () {
        // Routes réservées aux clients
    });
    
    Route::middleware('role:Agent commercial')->group(function () {
        // Routes pour agents comercial
    });
});