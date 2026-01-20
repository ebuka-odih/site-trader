<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AssetPriceService;

class UpdateAllAssetPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:update-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all asset prices with real market data from APIs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting asset price update...');
        
        $priceService = new AssetPriceService();
        
        // Update crypto prices
        $this->info('Updating crypto prices...');
        $priceService->updateCryptoPrices();
        
        // Update stock prices
        $this->info('Updating stock prices...');
        $priceService->updateStockPrices();
        
        $this->info('Asset price update completed successfully!');
        
        // Show some sample updated prices
        $this->info('Sample updated prices:');
        $this->table(
            ['Symbol', 'Type', 'Price', '24h Change'],
            \App\Models\Asset::take(10)->get()->map(function($asset) {
                return [
                    $asset->symbol,
                    $asset->type,
                    '$' . number_format($asset->current_price, 2),
                    $asset->price_change_24h . '%'
                ];
            })
        );
        
        return 0;
    }
}
