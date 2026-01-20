<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(AdminSeeder::class);
        $this->call(TradePairSeeder::class);
        $this->call(CopyTraderSeeder::class);
        $this->call(AssetSeeder::class);
        $this->call(PlanSeeder::class);
        $this->call(AiTraderPlanSeeder::class);
        $this->call(AiTraderSeeder::class);

        User::updateOrCreate(
            ['email' => 'test.trader@example.com'],
            [
                'name' => 'Test Trader',
                'username' => 'testtrader',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'status' => 'active',
                'currency' => 'USD',
                'balance' => 50000,
                'trading_balance' => 25000,
                'email_verified_at' => now(),
            ]
        );
    }
}
