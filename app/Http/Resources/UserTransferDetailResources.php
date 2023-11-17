<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserTransferDetailResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $transferOption = $this->transferOption;
        
        return [
            'id' => $this->id,
            'transfer_option_id' => $this->transfer_option_id,
            'transfer_option' => $transferOption,
            'primary' => (boolean) $this->primary,
            'bank_no' => $this->bank_no,
            'bank_account_holder_name' => $this->bank_account_holder_name,
            'phone_e164' => $this->phone_e164,
            'phone_owner_name' => $this->phone_owner_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->updated_at,
        ];
    }
}
