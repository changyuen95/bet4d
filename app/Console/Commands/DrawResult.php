<?php

namespace App\Console\Commands;

use App\Jobs\SyncDrawResult;
use Illuminate\Console\Command;

class DrawResult extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:draw-result';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Draw Result';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $drawNo = '4/23';
        SyncDrawResult::dispatch($drawNo);
    }
}
