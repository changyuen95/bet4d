<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreditTransactionResource extends BaseResource
{
    public static $wrap = null;
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
            'amount' => $this->amount,
            'type' => $this->type,
            'before_amount' => $this->before_amount,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'deleted_at' => $this->formatDate($this->deleted_at),
            'transaction_type' => $this->transaction_type,
            'description' => $this->description,
            'status' => $this->status,
            'user' => $this->user

        ];

        // if($relatedModel->creatable_type == 'App\Models\Admin'){
        //     $result['staff'] = ;
        // }
        // $relatedModel = $this->targetable;
        // if($this->targetable_type == 'App\Models\TopUp'){
        //     $result['top_up'] = $relatedModel;
        // }elseif($this->targetable_type == 'App\Models\Ticket'){
        //     $result['ticket'] = $relatedModel;
        // }else{
        //     $result['targetable'] = $relatedModel;
        //

        return $result;
    }
}
