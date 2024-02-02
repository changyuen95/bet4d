<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class VerifyPrizeResource extends JsonResource
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
  
        return [
            'id' => $this->id,
            'draw_result_id' => $this->draw_result_id,
            'ticket_number_id' => $this->ticket_number_id,
            'outlet' => $this->outlet,
            'staff' => $this->staff,
            'amount' => $this->amount,
            'is_distribute' => $this->is_distribute,
            'is_verified' => $this->is_verified,
            'verified_at' => Carbon::parse($this->verified_at)->format('Y-m-d H:i:s'),
            'distribute_attachment' => $this->distribute_attachment,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),
            'deleted_at' => Carbon::parse($this->deleted_at)->format('Y-m-d H:i:s'),
            'draw_result' => $this->drawResult,
            'ticket_number' => $this->ticketNumber,
            'winner' => $winner,
        ];
    }
}
