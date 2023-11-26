<?php

namespace App\Traits;
use App\Models\AdminCreditTransaction;
use Carbon\Carbon;

trait CreditTransactionTrait
{
    public function creditTransaction($admin_id, $transaction_type = null, $duration = null, $limit)
    {
        $transactions = AdminCreditTransaction::query();

        if ($transaction_type) {
            $transactions = $transactions->where('transaction_type', $transaction_type);
        }

        if ($duration) {
            $dateFrom = Carbon::now()->subDays($duration)->format("Y-m-d 00:00:00");
            $transactions = $transactions->where('created_at', '>=', $dateFrom);
        }

        $transactions = $transactions->where('admin_id', $admin_id)->paginate($limit ?? 10);

        return $transactions;
    }
}