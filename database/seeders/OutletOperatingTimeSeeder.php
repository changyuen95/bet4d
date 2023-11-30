<?php

namespace Database\Seeders;

use App\Models\Outlet;
use App\Models\OutletOperatingTime;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OutletOperatingTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $operatingTimes = [
            [
                'day' => OutletOperatingTime::DAYS['Monday'],
                'from' => Carbon::createFromTime(10, 0, 0),
                'to' => Carbon::createFromTime(18, 0, 0),
            ],
            [
                'day' => OutletOperatingTime::DAYS['Tuesday'],
                'from' => Carbon::createFromTime(10, 0, 0),
                'to' => Carbon::createFromTime(18, 0, 0),
            ],
            [
                'day' => OutletOperatingTime::DAYS['Wednesday'],
                'from' => Carbon::createFromTime(10, 0, 0),
                'to' => Carbon::createFromTime(19, 0, 0),
            ],
            [
                'day' => OutletOperatingTime::DAYS['Thursday'],
                'from' => Carbon::createFromTime(10, 0, 0),
                'to' => Carbon::createFromTime(18, 0, 0),
            ],
            [
                'day' => OutletOperatingTime::DAYS['Friday'],
                'from' => Carbon::createFromTime(10, 0, 0),
                'to' => Carbon::createFromTime(18, 0, 0),
            ],
            [
                'day' => OutletOperatingTime::DAYS['Saturday'],
                'from' => Carbon::createFromTime(10, 0, 0),
                'to' => Carbon::createFromTime(19, 0, 0),
            ],
            [
                'day' => OutletOperatingTime::DAYS['Sunday'],
                'from' => Carbon::createFromTime(10, 0, 0),
                'to' => Carbon::createFromTime(19, 0, 0),
            ],
        ];

        $outlets = Outlet::all();
        foreach($outlets as $outlet){
            foreach($operatingTimes as $operatingTime){
                $outlet->operatingTime()->updateOrCreate([
                    'day' => $operatingTime['day']
                ],[
                    'from_time' => $operatingTime['from'],
                    'to_time' => $operatingTime['to']
                ]);
            }
        }
    }
}
