<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Platform extends Model
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
    
    public function games()
    {
        return $this->hasMany(Game::class, 'platform_id');
    }

    public function outlets()
    {
        return $this->hasMany(Outlet::class, 'platform_id');
    }

    public function draws()
    {
        return $this->hasMany(Draw::class, 'platform_id');
    }
}
