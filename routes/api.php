<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Public
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/products', [ProductsController::class, 'show']);
Route::get('/products/{id}', [ProductsController::class, 'showId']);
Route::get('/categories', [CategoriesController::class, 'show']);



// Any authenticated user
Route::middleware('auth:api')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Admin
Route::middleware(['auth:api', 'scope:admin'])->group(function () {
    Route::post('/products', [ProductsController::class, 'store']);
    Route::put('/products/{id}', [ProductsController::class, 'update']);
    Route::delete('/products/{id}', [ProductsController::class, 'destroy']);
});

// Admin + Super Admin
Route::middleware(['auth:api', 'scope:admin,super_admin'])->group(function () {});

// Super Admin
Route::middleware(['auth:api', 'scope:super_admin'])->group(function () {});
