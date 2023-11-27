<?php

namespace Database\Seeders;

use App\Models\DrawCalendar;
use App\Models\Platform;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DrawCalendarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startDate = Carbon::now();
        $endDate = Carbon::createFromDate(2030, 12, 31);

        $currentDate = $startDate->copy();
        $platform = Platform::where('name', 'STC')->first();
        if($platform){
            while ($currentDate->lte($endDate)) {
                // Check if the current day is either Wednesday (3) or Saturday (6)
                if ($currentDate->dayOfWeek == Carbon::WEDNESDAY || $currentDate->dayOfWeek == Carbon::SATURDAY) {
                    // Insert into the drawCalender model
                    DrawCalendar::updateOrCreate([
                        'date' => $currentDate->toDateString(),
                        'platform_id' => $platform->id,
                    ],[
                        'type' => DrawCalendar::TYPE['Normal'],
                        'color' => '#228734'
                    ]);
                }

                // Move to the next day
                $currentDate->addDay();
            }
        }
    }
}
