<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DistributeResource extends BaseResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $winner = $this->winner;
        $winner->transfer_details = $this->winner->transferDetails()->orderBy('primary', 'asc')->get();
        $is_claimable = false;

        if($this->is_distribute == 0 && $this->is_request == 1){
            $is_claimable = true;
        }


        return [
            'id' => $this->id,
            'draw_result_id' => $this->draw_result_id,
            'ticket_number_id' => $this->ticket_number_id,
            'outlet' => $this->outlet,
            'action_by' => $this->action_by,
            'staff' => $this->staff,
            'amount' => $this->amount,
            'is_distribute' => $this->is_distribute,
            'distribute_attachment' => $this->distribute_attachment,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'deleted_at' => $this->formatDate($this->deleted_at),
            'draw_result' => $this->drawResult,
            'ticket_number' => $this->ticketNumber,
            'winner' => $winner,
            'is_claimable' => $is_claimable,
            'keep_ticket' => $this->keep_ticket ? true : false,
        ];
    }
}
