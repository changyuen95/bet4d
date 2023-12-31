<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use HasFactory, SoftDeletes, HasUlids;
    protected $guarded = ['id'];

    const STATUS = [
        'Active' => 'active',
        'Inactive' => 'inactive'
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
}
