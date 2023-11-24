<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminCreditTransaction extends Model
{
    use HasFactory, HasUlids;

    const TYPE = [
        'Increase' => 'increase',
        'Decrease' => 'decrease'
    ];

    const TRANSACTION_TYPE = [
        'TopUp' => 'topup',
        'Cleared' => 'cleared'
    ];
    
    public function admin_credit()
    {
        return $this->belongsTo(AdminCredit::class, 'admin_id', 'admin_id');
    }

    public function outlet()
    {
        return $this->belongsTo(outlet::class, 'outlet_id');
    }

    public function targetable()
    {
        return $this->morphTo();
    }

}
