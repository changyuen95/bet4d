<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OutletOperatingTime extends Model
{
    use HasFactory, HasUlids, SoftDeletes;
    protected $guarded = ['id'];
    protected $appends = ['from_time_twelve_hr_format','to_time_twelve_hr_format'];


    const DAYS = [
        'Monday' => 'monday',
        'Tuesday' => 'tuesday',
        'Wednesday' => 'wednesday',
        'Thursday' => 'thursday',
        'Friday' => 'friday',
        'Saturday' => 'saturday',
        'Sunday' => 'sunday',
    ];

    public function getFromTimeTwelveHrFormatAttribute()
    {
        $carbonTime = Carbon::parse($this->from_time);

        // Format the Carbon instance as a 12-hour time with am/pm
        $formattedTime = $carbonTime->format('g:iA');
        return $formattedTime;
    }

    public function getToTimeTwelveHrFormatAttribute()
    {
        $carbonTime = Carbon::parse($this->to_time);

        // Format the Carbon instance as a 12-hour time with am/pm
        $formattedTime = $carbonTime->format('g:iA');
        return $formattedTime;
    }
}
