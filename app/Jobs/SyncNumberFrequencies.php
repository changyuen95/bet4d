<?php

namespace App\Jobs;

use App\Models\NumberFrequencies;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Goutte\Client;

class SyncNumberFrequencies implements ShouldQueue
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
        $url = 'http://www.stc4d.com/statistic_frequencies.aspx';
        // Send a GET request to the URL
        $crawler = $client->request('GET', $url);
        $table1 = $crawler->filter('#MainCont_DIV001 table.table');
        $results = array();
        if ($table1->count() > 0) {
            $counter = 0;
            $numOfRow = 0;
            // Loop through each row in the table
            $table1->filter('tr')->each(function ($row) use(&$numOfRow,&$results,&$counter){
                if($numOfRow == 2 || $numOfRow == 3){
                    $rowData = $row->filter('td')->each(function ($cell) use (&$numOfRow,&$counter, &$results) {
                        if($numOfRow == 2){
                            $results['overall_digit_statistics'][$counter]['number'] = $cell->text();

                        }elseif($numOfRow == 3){
                            $results['overall_digit_statistics'][$counter]['frequencies'] = filter_var($cell->text(), FILTER_SANITIZE_NUMBER_INT);

                        }
                        $counter++;
                    });
                }
                $counter = 0;
                $numOfRow++;
            });
        }

        $table2 = $crawler->filter('#MainCont_DIV002 table.table');
        if ($table2->count() > 0) {
            $counter = 0;
            $numOfRow = 0;
            // Loop through each row in the table
            $table2->filter('tr')->each(function ($row) use(&$numOfRow,&$results,&$counter){
                if($numOfRow == 2 || $numOfRow == 3){
                    $rowData = $row->filter('td')->each(function ($cell) use (&$numOfRow,&$counter, &$results) {
                        if($numOfRow == 2){
                            $results['1st_digit_statistics'][$counter]['number'] = $cell->text();

                        }elseif($numOfRow == 3){
                            $results['1st_digit_statistics'][$counter]['frequencies'] = filter_var($cell->text(), FILTER_SANITIZE_NUMBER_INT);;

                        }
                        $counter++;
                    });
                }
                $counter = 0;
                $numOfRow++;
            });
        }

        $table3 = $crawler->filter('#MainCont_DIV003 table.table');
        if ($table3->count() > 0) {
            $counter = 0;
            $numOfRow = 0;
            // Loop through each row in the table
            $table3->filter('tr')->each(function ($row) use(&$numOfRow,&$results,&$counter){
                if($numOfRow == 2 || $numOfRow == 3){
                    $rowData = $row->filter('td')->each(function ($cell) use (&$numOfRow,&$counter, &$results) {
                        if($numOfRow == 2){
                            $results['2nd_digit_statistics'][$counter]['number'] = $cell->text();

                        }elseif($numOfRow == 3){
                            $results['2nd_digit_statistics'][$counter]['frequencies'] = filter_var($cell->text(), FILTER_SANITIZE_NUMBER_INT);;

                        }
                        $counter++;
                    });
                }
                $counter = 0;
                $numOfRow++;
            });
        }

        $table4 = $crawler->filter('#MainCont_DIV004 table.table');
        if ($table4->count() > 0) {
            $counter = 0;
            $numOfRow = 0;
            // Loop through each row in the table
            $table4->filter('tr')->each(function ($row) use(&$numOfRow,&$results,&$counter){
                if($numOfRow == 2 || $numOfRow == 3){
                    $rowData = $row->filter('td')->each(function ($cell) use (&$numOfRow,&$counter, &$results) {
                        if($numOfRow == 2){
                            $results['3rd_digit_statistics'][$counter]['number'] = $cell->text();

                        }elseif($numOfRow == 3){
                            $results['3rd_digit_statistics'][$counter]['frequencies'] = filter_var($cell->text(), FILTER_SANITIZE_NUMBER_INT);;

                        }
                        $counter++;
                    });
                }
                $counter = 0;
                $numOfRow++;
            });
        }

        $table5 = $crawler->filter('#MainCont_DIV005 table.table');
        if ($table5->count() > 0) {
            $counter = 0;
            $numOfRow = 0;
            // Loop through each row in the table
            $table5->filter('tr')->each(function ($row) use(&$numOfRow,&$results,&$counter){
                if($numOfRow == 2 || $numOfRow == 3){
                    $rowData = $row->filter('td')->each(function ($cell) use (&$numOfRow,&$counter, &$results) {
                        if($numOfRow == 2){
                            $results['4th_digit_statistics'][$counter]['number'] = $cell->text();

                        }elseif($numOfRow == 3){
                            $results['4th_digit_statistics'][$counter]['frequencies'] = filter_var($cell->text(), FILTER_SANITIZE_NUMBER_INT);;

                        }
                        $counter++;
                    });
                }
                $counter = 0;
                $numOfRow++;
            });
        }
        
        foreach($results as $key => $result){
            $numberFrequency = NumberFrequencies::updateOrCreate([
                'name' => $key
            ]);
            foreach($result as $frequencyResult){
                $numberFrequency->details()->updateOrCreate(
                    [
                        'number' => $frequencyResult['number']
                    ],
                    [
                        'frequencies' => $frequencyResult['frequencies']
                    ]
                );
            }
        }
    }
}
