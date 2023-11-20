<?php

namespace App\Jobs;

use App\Models\Draw;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateWinnerListDisplay implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $draw = Draw::whereHas('results')->orderBy('created_at','DESC')->first();
        if($draw){
            $drawResults = $draw->results;
            foreach($drawResults as $drawResult){
                $resultArray = array();
                $winners = $drawResult->winners;
                $winnerCount = $drawResult->winners()->count();
                $totalWinAmount = 0;
                
                if($winnerCount > 0){
                    foreach($winners as $winner){
                        $ticketNumber = $winner->ticketNumber;
                        $ticket = $ticketNumber->ticket;
                        $outlet = $ticket->outlet;

                        if(isset($resultArray[$outlet->id]['winning_amount'])){
                            $resultArray[$outlet->id]['winning_amount'] = $resultArray[$outlet->id]['winning_amount'] + $winner->amount;
                        }else{
                            $resultArray[$outlet->id]['winning_amount'] = $winner->amount;
                        }

                        if(isset($resultArray[$outlet->id]['no_of_user'])){
                            $resultArray[$outlet->id]['no_of_user'] = $resultArray[$outlet->id]['no_of_user'] + 1;
                        }else{
                            $resultArray[$outlet->id]['no_of_user'] = 1;
                        }

                        $resultArray[$outlet->id]['outlet_address'] = $outlet->address;
                       
                    }
                }
              
                if(count($resultArray) > 0){
                    foreach($resultArray as $key => $result){
                        $draw->winnerListDisplay()->create([
                            'draw_result_id' => $drawResult->id,
                            'outlet_id' => $key,
                            'winning_amount' => $result['winning_amount'],
                            'no_of_user' => $result['no_of_user'],
                            'number' => $drawResult->number,
                            'description' => $result['no_of_user'].' customer won '.$drawResult->type.' Prize at '.$result['outlet_address'].' - RM'.$result['winning_amount']
                        ]);
                    }
                }
            }
        }
    }
}
