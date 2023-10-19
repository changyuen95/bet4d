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
        'Active' => 1,
        'Inactive' => 0
    ];

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
