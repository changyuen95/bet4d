<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Platform;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $platform = Platform::updateOrCreate([
            'name' => 'STC',
        ],[
            'status' => Platform::STATUS['Active'],
            'image' => '',
        ]);

        $platform->games()->updateOrCreate([
            'name' => 'STC 4D',
        ],[
            'status' => Game::STATUS['Active'],
            'image' => '',
        ]);

        $platform->outlets()->updateOrCreate([
            'name' => 'Outlet 1',
        ],[
            'address' => 'test address',
            'image' => '',
        ]);

        $platform->outlets()->updateOrCreate([
            'name' => 'Outlet 2',
        ],[
            'address' => 'test address',
            'image' => '',
        ]);
    }
}
