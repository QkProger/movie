<?php

use App\Http\Controllers\Api\GenreController;
use App\Http\Controllers\Api\MovieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/genres', [GenreController::class, 'index']);
// Route::get('/genres/{id}', [GenreController::class, 'show']);
// Route::get('/movies', [MovieController::class, 'index']);
// Route::get('/movies/{id}', [MovieController::class, 'show']);

Route::resource('genres', GenreController::class)->except(['create', 'edit']);
Route::resource('movies', MovieController::class)->except(['create', 'edit']);
Route::post('/movie/public/{id}', [MovieController::class, 'public']);