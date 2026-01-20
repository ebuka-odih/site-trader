<?php

namespace App\Services;

use App\Models\Asset;
use App\Events\PriceUpdated;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AssetPriceService
{
    private $coinmarketcapApiKey;
    private $finnhubApiKey;

    public function __construct()
    {
        $this->coinmarketcapApiKey = config('services.coinmarketcap.api_key');
        $this->finnhubApiKey = config('services.finnhub.api_key');
    }

    public function updateCryptoPrices(): void
    {
        try {
            $cryptoAssets = Asset::where('type', 'crypto')
                ->where('is_active', true)
                ->get();
            
            if ($cryptoAssets->isEmpty()) {
                Log::info('No crypto assets found to update');
                return;
            }
            
            // Group assets by chunks to avoid rate limits
            $chunks = $cryptoAssets->chunk(100); // CoinMarketCap allows up to 100 symbols per request
            
            foreach ($chunks as $chunk) {
                $symbols = $chunk->pluck('symbol')->toArray();
                $this->updateCryptoBatch($symbols);
                
                // Rate limiting - be conservative with CoinMarketCap
                sleep(1);
            }
            
            Log::info('Crypto prices updated successfully', ['count' => $cryptoAssets->count()]);
        } catch (\Exception $e) {
            Log::error('Failed to update crypto prices: ' . $e->getMessage());
        }
    }

    public function updateStockPrices()
    {
        try {
            $stocks = Asset::where('type', 'stock')->get();
            
            foreach ($stocks as $stock) {
                $price = $this->getStockPrice($stock->symbol);
                if ($price) {
                    $oldPrice = $stock->current_price;
                    $oldChange = $stock->price_change_24h;
                    
                    $stock->update([
                        'current_price' => $price['current_price'],
                        'price_change_24h' => $price['price_change_24h'],
                        'market_cap' => $price['market_cap'] ?? 0,
                        'last_updated' => now()
                    ]);
                    
                    // Broadcast price update if price or change percentage changed
                    if ($oldPrice != $price['current_price'] || $oldChange != $price['price_change_24h']) {
                        broadcast(new PriceUpdated($stock, $price['current_price'], $price['price_change_24h']))->toOthers();
                        Log::info("Stock price updated: {$stock->symbol} - Price: {$oldPrice} -> {$price['current_price']}, Change: {$oldChange}% -> {$price['price_change_24h']}%");
                    }
                }
            }
            
            Log::info('Stock prices updated successfully');
            return true;
        } catch (\Exception $e) {
            Log::error('Error updating stock prices: ' . $e->getMessage());
            return false;
        }
    }

    private function updateCryptoBatch(array $symbols): void
    {
        try {
            if (!$this->coinmarketcapApiKey) {
                Log::warning('CoinMarketCap API key not configured');
                return;
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'X-CMC_PRO_API_KEY' => $this->coinmarketcapApiKey,
                    'Accept' => 'application/json'
                ])
                ->get('https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest', [
                    'symbol' => implode(',', $symbols),
                    'convert' => 'USD'
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['data'])) {
                    foreach ($data['data'] as $symbol => $cryptoData) {
                        $asset = Asset::where('symbol', $symbol)->first();
                        if ($asset && isset($cryptoData['quote']['USD'])) {
                            $quote = $cryptoData['quote']['USD'];
                            $oldPrice = $asset->current_price;
                            $newPrice = $quote['price'] ?? 0;
                            
                            $asset->update([
                                'current_price' => $newPrice,
                                'price_change_24h' => $quote['percent_change_24h'] ?? 0,
                                'price_change_percentage_24h' => $quote['percent_change_24h'] ?? 0,
                                'market_cap' => $quote['market_cap'] ?? 0,
                                'volume_24h' => $quote['volume_24h'] ?? 0,
                                'last_updated' => now(),
                            ]);
                            
                            Log::info("Crypto price updated: {$asset->symbol} - Price: {$oldPrice} -> {$newPrice}, Change: {$quote['percent_change_24h']}%");
                            
                            // Broadcast price update if price changed
                            if ($oldPrice != $newPrice) {
                                broadcast(new PriceUpdated($asset, $newPrice, $quote['percent_change_24h'] ?? 0))->toOthers();
                                Log::info("Crypto price updated: {$asset->symbol} - {$oldPrice} -> {$newPrice}");
                            }
                        }
                    }
                }
            } else {
                Log::warning('CoinMarketCap API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error updating crypto batch: ' . $e->getMessage());
        }
    }

    private function getStockPrice($symbol)
    {
        try {
            // Check if API key is configured
            if (!$this->finnhubApiKey) {
                Log::warning("Finnhub API key not configured for stock: {$symbol}");
                return null;
            }

            $response = Http::get('https://finnhub.io/api/v1/quote', [
                'symbol' => $symbol,
                'token' => $this->finnhubApiKey
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Log the response for debugging
                Log::info("Finnhub API response for {$symbol}:", $data);
                
                $currentPrice = $data['c'] ?? 0;
                $priceChange = $data['dp'] ?? 0;
                $marketCap = $data['market_cap'] ?? 0;
                
                Log::info("Stock price data for {$symbol}:", [
                    'current_price' => $currentPrice,
                    'price_change_24h' => $priceChange,
                    'market_cap' => $marketCap
                ]);
                
                return [
                    'current_price' => $currentPrice,
                    'price_change_24h' => $priceChange,
                    'market_cap' => $marketCap
                ];
            } else {
                Log::error("Finnhub API request failed for {$symbol}:", [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                // Return null instead of demo data when API fails
                return null;
            }
        } catch (\Exception $e) {
            Log::error("Error fetching stock price for {$symbol}: " . $e->getMessage());
            return null;
        }
    }

    public function getCryptoAssets(): array
    {
        try {
            if (!$this->coinmarketcapApiKey) {
                Log::warning('CoinMarketCap API key not configured');
                return [];
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'X-CMC_PRO_API_KEY' => $this->coinmarketcapApiKey,
                    'Accept' => 'application/json'
                ])
                ->get('https://pro-api.coinmarketcap.com/v1/cryptocurrency/map', [
                    'limit' => 100,
                    'sort' => 'cmc_rank'
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['data'] ?? [];
            }
        } catch (\Exception $e) {
            Log::error('Error fetching crypto assets: ' . $e->getMessage());
        }

        return [];
    }

    public function getCryptoMetadata(string $symbol): ?array
    {
        try {
            if (!$this->coinmarketcapApiKey) {
                return null;
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'X-CMC_PRO_API_KEY' => $this->coinmarketcapApiKey,
                    'Accept' => 'application/json'
                ])
                ->get($this->coinMarketCapApi . '/cryptocurrency/info', [
                    'symbol' => $symbol
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['data'][$symbol] ?? null;
            }
        } catch (\Exception $e) {
            Log::error('Error fetching crypto metadata for ' . $symbol . ': ' . $e->getMessage());
        }

        return null;
    }

    public function populateCryptoAssets(): void
    {
        try {
            Log::info('Starting crypto assets population from CoinMarketCap');
            
            $cryptoAssets = $this->getCryptoAssets();
            
            if (empty($cryptoAssets)) {
                Log::warning('No crypto assets received from CoinMarketCap');
                return;
            }
            
            $count = 0;
            foreach ($cryptoAssets as $crypto) {
                $symbol = $crypto['symbol'];
                $name = $crypto['name'];
                
                // Check if asset already exists
                $existingAsset = Asset::where('symbol', $symbol)->first();
                
                if (!$existingAsset) {
                    Asset::create([
                        'symbol' => $symbol,
                        'name' => $name,
                        'type' => 'crypto',
                        'current_price' => 0, // Will be updated by price update
                        'is_active' => true,
                    ]);
                    $count++;
                }
            }
            
            Log::info('Crypto assets populated successfully', ['new_assets' => $count]);
            
            // Update prices for all crypto assets
            $this->updateCryptoPrices();
            
        } catch (\Exception $e) {
            Log::error('Error populating crypto assets: ' . $e->getMessage());
        }
    }

    public function populateStockAssets()
    {
        try {
            // Popular stocks to populate
            $popularStocks = [
                'AAPL' => 'Apple Inc.',
                'MSFT' => 'Microsoft Corporation',
                'GOOGL' => 'Alphabet Inc.',
                'AMZN' => 'Amazon.com Inc.',
                'TSLA' => 'Tesla Inc.',
                'META' => 'Meta Platforms Inc.',
                'NVDA' => 'NVIDIA Corporation',
                'BRK.A' => 'Berkshire Hathaway Inc.',
                'JNJ' => 'Johnson & Johnson',
                'V' => 'Visa Inc.',
                'JPM' => 'JPMorgan Chase & Co.',
                'PG' => 'Procter & Gamble Co.',
                'UNH' => 'UnitedHealth Group Inc.',
                'HD' => 'The Home Depot Inc.',
                'MA' => 'Mastercard Inc.',
                'DIS' => 'The Walt Disney Company',
                'PYPL' => 'PayPal Holdings Inc.',
                'ADBE' => 'Adobe Inc.',
                'CRM' => 'Salesforce Inc.',
                'NFLX' => 'Netflix Inc.'
            ];

            foreach ($popularStocks as $symbol => $name) {
                // Check if asset already exists
                $existingAsset = Asset::where('symbol', $symbol)->first();
                if (!$existingAsset) {
                    // Get initial price data
                    $priceData = $this->getStockPrice($symbol);
                    
                    Asset::create([
                        'symbol' => $symbol,
                        'name' => $name,
                        'type' => 'stock',
                        'current_price' => $priceData['current_price'] ?? 0,
                        'price_change_24h' => $priceData['price_change_24h'] ?? 0,
                        'market_cap' => $priceData['market_cap'] ?? 0,
                        'last_updated' => now()
                    ]);
                }
            }
            
            Log::info('Stock assets populated successfully');
            return true;
        } catch (\Exception $e) {
            Log::error('Error populating stock assets: ' . $e->getMessage());
            return false;
        }
    }
}
