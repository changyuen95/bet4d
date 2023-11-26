<?php

namespace Database\Seeders;

use App\Models\Platform;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DrawCurrentYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $platform = Platform::where('name','STC')->first();
        $currentYear = Carbon::now()->year;
   
        // $dateAddHour = $todayDateTime;
        $draw = $platform->draws()->whereYear('expired_at', $currentYear)->orderBy('expired_at', 'DESC')->first();

        $drawNo = 1;
        if($draw){
            $drawNo = $draw->draw_no + 1;
        }

        $endDate = Carbon::createFromDate($currentYear, 12, 31);

        if($draw){
            $lastDrawDate = Carbon::parse($draw->expired_at);
            $lastDrawDate->addDay();
        }else{
            $lastDrawDate = Carbon::createFromDate($currentYear, 1, 1);
        }

        $lastTwoDigitsOfYear = $lastDrawDate->format('y');

        while ($lastDrawDate->lte($endDate)) {
            // Check if the current day is either Wednesday (3) or Saturday (6)
            if ($lastDrawDate->dayOfWeek == Carbon::WEDNESDAY || $lastDrawDate->dayOfWeek == Carbon::SATURDAY) {
                // Insert into the drawCalender model
                $expired_at = $lastDrawDate->copy();
                $expired_at->setTime(19, 0, 0);
                $draw = $platform->draws()->create([
                    'draw_no' => $drawNo,
                    'year'  => $lastTwoDigitsOfYear,
                    'expired_at' => $expired_at
                ]);
                $drawNo++;
            }
            // Move to the next day
            $lastDrawDate->addDay();
        }
    }
}
