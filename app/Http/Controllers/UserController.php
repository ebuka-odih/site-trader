<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Trade;
use App\Models\LiveTrade;
use App\Models\TradePair;
use App\Models\User;
use App\Models\BotTrade;
use App\Models\UserNotification;
use App\Services\BalanceHistoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserController extends Controller
{
    protected BalanceHistoryService $balanceHistoryService;

    public function __construct(BalanceHistoryService $balanceHistoryService)
    {
        $this->balanceHistoryService = $balanceHistoryService;
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        // Get user's trades
        $trades = Trade::whereUserId($user->id)->latest()->get();
        $openTrades = $trades->filter(function($trade) {
            return $trade->status === 'open';
        });
        $closedTrades = $trades->filter(function($trade) {
            return $trade->status === 'closed';
        });
        
        // Get user's active subscriptions/plans
        $activePlans = $user->activeUserPlans()->with('plan')->get();
        $tradingPlans = $activePlans->filter(function($plan) {
            return $plan->plan && $plan->plan->type === 'trading';
        })->count();
        $signalPlans = $activePlans->filter(function($plan) {
            return $plan->plan && $plan->plan->type === 'signal';
        })->count();
        $stakingPlans = $activePlans->filter(function($plan) {
            return $plan->plan && $plan->plan->type === 'staking';
        })->count();
        $miningPlans = $activePlans->filter(function($plan) {
            return $plan->plan && $plan->plan->type === 'mining';
        })->count();
        
        // Get user's holdings data
        $holdings = $user->holdings()->with('asset')->get();
        $totalHoldingsValue = $holdings->sum('current_value');
        $totalInvestedInStocks = $holdings->sum('total_invested');
        $totalStockPnl = $holdings->sum('unrealized_pnl');
        $currentHoldingsValue = $holdings->sum('current_value');
        $investingChangePercent = $totalInvestedInStocks > 0
            ? (($currentHoldingsValue - $totalInvestedInStocks) / $totalInvestedInStocks) * 100
            : 0;
        $investingMetrics = $this->calculateInvestingMetrics($user, $holdings);
        $investingBalanceRaw = $investingMetrics['balance_raw'];
        $investingBalanceFormatted = $investingMetrics['balance_formatted'];
        $investingChangeText = $investingMetrics['change_text'];
        $investingIsPositive = $investingMetrics['is_positive'];
        
        // Get bot trading data
        $botTradings = $user->botTradings()->get();
        $activeBots = $botTradings->filter(function($bot) {
            return $bot->status === 'active';
        })->count();
        $totalBotProfit = $botTradings->sum('total_profit');
        
        // Calculate trading performance metrics
        $totalTrades = $trades->count();
        $winningTrades = $closedTrades->filter(function($trade) {
            return $trade->profit_loss > 0;
        })->count();
        $winRate = $totalTrades > 0 ? ($winningTrades / $totalTrades) * 100 : 0;
        $avgProfit = $closedTrades->count() > 0 ? $closedTrades->avg('profit_loss') : 0;
        
        // Get recent transactions for activity feed
        $recentTransactions = $user->holdingTransactions()
            ->with('asset')
            ->latest()
            ->take(5)
            ->get();
        
        // Get recent activity (deposits, withdrawals, trades)
        $recentDeposits = $user->deposits()
            ->where('status', 1) // Only approved deposits
            ->latest()
            ->take(3)
            ->get()
            ->map(function($deposit) use ($user) {
                return [
                    'type' => 'deposit',
                    'title' => 'Deposit',
                    'amount' => $deposit->amount,
                    'formatted_amount' => $user->formatAmount($deposit->amount),
                    'created_at' => $deposit->created_at,
                    'time_ago' => $deposit->created_at->diffForHumans(),
                ];
            });
        
        $recentWithdrawals = $user->withdrawals()
            ->where('status', 1) // Only approved withdrawals
            ->latest()
            ->take(3)
            ->get()
            ->map(function($withdrawal) use ($user) {
                return [
                    'type' => 'withdrawal',
                    'title' => 'Withdrawal',
                    'amount' => -$withdrawal->amount,
                    'formatted_amount' => $user->formatAmount($withdrawal->amount),
                    'created_at' => $withdrawal->created_at,
                    'time_ago' => $withdrawal->created_at->diffForHumans(),
                ];
            });
        
        $recentClosedTrades = $closedTrades
            ->take(3)
            ->map(function($trade) use ($user) {
                return [
                    'type' => $trade->profit_loss >= 0 ? 'trade_profit' : 'trade_loss',
                    'title' => $trade->profit_loss >= 0 ? 'Trade Profit' : 'Trade Loss',
                    'amount' => $trade->profit_loss,
                    'formatted_amount' => $user->formatAmount(abs($trade->profit_loss)),
                    'created_at' => $trade->updated_at,
                    'time_ago' => $trade->updated_at->diffForHumans(),
                ];
            });
        
        // Combine and sort all activities by date
        $recentActivity = collect()
            ->merge($recentDeposits)
            ->merge($recentWithdrawals)
            ->merge($recentClosedTrades)
            ->sortByDesc('created_at')
            ->take(5)
            ->values();
        
        // Get copy trading data
        $copyTrades = $user->copiedTrades()->get();
        $activeCopyTrades = $copyTrades->filter(function($copy) {
            return $copy->status == 1;
        })->count();
        
        // Get user's favorite stocks first, if any
        $favoriteStocks = $user->favorites()
            ->where('type', 'stock')
            ->orderByDesc('price_change_24h')
            ->get();

        if ($favoriteStocks->count() > 0) {
            // Use favorites if available
            $stockAssets = $favoriteStocks;
        } else {
            // Fall back to preferred stocks
            $topSymbols = [
                'SPY', 'AAPL', 'NVDA', 'AMZN', 'TSLA', 'MSFT',
                'META', 'GOOGL', 'NFLX', 'ADBE', 'PEP',
                'DIS', 'LLY', 'COST', 'BRK.A', 'JNJ'
            ];

            $preferredStocks = Asset::where('type', 'stock')
                ->whereIn('symbol', $topSymbols)
                ->get()
                ->sortBy(function ($asset) use ($topSymbols) {
                    return array_search($asset->symbol, $topSymbols);
                })
                ->values();

            if ($preferredStocks->count() < 12) {
                $additionalNeeded = 12 - $preferredStocks->count();
                $additionalStocks = Asset::where('type', 'stock')
                    ->whereNotIn('id', $preferredStocks->pluck('id'))
                    ->orderByDesc('price_change_24h')
                    ->take($additionalNeeded)
                    ->get();

                $stockAssets = $preferredStocks->merge($additionalStocks);
            } else {
                $stockAssets = $preferredStocks;
            }
        }
        $this->balanceHistoryService->recordSnapshots($user, [
            'investing' => $investingBalanceRaw,
            'pnl' => (float) ($user->profit ?? 0),
            'wallet' => (float) ($user->balance ?? 0),
            'trading' => (float) ($user->trading_balance ?? 0),
        ]);

        $pnlChangeData = $this->buildChangeSummary($user, 'pnl', (float) ($user->profit ?? 0));
        $walletChangeData = $this->buildChangeSummary($user, 'wallet', (float) ($user->balance ?? 0), 'Available to invest');

        $accountTabs = [
            [
                'id' => 'investing',
                'label' => 'Investing',
                'balance' => $investingBalanceFormatted,
                'change' => $investingChangeText,
                'isPositive' => $investingIsPositive,
                'raw_balance' => $investingBalanceRaw,
            ],
            [
                'id' => 'pnl',
                'label' => 'PNL',
                'balance' => $user->formatAmount($user->profit ?? 0),
                'change' => $pnlChangeData['text'],
                'isPositive' => $pnlChangeData['is_positive'],
                'raw_balance' => (float) ($user->profit ?? 0),
            ],
            [
                'id' => 'wallet',
                'label' => 'Wallet Balance',
                'balance' => $user->formatAmount($user->balance ?? 0),
                'change' => $walletChangeData['text'],
                'isPositive' => $walletChangeData['is_positive'],
                'raw_balance' => (float) ($user->balance ?? 0),
            ],
        ];
        // Get portfolio chart data from dedicated controller
        $portfolioChartController = app(\App\Http\Controllers\PortfolioChartController::class);
        $chartDataResponse = $portfolioChartController->getChartData(request());
        $chartDataArray = $chartDataResponse->getData(true);
        $portfolioChartData = $chartDataArray['data'] ?? [
            'wallet' => []
        ];

        $reactProps = $this->buildReactDashboardProps($user, [
            'accountTabs' => $accountTabs,
            'portfolioChartData' => $portfolioChartData,
            'stockAssets' => $stockAssets,
            'recentActivity' => $recentActivity,
            'openTrades' => $openTrades,
        ]);
        
        return view('dashboard.react', [
            'reactProps' => $reactProps,
        ]);
    }

    public function tradeHub()
    {
        $user = Auth::user();
        $topSymbols = [
            'SPY', 'AAPL', 'NVDA', 'AMZN', 'TSLA', 'MSFT',
            'META', 'GOOGL', 'NFLX', 'ADBE', 'PEP',
            'DIS', 'LLY', 'COST', 'BRK.A', 'JNJ'
        ];

        $preferredStocks = Asset::where('type', 'stock')
            ->whereIn('symbol', $topSymbols)
            ->get()
            ->sortBy(function ($asset) use ($topSymbols) {
                return array_search($asset->symbol, $topSymbols);
            })
            ->values();

        if ($preferredStocks->count() < 12) {
            $additionalNeeded = 12 - $preferredStocks->count();
            $additionalStocks = Asset::where('type', 'stock')
                ->whereNotIn('id', $preferredStocks->pluck('id'))
                ->orderByDesc('price_change_24h')
                ->take($additionalNeeded)
                ->get();

            $stockAssets = $preferredStocks->merge($additionalStocks);
        } else {
            $stockAssets = $preferredStocks->take(12);
        }

        $cryptoAssets = Asset::where('type', 'crypto')
            ->orderByDesc('market_cap')
            ->take(12)
            ->get();
        $tradeHistory = LiveTrade::where('user_id', $user->id)
            ->latest()
            ->take(6)
            ->get();
        $holdingsBySymbol = $user->holdings()
            ->with('asset')
            ->get()
            ->filter(fn ($holding) => $holding->asset && $holding->asset->symbol)
            ->mapWithKeys(function ($holding) {
                return [strtoupper($holding->asset->symbol) => $holding];
            });
        return view('dashboard.nav.trade', compact('user', 'stockAssets', 'cryptoAssets', 'tradeHistory', 'holdingsBySymbol'));
    }

    public function botTradingHub()
    {
        $user = Auth::user();
        $bots = $user->botTradings()
            ->with(['trades' => function ($query) {
                $query->latest()->limit(5);
            }])
            ->latest()
            ->get();

        $stats = [
            'total_bots' => $bots->count(),
            'active_bots' => $bots->where('status', 'active')->count(),
            'paused_bots' => $bots->where('status', 'paused')->count(),
            'stopped_bots' => $bots->where('status', 'stopped')->count(),
            'total_profit' => $bots->sum('total_profit'),
            'total_invested' => $bots->sum('total_invested'),
            'total_participants' => $bots->sum('participants_count'),
        ];

        $recentTrades = $bots->isEmpty()
            ? collect()
            : BotTrade::with('botTrading')
                ->whereIn('bot_trading_id', $bots->pluck('id'))
                ->latest()
                ->take(8)
                ->get();

        return view('dashboard.nav.bot-trading', compact('user', 'bots', 'stats', 'recentTrades'));
    }

    public function assetsDirectory(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');
        $type = $request->input('type', 'stock');
        if (!in_array($type, ['stock', 'crypto'])) {
            $type = 'stock';
        }

        $assets = Asset::where('type', $type)
            ->when($search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('symbol', 'like', '%' . $search . '%')
                      ->orWhere('name', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('market_cap')
            ->orderByDesc('price_change_24h')
            ->paginate(50)
            ->appends([
                'search' => $search,
                'type' => $type,
            ]);

        // Get user's favorite asset IDs
        $favoriteIds = $user->favorites()->get()->pluck('id')->toArray();

        return view('dashboard.nav.assets', [
            'user' => $user,
            'assets' => $assets,
            'search' => $search,
            'type' => $type,
            'favoriteIds' => $favoriteIds,
        ]);
    }

    private function calculateInvestingMetrics(User $user, $holdings = null): array
    {
        $holdings = $holdings ?? $user->holdings()->with('asset')->get();
        $completedLiveTrades = LiveTrade::where('user_id', $user->id)
            ->whereIn('status', ['completed', 'filled', 'closed'])
            ->orderBy('created_at')
            ->get();

        $livePortfolio = [];
        foreach ($completedLiveTrades as $trade) {
            $symbol = strtoupper($trade->symbol);
            $quantity = (float) ($trade->quantity ?? 0);
            if ($quantity <= 0) {
                $priceForQuantity = (float) ($trade->price ?? $trade->entry_price ?? 0);
                if ($priceForQuantity > 0 && $trade->amount) {
                    $quantity = (float) $trade->amount / $priceForQuantity;
                }
            }
            if ($quantity <= 0) {
                continue;
            }
            $cost = (float) ($trade->amount ?? ($quantity * ($trade->price ?? $trade->entry_price ?? 0)));
            if (!isset($livePortfolio[$symbol])) {
                $livePortfolio[$symbol] = [
                    'symbol' => $symbol,
                    'quantity' => 0,
                    'cost' => 0,
                ];
            }
            if ($trade->side === 'buy') {
                $livePortfolio[$symbol]['quantity'] += $quantity;
                $livePortfolio[$symbol]['cost'] += $cost;
            } else {
                $existingQty = $livePortfolio[$symbol]['quantity'];
                if ($existingQty <= 0) {
                    continue;
                }
                $qtyToRemove = min($quantity, $existingQty);
                $avgCost = $livePortfolio[$symbol]['cost'] / max($existingQty, 0.0000001);
                $livePortfolio[$symbol]['quantity'] = max($existingQty - $qtyToRemove, 0);
                $livePortfolio[$symbol]['cost'] = max($livePortfolio[$symbol]['cost'] - ($avgCost * $qtyToRemove), 0);
            }
        }
        $livePortfolio = array_filter($livePortfolio, fn ($position) => $position['quantity'] > 0 && $position['cost'] > 0);

        if (!empty($livePortfolio)) {
            $symbols = array_keys($livePortfolio);
            $assetPrices = Asset::whereIn('symbol', $symbols)->get()->keyBy('symbol');
            $totalCostBasis = 0;
            $totalGain = 0;

            foreach ($livePortfolio as $symbol => &$position) {
                $assetPrice = optional($assetPrices->get($symbol))->current_price;
                $currentPrice = $assetPrice !== null
                    ? (float) $assetPrice
                    : ($position['quantity'] > 0 ? $position['cost'] / $position['quantity'] : 0);
                $position['current_value'] = $currentPrice * $position['quantity'];
                $position['gain'] = $position['current_value'] - $position['cost'];
                $position['gain_percent'] = $position['cost'] > 0
                    ? ($position['gain'] / $position['cost']) * 100
                    : 0;
                $totalCostBasis += $position['cost'];
                $totalGain += $position['gain'];
            }
            unset($position);

            $gainPercent = $totalCostBasis > 0 ? ($totalGain / $totalCostBasis) * 100 : 0;
            $investingBalanceRaw = $totalCostBasis;
            $investingBalanceFormatted = $user->formatAmount($investingBalanceRaw);
            $investingChangeText = sprintf('%s (%+.2f%%)', $user->formatAmount($totalGain), $gainPercent);
            $investingIsPositive = $totalGain >= 0;
        } else {
            $liveTradeInvested = LiveTrade::where('user_id', $user->id)
                ->where('side', 'buy')
                ->whereNotIn('status', ['cancelled'])
                ->sum('amount');
            $investingBalanceRaw = $liveTradeInvested;
            $investingBalanceFormatted = $user->formatAmount($investingBalanceRaw);
            $investingChangeText = $liveTradeInvested > 0 ? 'Total spent on orders' : 'No holdings yet';
            $investingIsPositive = true;
        }

        return [
            'holdings' => $holdings,
            'balance_raw' => $investingBalanceRaw,
            'balance_formatted' => $investingBalanceFormatted,
            'change_text' => $investingChangeText,
            'is_positive' => $investingIsPositive,
        ];
    }

    private function buildPortfolioChartData(User $user, string $type, float $currentBalance, float $volatilityBase = 1): array
    {
        $now = Carbon::now();
        $timeframes = [
            'LIVE' => $now->copy()->subDays(30),
            '1D' => $now->copy()->subDay(),
            '1W' => $now->copy()->subWeek(),
            '1M' => $now->copy()->subMonth(),
            '3M' => $now->copy()->subMonths(3),
            'YTD' => $now->copy()->startOfYear(),
            '1Y' => $now->copy()->subYear(),
            'All' => $now->copy()->subYears(5),
        ];

        $history = [];
        foreach ($timeframes as $range => $startDate) {
            $series = collect(
                $this->balanceHistoryService->getHistorySeries($user, $type, $startDate)
            );

            if ($series->count() >= 2) {
                $labels = $series->map(fn ($point) => $this->formatHistoryLabel(Carbon::parse($point['timestamp']), $range));
                $data = $series->map(fn ($point) => round($point['value'], 2));

                $minVal = $data->min();
                $maxVal = $data->max();
                $padding = max(($maxVal - $minVal) * 2, max($maxVal, $currentBalance) * 0.05);
                $rangeMin = max($minVal - $padding, 0);
                $rangeMax = $maxVal + $padding;

                $history[$range] = [
                    'labels' => $labels->toArray(),
                    'data' => $data->toArray(),
                    'raw' => true,
                    'range' => [
                        'min' => $rangeMin,
                        'max' => $rangeMax,
                    ],
                ];
            } else {
                $history[$range] = $this->fallbackChartDataset($range, $currentBalance, $volatilityBase);
            }
        }

        return $history;
    }

    private function generateTrendSeries(int $points, float $startValue, float $endValue, float $profit, string $seedKey): array
    {
        if ($points <= 1) {
            return [round($endValue, 2)];
        }

        $series = [];
        $delta = $endValue - $startValue;
        $baseValue = max($startValue, $endValue, 1);
        $amplitude = min(max(abs($delta) * 0.2, $baseValue * 0.01), $baseValue * 0.25);
        if (abs($delta) < 1 && abs($profit) < 1) {
            $amplitude = 0;
        }

        $seed = (crc32($seedKey) % 1000) / 1000;
        for ($i = 0; $i < $points; $i++) {
            $progress = $i / ($points - 1);
            $trend = $startValue + ($delta * $progress);
            $wave = $amplitude * sin(($progress + $seed) * M_PI * 1.5);
            $value = max($trend + $wave, 0);
            $series[] = round($value, 2);
        }

        return $series;
    }

    private function fallbackChartDataset(string $range, float $currentBalance, float $volatilityBase): array
    {
        $labelSets = [
            'LIVE' => [], // Will be generated dynamically
            '1D' => ['9a', '10a', '11a', '12p', '1p', '2p', '3p'],
            '1W' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
            '1M' => ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5'],
            '3M' => ['Month 1', 'Month 2', 'Month 3'],
            'YTD' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            '1Y' => ['Jan', 'Mar', 'May', 'Jul', 'Sep', 'Nov'],
            'All' => ['2019', '2020', '2021', '2022', '2023'],
        ];

        if ($range === 'LIVE') {
            $labels = [];
            for ($i = 30; $i >= 0; $i--) {
                $labels[] = now()->subDays($i)->format('M d');
            }
        } elseif ($range === 'YTD') {
            $labels = [];
            $start = now()->startOfYear();
            $months = $start->diffInMonths(now()) + 1;
            for ($i = 0; $i < $months; $i++) {
                $labels[] = $start->copy()->addMonths($i)->format('M');
            }
        } elseif ($range === '1W') {
            $labels = [];
            for ($i = 6; $i >= 0; $i--) {
                $labels[] = now()->subDays($i)->format('D');
            }
        } elseif ($range === '1D') {
            $labels = [
                now()->subDay()->format('M d'),
                now()->format('M d')
            ];
        } elseif ($range === '3M') {
            $labels = [];
            for ($i = 90; $i >= 0; $i--) {
                $labels[] = now()->subDays($i)->format('M d');
            }
        } elseif ($range === '1Y') {
            $labels = [];
            for ($i = 365; $i >= 0; $i--) {
                $labels[] = now()->subDays($i)->format('M d');
            }
        } else {
            $labels = $labelSets[$range] ?? $labelSets['1M'];
        }
        $points = max(count($labels), 2);
        $endValue = max($currentBalance, 0);
        $startValue = max($endValue - $volatilityBase, 0);
        $series = $this->generateTrendSeries($points, $startValue, $endValue, $volatilityBase, $range);

        return [
            'labels' => $labels,
            'data' => $series,
            'raw' => false,
            'range' => null,
        ];
    }

    private function formatHistoryLabel(Carbon $timestamp, string $range): string
    {
        return match ($range) {
            'LIVE' => $timestamp->format('M d'),
            '1D' => $timestamp->format('H:i'),
            '1W' => $timestamp->format('D'),
            '1M' => $timestamp->format('M d'),
            '3M' => $timestamp->format('M d'),
            'YTD' => $timestamp->format('M'),
            '1Y', 'All' => $timestamp->format('M Y'),
            default => $timestamp->format('M d'),
        };
    }

    private function buildChangeSummary(User $user, string $type, float $currentValue, string $defaultText = null): array
    {
        $snapshots = $this->balanceHistoryService
            ->getLatestSnapshots($user, $type, 2)
            ->values();

        if ($snapshots->isEmpty()) {
            return [
                'text' => $defaultText ?? $user->formatAmount(0),
                'is_positive' => true,
                'delta' => 0,
            ];
        }

        $currentSnapshot = $snapshots->first();
        $previousSnapshot = $snapshots->count() > 1 ? $snapshots->get(1) : null;

        $previousValue = $previousSnapshot
            ? (float) ($previousSnapshot->new_amount ?? $previousSnapshot->previous_amount ?? 0)
            : (float) ($currentSnapshot->previous_amount ?? 0);

        $delta = $currentValue - $previousValue;
        $percent = $previousValue != 0 ? ($delta / $previousValue) * 100 : ($delta == 0 ? 0 : 100);

        return [
            'text' => sprintf('%s (%+.2f%%)', $user->formatAmount($delta), $percent),
            'is_positive' => $delta >= 0,
            'delta' => $delta,
        ];
    }

    public function wallet()
    {
        $user = Auth::user();
        $holdings = $user->holdings()->with('asset')->get();
        $investingMetrics = $this->calculateInvestingMetrics($user, $holdings);
        $totalInvested = $investingMetrics['balance_raw'];
        $portfolioTransactions = $user->holdingTransactions()
            ->with('asset')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($transaction) {
                $isBuy = strtolower($transaction->type ?? '') === 'buy';
                $amount = (float) ($transaction->total_amount ?? 0);

                return [
                    'label' => ucfirst($transaction->type ?? 'Trade'),
                    'subtext' => $transaction->asset->symbol ?? 'Portfolio',
                    'amount' => $isBuy ? -abs($amount) : abs($amount),
                    'timestamp' => $transaction->created_at,
                ];
            });

        $depositTransactions = $user->deposits()
            ->with('payment_method')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($deposit) {
                $methodLabel = optional($deposit->payment_method)->name
                    ?? Str::title($deposit->wallet_type ?? 'Wallet');

                return [
                    'label' => 'Deposit',
                    'subtext' => $methodLabel,
                    'amount' => (float) $deposit->amount,
                    'timestamp' => $deposit->created_at,
                ];
            });

        $withdrawalTransactions = $user->withdrawals()
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($withdrawal) {
                return [
                    'label' => 'Withdrawal',
                    'subtext' => Str::title($withdrawal->payment_method ?? 'Wallet'),
                    'amount' => -(float) $withdrawal->amount,
                    'timestamp' => $withdrawal->created_at,
                ];
            });

        $recentMovements = collect()
            ->merge($portfolioTransactions)
            ->merge($depositTransactions)
            ->merge($withdrawalTransactions)
            ->sortByDesc('timestamp')
            ->take(6)
            ->values();

        return view('dashboard.nav.wallet', [
            'user' => $user,
            'recentMovements' => $recentMovements,
            'totalInvested' => $totalInvested,
        ]);
    }

    public function profileOverview()
    {
        $user = Auth::user();
        
        // Ensure user has a referral code
        if (!$user->referral_code) {
            $user->referral_code = $this->generateReferralCode();
            $user->save();
        }
        
        // Fetch recent notifications for the user
        $notifications = UserNotification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        $unreadCount = UserNotification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();
        
        return view('dashboard.nav.profile', compact('user', 'notifications', 'unreadCount'));
    }
    
    private function generateReferralCode(): string
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (User::where('referral_code', $code)->exists());
        
        return $code;
    }

    public function profile()
    {
        $user = Auth::user();
        return view('dashboard.profile', compact('user'));
    }

    public function updateProfile(Request $request, $id)
    {
        $validated = $request->validate([
           'name' => 'nullable|string|max:255',
           'phone' => 'nullable|string|max:20',
           'telegram' => 'nullable|string|max:255',
           'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        $user = User::findOrFail($id);
        
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            // Store new avatar
            $avatarPath = $request->file('avatar')->store('files', 'public');
            $validated['avatar'] = $avatarPath;
        }
        
        $user->update($validated);
        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string', 'min:8'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('status', 'Password updated successfully!');
    }

    public function loading()
    {
        return view('dashboard.loading');
    }

    private function buildReactDashboardProps(User $user, array $payload): array
    {
        $tabs = array_map(function ($tab) {
            return [
                'id' => $tab['id'],
                'label' => $tab['label'],
                'balance' => $tab['balance'],
                'change' => $tab['change'],
                'isPositive' => (bool) ($tab['isPositive'] ?? false),
                'raw_balance' => $tab['raw_balance'] ?? 0,
            ];
        }, $payload['accountTabs']);

        $watchlist = ($payload['stockAssets'] ?? collect())
            ->take(5)
            ->map(function ($asset) {
                $price = round($asset->current_price ?? 0, 2);
                $change = round($asset->price_change_24h ?? 0, 2);
                $changePercent = $price > 0 ? round(($change / ($price - $change)) * 100, 2) : 0;
                
                return [
                    'symbol' => $asset->symbol,
                    'name' => $asset->name,
                    'price' => $price,
                    'change' => $change,
                    'change_percentage' => $changePercent,
                ];
            })
            ->values()
            ->toArray();

        $activity = ($payload['recentActivity'] ?? collect())
            ->map(function ($entry) {
                return [
                    'title' => $entry['title'] ?? ucfirst($entry['type'] ?? 'activity'),
                    'amount' => $entry['amount'] ?? 0,
                    'time_ago' => $entry['time_ago'] ?? '',
                    'type' => $entry['type'] ?? 'activity',
                ];
            })
            ->values()
            ->toArray();

        $openTrades = ($payload['openTrades'] ?? collect())
            ->take(4)
            ->map(function ($trade) {
                return [
                    'symbol' => $trade->symbol ?? $trade->pair ?? '—',
                    'type' => strtoupper($trade->side ?? $trade->type ?? 'N/A'),
                    'amount' => (float) ($trade->amount ?? 0),
                    'pnl' => (float) ($trade->profit_loss ?? 0),
                    'created_at' => optional($trade->created_at)->diffForHumans() ?? '',
                ];
            })
            ->values()
            ->toArray();

        $news = [
            [
                'title' => 'Markets digest earnings-week volatility',
                'source' => 'CNBC',
                'time' => '2h ago',
            ],
            [
                'title' => 'First-ever 3x levered bitcoin funds launch in Europe',
                'source' => 'MarketWatch',
                'time' => '5h ago',
            ],
            [
                'title' => 'Global bond buyers eye U.S. debt ahead of FOMC minutes',
                'source' => 'Bloomberg',
                'time' => '8h ago',
            ],
        ];

        return [
            'user' => [
                'name' => $user->name,
                'greeting' => 'Welcome back, ' . $user->name . '!',
                'total_balance' => (float) (($user->balance ?? 0)
                    + ($user->trading_balance ?? 0)
                    + ($user->holding_balance ?? 0)
                    + ($user->staking_balance ?? 0)
                    + ($user->profit ?? 0)
                    + ($user->mining_balance ?? 0)),
                'wallet_balance' => (float) ($user->balance ?? 0),
                'pnl' => (float) ($user->profit ?? 0),
                'buying_power' => (float) ($user->trading_balance ?? 0),
            ],
            'accountTabs' => $tabs,
            'chartData' => $payload['portfolioChartData'],
            'watchlist' => $watchlist,
            'activity' => $activity,
            'openTrades' => $openTrades,
            'news' => $news,
            'routes' => [
                'trade' => route('user.nav.trade'),
            ],
        ];
    }
}
