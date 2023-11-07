<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTransferDetails extends Model
{
    use HasFactory, HasUlids, SoftDeletes;
    protected $guarded = ['id'];

    const PRIMARY = [
        'Yes' => 1,
        'No' => 0
    ];

    public function transferOption()
    {
        return $this->belongsTo(TransferOption::class, 'transfer_option_id');
    }
}
