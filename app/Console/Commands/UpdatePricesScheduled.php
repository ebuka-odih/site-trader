<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AssetPriceService;

class UpdatePricesScheduled extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prices:update-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update crypto and stock prices from real market data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting scheduled price updates...');
        
        $service = new AssetPriceService();
        
        // Update crypto prices
        $this->info('Updating crypto prices...');
        $service->updateCryptoPrices();
        
        // Update stock prices
        $this->info('Updating stock prices...');
        $service->updateStockPrices();
        
        $this->info('Price updates completed successfully!');
        
        return 0;
    }
}
