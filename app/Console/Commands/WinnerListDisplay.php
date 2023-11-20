<?php

namespace App\Console\Commands;

use App\Jobs\GenerateWinnerListDisplay;
use Illuminate\Console\Command;

class WinnerListDisplay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:winner-list-display';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Winner List Display';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        GenerateWinnerListDisplay::dispatch();
    }
}
