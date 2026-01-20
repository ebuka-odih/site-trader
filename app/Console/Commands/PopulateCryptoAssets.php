<?php

namespace App\Console\Commands;

use App\Services\AssetPriceService;
use Illuminate\Console\Command;

class PopulateCryptoAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:populate-crypto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate database with crypto assets from CoinMarketCap';

    /**
     * Execute the console command.
     */
    public function handle(AssetPriceService $assetPriceService)
    {
        $this->info('Starting crypto assets population...');
        
        try {
            $assetPriceService->populateCryptoAssets();
            $this->info('Crypto assets populated successfully!');
        } catch (\Exception $e) {
            $this->error('Error populating crypto assets: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
