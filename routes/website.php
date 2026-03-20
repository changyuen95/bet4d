<?php

use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\LiveDrawController;
use App\Http\Controllers\Website\ResultController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Website Routes (Public Frontend)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/live-draw', [LiveDrawController::class, 'index'])->name('live-draw');

Route::prefix('results')->name('results.')->group(function () {
    Route::get('/', [ResultController::class, 'index'])->name('index');
    Route::get('/{draw}', [ResultController::class, 'show'])->name('show');
});

// API endpoint for live-draw polling (same domain as website)
Route::get('/api/draw-results', [LiveDrawController::class, 'latestResult'])->name('api.draw-results');
