<?php

namespace App\Console\Commands;

use App\Jobs\SyncPopularNumber;
use Illuminate\Console\Command;

class PopularNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:popular-number';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Popular Number';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        SyncPopularNumber::dispatch();
    }
}
