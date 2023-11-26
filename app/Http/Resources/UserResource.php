<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends BaseResource
{
    public static $wrap = null;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userCredit = $this->credit;
        $userPoint = $this->point;

        $result = [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'phone_e164' => $this->phone_e164,
            'avatar' => $this->avatar,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'remember_token' => $this->remember_token,
            'status' => $this->status,
            'is_verified' => $this->is_verified ? true : false,
            'is_bank_transferrable' => $this->is_bank_transferrable,
            'is_finish_first_time_topup' => $this->is_finish_first_time_topup,
            'winning_amount' => $this->winning_amount,
            'user_credit' => $userCredit,
            'user_point' => $userPoint,
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
