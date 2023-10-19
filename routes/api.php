<?php

use App\Http\Controllers\API\ForgotPasswordController;
use App\Models\Role;
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
    Route::post('forgotPassword',ForgotPasswordController::class);
    Route::post('resetPassword','ForgotPasswordController@reset');
    Route::post('verifyTac', VerifyTacController::class);
    Route::post('registerTac','RegisterController@registerTac');

});

Route::namespace('API')->middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', 'LoginController@logout');
    Route::get('getOutletListing','OutletController@index');
    Route::get('getPlatformListing','PlatformController@index');
    Route::get('getGameListing','GameController@index');
    Route::post('ticketRequest','TicketController@store');
    Route::post('ticket/updateTicketStatus','TicketController@updateTicketStatus');
    Route::get('getUserTicketListing','TicketController@index');

    
});

Route::namespace('API')->middleware(['auth:sanctum', 'checkUserType:'.Role::NORMAL_USER])->group(function () {
    Route::get('forgotpassword', ForgotPasswordController::class);
});

Route::namespace('API')->middleware(['auth:sanctum', 'checkUserType:'.Role::MEMBER])->group(function () {
    // Routes for Member users
});

Route::namespace('API')->middleware(['auth:sanctum', 'checkUserType:'.Role::OPERATOR])->group(function () {
    // Routes for Operator users
});

Route::namespace('API')->middleware(['auth:sanctum', 'checkUserType:'.Role::SUPER_ADMIN])->group(function () {
    // Routes for Super Admin users
});