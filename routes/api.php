<?php

use App\Http\Controllers\API\ForgotPasswordController;
use App\Models\User;
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

Route::namespace('API')->group(function () {
    Route::post("login", LoginController::class);
    Route::post("register", RegisterController::class);
});

Route::namespace('API')->middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', 'LoginController@logout');
});

Route::namespace('API')->middleware(['auth:sanctum', 'checkUserType:'.User::TYPE['Normal_User']])->group(function () {
    Route::get('forgotpassword', ForgotPasswordController::class);
});

Route::namespace('API')->middleware(['auth:sanctum', 'checkUserType:'.User::TYPE['Member']])->group(function () {
    // Routes for Member users
});

Route::namespace('API')->middleware(['auth:sanctum', 'checkUserType:'.User::TYPE['Staff']])->group(function () {
    // Routes for Staff users
});

Route::namespace('API')->middleware(['auth:sanctum', 'checkUserType:'.User::TYPE['Admin']])->group(function () {
    // Routes for admin users
});