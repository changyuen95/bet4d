<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WinnerListDisplayResource extends JsonResource
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
            'reference_id' => $this->reference_id,
            'platform_id' => $this->platform_id,
            'draw_no' => $this->draw_no,
            'year' => $this->year,
            'full_draw_no' => $this->full_draw_no,
            'is_open_result' => $this->is_open_result,
            'expired_at' => $this->expired_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'winner_list_display' => $this->winnerListDisplay, 
        ];
    }
}
