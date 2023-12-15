<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\WinnerList;
use Illuminate\Console\Command;

class MyTestingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testing:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //

        // $distributed_winner_list = WinnerList::where('is_distribute', 1)->whereHas('ticketNumber', function($q){
        //     $q->whereHas('ticket', function($q){
                
        //     });
        // })->where('outlet_id','01hdfv5e9k4axefw12hwk52y8w')->with('drawResult','ticketNumber','winner');

        // $distributed_winner_list = WinnerList::where('is_distribute', 1)
        //     ->whereHas('ticketNumber.ticket.outlet', function($q){
        //         $q->where('outlets.id', '01hdfv5e9k4axefw12hwk52y8w');
        //     })
        //     ->with('drawResult', 'ticketNumber', 'winner')
        //     ->get();
        
        $winner= WinnerList::where('action_by', '01hqwgalqwgqtew9vj12w00')
                            ->where('id', '01hevst6g0xt7cc003ssx1t6vs')
                            ->with('drawResult', 'ticketNumber', 'winner')
                            ->first();
        dd($winner);
    }
}
