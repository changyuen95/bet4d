<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PointTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $relatedModel = $this->targetable;
        if($relatedModel->creatable_type == 'App\Models\Admin'){
            $relatedModel->creatable->outlet->platform;  
        }
        $result = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'targetable_type' => $this->targetable_type,
            'targetable_id' => $this->targetable_id,
            'targetable' => $relatedModel,
            'point' => $this->point,
            'amount' => $this->amount,
            'type' => $this->type,
            'before_point' => $this->before_point,
            'before_amount' => $this->before_amount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,

        ];

        return $result;

    }
}
