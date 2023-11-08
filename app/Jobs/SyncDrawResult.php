<?php

namespace App\Jobs;

use App\Models\Draw;
use App\Models\DrawResult;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Str;
class SyncDrawResult implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $drawNo = '';
    /**
     * Create a new job instance.
     */
    public function __construct($drawNo)
    {
        $this->drawNo = $drawNo;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $drawNoArray = explode('/',$this->drawNo);
        $draw = Draw::where('draw_no',$drawNoArray[0])->where('year',$drawNoArray[1])->first();
        $results = [
            DrawResult::TYPE['1st'] => [
                [
                    'number' => '7666',
                    'position' => 1
                ]
            ],
            DrawResult::TYPE['2nd'] => [
                [
                    'number' => '2222',
                    'position' => 1
                ]
            ],
            DrawResult::TYPE['3rd'] => [
                [
                    'number' => '3333',
                    'position' => 1
                ]
            ],
            DrawResult::TYPE['special'] => [
                [
                    'number' => '4444',
                    'position' => 1
                ],[
                    'number' => '5555',
                    'position' => 2
                ],[
                    'number' => '6666',
                    'position' => 4
                ],[
                    'number' => '7777',
                    'position' => 5
                ],[
                    'number' => '8888',
                    'position' => 6
                ],[
                    'number' => '9999',
                    'position' => 8
                ],[
                    'number' => '0000',
                    'position' => 9
                ],
            ],
            DrawResult::TYPE['consolation'] => [
                [
                    'number' => '1222',
                    'position' => 1
                ],[
                    'number' => '1333',
                    'position' => 2
                ],[
                    'number' => '1444',
                    'position' => 3
                ],[
                    'number' => '1555',
                    'position' => 4
                ],[
                    'number' => '1666',
                    'position' => 5
                ],[
                    'number' => '1777',
                    'position' => 6
                ],[
                    'number' => '1888',
                    'position' => 7
                ],[
                    'number' => '1999',
                    'position' => 8
                ],[
                    'number' => '2111',
                    'position' => 9
                ],[
                    'number' => '6345',
                    'position' => 10
                ],
            ],
        ];
        if($draw){
            $firstPrice = $draw->results()->where('type',DrawResult::TYPE['1st'])->where('number','!=','')->first(); 
            if(!$firstPrice){
                foreach($results as $key => $result){
                    foreach($result as $number){
                        $draw->results()->updateOrCreate([
                            'type' => $key,
                            'position' => $number['position']
                        ],[
                            'number' => $number['number']
                        ]);
                    }
                }

                $updatedFirstPrice = $draw->results()->where('type',DrawResult::TYPE['1st'])->first(); 

                if($updatedFirstPrice){
                    if($updatedFirstPrice->number != ''){
                        GetWinnerList::dispatch($draw);
                    }
                }
            }else{
                GetWinnerList::dispatch($draw);
            }
        }
    }
}
