<?php

namespace App\Http\Controllers\API;

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
use Auth;
use Validator;

class AdminCreditTransactionController extends Controller
{
    use CreditTransactionTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $staff = Auth::user();
        $transactions = AdminCreditTransaction::query();

        if ($request->filled('transaction_type')) {
            $transaction_type = $request->transaction_type;
        }

        if ($request->filled('duration')) {
            $duration = $request->duration;
        }

        $transactions = $this->creditTransaction($staff->id, $transaction_type ?? null, $duration ?? null, $request->get('limit'));
        $distributed_today = $this->getTodayDistributed($staff->id);


        $result = [
            'distributed_today' => $distributed_today,
            'transactions' => $transactions,
        ];

        if ($result) {
            return $result;
        }

        return response(['message' => trans('admin.transaction_not_found')], 422);
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
    public function show(Request $request , string $id)
    {
        $staff = Auth::user();
        $transaction = AdminCreditTransaction::with('admin', 'outlet.platform', 'targetable')->where('admin_id', $staff->id)->where('id', $id)->first();

        if ($transaction) {
            return $transaction;
        }

        return response(['message' => trans('admin.transaction_detail_not_found')], 422);
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
}
