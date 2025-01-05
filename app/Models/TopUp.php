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
        'Bonus' => 'bonus',
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
        return $this->hasOne(BankReceipt::class, 'bank_receipt_id');
    }

    public function getTopUpWithAttribute($value)
    {
        return array_search($this->top_up_with, self::TOP_UP_WITH) ?? $this->top_up_with;
    }

    public function bonus()
    {
        return $this->belongsTo(Bonus::class, 'bonus_id');
    }

    public function scopeFilterByType($query, $type)
    {
        return $query->where('top_up_with', $type);
    }

    public function scopeFilterByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeFilterByDate($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function scopeFilterByOutlet($query, $outletId)
    {
        return $query->where('creatable_type', 'App\\Models\\Outlet')
                     ->where('creatable_id', $outletId);
    }


}
