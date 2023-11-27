<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class QrScannedList extends Model
{
    use HasFactory, SoftDeletes;



    /**
     * RELATIONSHIPs
     */
    public function user()
    {
        $this->belongsTo(User::class, "user_id", "id");
    }




    /**
     * ATTRIBUTEs
     */

}
