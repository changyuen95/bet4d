<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TopUp extends Model
{
    use HasFactory, HasUlids, SoftDeletes;
    protected $guarded = ['id'];
    protected $with = ['bankReceipt'];

    const TOP_UP_WITH = [
        'Outlet' => 'outlet',
        'QR'    => 'qr',
        'Bank'  => 'bank',
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->reference_id = uniqid();
        });
    }

    public function creditTransaction()
    {
        return $this->morphOne(CreditTransaction::class, 'targetable');
    }

    public function adminTransaction()
    {
        return $this->morphOne(AdminCreditTransaction::class, 'targetable');
    }

    public function pointTransaction()
    {
        return $this->morphOne(PointTransaction::class, 'targetable');
    }

    public function creatable()
    {
        return $this->morphTo();
    }

    public function bankReceipt()
    {
        return $this->hasOne(BankReceipt::class, 'top_up_id');
    }
}
