<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userCredit = $this->credit;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'phone_e164' => $this->phone_e164,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'remember_token' => $this->remember_token,
            'status' => $this->status,
            'user_credit' => $userCredit,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->updated_at,
        ];
    }
}
