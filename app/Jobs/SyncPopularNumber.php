<?php

namespace App\Jobs;

use App\Models\PopularNumber;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Goutte\Client;

class SyncPopularNumber implements ShouldQueue
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
        $client = new Client();
        // Specify the URL you want to scrape
        $url = 'http://www.stc4d.com/statistic_popular.aspx';
        // Send a GET request to the URL
        $crawler = $client->request('GET', $url);
        $table = $crawler->filter('#MainCont_DIV001 table.table');
        $results = array();
        if ($table->count() > 0) {
            $counter = 0;
            $numOfRow = 0;
            // Loop through each row in the table
            $position = 1;
            $table->filter('tr')->each(function ($row) use(&$numOfRow,&$results,&$counter,&$position){
                
                if($numOfRow >= 2){
                    $result = array();
                    $rowData = $row->filter('td')->each(function ($cell) use (&$numOfRow,&$counter,&$result) {
                        if($counter == 0){
                            $result['number'] = filter_var($cell->text(), FILTER_SANITIZE_NUMBER_INT);
                        }elseif($counter == 1){
                            $result['last_prize'] = $cell->text();
                        }elseif($counter == 2){
                            $result['date'] = Carbon::createFromFormat('d/m/Y', $cell->text())->format('Y-m-d');
                        }elseif($counter == 3){
                            $result['no_of_times_drawn'] = filter_var($cell->text(), FILTER_SANITIZE_NUMBER_INT);
                        }
                        $counter++;
                    });
                    $result['position'] = $position;
                    array_push($results,$result);
                    $position++;
                }
                
                $counter = 0;
                $numOfRow++;
            });
        }
        PopularNumber::truncate();
        foreach($results as $popularNumber){
            PopularNumber::create([
                'number' => $popularNumber['number'],
                'last_prize' => $popularNumber['last_prize'],
                'date' => $popularNumber['date'],
                'no_of_times_drawn' => $popularNumber['no_of_times_drawn'],
                'position' => $popularNumber['position'],
            ]);
        }
    }
}
