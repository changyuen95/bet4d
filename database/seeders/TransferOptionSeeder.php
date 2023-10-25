<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\TransferOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransferOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bankData = [
            [
                'name' => 'TNG eWallet',
                'type' => TransferOption::TYPE['eWallet'],
            ],
            [
                'name' => 'Maybank',
                'type' => TransferOption::TYPE['Bank'],
            ],
            [
                'name' => 'Public Bank',
                'type' => TransferOption::TYPE['Bank'],
            ],
            [
                'name' => 'HSBC Bank',
                'type' => TransferOption::TYPE['Bank'],
            ],
            [
                'name' => 'OCBC',
                'type' => TransferOption::TYPE['Bank'],
            ],
            [
                'name' => 'CIMB Bank',
                'type' => TransferOption::TYPE['Bank'],
            ],
            [
                'name' => 'RHB Bank',
                'type' => TransferOption::TYPE['Bank'],
            ],
            [
                'name' => 'Hong Leong Bank',
                'type' => TransferOption::TYPE['Bank'],
            ],
            [
                'name' => 'AmBank Group',
                'type' => TransferOption::TYPE['Bank'],
            ],
            [
                'name' => 'BSN',
                'type' => TransferOption::TYPE['Bank'],
            ],
            [
                'name' => 'Alliance Bank',
                'type' => TransferOption::TYPE['Bank'],
            ],
            [
                'name' => 'Standard Chartered',
                'type' => TransferOption::TYPE['Bank'],
            ],
            [
                'name' => 'Affin Bank',
                'type' => TransferOption::TYPE['Bank'],
            ],
            [
                'name' => 'Bank Islam',
                'type' => TransferOption::TYPE['Bank'],
            ],
        ];

        foreach($bankData as $bank){
            TransferOption::updateOrCreate([
                'name' => $bank['name'],
            ],[
                'type' => $bank['type'],
            ]);
        }
    }
}
