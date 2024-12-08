<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketNumber extends Model
{
    use HasFactory, HasUlids;
    protected $guarded = ['id'];
    protected $appends = ['potential_winning'];
    protected $with = ['tax'];
    const TYPE = [
        'Straight' => 'straight',
        'Box' => 'box',
        'e-box' => 'e-box'
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function win()
    {
        return $this->hasOne(WinnerList::class, 'ticket_number_id');
    }

    public function tax()
    {
        return $this->hasOne(Tax::class, 'id', 'tax_id');
    }

    public function getPotentialWinningAttribute()
    {
        $potentialWinningData = PotentialWinningPriceList::where('type',$this->type)->first();
        $potentialWinning = [
            'big_1st' => 0,
            'big_2nd' => 0,
            'big_3rd' => 0,
            'big_special' => 0,
            'big_consolation' => 0,
            'small_1st' => 0,
            'small_2nd' => 0,
            'small_3rd' => 0,
        ];

        if($potentialWinningData){
            $potentialWinning = [
                'big_1st' => $this->big_amount * $potentialWinningData->big1st,
                'big_2nd' => $this->big_amount * $potentialWinningData->big2nd,
                'big_3rd' => $this->big_amount * $potentialWinningData->big3rd,
                'big_special' => $this->big_amount * $potentialWinningData->big_special,
                'big_consolation' => $this->big_amount * $potentialWinningData->big_consolation,
                'small_1st' => $this->small_amount * $potentialWinningData->small1st,
                'small_2nd' => $this->small_amount * $potentialWinningData->small2nd,
                'small_3rd' => $this->small_amount * $potentialWinningData->small3rd,
            ];
        }
        return $potentialWinning;
    }
}
