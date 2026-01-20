<?php

namespace App\Console\Commands;

use App\Services\AssetPriceService;
use Illuminate\Console\Command;

class UpdateCryptoPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:update-crypto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update cryptocurrency prices from CoinGecko API';

    /**
     * Execute the console command.
     */
    public function handle(AssetPriceService $priceService)
    {
        $this->info('Updating cryptocurrency prices...');
        
        try {
            $priceService->updateCryptoPrices();
            $this->info('Cryptocurrency prices updated successfully!');
        } catch (\Exception $e) {
            $this->error('Failed to update cryptocurrency prices: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
