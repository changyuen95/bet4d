<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DrawResult extends Model
{
    use HasFactory, HasUlids, SoftDeletes;
    protected $guarded = ['id'];

    const TYPE = [
        '1st' => '1st',
        '2nd' => '2nd',
        '3rd' => '3rd',
        'special' => 'special',
        'consolation' => 'consolation',
    ];

    public function winners()
    {
        return $this->hasMany(WinnerList::class, 'draw_result_id');
    }
}
