# Holding Model - Detailed Implementation

## 1. Database Migrations

### Create Assets Migration
```bash
php artisan make:migration create_assets_table
```

```php
// database/migrations/xxxx_create_assets_table.php
public function up()
{
    Schema::create('assets', function (Blueprint $table) {
        $table->id();
        $table->string('symbol', 20)->unique();
        $table->string('name', 100);
        $table->enum('type', ['crypto', 'stock']);
        $table->decimal('current_price', 20, 8)->default(0);
        $table->decimal('market_cap', 20, 2)->default(0);
        $table->decimal('volume_24h', 20, 2)->default(0);
        $table->decimal('price_change_24h', 10, 4)->default(0);
        $table->decimal('price_change_percentage_24h', 10, 4)->default(0);
        $table->timestamp('last_updated')->useCurrent();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}
```

### Create User Holdings Migration
```bash
php artisan make:migration create_user_holdings_table
```

```php
// database/migrations/xxxx_create_user_holdings_table.php
public function up()
{
    Schema::create('user_holdings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('asset_id')->constrained()->onDelete('cascade');
        $table->decimal('quantity', 20, 8)->default(0);
        $table->decimal('average_buy_price', 20, 8)->default(0);
        $table->decimal('total_invested', 20, 8)->default(0);
        $table->decimal('current_value', 20, 8)->default(0);
        $table->decimal('unrealized_pnl', 20, 8)->default(0);
        $table->decimal('unrealized_pnl_percentage', 10, 4)->default(0);
        $table->timestamp('last_updated')->useCurrent();
        $table->timestamps();
        
        $table->unique(['user_id', 'asset_id']);
    });
}
```

### Create Holding Transactions Migration
```bash
php artisan make:migration create_holding_transactions_table
```

```php
// database/migrations/xxxx_create_holding_transactions_table.php
public function up()
{
    Schema::create('holding_transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('asset_id')->constrained()->onDelete('cascade');
        $table->enum('type', ['buy', 'sell']);
        $table->decimal('quantity', 20, 8);
        $table->decimal('price_per_unit', 20, 8);
        $table->decimal('total_amount', 20, 8);
        $table->decimal('fee', 20, 8)->default(0);
        $table->timestamp('transaction_date')->useCurrent();
        $table->enum('status', ['pending', 'completed', 'cancelled'])->default('completed');
        $table->timestamps();
    });
}
```

## 2. Models

### Asset Model
```bash
php artisan make:model Asset
```

```php
// app/Models/Asset.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    protected $fillable = [
        'symbol', 'name', 'type', 'current_price', 'market_cap',
        'volume_24h', 'price_change_24h', 'price_change_percentage_24h',
        'is_active'
    ];

    protected $casts = [
        'current_price' => 'decimal:8',
        'market_cap' => 'decimal:2',
        'volume_24h' => 'decimal:2',
        'price_change_24h' => 'decimal:4',
        'price_change_percentage_24h' => 'decimal:4',
        'is_active' => 'boolean',
    ];

    public function holdings(): HasMany
    {
        return $this->hasMany(UserHolding::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(HoldingTransaction::class);
    }
}
```

### UserHolding Model
```bash
php artisan make:model UserHolding
```

```php
// app/Models/UserHolding.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserHolding extends Model
{
    protected $fillable = [
        'user_id', 'asset_id', 'quantity', 'average_buy_price',
        'total_invested', 'current_value', 'unrealized_pnl',
        'unrealized_pnl_percentage'
    ];

    protected $casts = [
        'quantity' => 'decimal:8',
        'average_buy_price' => 'decimal:8',
        'total_invested' => 'decimal:8',
        'current_value' => 'decimal:8',
        'unrealized_pnl' => 'decimal:8',
        'unrealized_pnl_percentage' => 'decimal:4',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(HoldingTransaction::class, 'asset_id', 'asset_id')
            ->where('user_id', $this->user_id);
    }
}
```

### HoldingTransaction Model
```bash
php artisan make:model HoldingTransaction
```

```php
// app/Models/HoldingTransaction.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HoldingTransaction extends Model
{
    protected $fillable = [
        'user_id', 'asset_id', 'type', 'quantity', 'price_per_unit',
        'total_amount', 'fee', 'status'
    ];

    protected $casts = [
        'quantity' => 'decimal:8',
        'price_per_unit' => 'decimal:8',
        'total_amount' => 'decimal:8',
        'fee' => 'decimal:8',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
```

## 3. Services

### Asset Price Service
```bash
php artisan make:service AssetPriceService
```

