<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tac extends Model
{
    use HasFactory, HasUlids;
    protected $guarded = ['id'];
    const REFERENCE = [
        'Forgot_Password' => 'Forgot Password TAC',
        'Register_User' => 'Register User',
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    public function ownerable()
    {
        return $this->morphTo();
    }
}
