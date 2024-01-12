<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminCreditTransactionResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $relatedModel = $this->targetable;

        $result = [
            'id' => $this->id,
            'admin_id' => $this->admin_id,
            'outlet_id' => $this->outlet_id,
            'amount' => $this->amount,
            'before_amount' => $this->before_amount,
            'after_amount' => $this->after_amount,
            'type' => $this->type,
            'transaction_type' => $this->transaction_type,
            'reference_id' => $this->reference_id,
            'is_verified' => $this->is_verified,
            'targetable_type' => $this->targetable_type,
            'targetable_id' => $this->targetable_id,
            'targetable' => $relatedModel,
            'admin_clear_credit_transactions_id' => $this->admin_clear_credit_transactions_id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'description' => $this->description,
            'status' => $this->status,
            'admin' => $this->admin
        ];

        return $result;
    }
}
