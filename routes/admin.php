<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\QrcodeController;
use App\Http\Controllers\Admin\ScoreboardController;
use App\Http\Controllers\Admin\TicketSalesReportController;
use App\Http\Controllers\Admin\TopupReportController;
use App\Http\Controllers\Admin\TicketPrintingController;
use App\Http\Controllers\Admin\WitnessController; 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\Auth\LoginController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
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

        Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

        Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [LoginController::class, 'login']);
        Route::post('cus/login', [LoginController::class, 'login'])->name('cus.login');

        Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/_debug/broadcast', function () {
    try {
              $payload = [
            'stc4d' => [
                'title' => 'WINNING RESULTS',
                'jackpot1' => 10000,
                'jackpot2' => 10000
            ]
        ];

        event(new \App\Events\ScoreboardUpdated($payload));
        return [
            'status' => 'ok',
            'message' => 'Broadcast sent',
        ];
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'failed',
            'error' => $e->getMessage(),
        ], 500);
    }
});



Route::get('/_debug/db', function () {
    try {
        $result = DB::connection('stcmaster')->select('SELECT 1 AS ok');

        return [
            'status' => 'connected',
            'result' => $result,
            'config' => [
                'host' => config('database.connections.stcmaster.host'),
                'port' => config('database.connections.stcmaster.port'),
                'database' => config('database.connections.stcmaster.database'),
                'username' => config('database.connections.stcmaster.username'),
            ],
        ];
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'failed',
            'error' => $e->getMessage(),
            'class' => get_class($e),
            'config' => [
                'host' => config('database.connections.stcmaster.host'),
                'port' => config('database.connections.stcmaster.port'),
                'database' => config('database.connections.stcmaster.database'),
                'username' => config('database.connections.stcmaster.username'),
            ],
        ], 500);
    }
});

        Route::resource("scoreboard", ScoreboardController::class);

        Route::middleware('auth:admin')->group(function () {
            Route::get('admin/dashboard', function () {
                return view('admin.dashboard'); // Ensure this view exists
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
        Route::post('sign-ticket', [TicketPrintingController::class, 'sign'])->name('ticket.sign');

        Route::get('ticket_printing/{id}/print', [TicketPrintingController::class, 'print'])->name('ticket_printing.print');

        Route::resource("ticket_printing", TicketPrintingController::class);
        Route::group(['prefix' => 'reports', 'as' => 'reports.'], function() {
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
        //             'special' => ['0590','6087','2711','7952','7428','2318','3512','5466','9736','7233','5675','7718','5675'],
        //             'consolation' => ['3881','5307','1528','7515','5826','9184','3284','8544','2167','7520'],
        //             'jackpot1' => 1000000,
        //             'jackpot2' => 500000
        //         ]
        //     ];

Route::get('test-ticket-print', function () {
    return view('test-print');
});

            Route::resource("tickets", TicketSalesReportController::class);
            Route::resource("topups", TopupReportController::class);
        //     return response()->json(['status' => 'broadcasted', 'payload' => $payload]);
        // });
        
        Route::resource("scoreboard", ScoreboardController::class);

            Route::get('ticket/export-csv', [TicketSalesReportController::class, 'exportCsv'])->name('ticket.exportCsv');
            Route::get('ticket/export-pdf', [TicketSalesReportController::class, 'exportPdf'])->name('ticket.exportPdf');

            Route::get('topup/export-csv', [TopUpReportController::class, 'exportCsv'])->name('topup.exportCsv');
            Route::get('topup/export-pdf', [TopUpReportController::class, 'exportPdf'])->name('topup.exportPdf');

            Route::get('tickets/outlet/{outlet}', [TicketSalesReportController::class, 'ticketDetails'])->name('outlet.show');
            // Route::get('topups/outlet/{outlet}', [TopupReportController::class, 'show'])->name('outlet.show');

            Route::get('tickets-filter', [TicketSalesReportController::class, 'index'])->name('ticket_sales');
            Route::get('topups-filter', [TopupReportController::class, 'index'])->name('topup_sales');


            Route::get('tickets-export-csv', [TicketSalesReportController::class, 'exportCsv'])->name('ticket_sales.export_csv');
            Route::get('tickets-export-pdf', [TicketSalesReportController::class, 'exportPdf'])->name('ticket_sales.export_pdf');
            Route::get('tickets-details', [TicketSalesReportController::class, 'ticketDetails'])->name('ticket_sales.details');
            Route::get('tickets-details', [TicketSalesReportController::class, 'ticketDetails'])->name('ticket.details');
            
            

        });



    });


