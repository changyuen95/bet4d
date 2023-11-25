<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPoint extends Model
{
    use HasFactory, HasUlids, SoftDeletes;
    protected $guarded = ['id'];
    protected $appends = ['amount'];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    public function getAmountAttribute(){
        return $this->point;
    }
}
