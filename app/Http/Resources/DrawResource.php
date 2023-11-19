<?php

namespace App\Http\Resources;

use App\Models\DrawResult;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DrawResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $drawResults = $this->results;
        $results = array();

        foreach($drawResults as $drawResult){
            if($drawResult->number != '' && $drawResult->number != '-'){
                if($drawResult->type != DrawResult::TYPE['1st'] && $drawResult->type != DrawResult::TYPE['2nd'] && $drawResult->type != DrawResult::TYPE['3rd']){
                    if(!isset($results[$drawResult->type])){
                        $results[$drawResult->type] = [];
                    }
                    $results[$drawResult->type][$drawResult->position] = $drawResult->number; 
                    ksort($results[$drawResult->type]);
                }else{
                    $results[$drawResult->type] = $drawResult->number; 
                }
            }
        }
        return [
            'id' => $this->id,
            'reference_id' => $this->reference_id,
            'platform_id' => $this->platform_id,
            'draw_no' => $this->draw_no,
            'year' => $this->year,
            'full_draw_no' => $this->full_draw_no,
            'expired_at' => $this->expired_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'results' => $results
        ];
    }
}
