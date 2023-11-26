<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PointTransaction extends Model
{
    use HasFactory, HasUlids, SoftDeletes;
    protected $guarded = ['id'];
    protected $appends = ['amount','before_amount','transaction_type','description'];

    const TYPE = [
        'Increase' => 'increase',
        'Decrease' => 'decrease'
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    public function targetable()
    {
        return $this->morphTo();
    }

    public function getAmountAttribute()
    {
        return $this->point;
    }

    public function getBeforeAmountAttribute()
    {
        return $this->before_point;
    }

    public function getTransactionTypeAttribute()
    {
        return str_replace('App\\Models\\',"",$this->targetable_type);
    }

    public function getDescriptionAttribute()
    {
        $relatedModel = $this->targetable;
        $description = '';
        if($this->targetable_type == 'App\Models\TopUp'){
            $description = trans('messages.top_up_completed');
        }

        return $description;
    }
}
