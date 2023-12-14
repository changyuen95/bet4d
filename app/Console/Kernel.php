<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\DrawResult::class,
        \App\Console\Commands\WinnerListDisplay::class,
        \App\Console\Commands\NumberFrequencies::class,
        \App\Console\Commands\PopularNumber::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('sync:draw-result')
                //  ->timezone('Your_Timezone') // Replace 'Your_Timezone' with your actual timezone
                 ->days([Schedule::WEDNESDAY, Schedule::SATURDAY, Schedule::SUNDAY])
                 ->dailyAt('19:00')
                 ->everyFiveMinutes()
                 ->between('19:00', '21:00');

        $schedule->command('sync:popular-number')
                //  ->timezone('Your_Timezone') // Replace 'Your_Timezone' with your actual timezone
                 ->days([Schedule::WEDNESDAY, Schedule::SATURDAY, Schedule::SUNDAY])
                 ->dailyAt('21:00');

        $schedule->command('sync:number-frequencies')
                //  ->timezone('Your_Timezone') // Replace 'Your_Timezone' with your actual timezone
                 ->days([Schedule::WEDNESDAY, Schedule::SATURDAY, Schedule::SUNDAY])
                 ->dailyAt('21:00');

        $schedule->command('generate:winner-list-display')
                //  ->timezone('Your_Timezone') // Replace 'Your_Timezone' with your actual timezone
                 ->days([Schedule::WEDNESDAY, Schedule::SATURDAY, Schedule::SUNDAY])
                 ->dailyAt('21:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
