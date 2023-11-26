<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class BaseResource extends JsonResource
{
    /**
     * Format a date attribute.
     *
     * @param \Carbon\Carbon $date
     * @return string
     */
    protected function formatDate($date)
    {
        if($date != ''){
            return Carbon::createFromFormat('Y-m-d H:i:s',$date)->format('Y-m-d H:i:s');
        }else{
            return $date;
        }
    }
}
