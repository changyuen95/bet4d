<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankReceipt extends Model
{
    use HasFactory, HasUlids, SoftDeletes;
    protected $guarded = ['id'];
    protected $with = ['user'];
    const STATUS = [
        'RECEIPT_REQUESTED' => 'requested',
        'RECEIPT_SUCCESSFUL' => 'successful',
        'RECEIPT_REJECTED' => 'rejected',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creditTransaction()
    {
        return $this->morphOne(CreditTransaction::class, 'targetable');
    }

}