```php
// app/Services/AssetPriceService.php
<?php

namespace App\Services;

use App\Models\Asset;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AssetPriceService
{
    private $coinGeckoApi = 'https://api.coingecko.com/api/v3';
    private $alphaVantageApi = 'https://www.alphavantage.co/query';
    private $alphaVantageKey;

    public function __construct()
    {
        $this->alphaVantageKey = config('services.alpha_vantage.key');
    }

    public function updateCryptoPrices(): void
    {
        try {
            $cryptoAssets = Asset::where('type', 'crypto')
                ->where('is_active', true)
                ->get();
            
            // Group assets by chunks to avoid rate limits
            $chunks = $cryptoAssets->chunk(50);
            
            foreach ($chunks as $chunk) {
                $symbols = $chunk->pluck('symbol')->toArray();
                $this->updateCryptoBatch($symbols);
                
                // Rate limiting
                sleep(2);
            }
        } catch (\Exception $e) {
            Log::error('Failed to update crypto prices: ' . $e->getMessage());
        }
    }

    public function updateStockPrices(): void
    {
        try {
            $stockAssets = Asset::where('type', 'stock')
                ->where('is_active', true)
                ->get();
            
            foreach ($stockAssets as $asset) {
                $this->updateStockPrice($asset);
                
                // Rate limiting for Alpha Vantage
                sleep(12); // 5 calls per minute = 12 seconds between calls
            }
        } catch (\Exception $e) {
            Log::error('Failed to update stock prices: ' . $e->getMessage());
        }
    }

    private function updateCryptoBatch(array $symbols): void
    {
        $response = Http::get($this->coinGeckoApi . '/simple/price', [
            'ids' => implode(',', $symbols),
            'vs_currencies' => 'usd',
            'include_24hr_change' => 'true',
            'include_market_cap' => 'true',
            'include_24hr_vol' => 'true'
        ]);

        if ($response->successful()) {
            $data = $response->json();
            
            foreach ($data as $symbol => $priceData) {
                $asset = Asset::where('symbol', $symbol)->first();
                if ($asset) {
                    $asset->update([
                        'current_price' => $priceData['usd'],
                        'price_change_percentage_24h' => $priceData['usd_24h_change'] ?? 0,
                        'market_cap' => $priceData['usd_market_cap'] ?? 0,
                        'volume_24h' => $priceData['usd_24h_vol'] ?? 0,
                    ]);
                }
            }
        }
    }

    private function updateStockPrice(Asset $asset): void
    {
        $response = Http::get($this->alphaVantageApi, [
            'function' => 'GLOBAL_QUOTE',
            'symbol' => $asset->symbol,
            'apikey' => $this->alphaVantageKey
        ]);

        if ($response->successful()) {
            $data = $response->json();
            
            if (isset($data['Global Quote'])) {
                $quote = $data['Global Quote'];
                
                $asset->update([
                    'current_price' => $quote['05. price'] ?? 0,
                    'price_change_24h' => $quote['09. change'] ?? 0,
                    'price_change_percentage_24h' => $quote['10. change percent'] ?? 0,
                    'volume_24h' => $quote['06. volume'] ?? 0,
                ]);
            }
        }
    }
}
```

### Portfolio Calculation Service
```bash
php artisan make:service PortfolioCalculationService
```

