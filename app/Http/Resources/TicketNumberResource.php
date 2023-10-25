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
        return [
            'id' => $this->id,
            'ticket_id' => $this->ticket_id,
            'number' => $this->number,
            'big_amount' => $this->big_amount,
            'small_amount' => $this->small_amount,
            'type' => $this->type,
            'potential_winning' => $this->potentialWinning,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
