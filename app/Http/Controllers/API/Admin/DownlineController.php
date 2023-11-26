<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Traits\CreditTransactionTrait;
use Carbon\Carbon;
use App\Models\Admin;
use App\Models\Role;
use App\Models\AdminCredit;
use App\Models\AdminCreditTransaction;
use App\Models\AdminClearCreditTransaction;
use File;
use Image;
use Auth;
use Validator;

class DownlineController extends Controller
{
    use CreditTransactionTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        if(!$user){
            return response(['message' => trans('messages.no_user_found')], 422);
        }

        if($user->hasRole(Role::SUPER_ADMIN)){
            // return all admin/operator
            $admin = Admin::with('admin_credit')->where('outlet_id', $user->outlet_id)->where('role', Role::OPERATOR)->paginate($request->get('limit') ?? 10);
            if($admin){
                return $admin;
            }
        }
        
        return response(['message' => trans('admin.staff_not_found')], 422);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // List Admin Credit Transactions
    // public function creditTransactions(Request $request, string $id)
    // {
    //     $transactions = AdminCreditTransaction::query();

    //     if ($request->filled('transaction_type')) {
    //         $transaction_type = $request->transaction_type;
    //     }

    //     if ($request->filled('duration')) {
    //         $duration = $request->duration;
    //     }

    //     $transactions = $this->CreditTransaction($id, $transaction_type ?? null, $duration ?? null, $request->get('limit'));

    //     $admin = AdminCredit::where('admin_id', $id)->value('amount');

    //     $result = [
    //         'admin' => $admin,
    //         'transactions' => $transactions,
    //     ];

    //     if ($result) {
    //         return $result;
    //     }

    //     return response(['message' => trans('admin.transaction_not_found')], 422);
    // }

    // Show Admin Credit Transactions Detail
    

    // List Clear Credit Transactions
    public function clearTransactions(Request $request, string $id)
    {
        $dateFrom = Carbon::parse($request->date_from);
        $dateTo = Carbon::parse($request->date_to);

        $transactions = AdminCreditTransaction::where('admin_id', $id)
                    ->where('type', AdminCreditTransaction::TYPE['Increase'])
                    ->where('transaction_type', AdminCreditTransaction::TRANSACTION_TYPE['TopUp'])
                    ->whereDate('created_at', '>=', $dateFrom)
                    ->whereDate('created_at', '<=', $dateTo)
                    ->where('is_verified', false)
                    ->orderBy('created_at', 'asc')
                    ->get()
                    ->groupBy(function($date) {
                        return Carbon::parse($date->created_at)->format('Y-m-d'); // grouping by date part only
                    })->map(function ($transactions) {
                        return $transactions->sum('amount');
                    });

        // Calculate the total sum for all dates
        $totalSum = $transactions->sum();

        // Add the total sum to the result
        $transactions['total_amount'] = $totalSum;

        if ($transactions) {
            return $transactions;
        }
        
        return response(['message' => trans('admin.no_transaction_records')], 422);
    }

    // not require
    // List Credit Distribute
    public function creditDistribute(Request $request, string $id)
    {
        $transactions = AdminCreditTransaction::where('admin_id', $id)->whereDate('created_at', $request->date)->paginate($request->get('limit') ?? 10);

        return $transactions;
    }

    // List Credit Distribute Detail
    public function creditDistributeDetail(Request $request, string $admin_id, string $id)
    {
        $transaction = AdminCreditTransaction::with('admin_credit.admin', 'outlet.platform', 'targetable')->where('admin_id', $admin_id)->where('id', $id)->first();
        return $transaction;
    }

    public function clearTransactionsProcess(Request $request, string $id)
    {
        // request validation
        $validator = Validator::make($request->all(), [
            "date_from" => "required|date|date_format:Y-m-d",
            "date_to" => "required|date|date_format:Y-m-d",
            "image_path" => "required|image|mimes:jpg,png,jpeg"
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $dateFrom = Carbon::parse($request->date_from);
        $dateTo = Carbon::parse($request->date_to);

        $transactions = AdminCreditTransaction::where('admin_id', $id)
                    ->where('transaction_type', AdminCreditTransaction::TRANSACTION_TYPE['TopUp'])
                    ->whereDate('created_at', '>=', $dateFrom)
                    ->whereDate('created_at', '<=', $dateTo)
                    ->where('is_verified', false)
                    ->orderBy('created_at', 'asc')
                    ->get();

        if($request->hasFile('image_path'))
        {
            $allowedfileExtension = ['jpg','png','jpeg'];
            $imagePathFile = $request->file('image_path');
            $imagePathFilename = $imagePathFile->getClientOriginalName();
            $imagePathExtension = $imagePathFile->extension();

            $verifyImage = in_array($imagePathExtension, $allowedfileExtension);
            
            if($verifyImage)
            {
                File::makeDirectory(storage_path('app/public/clear_credit/'.auth()->user()->id.'/attachment/'), $mode = 0777, true, true);

                $input['clear_credit'] = 'clear_credit'.time().'.'.$imagePathFile->getClientOriginalExtension();
                $destination_path = storage_path('app/public/clear_credit/'.auth()->user()->id.'/attachment/');
                $imagePath = Image::make($imagePathFile->path());

                $imagePath->save($destination_path.'/'.$input['clear_credit']);

                $image_full_path = 'clear_credit/'.auth()->user()->id.'/attachment/'.$input['clear_credit'];
            }
        }

        // $adminClearCredit = AdminClearCreditTransaction::create([
        //     'admin_id' => $id,
        //     'amount' => $transactions->sum('amount'),
        //     'reference_id' => Str::uuid(),
        //     'image_path' => $request->image_path,
        //     'date_clear_from' => $dateFrom,
        //     'date_clear_to' => $dateTo,
        // ]);


        // $transactions->update(['is_verified' => true]);

        // AdminCreditTransaction::create([
        //     'admin_id' => $id,
        //     'outlet_id' => '',
        //     'amount' => $transactions->sum('amount'),
        //     'before_amount' => '',
        //     'after_amount' => '',
        //     'type' => AdminCreditTransaction::TYPE['Decrease'],
        //     'transaction_type' => AdminCreditTransaction::TRANSACTION_TYPE['Cleared'],
        //     'reference_id' => Str::uuid(),
        //     'is_verified' => true,
        //     'targetable_type' => 'App\Models\AdminClearCreditTransaction',
        //     'targetable_type' => $adminClearCredit->id,
        //     'admin_clear_credit_transactions_id' => $adminClearCredit->id,
        // ]);
        

        return $transactions;
    }
}
