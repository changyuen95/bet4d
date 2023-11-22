<?php

namespace App\Console\Commands;

use App\Jobs\SyncNumberFrequencies;
use Illuminate\Console\Command;

class NumberFrequencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:number-frequencies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'SyncNumberFrequencies';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        SyncNumberFrequencies::dispatch();
    }
}
