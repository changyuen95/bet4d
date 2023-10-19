<?php

namespace App\Http\Resources;

use App\Models\PotentialWinningPriceList;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketNumberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $potentialWinningData = PotentialWinningPriceList::where('type_id',$this->type)->first();
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
        
        return [
            'id' => $this->id,
            'ticket_id' => $this->ticket_id,
            'number' => $this->number,
            'big_amount' => $this->big_amount,
            'small_amount' => $this->small_amount,
            'type' => $this->type,
            'potential_winning' => $potentialWinning,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
