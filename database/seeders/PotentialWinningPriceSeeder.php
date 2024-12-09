<?php

namespace Database\Seeders;

use App\Models\PotentialWinningPriceList;
use App\Models\TicketNumber;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PotentialWinningPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $winningListArray = [
            [
                'type' => TicketNumber::TYPE['Straight'],
                'big1st' => 2500,
                'big2nd' => 1000,
                'big3rd' => 500,
                'big_special' => 180,
                'big_consolation' => 60,
                'small1st' => 3500,
                'small2nd' => 2000,
                'small3rd' => 1000,
                'small_special' => 0,
                'small_consolation' => 0,
                'remark' => 'Straight',
            ],
            [
                'type' => TicketNumber::TYPE['Box'],
                'big1st' => 2500,
                'big2nd' => 1000,
                'big3rd' => 500,
                'big_special' => 180,
                'big_consolation' => 60,
                'small1st' => 3500,
                'small2nd' => 2000,
                'small3rd' => 1000,
                'small_special' => 0,
                'small_consolation' => 0,
                'remark' => 'Straight',
            ],
            [
                'type' => TicketNumber::TYPE['e-box'],
                'big1st' => 625,
                'big2nd' => 250,
                'big3rd' => 125,
                'big_special' => 45,
                'big_consolation' => 15,
                'small1st' => 875,
                'small2nd' => 500,
                'small3rd' => 250,
                'small_special' => 0,
                'small_consolation' => 0,
                'remark' => '4 Permutation',
            ],[
                'type' => TicketNumber::TYPE['e-box'],
                'big1st' => 417,
                'big2nd' => 167,
                'big3rd' => 84,
                'big_special' => 30,
                'big_consolation' => 10,
                'small1st' => 584,
                'small2nd' => 334,
                'small3rd' => 167,
                'small_special' => 0,
                'small_consolation' => 0,
                'remark' => '6 Permutation',
            ],[
                'type' => TicketNumber::TYPE['e-box'],
                'big1st' => 209,
                'big2nd' => 84,
                'big3rd' => 42,
                'big_special' => 15,
                'big_consolation' => 5,
                'small1st' => 292,
                'small2nd' => 167,
                'small3rd' => 84,
                'small_special' => 0,
                'small_consolation' => 0,
                'remark' => '12 Permutation',
            ],[
                'type' => TicketNumber::TYPE['e-box'],
                'big1st' => 105,
                'big2nd' => 42,
                'big3rd' => 21,
                'big_special' => 8,
                'big_consolation' => 3,
                'small1st' => 146,
                'small2nd' => 84,
                'small3rd' => 42,
                'small_special' => 0,
                'small_consolation' => 0,
                'remark' => '24 Permutation',
            ],
        ];

        PotentialWinningPriceList::truncate();

        foreach($winningListArray as $potentialWinning){
            PotentialWinningPriceList::updateOrCreate($potentialWinning);
        }
    }
}
