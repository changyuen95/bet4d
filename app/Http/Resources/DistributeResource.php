<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DistributeResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $winner = $this->winner;
        $winner->transfer_details = $this->winner->transferDetails()->orderBy('primary', 'asc')->get();

        return [
            'id' => $this->id,
            'draw_result_id' => $this->draw_result_id,
            'ticket_number_id' => $this->ticket_number_id,
            'user_id' => $this->user_id,
            'action_by' => $this->action_by,
            'amount' => $this->amount,
            'is_distribute' => $this->is_distribute,
            'distribute_attachment' => $this->distribute_attachment,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'deleted_at' => $this->formatDate($this->deleted_at),
            'draw_result' => $this->drawResult,
            'ticket_number' => $this->ticketNumber,
            'winner' => $winner,
        ];
    }
}
