<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WinningHistoryResource extends BaseResource
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
            'draw_result_id' => $this->draw_result_id,
            'draw_result' => $this->drawResult,
            'ticket_number_id' => $this->ticket_number_id,
            'ticket_numer' => $this->ticketNumber,
            'user_id' => $this->user_id,
            'action_by' => $this->action_by,
            'outlet_id' => $this->outlet_id,
            'amount' => $this->amount,
            'is_distribute' => $this->is_distribute,
            'distribute_attachment' => $this->distribute_attachment,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'deleted_at' => $this->formatDate($this->deleted_at),
            'distribute_attachment_full_path' => $this->distribute_attachment_full_path,
        ];
    }
}
