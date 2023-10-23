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

    Route::prefix('tac')->group(function () {
        Route::post('verify', VerifyTacController::class);
    });

    Route::prefix('register')->group(function () {
        Route::post('', RegisterController::class);
        Route::post('send-tac','RegisterController@registerTac');
    });
    
    Route::prefix('forgot-password')->middleware(['throttle:10,60'])->group(function () {
        Route::post('', ForgotPasswordController::class);
        Route::post('reset', [ForgotPasswordController::class, 'reset']);
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('logout', 'LoginController@logout');

        Route::prefix('platform')->group(function () {
            Route::get('','PlatformController@index');

            Route::prefix('{platform_id}/game')->group(function () {
                Route::get('','GameController@index');
            });

            Route::prefix('{platform_id}/outlet')->group(function () {
                Route::get('','OutletController@index');
            });
        });

        Route::prefix('ticket')->group(function () {
            Route::post('','TicketController@store');
            Route::post('update-status','TicketController@updateTicketStatus');

        });

        Route::prefix('me')->group(function () {
            Route::get('ticket','TicketController@index');
        });

    });
});



Route::namespace('API')->middleware(['auth:sanctum', 'checkUserType:'.Role::NORMAL_USER])->group(function () {
});

Route::namespace('API')->middleware(['auth:sanctum', 'checkUserType:'.Role::MEMBER])->group(function () {
    // Routes for Member users
});

Route::namespace('API')->middleware(['auth:sanctum', 'checkUserType:'.Role::OPERATOR])->group(function () {
    // Routes for Operator users
    Route::prefix('topup')->group(function () {
        Route::post('{id}','TopUpController@store');
    });
});

Route::namespace('API')->middleware(['auth:sanctum', 'checkUserType:'.Role::SUPER_ADMIN])->group(function () {
    // Routes for Super Admin users
});