```php
// app/Services/PortfolioCalculationService.php
<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserHolding;
use Illuminate\Support\Facades\DB;

class PortfolioCalculationService
{
    public function updateUserPortfolio(User $user): void
    {
        $holdings = $user->holdings()->with('asset')->get();
        
        foreach ($holdings as $holding) {
            $this->updateHoldingValues($holding);
        }
        
        // Update user's holding balance
        $totalHoldingValue = $holdings->sum('current_value');
        $user->update(['holding_balance' => $totalHoldingValue]);
    }

    public function updateHoldingValues(UserHolding $holding): void
    {
        $currentPrice = $holding->asset->current_price;
        $currentValue = $holding->quantity * $currentPrice;
        $unrealizedPnl = $currentValue - $holding->total_invested;
        $unrealizedPnlPercentage = $holding->total_invested > 0 
            ? ($unrealizedPnl / $holding->total_invested) * 100 
            : 0;

        $holding->update([
            'current_value' => $currentValue,
            'unrealized_pnl' => $unrealizedPnl,
            'unrealized_pnl_percentage' => $unrealizedPnlPercentage,
        ]);
    }

    public function buyAsset(User $user, Asset $asset, float $quantity, float $pricePerUnit): UserHolding
    {
        return DB::transaction(function () use ($user, $asset, $quantity, $pricePerUnit) {
            $totalAmount = $quantity * $pricePerUnit;
            
            // Check if user has enough balance
            if ($user->holding_balance < $totalAmount) {
                throw new \Exception('Insufficient balance');
            }
            
            // Deduct from user's holding balance
            $user->decrement('holding_balance', $totalAmount);
            
            // Find existing holding or create new one
            $holding = UserHolding::firstOrNew([
                'user_id' => $user->id,
                'asset_id' => $asset->id,
            ]);
            
            if ($holding->exists) {
                // Update existing holding
                $newQuantity = $holding->quantity + $quantity;
                $newTotalInvested = $holding->total_invested + $totalAmount;
                $newAveragePrice = $newTotalInvested / $newQuantity;
                
                $holding->update([
                    'quantity' => $newQuantity,
                    'average_buy_price' => $newAveragePrice,
                    'total_invested' => $newTotalInvested,
                ]);
            } else {
                // Create new holding
                $holding->fill([
                    'quantity' => $quantity,
                    'average_buy_price' => $pricePerUnit,
                    'total_invested' => $totalAmount,
                ]);
                $holding->save();
            }
            
            // Create transaction record
            $holding->transactions()->create([
                'user_id' => $user->id,
                'asset_id' => $asset->id,
                'type' => 'buy',
                'quantity' => $quantity,
                'price_per_unit' => $pricePerUnit,
                'total_amount' => $totalAmount,
            ]);
            
            // Update current values
            $this->updateHoldingValues($holding);
            
            return $holding;
        });
    }

    public function sellAsset(User $user, Asset $asset, float $quantity, float $pricePerUnit): UserHolding
    {
        return DB::transaction(function () use ($user, $asset, $quantity, $pricePerUnit) {
            $holding = UserHolding::where('user_id', $user->id)
                ->where('asset_id', $asset->id)
                ->firstOrFail();
            
            if ($holding->quantity < $quantity) {
                throw new \Exception('Insufficient quantity to sell');
            }
            
            $totalAmount = $quantity * $pricePerUnit;
            
            // Add to user's holding balance
            $user->increment('holding_balance', $totalAmount);
            
            // Update holding
            $newQuantity = $holding->quantity - $quantity;
            
            if ($newQuantity > 0) {
                $holding->update(['quantity' => $newQuantity]);
            } else {
                $holding->delete();
            }
            
            // Create transaction record
            $holding->transactions()->create([
                'user_id' => $user->id,
                'asset_id' => $asset->id,
                'type' => 'sell',
                'quantity' => $quantity,
                'price_per_unit' => $pricePerUnit,
                'total_amount' => $totalAmount,
            ]);
            
            return $holding;
        });
    }
}
```

## 4. Controllers

### Holding Controller
```bash
php artisan make:controller HoldingController
```

```php
// app/Http/Controllers/HoldingController.php
<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\UserHolding;
use App\Services\PortfolioCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HoldingController extends Controller
{
    private $portfolioService;

    public function __construct(PortfolioCalculationService $portfolioService)
    {
        $this->portfolioService = $portfolioService;
    }

    public function index()
    {
        $user = Auth::user();
        $holdings = $user->holdings()->with('asset')->get();
        
        // Calculate portfolio summary
        $totalValue = $holdings->sum('current_value');
        $totalInvested = $holdings->sum('total_invested');
        $totalPnl = $totalValue - $totalInvested;
        $totalPnlPercentage = $totalInvested > 0 ? ($totalPnl / $totalInvested) * 100 : 0;
        
        // Update portfolio values
        $this->portfolioService->updateUserPortfolio($user);
        
        return view('dashboard.portfolio.holding', compact(
            'holdings', 'totalValue', 'totalInvested', 'totalPnl', 'totalPnlPercentage'
        ));
    }

    public function buy(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'quantity' => 'required|numeric|min:0.00000001',
            'price_per_unit' => 'required|numeric|min:0.00000001',
        ]);

        try {
            $asset = Asset::findOrFail($request->asset_id);
            $holding = $this->portfolioService->buyAsset(
                Auth::user(),
                $asset,
                $request->quantity,
                $request->price_per_unit
            );

            return response()->json([
                'success' => true,
                'message' => 'Asset purchased successfully',
                'holding' => $holding->load('asset')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function sell(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'quantity' => 'required|numeric|min:0.00000001',
            'price_per_unit' => 'required|numeric|min:0.00000001',
        ]);

        try {
            $asset = Asset::findOrFail($request->asset_id);
            $holding = $this->portfolioService->sellAsset(
                Auth::user(),
                $asset,
                $request->quantity,
                $request->price_per_unit
            );

            return response()->json([
                'success' => true,
                'message' => 'Asset sold successfully',
                'holding' => $holding->load('asset')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function searchAssets(Request $request)
    {
        $query = $request->get('q');
        
        $assets = Asset::where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('symbol', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get();

        return response()->json($assets);
    }
}
```

