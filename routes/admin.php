<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DrawController;
use App\Http\Controllers\Admin\QrcodeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

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
        Route::get('password/request', 'ForgotPasswordController@showLinkRequestForm')->name('.password.request');
        Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('.password.email');
        Route::post('reset-password', 'ForgotPasswordController@reset_password')->name('.password.reset');
        Route::get('forgot-password/{email}/{token}', 'ForgotPasswordController@forgot_password')->name('.forgot-password');


    });


    // Route::middleware('auth')->group(function () {
        Route::get('/dashboard', function () {

            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::post('/profile-image-update', [AdminController::class, 'updateProfileImg'])->name('admin.update_profile_img');


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


        Route::group(['prefix' => 'draws', 'as' => 'draws'], function() {
            Route::get('draw-calendar-list', [DrawController::class, 'getCalendarList'])->name('.calendar_list');
            Route::get('special-draw-tickets/{date}', [DrawController::class, 'showDrawTicketList'])->name('.special_draw_ticket_list');
            Route::get('ticket-list-datatable', [DrawController::class, 'ticketListDatatable'])->name('.ticket_list_datatable');
        });
        Route::resource("draws", DrawController::class);

        require __DIR__.'/auth.php';


    // });


