<?php

use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [RecipeController::class, 'index'])->name('recipes.index');
Route::get('/recipes/search', [RecipeController::class, 'recipesSearch'])->middleware(['auth', 'verified'])->name('recipes.search');

Route::get('/recipes/create', [RecipeController::class, 'create'])->middleware(['auth', 'verified'])->name('recipes.create');
Route::get('/recipes/{recipe}', [RecipeController::class, 'show'])->middleware(['auth', 'verified'])->name('recipes.show');
Route::get('/recipes/{recipe}/edit', [RecipeController::class, 'edit'])->middleware(['auth', 'verified'])->name('recipes.edit');

Route::post('/recipes/create', [RecipeController::class, 'store'])->middleware(['auth','verified'])->name('recipes.store');
Route::post('/recipes/{recipe}', [RecipeController::class, 'update'])->middleware(['auth', 'verified'])->name('recipes.update');

Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->middleware(['auth', 'verified'])->name('recipes.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::resource('recipes', RecipeController::class);
});

require __DIR__.'/auth.php';
