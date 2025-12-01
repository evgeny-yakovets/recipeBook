<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RecipeController;
use App\Http\Controllers\Api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('recipes')->group(function () {
    Route::get('search', [RecipeController::class, 'search']);
    Route::get('/', [RecipeController::class, 'index']);
    Route::post('/', [RecipeController::class, 'store'])->middleware('auth:sanctum');
    Route::get('{recipe}', [RecipeController::class, 'show'])->middleware('auth:sanctum');
    Route::put('{recipe}', [RecipeController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('{recipe}', [RecipeController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:sanctum');
});
