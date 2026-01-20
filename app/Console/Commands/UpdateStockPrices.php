<?php

namespace App\Console\Commands;

use App\Services\AssetPriceService;
use Illuminate\Console\Command;

class UpdateStockPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:update-stocks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update stock prices from Alpha Vantage API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to update stock prices...');
        
        $service = new AssetPriceService();
        $result = $service->updateStockPrices();
        
        if ($result) {
            $this->info('Stock prices updated successfully!');
        } else {
            $this->error('Failed to update stock prices. Check the logs for details.');
        }
    }
}
