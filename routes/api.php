<?php

use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\API\UserTransferDetailsController;
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
    Route::post("admin/login", 'LoginController@adminLogin');

    Route::prefix('tac')->group(function () {
        Route::post('verify', VerifyTacController::class);
        Route::get('latest-tac', 'VerifyTacController@index');
    });

    Route::prefix('register')->group(function () {
        Route::post('', RegisterController::class);
        Route::post('send-tac','RegisterController@registerTac');
    });

    Route::prefix('forgot-password')->middleware(['throttle:10,60'])->group(function () {
        Route::post('', ForgotPasswordController::class);
        Route::post('reset', [ForgotPasswordController::class, 'reset']);
    });

    Route::prefix('transfer-options')->group(function () {
        Route::get('','TransferOptionsController@index');
    });

    Route::prefix('banners')->group(function () {
        Route::get('', 'BannerController@index');
    });

    Route::prefix('platforms')->group(function () {
        Route::get('','PlatformController@index');

        Route::prefix('{platform_id}/games')->group(function () {
            Route::get('','GameController@index');
        });

        Route::prefix('{platform_id}/outlets')->group(function () {
            Route::get('','OutletController@index');
        });
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('logout', 'LoginController@logout');
        Route::prefix('me')->group(function () {
            Route::get('','MeController@me');
            Route::put('','MeController@update');
            Route::delete('','MeController@destroy');
            Route::get('ticket','TicketController@index');
            Route::prefix('transfer-details')->group(function () {
                Route::get('','UserTransferDetailsController@index');
                Route::post('','UserTransferDetailsController@store');
                Route::put('{id}','UserTransferDetailsController@update');
                Route::delete('{id}','UserTransferDetailsController@destroy');
            });
            Route::prefix('credit-transactions')->group(function () {
                Route::get('','CreditTransactionController@index');
                Route::get('{id}','CreditTransactionController@show');

            });
            Route::prefix('point-transactions')->group(function () {
                Route::get('','PointTransactionController@index');
                Route::get('{id}','PointTransactionController@show');

            });
        });

        Route::prefix('tickets')->group(function () {
            Route::post('','TicketController@store');
            Route::post('update-status/{id}','TicketController@updateTicketStatus');    
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
    Route::prefix('me')->group(function () {
        
    });
    Route::prefix('tickets')->group(function () {
        Route::post('staff-update-status/{id}','TicketController@staffUpdateTicketStatus');
        Route::post('staff-scan-barcode/{id}','TicketController@staffScanBarcode');
    });
});

Route::namespace('API')->middleware(['auth:sanctum', 'checkUserType:'.Role::SUPER_ADMIN])->group(function () {
    // Routes for Super Admin users
});
