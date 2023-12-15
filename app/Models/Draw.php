<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Draw extends Model
{
    use HasFactory, HasUlids, SoftDeletes;
    protected $guarded = ['id'];
    protected $appends = ['full_draw_no', 'open_result_date'];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->reference_id = uniqid();
        });
    }

    public static function getDrawData($platformId){
        $platform = Platform::find($platformId);
        // $todayDateString = "04/11/2023 19:00:00";
        $todayDateString = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->toDateTimeString())->format('d/m/Y H:i:s');
        $todayDateTime = Carbon::createFromFormat('d/m/Y H:i:s', $todayDateString);
        // $dateAddHour = $todayDateTime;
        $draw = $platform->draws()
                        ->where('expired_at', '>', $todayDateTime)
                        ->orderBy('expired_at', 'ASC')
                        ->first();

        if($draw){
            return $draw;
        }else{
            $lastDrawRecord = $platform->draws()->orderBy('created_at', 'desc')->first();
            $drawNo = 1;

            if($lastDrawRecord){
                $drawNo = $lastDrawRecord->draw_no + 1;
            }

            $currentDate = Carbon::createFromFormat('d/m/Y H:i:s', $todayDateString);
            $lastTwoDigitsOfYear = $currentDate->format('y');
            $currentDate1 = Carbon::createFromFormat('d/m/Y H:i:s', $todayDateString);
            $currentDate2 = Carbon::createFromFormat('d/m/Y H:i:s', $todayDateString);

            $nextSaturday = $currentDate1->next(Carbon::SATURDAY);
            $nextWednesday = $currentDate2->next(Carbon::WEDNESDAY);
            $todayDate = Carbon::now();
            $nextWednesday->setTime(19, 0, 0);
            $nextSaturday->setTime(19, 0, 0);
            if ($nextWednesday->lt($nextSaturday)) {
                $draw = $platform->draws()->create([
                    'draw_no' => $drawNo,
                    'year'  => $lastTwoDigitsOfYear,
                    'expired_at' => $nextWednesday
                ]);
            } else {
                $draw = $platform->draws()->create([
                    'draw_no' => $drawNo,
                    'year'  => $lastTwoDigitsOfYear,
                    'expired_at' => $nextSaturday
                ]);
            }

            return $draw;

        }


    }

    public function getFullDrawNoAttribute()
    {
        return $this->draw_no.'/'.$this->year;
    }

    public function getOpenResultDateAttribute()
    {
        if($this->expired_at != ''){
            $resultDate = Carbon::createFromFormat('Y-m-d H:i:s',$this->expired_at)->addHour()->format('Y-m-d H:i:s');
            return $resultDate;
        }else{
            return $this->expired_at;
        }
    }

    public function results()
    {
        return $this->hasMany(DrawResult::class, 'draw_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'draw_id');
    }

    public function winnerListDisplay()
    {
        return $this->hasMany(WinnerListDisplay::class, 'draw_id');
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class, 'platform_id');
    }

    public static function getCurrentDraw(){
        // $todayDateString = "25/11/2023 19:00:00";
        $todayDateString = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->toDateTimeString())->format('d/m/Y H:i:s');
        $currentDateTime = Carbon::createFromFormat('d/m/Y H:i:s', $todayDateString);
        $draw = Draw::where('expired_at', '>', $currentDateTime)
                    ->orderBy('expired_at', 'ASC') // Order by expired_at in descending order
                    ->first();
        return $draw;
    }

    public static function checkIsExpired($draw){
        if (Carbon::parse($draw->expired_at)->isPast()) {
            return true;
        }
        return false;
    }
}
