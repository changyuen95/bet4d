<?php

namespace App\Jobs;

use App\Models\Draw;
use App\Models\DrawResult;
use App\Models\User;
use App\Traits\NotificationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Str;
use Goutte\Client;

class SyncDrawResult implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, NotificationTrait;

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
        // Create a Goutte client
        $client = new Client();
        // Specify the URL you want to scrape
        $url = 'http://www.stc4d.com/live_draw.aspx';
        // Send a GET request to the URL
        $crawler = $client->request('GET', $url);
        // Extract content by element ID
        $elementId = 'MainCont_LBL001';
        $content = $crawler->filter("#$elementId")->text();
        $contentArray = explode(':',$content);
        $drawNo = str_replace(" ","",$contentArray[1]);
        $drawNoArray = explode('/', $drawNo );
        $draw = Draw::where('draw_no',ltrim((int)$drawNoArray[0], '0'))->where('year',$drawNoArray[1])->first();
        $allUser = User::all();
        $results = [
            DrawResult::TYPE['1st'] => [
                [
                    'number' => $crawler->filter("#MainCont_LBL003")->text(),
                    'position' => 1
                ]
            ],
            DrawResult::TYPE['2nd'] => [
                [
                    'number' => $crawler->filter("#MainCont_LBL004")->text(),
                    'position' => 1
                ]
            ],
            DrawResult::TYPE['3rd'] => [
                [
                    'number' => $crawler->filter("#MainCont_LBL005")->text(),
                    'position' => 1
                ]
            ],
            DrawResult::TYPE['special'] => [
                [
                    'number' => $crawler->filter("#MainCont_LBL006")->text(),
                    'position' => 1
                ],[
                    'number' => $crawler->filter("#MainCont_LBL007")->text(),
                    'position' => 2
                ],[
                    'number' => $crawler->filter("#MainCont_LBL008")->text(),
                    'position' => 3
                ],[
                    'number' => $crawler->filter("#MainCont_LBL009")->text(),
                    'position' => 4
                ],[
                    'number' => $crawler->filter("#MainCont_LBL010")->text(),
                    'position' => 5
                ],[
                    'number' => $crawler->filter("#MainCont_LBL011")->text(),
                    'position' => 6
                ],[
                    'number' => $crawler->filter("#MainCont_LBL012")->text(),
                    'position' => 7
                ],[
                    'number' => $crawler->filter("#MainCont_LBL013")->text(),
                    'position' => 8
                ],[
                    'number' => $crawler->filter("#MainCont_LBL014")->text(),
                    'position' => 9
                ],[
                    'number' => $crawler->filter("#MainCont_LBL015")->text(),
                    'position' => 10
                ],[
                    'number' => $crawler->filter("#MainCont_LBL016")->text(),
                    'position' => 11
                ],[
                    'number' => $crawler->filter("#MainCont_LBL017")->text(),
                    'position' => 12
                ],[
                    'number' => $crawler->filter("#MainCont_LBL018")->text(),
                    'position' => 13
                ],
            ],
            DrawResult::TYPE['consolation'] => [
                [
                    'number' => $crawler->filter("#MainCont_LBL019")->text(),
                    'position' => 1
                ],[
                    'number' => $crawler->filter("#MainCont_LBL020")->text(),
                    'position' => 2
                ],[
                    'number' => $crawler->filter("#MainCont_LBL021")->text(),
                    'position' => 3
                ],[
                    'number' => $crawler->filter("#MainCont_LBL022")->text(),
                    'position' => 4
                ],[
                    'number' => $crawler->filter("#MainCont_LBL023")->text(),
                    'position' => 5
                ],[
                    'number' => $crawler->filter("#MainCont_LBL024")->text(),
                    'position' => 6
                ],[
                    'number' => $crawler->filter("#MainCont_LBL025")->text(),
                    'position' => 7
                ],[
                    'number' => $crawler->filter("#MainCont_LBL026")->text(),
                    'position' => 8
                ],[
                    'number' => $crawler->filter("#MainCont_LBL027")->text(),
                    'position' => 9
                ],[
                    'number' => $crawler->filter("#MainCont_LBL028")->text(),
                    'position' => 10
                ],
            ],
        ];
        if($draw){
            $firstPrice = $draw->results()->where('type',DrawResult::TYPE['1st'])->whereNotIn('number',['-',''])->first(); 
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
                    if($updatedFirstPrice->number != '-' && $updatedFirstPrice->number != ''){
                        if(!$draw->is_open_result){
                            foreach($allUser as $notifyUser){
                                $notificationData = [];
                                $notificationData['title'] = 'Draw result is released!';
                                $notificationData['message'] = 'Draw result for '.$drawNo.' is released!';
                                $notificationData['deepLink'] = 'fortknox://draw-results/'.$draw->id;
                                $appId = env('ONESIGNAL_APP_ID');
                                $apiKey = env('ONESIGNAL_REST_API_KEY');
                                $this->sendNotification($appId, $apiKey, $notifyUser,$notificationData);
                            }
                        }
                        $draw->update([
                            'is_open_result' => true
                        ]);
                        GetWinnerList::dispatch($draw);
                    }
                }
            }else{
                GetWinnerList::dispatch($draw);
            }
        }
    }
}
