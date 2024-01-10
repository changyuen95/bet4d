<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminCreditTransaction extends Model
{
    use HasFactory, HasUlids;
    protected $guarded = ['id'];

    const TYPE = [
        'Increase' => 'increase',
        'Decrease' => 'decrease'
    ];

    const TRANSACTION_TYPE = [
        'TopUp' => 'topup',
        'Cleared' => 'cleared'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    public function admin_credit()
    {
        return $this->belongsTo(AdminCredit::class, 'admin_id', 'admin_id');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id','id');
    }

    public function targetable()
    {
        return $this->morphTo();
    }

    public function clear_credit_transaction()
    {
        return $this->belongsTo(AdminClearCreditTransaction::class, 'admin_clear_credit_transactions_id', 'id');
    }

}
