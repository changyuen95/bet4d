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
                'type_id' => TicketNumber::TYPE['Straight'],
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
                'type_id' => TicketNumber::TYPE['Permutation'],
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
            ],
        ];

        PotentialWinningPriceList::truncate();

        foreach($winningListArray as $potentialWinning){
            PotentialWinningPriceList::create($potentialWinning);
        }
    }
}
