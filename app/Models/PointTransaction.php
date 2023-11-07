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
    protected $appends = ['amount','before_amount'];

    const TYPE = [
        'Increase' => 'increase',
        'Decrease' => 'decrease'
    ];

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
}