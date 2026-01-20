<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\QrcodeController;
use App\Http\Controllers\Admin\WitnessController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\DB;

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

    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/login', function () {

        return redirect()->route('admin.login');
    });

    Route::namespace('Auth')->group(function () {

        Route::get('login', 'LoginController@showLoginForm')->name('.login');
        Route::post('login', 'LoginController@login');
        Route::post('logout', 'LoginController@logout')->name('.logout');
        // Route::get('password/request', 'ForgotPasswordController@showLinkRequestForm')->name('.password.request');
        // Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('.password.email');
        // Route::post('reset-password', 'ForgotPasswordController@reset_password')->name('.password.reset');
        // Route::get('forgot-password/{email}/{token}', 'ForgotPasswordController@forgot_password')->name('.forgot-password');


    });


    // Route::middleware('auth')->group(function () {
        Route::get('/dashboard', function () {

            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


        Route::group(['prefix' => 'admins', 'as' => 'admins'], function() {
            Route::post('resend-email/{id}', [AdminController::class, 'resendEmail'])->name('.resend_email');
        });
        Route::resource("admins", AdminController::class);


        Route::group(['prefix' => 'qrcodes', 'as' => 'qrcodes'], function() {
            Route::get('index-scanned-list', [QrcodeController::class, 'indexScannedList'])->name('.scanned_list');
            Route::get('scanned-list-datatable', [QrcodeController::class, 'scannedListDatatable'])->name('.scanned_list_datatable');
            Route::post('/print-qrcode/{id}', [QrcodeController::class, 'qrCodePrint'])->name('.qr_print');
        });
        Route::resource("qrcodes", QrcodeController::class);

        Route::group(['prefix' => 'witnesses', 'as' => 'witnesses'], function() {
            Route::get('select-for-draw', [WitnessController::class, 'selectForDraw'])->name('.select-for-draw');
            Route::post('save-selected', [WitnessController::class, 'saveSelectedWitnesses'])->name('.save-selected');
            Route::get('print', [WitnessController::class, 'printWitnessForm'])->name('.print');
        });
        Route::resource("witnesses", WitnessController::class);

        Route::get('/scoreboard', function () {
            return view('scoreboard', ['result' => []]);
        })->name('scoreboard');

        // Route::get('scoreboard/trigger', function () {
        //     $payload = [
        //         'stc4d' => [
        //             'title' => 'WINNING RESULTS',
        //             'draw_no' => '001/26',
        //             'date' => '03/01/2026 (SAT)',
        //             'first' => '9058',
        //             'second' => '5706',
        //             'third' => '0124',
        //             'special' => ['0590','6087','2711','7952','7428','2318','3512','5466','9736','7233'],
        //             'consolation' => ['3881','5307','1528','7515','5826','9184','3284','8544','2167','7520'],
        //             'jackpot1' => '1,000,000',
        //             'jackpot2' => '500,000'
        //         ]
        //     ];

        //     event(new \App\Events\ScoreboardUpdated($payload));

        //     return response()->json(['status' => 'broadcasted', 'payload' => $payload]);
        // });

        // Route::get('/db-remote-test', function () {
        //    $data = DB::connection('stcmaster')
        //         ->table('tmpresultmaster')
        //         ->where('DrwKey', '001/17')
        //         ->get();
        //         dd($data);
        // });

        require __DIR__.'/auth.php';


    // });


