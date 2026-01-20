<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CopyTrader;

class CopyTraderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $traders = [
            [
                'name' => 'Keely Blake',
                'avatar' => 'img/avatars/trader1.jpg',
                'amount' => 8.00,
                'win_rate' => 72,
                'profit_share' => 876.00,
                'win' => 270,
                'loss' => 371,
                'status' => 'active',
            ],
            [
                'name' => 'Blossom Rocha',
                'avatar' => 'img/avatars/trader2.jpg',
                'amount' => 788.00,
                'win_rate' => 64,
                'profit_share' => 656.00,
                'win' => 638,
                'loss' => 719,
                'status' => 'active',
            ],
            [
                'name' => 'Orion Vega',
                'avatar' => 'img/avatars/trader3.jpg',
                'amount' => 120.00,
                'win_rate' => 55,
                'profit_share' => 312.50,
                'win' => 120,
                'loss' => 98,
                'status' => 'active',
            ],
        ];

        foreach ($traders as $data) {
            CopyTrader::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }
    }
}
