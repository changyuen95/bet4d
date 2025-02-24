<?php

namespace App\Http\Resources;

use App\Models\PotentialWinningPriceList;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketNumberResource extends BaseResource
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
            'permutation_image' => $this->permutation_image,
            'permutation_number' => $this->permutation_number,
            'permutation_type' => $this->permutation_type,
            'potential_winning' => $this->potentialWinning,
            'actual_small_amount' => $this->actual_small_amount,
            'actual_big_amount' => $this->actual_big_amount,
            'refund_amount' => $this->refund_amount,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'tax' => $this->tax,
            'is_main' => (int)$this->is_main,
            'refund_tickets' => $this->refund_tickets,
            'sub_tickets' => $this->sub_tickets,
            'prizes' => $this->win,
            // 'maid_ticket_id' =>$this->main_ticket_id,

        ];
    }
}
