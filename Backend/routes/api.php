<?php
// routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RawMaterialController;
use App\Http\Controllers\Api\CommandeController;

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

// Routes publiques pour les catégories (accès sans authentification)
Route::get('/public/categories', [CategoryController::class, 'index']);
Route::get('/public/categories/{id}', [CategoryController::class, 'show']);

// Routes publiques pour les produits (accès sans authentification)
Route::get('/public/products', [ProductController::class, 'index']);
Route::get('/public/products/{id}', [ProductController::class, 'show']);

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
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        
        Route::middleware('role:Responsable production')->group(function () {
            Route::post('/', [CategoryController::class, 'store']);
            Route::put('/{id}', [CategoryController::class, 'update']);
            Route::delete('/{id}', [CategoryController::class, 'destroy']);
        });
    });
    
    // Routes pour la gestion des produits
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
        
        Route::middleware('role:Responsable production')->group(function () {
            Route::post('/', [ProductController::class, 'store']);
            Route::put('/{id}', [ProductController::class, 'update']);
            Route::delete('/{id}', [ProductController::class, 'destroy']);
        });
    });
    
    // Routes pour la gestion des matières premières
    Route::prefix('raw-materials')->group(function () {
        Route::get('/', [RawMaterialController::class, 'index']);
        Route::get('/{id}', [RawMaterialController::class, 'show']);
        
        Route::middleware('role:Responsable production')->group(function () {
            Route::post('/', [RawMaterialController::class, 'store']);
            Route::put('/{id}', [RawMaterialController::class, 'update']);
            Route::delete('/{id}', [RawMaterialController::class, 'destroy']);
        });
    });

    // Routes pour la gestion des commandes (Clients uniquement)
    Route::prefix('commandes')->middleware('role:Client')->group(function () {
        Route::get('/', [CommandeController::class, 'index']);
        Route::get('/{id}', [CommandeController::class, 'show']);
        Route::post('/', [CommandeController::class, 'store']);
        Route::put('/{id}', [CommandeController::class, 'update']);
        Route::delete('/{id}', [CommandeController::class, 'destroy']);
    });
    
    // Routes protégées par rôle Agent commercial
    Route::middleware('role:Agent commercial')->group(function () {
       
    });
});