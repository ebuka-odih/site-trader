<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AssetPriceService;

class PopulateStockAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:populate-stocks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate stock assets from Finnhub API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to populate stock assets...');
        
        $service = new AssetPriceService();
        $result = $service->populateStockAssets();
        
        if ($result) {
            $this->info('Stock assets populated successfully!');
        } else {
            $this->error('Failed to populate stock assets. Check the logs for details.');
        }
    }
}
