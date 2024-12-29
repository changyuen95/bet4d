<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends BaseResource
{

    public static $wrap = null;
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
            'user' => $this->user,
            'outlet_id' => $this->outlet_id,
            'outlet' => $this->outlet,
            'platform_id' => $this->platform_id,
            'game_id' => $this->game_id,
            'draw_id' => $this->draw_id,
            'sub_total' => $this->sub_total,
            'total_amount' => $this->total_amount,
            'tax_amount' => $this->total_tax,
            'actual_total_amount' => $this->actual_total_amount,
            'refund_amount' => $this->total_refund,
            'draw' => $this->draws,
            'platform' => $this->platform,
            'game' => $this->game,
            'status' => ucwords(strtolower($this->status)),
            'is_claimable' => $this->isClaimable,
            'keep_ticket' => $this->keep_ticket ?true: false,
            'claim_status' => $this->claim_status,
            'ticketNumbers' => TicketNumberResource::collection($ticketNumber),
            'prize' => $this->winner,
            'action_by' => $this->action_by,
            'staff' => $this->staff,
            'reject_reason' => $this->reject_reason,
            'completed_at' => $this->formatDate($this->completed_at),
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'deleted_at' => $this->formatDate($this->updated_at),
            'barcodes' => $this->barcode,
            'receipts' => $this->receipts,
        ];
    }
}
