<?php

use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\API\BankReceiptController;
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

    Route::get('webviews', 'SettingController@index');


    Route::post("login", LoginController::class);
    Route::post("admin/login", 'LoginController@adminLogin');
    Route::get('locales', LocaleController::class);

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
            Route::get('{id}','OutletController@show');
        });
    });

    Route::prefix('dictionaries')->group(function () {
        Route::get('','DictionaryController@index');
    });

    Route::prefix('draw-results')->group(function () {
        Route::get('','DrawResultController@index');
        Route::get('{id}','DrawResultController@show');
    });

    Route::prefix('draw-calendar')->group(function () {
        Route::get('','DrawCalendarController@index');
    });
    Route::prefix('draw')->group(function () {
        Route::get('current-draw','DrawController@getCurrentDraw');
        Route::get('count-down-time','DrawController@getCountDownTime');
    });

    Route::prefix('winning-list')->group(function () {
        Route::get('','WinnerListDisplayController@index');
        Route::get('draw/{id}','WinnerListDisplayController@show');
    });

    Route::prefix('number-frequencies')->group(function () {
        Route::get('','NumberFrequenciesController@index');
        Route::get('{id}','NumberFrequenciesController@show');
    });

    Route::prefix('popular-number')->group(function () {
        Route::get('','PopularNumberController@index');
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('logout', 'LoginController@logout');
        Route::prefix('me')->group(function () {
            Route::post('one-signal-test', 'MeController@oneSignalTest');
            Route::get('','MeController@me');
            Route::post('','MeController@update');
            Route::post('update-avatar','MeController@updateAvatar');
            Route::delete('','MeController@destroy');

            Route::prefix('tickets')->group(function () {
                Route::get('','TicketController@index');
                Route::get('{id}','TicketController@show');
            });

            Route::prefix('transfer-details')->group(function () {
                Route::get('','UserTransferDetailsController@index');
                Route::get('{id}','UserTransferDetailsController@show');
                Route::post('','UserTransferDetailsController@store');
                Route::post('{id}','UserTransferDetailsController@update');
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
            Route::prefix('winning-history')->group(function () {
                Route::get('','WinningHistoryController@index');
                // Route::get('{id}','WinningHistoryController@show');
            });

            Route::prefix('verify-profiles')->group(function () {
                Route::get('','VerifyProfileController@index');
                Route::post('','VerifyProfileController@store');
            });

            Route::prefix('topup')->group(function () {
                Route::post('/qrcode/{id}','TopUpController@topupByQrCode');
            });

            Route::prefix('winner')->group(function () {
                Route::get('{id}','WinningHistoryController@show');
                // Route::get('{id}','WinningHistoryController@show');
            });

            Route::prefix('bank-receipts')->group(function() {
                Route::get('/bank-account', [BankReceiptController::class, 'bankAccount'])->name('bank-receipts.bankAccount');

                // Display all receipts
                Route::get('/', [BankReceiptController::class, 'index'])->name('bank-receipts.index');

                // Create a new receipt
                Route::post('/', [BankReceiptController::class, 'store'])->name('bank-receipts.store');

                // Show a specific receipt
                Route::get('/{id}', [BankReceiptController::class, 'show'])->name('bank-receipts.show');

                // Update a receipt status (user)
                Route::put('/{id}/update-status', [BankReceiptController::class, 'updateReceiptStatus'])->name('bank-receipts.updateStatus');

                // Get bank account details
            });
        });



        Route::prefix('notifications')->group(function () {
            Route::get('','NotificationController@index');
            Route::post('mark-as-read/{id}','NotificationController@markAsRead');
            Route::post('mark-all-as-read','NotificationController@markAllAsRead');
            Route::get('unread-count','NotificationController@unReadCount');

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

Route::namespace('API')->prefix('admin')->middleware(['auth:sanctum', 'checkIsAdmin'])->group(function () {
    Route::post('logout', 'LoginController@adminLogout');

    Route::prefix('me')->group(function () {
        Route::get('','Admin\MeController@me');
        Route::post('','Admin\MeController@update');
        Route::post('update-avatar','Admin\MeController@updateAvatar');
        Route::delete('','Admin\MeController@destroy');
    });

    Route::middleware(['checkUserType:'.Role::HQ])->group(function () {
        Route::prefix('verify-user-profiles')->group(function () {
            Route::get('','VerifyProfileController@pendingListing');
            Route::get('{id}','VerifyProfileController@verifyProfileDetail');
            Route::post('{id}/approved','VerifyProfileController@approvedICVerification');
            Route::post('{id}/rejected','VerifyProfileController@rejectedICVerification');
        });

        Route::prefix('bank-receipts')->group(function() {
            // Display all receipts
            Route::get('/', [BankReceiptController::class, 'index'])->name('bank-receipts.index');

            // Show a specific receipt
            Route::get('/{id}', [BankReceiptController::class, 'show'])->name('bank-receipts.show');

            Route::put('/{id}/update-status', [BankReceiptController::class, 'staffUpdateReceiptStatus'])->name('bank-receipts.staffUpdateStatus');

        });

        Route::prefix('bank-receipts')->group(function() {
            Route::get('/bank-account', [BankReceiptController::class, 'bankAccount'])->name('bank-receipts.bankAccount');

            // Display all receipts
            Route::get('/', [BankReceiptController::class, 'index'])->name('bank-receipts.index');

            // Create a new receipt
            Route::post('/', [BankReceiptController::class, 'store'])->name('bank-receipts.store');

            // Show a specific receipt
            Route::get('/{id}', [BankReceiptController::class, 'show'])->name('bank-receipts.show');


            // admin update receipt
            Route::put('/{id}/update-status', [BankReceiptController::class, 'staffUpdateReceiptStatus'])->name('bank-receipts.updateStatus');


            // Get bank account details
        });
    });

    Route::middleware(['checkUserType:'.Role::OPERATOR])->group(function () {

        Route::prefix('notifications')->group(function () {
            Route::get('','NotificationController@index');
            Route::post('mark-as-read/{id}','NotificationController@markAsRead');
            Route::post('mark-all-as-read','NotificationController@markAllAsRead');
            Route::get('unread-count','NotificationController@unReadCount');

        });

        Route::prefix('topup')->group(function () {
            Route::post('/qrcode/{id}','TopUpController@topupByQrCode');
            Route::post('{id}','TopUpController@store');

        });

        Route::prefix('tickets')->group(function () {
            Route::get('','StaffTicketController@index');
            Route::get('requested','TicketController@staffTicketListing');
            Route::get('pending-count','StaffTicketController@pending_count');
            Route::get('{id}','StaffTicketController@show');
            Route::post('update-status/{id}','TicketController@staffUpdateTicketStatus');
            Route::post('{id}/staff-scan-barcode','TicketController@staffScanBarcode');
            Route::get('{id}/barcode','TicketController@barcodeListing');
            Route::post('{id}/remove-barcode/{barcode_id}','TicketController@removeBarcode');
            Route::post('{id}/modify-ticket-amounts','TicketController@modifyTicketAmount');

            Route::prefix('{ticket_id}/ticket-number')->group(function () {
                Route::post('{ticket_number_id}/permutation-image','StaffTicketController@permutationImage');
                Route::post('{ticket_number_id}/remove-permutation-image','StaffTicketController@removePermutationImage');
                Route::get('{ticket_number_id}/permutation-image','StaffTicketController@ticketNumberImage');

            });
        });

        Route::prefix('credit-transactions')->group(function () {
            Route::get('','AdminCreditTransactionController@index');
            Route::get('{id}','AdminCreditTransactionController@show');
        });

        Route::prefix('distribute-prizes')->group(function () {
            Route::get('','DistributePrizeController@index');
            Route::get('{id}','DistributePrizeController@show');
            Route::post('{id}','DistributePrizeController@store');
        });

        Route::get('cleared-transactions/credit-distribute','Admin\DownlineController@creditDistribute');
        Route::get('{admin_id}/clear-transactions/credit-distribute/{id}','Admin\DownlineController@creditDistributeDetail');

    });
    // Routes for Operator users

    Route::middleware(['checkUserType:'.Role::SUPER_ADMIN])->group(function () {

        // Route::prefix('notifications')->group(function () {
        //     Route::get('','NotificationController@index');
        //     Route::post('mark-all-as-read/{id}','NotificationController@markAsRead');
        //     Route::get('unread-count','NotificationController@unReadCount');

        // });

        Route::prefix('cleared-credit')->group(function () {
            Route::get('', 'Admin\RecordController@indexClearedCredit');
            Route::get('{id}', 'Admin\RecordController@showClearedCredit');
        });

        Route::prefix('verified-prize')->group(function () {
            Route::get('','Admin\RecordController@indexVerifiedPrize');
            Route::get('{id}','Admin\RecordController@showVerifiedPrize');
        });

        Route::prefix('downlines')->group(function () {
            Route::get('','Admin\DownlineController@index');

            /****** Credit Trasaction API for downlines ******/
            Route::get('{admin_id}/credit-transactions','Admin\DownlineController@adminCreditTransaction');
            Route::get('{admin_id}/credit-transactions/{id}','Admin\DownlineController@showAdminCreditTransaction');

            Route::get('{id}/clear-transactions','Admin\DownlineController@clearTransactions');
            Route::get('{id}/clear-transactions/credit-distribute','Admin\DownlineController@creditDistribute');
            Route::get('{admin_id}/clear-transactions/credit-distribute/{id}','Admin\DownlineController@creditDistributeDetail');
            Route::post('{id}/clear-transactions','Admin\DownlineController@clearTransactionsProcess');

            /****** Prize Trasaction API for downlines ******/
            Route::get('{id}/prize-transactions','Admin\PrizeTransactionController@index');
            Route::get('{admin_id}/prize-transactions/{id}','Admin\PrizeTransactionController@show');

            Route::prefix('{admin_id}/verify-pending-prize')->group(function () {
                Route::get('', 'Admin\PrizeTransactionController@pendingList');
                Route::get('{id}', 'Admin\PrizeTransactionController@pendingDetail');
                Route::post('{id}', 'Admin\PrizeTransactionController@verifyPendingprize');
            });

        });


        /****** Pending Prize to distribute ******/
        Route::prefix('pending-prize-distribution')->group(function () {
            Route::get('pending-count', 'Admin\PendingPrizeDistributionController@getCount');
            Route::get('', 'Admin\PendingPrizeDistributionController@index');
            Route::get('{id}','Admin\PendingPrizeDistributionController@show');
            Route::post('{id}/resend-notification', 'Admin\PendingPrizeDistributionController@resendNotification');
        });

        Route::prefix('pending-verify-distributed-prize')->group(function () {
            Route::get('', 'Admin\VerifyPrizeController@index');
            Route::get('{id}', 'Admin\VerifyPrizeController@show');
            Route::post('{id}', 'Admin\VerifyPrizeController@verifyDistributePrize');
        });



        /****** Records ******/
        Route::prefix('records')->group(function () {
            Route::get('profile', 'Admin\RecordController@indexProfile');
            Route::get('profile/{id}', 'Admin\RecordController@showProfile');
            Route::get('cleared-credit', 'Admin\RecordController@indexClearedCredit');
            Route::get('cleared-credit/{id}', 'Admin\RecordController@showClearedCredit');
            Route::get('verified-prize','Admin\RecordController@indexVerifiedPrize');
            Route::get('verified-prize/{id}','Admin\RecordController@showVerifiedPrize');
        });

    });
});

// Route::namespace('API\Admin')->prefix('admin')->middleware(['auth:sanctum', 'checkUserType:'.Role::SUPER_ADMIN])->group(function () {
//     // Routes for Super Admin users

// });
