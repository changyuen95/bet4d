<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $ticketNumber = $this->ticketNumbers;
        
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'outlet_id' => $this->outlet_id,
            'platform_id' => $this->platform_id,
            'game_id' => $this->game_id,
            'draw_id' => $this->draw_id,
            'draw' => $this->draws,
            'status' => $this->status,
            'ticketNumbers' => TicketNumberResource::collection($ticketNumber),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->updated_at,
        ];
    }
}
