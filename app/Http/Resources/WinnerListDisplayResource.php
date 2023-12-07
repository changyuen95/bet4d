<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WinnerListDisplayResource extends BaseResource
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
            'platform' => $this->platform,
            'draw_no' => $this->draw_no,
            'year' => $this->year,
            'full_draw_no' => $this->full_draw_no,
            'is_open_result' => $this->is_open_result,
            'expired_at' => $this->formatDate($this->expired_at),
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'deleted_at' => $this->formatDate($this->deleted_at),
            'winner_list_display' => $this->winnerListDisplay, 
        ];
    }
}
