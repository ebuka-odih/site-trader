<?php

namespace App\Http\Controllers;

use App\Mail\AssetPurchaseMail;
use App\Models\Asset;
use App\Models\UserHolding;
use App\Models\HoldingTransaction;
use App\Services\PortfolioCalculationService;
use App\Services\AssetPriceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class HoldingController extends Controller
{
    private $portfolioService;

    public function __construct(PortfolioCalculationService $portfolioService)
    {
        $this->portfolioService = $portfolioService;
    }

    public function index()
    {
        try {
            $user = Auth::user();
            
            // Get portfolio summary
            $portfolioSummary = $this->portfolioService->getPortfolioSummary($user);
            
            // Update portfolio values
            $this->portfolioService->updateUserPortfolio($user);
            
            return view('dashboard.portfolio.holding', $portfolioSummary);
        } catch (\Exception $e) {
            Log::error('Error in holding index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load portfolio data.');
        }
    }

    public function buyAssets()
    {
        return view('dashboard.portfolio.buy-assets');
    }

    public function assetChart($symbol)
    {
        // Get asset details
        $asset = Asset::where('symbol', strtoupper($symbol))->first();
        
        if (!$asset) {
            abort(404, 'Asset not found');
        }
        
        return view('dashboard.portfolio.asset-chart', compact('asset'));
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

            // Get the latest transaction for this purchase
            $transaction = HoldingTransaction::where('user_id', Auth::id())
                ->where('asset_id', $asset->id)
                ->where('type', 'buy')
                ->latest()
                ->first();

            // Get portfolio summary for this asset
            $portfolioSummary = null;
            if ($holding) {
                $portfolioSummary = [
                    'total_holdings' => $holding->quantity,
                    'average_buy_price' => $holding->average_buy_price,
                    'total_invested' => $holding->total_invested,
                    'current_value' => $holding->current_value,
                    'unrealized_pnl' => $holding->unrealized_pnl,
                    'unrealized_pnl_percentage' => $holding->unrealized_pnl_percentage,
                ];
            }

            // Send email notification
            if ($transaction) {
                try {
                    Mail::to(Auth::user()->email)->send(new AssetPurchaseMail($transaction, $portfolioSummary));
                } catch (\Exception $e) {
                    Log::error('Failed to send asset purchase email: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Asset purchased successfully!',
                'holding' => $holding->load('asset'),
                'new_balance' => Auth::user()->balance
            ]);
        } catch (\Exception $e) {
            Log::error('Buy asset error: ' . $e->getMessage());
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
                'message' => 'Asset sold successfully!',
                'holding' => $holding->load('asset'),
                'new_balance' => Auth::user()->holding_balance
            ]);
        } catch (\Exception $e) {
            Log::error('Sell asset error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function searchAssets(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2'
        ]);

        try {
            $query = $request->get('q');
            
            $assets = Asset::where('is_active', true)
                ->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('symbol', 'like', "%{$query}%");
                })
                ->limit(10)
                ->get(['id', 'symbol', 'name', 'type', 'current_price']);

            return response()->json($assets);
        } catch (\Exception $e) {
            Log::error('Asset search error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to search assets'
            ], 500);
        }
    }

    public function getHoldings()
    {
        try {
            $user = Auth::user();
            $holdings = $user->holdings()->with('asset')->get();
            
            // Update portfolio values
            $this->portfolioService->updateUserPortfolio($user);
            
            return response()->json([
                'success' => true,
                'holdings' => $holdings,
                'portfolio_summary' => $this->portfolioService->getPortfolioSummary($user)
            ]);
        } catch (\Exception $e) {
            Log::error('Get holdings error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load holdings'
            ], 500);
        }
    }

    public function getAssets(Request $request)
    {
        try {
            $type = $request->get('type', 'crypto');
            
            // For crypto assets, update prices first to ensure fresh data
            if ($type === 'crypto') {
                $assetPriceService = app(AssetPriceService::class);
                $assetPriceService->updateCryptoPrices();
            }
            
            $assets = Asset::where('is_active', true)
                ->where('type', $type)
                ->select([
                    'id', 
                    'symbol', 
                    'name', 
                    'type', 
                    'current_price',
                    'price_change_24h',
                    'price_change_percentage_24h'
                ])
                ->orderBy('symbol')
                ->get();

            return response()->json([
                'success' => true,
                'assets' => $assets
            ]);
        } catch (\Exception $e) {
            Log::error('Get assets error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load assets'
            ], 500);
        }
    }

    public function getTransactions(Request $request)
    {
        try {
            $user = Auth::user();
            $transactions = $user->holdingTransactions()
                ->with('asset')
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return response()->json([
                'success' => true,
                'transactions' => $transactions
            ]);
        } catch (\Exception $e) {
            Log::error('Get transactions error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load transactions'
            ], 500);
        }
    }

    public function getBalance(Request $request)
    {
        try {
            $user = Auth::user();
            
            return response()->json([
                'success' => true,
                'balance' => $user->balance
            ]);
        } catch (\Exception $e) {
            Log::error('Get balance error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load balance'
            ], 500);
        }
    }
}
