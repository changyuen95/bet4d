<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketNumber extends Model
{
    use HasFactory, HasUlids;
    protected $guarded = ['id'];

    const TYPE = [
        'Straight' => 1,
        'Permutation' => 2
    ];
}
