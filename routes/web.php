<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\UserIngredientController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\SuggestedRecipeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () { return view('top');})->name('top');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/ingredients/create', [IngredientController::class, 'create'])->name('ingredients.create');
Route::post('/ingredients', [IngredientController::class, 'store'])->name('ingredients.store');

Route::get('/user_ingredients/create', [UserIngredientController::class, 'create'])->name('user_ingredients.create');
Route::post('/user_ingredients', [UserIngredientController::class, 'store'])->name('user_ingredients.store');

Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create')->middleware('auth');
Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');

Route::get('/nutrient', [RecipeController::class, 'nutrient'])->name('recipes.nutrient');
Route::post('/nutrientSearch',[RecipeController::class, 'nutrientSearch'])->name('recipes.nutrientSearch');

require __DIR__.'/auth.php';
Route::get('/chart', [ChartController::class, 'getting_started']);

Route::get('/suggested-recipes/create', [SuggestedRecipeController::class, 'create'])->name('suggested_recipes.create');
Route::post('/suggested-recipes', [SuggestedRecipeController::class, 'store'])->name('suggested_recipes.store');

