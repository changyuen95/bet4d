<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Qrcode extends Model
{
    use HasFactory, HasUlids, SoftDeletes;
    protected $guarded = ['id'];


    /******* Attribute *******/
    public function getStringStatusAttribute()
    {
        $status = $this->status;

        $stringStatus = ($status == 0) ? "Inactive" : 'Active';

        return $stringStatus;
    }

}
