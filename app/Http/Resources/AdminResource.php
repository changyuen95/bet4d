<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends BaseResource
{
    public static $wrap = null;

    /**
     *
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $adminCredit = $this->admin_credit;

        $result = [
            'id' => $this->id,
            'reference_id' => $this->reference_id,
            'name' => $this->name,
            'username' => $this->username,
            'role' => $this->role,
            'phone_e164' => $this->phone_e164,
            'profile_image' => $this->profile_image,
            'email' => $this->email,
            'outlet_id' => $this->outlet_id,
            'email_verified_at' => $this->email_verified_at,
            'remember_token' => $this->remember_token,
            'status' => $this->status,
            'admin_credit' => $adminCredit,
            'outlet' => $this->outlet,
            'games' => optional(optional($this->outlet)->platform)->games,
            'roles' => $this->roles,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'deleted_at' => $this->formatDate($this->deleted_at),
        ];

        if(isset($this->access_token)){
            $result['access_token'] = $this->access_token;
        }

        return $result;
    }
}