## 5. Routes

Add to `routes/web.php`:
```php
Route::middleware(['auth'])->group(function () {
    Route::get('/portfolio/holding', [HoldingController::class, 'index'])->name('user.portfolio.holding');
    Route::post('/portfolio/holding/buy', [HoldingController::class, 'buy'])->name('user.portfolio.buy');
    Route::post('/portfolio/holding/sell', [HoldingController::class, 'sell'])->name('user.portfolio.sell');
    Route::get('/portfolio/holding/search', [HoldingController::class, 'searchAssets'])->name('user.portfolio.search');
});
```

## 6. Commands

### Update Crypto Prices Command
```bash
php artisan make:command UpdateCryptoPrices
```

```php
// app/Console/Commands/UpdateCryptoPrices.php
<?php

namespace App\Console\Commands;

use App\Services\AssetPriceService;
use Illuminate\Console\Command;

class UpdateCryptoPrices extends Command
{
    protected $signature = 'assets:update-crypto';
    protected $description = 'Update cryptocurrency prices from CoinGecko API';

    public function handle(AssetPriceService $priceService)
    {
        $this->info('Updating cryptocurrency prices...');
        $priceService->updateCryptoPrices();
        $this->info('Cryptocurrency prices updated successfully!');
    }
}
```

### Update Stock Prices Command
```bash
php artisan make:command UpdateStockPrices
```

```php
// app/Console/Commands/UpdateStockPrices.php
<?php

namespace App\Console\Commands;

use App\Services\AssetPriceService;
use Illuminate\Console\Command;

class UpdateStockPrices extends Command
{
    protected $signature = 'assets:update-stocks';
    protected $description = 'Update stock prices from Alpha Vantage API';

    public function handle(AssetPriceService $priceService)
    {
        $this->info('Updating stock prices...');
        $priceService->updateStockPrices();
        $this->info('Stock prices updated successfully!');
    }
}
```

## 7. Configuration

### Services Configuration
Add to `config/services.php`:
```php
'alpha_vantage' => [
    'key' => env('ALPHA_VANTAGE_API_KEY'),
],
```

### Environment Variables
Add to `.env`:
```env
ALPHA_VANTAGE_API_KEY=your_api_key_here
```

## 8. Scheduled Tasks

Add to `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    // Update crypto prices every 5 minutes
    $schedule->command('assets:update-crypto')->everyFiveMinutes();
    
    // Update stock prices every 15 minutes (due to rate limits)
    $schedule->command('assets:update-stocks')->everyFifteenMinutes();
    
    // Update user portfolio values every 10 minutes
    $schedule->call(function () {
        $users = User::all();
        $portfolioService = app(PortfolioCalculationService::class);
        
        foreach ($users as $user) {
            $portfolioService->updateUserPortfolio($user);
        }
    })->everyTenMinutes();
}
```

## 9. Seeder for Initial Assets

```bash
php artisan make:seeder AssetSeeder
```

```php
// database/seeders/AssetSeeder.php
<?php

namespace Database\Seeders;

use App\Models\Asset;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    public function run()
    {
        // Popular cryptocurrencies
        $cryptoAssets = [
            ['symbol' => 'bitcoin', 'name' => 'Bitcoin', 'type' => 'crypto'],
            ['symbol' => 'ethereum', 'name' => 'Ethereum', 'type' => 'crypto'],
            ['symbol' => 'binancecoin', 'name' => 'BNB', 'type' => 'crypto'],
            ['symbol' => 'cardano', 'name' => 'Cardano', 'type' => 'crypto'],
            ['symbol' => 'solana', 'name' => 'Solana', 'type' => 'crypto'],
        ];

        // Popular stocks
        $stockAssets = [
            ['symbol' => 'AAPL', 'name' => 'Apple Inc.', 'type' => 'stock'],
            ['symbol' => 'MSFT', 'name' => 'Microsoft Corporation', 'type' => 'stock'],
            ['symbol' => 'GOOGL', 'name' => 'Alphabet Inc.', 'type' => 'stock'],
            ['symbol' => 'AMZN', 'name' => 'Amazon.com Inc.', 'type' => 'stock'],
            ['symbol' => 'TSLA', 'name' => 'Tesla Inc.', 'type' => 'stock'],
        ];

        foreach ($cryptoAssets as $asset) {
            Asset::create($asset);
        }

        foreach ($stockAssets as $asset) {
            Asset::create($asset);
        }
    }
}
```

Run the seeder:
```bash
php artisan db:seed --class=AssetSeeder
```

This detailed implementation provides all the necessary code to build a comprehensive holding system with real-time crypto and stock data integration.
