<?php

namespace App\Http\Controllers;

use App\Models\BotTrading;
use App\Models\BotTrade;
use App\Models\BotTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BotTradingController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display the bot trading dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $bots = $user->botTradings()->with(['trades' => function($query) {
            $query->latest()->limit(10);
        }])->latest()->get();

        $totalBots = $bots->count();
        $activeBots = $bots->where('status', 'active')->count();
        $totalProfit = $bots->sum('total_profit');
        $totalTrades = $bots->sum('total_trades');

        return view('dashboard.bot-trading.index', compact('bots', 'totalBots', 'activeBots', 'totalProfit', 'totalTrades'));
    }

    /**
     * Show the form for creating a new bot
     */
    public function create()
    {
        $tradingPairs = $this->getAvailableTradingPairs();
        $strategies = $this->getAvailableStrategies();
        $templates = BotTemplate::where('is_active', true)->latest()->take(12)->get();
        
        return view('dashboard.bot-trading.create', compact('tradingPairs', 'strategies', 'templates'));
    }

    /**
     * Store a newly created bot
     */
    public function store(Request $request)
    {
        // Log the incoming request data for debugging
        \Log::info('Bot creation request data:', $request->all());
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'trading_type' => 'required|string|in:crypto,forex',
            'base_asset' => 'required|string|max:10',
            'quote_asset' => 'required|string|max:10',
            'leverage' => 'required|numeric|min:1.00|max:100.00',
            'trade_duration' => 'required|string|in:1h,4h,24h,1w,2w,1m,2m',
            'target_yield_percentage' => 'nullable|numeric|min:0.1|max:100',
            'auto_close' => 'nullable',
            'strategy' => ['required', Rule::in(['grid', 'dca', 'scalping', 'trend_following'])],
            'max_investment' => 'required|numeric|min:10|max:1000000',
            'min_trade_amount' => 'required|numeric|min:1',
            'max_trade_amount' => 'required|numeric|min:1|gte:min_trade_amount',
            'max_open_trades' => 'required|integer|min:1|max:50',
            'stop_loss_percentage' => 'nullable|numeric|min:0.1|max:50',
            'take_profit_percentage' => 'nullable|numeric|min:0.1|max:1000',
            'daily_loss_limit' => 'nullable|numeric|min:1',
            'trading_24_7' => 'nullable',
            'auto_restart' => 'nullable',
        ]);

        if ($validator->fails()) {
            \Log::error('Bot creation validation failed:', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed. Please check your input values.',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        
        // Check if user account is suspended
        if ($user->isSuspended()) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been suspended. Please contact support for assistance.'
            ], 403);
        }
        
        \Log::info('User trading balance:', ['trading_balance' => $user->trading_balance, 'requested_investment' => $request->max_investment]);
        
        // Check if user has sufficient trading balance
        if ($request->max_investment > $user->trading_balance) {
            \Log::warning('Insufficient trading balance for bot creation', [
                'user_trading_balance' => $user->trading_balance,
                'requested_investment' => $request->max_investment
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Insufficient trading balance. You need at least $' . number_format($request->max_investment, 2) . ' in your trading balance to create this bot.'
            ], 400);
        }

        try {
            // Create strategy configuration based on selected strategy
            $strategyConfig = $this->buildStrategyConfig($request);
            \Log::info('Strategy config created:', $strategyConfig);

            $bot = BotTrading::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'trading_type' => $request->trading_type,
                'base_asset' => strtoupper($request->base_asset),
                'quote_asset' => strtoupper($request->quote_asset),
                'leverage' => $request->leverage,
                'trade_duration' => $request->trade_duration,
                'target_yield_percentage' => $request->target_yield_percentage,
                'auto_close' => $request->boolean('auto_close', true),
                'strategy' => $request->strategy,
                'strategy_config' => $strategyConfig,
                'max_investment' => $request->max_investment,
                'min_trade_amount' => $request->min_trade_amount,
                'max_trade_amount' => $request->max_trade_amount,
                'max_open_trades' => $request->max_open_trades,
                'stop_loss_percentage' => $request->stop_loss_percentage,
                'take_profit_percentage' => $request->take_profit_percentage,
                'daily_loss_limit' => $request->daily_loss_limit,
                'trading_24_7' => $request->boolean('trading_24_7', true),
                'auto_restart' => $request->boolean('auto_restart', false),
                'status' => 'active', // Start active by default
            ]);

            \Log::info('Bot created successfully:', ['bot_id' => $bot->id, 'bot_name' => $bot->name]);

            // Create notification for the user
            $user->createNotification(
                'bot_created',
                'Bot Created Successfully',
                "Your bot '{$bot->name}' has been created successfully and is ready to start trading!",
                [
                    'bot_id' => $bot->id,
                    'bot_name' => $bot->name,
                    'trading_pair' => $bot->base_asset . '/' . $bot->quote_asset,
                    'strategy' => $bot->strategy,
                    'max_investment' => $bot->max_investment
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Bot created successfully!',
                'bot' => $bot
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating bot:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cloneTemplate(Request $request, BotTemplate $template)
    {
        $user = Auth::user();

        if (($template->max_investment ?? 0) > ($user->trading_balance ?? 0)) {
            return back()->with('error', 'Insufficient trading balance to copy this bot.');
        }

        try {
            $attributes = $template->toBotAttributes($user->id);
            $attributes['base_asset'] = strtoupper($attributes['base_asset']);
            $attributes['quote_asset'] = strtoupper($attributes['quote_asset']);

            $bot = BotTrading::create($attributes);

            $user->createNotification(
                'bot_cloned',
                'Bot Ready',
                "We set up {$template->name} for you. It is live and ready to trade.",
                [
                    'bot_template_id' => $template->id,
                    'bot_id' => $bot->id,
                ]
            );

            return redirect()->route('user.botTrading.show', $bot)->with('success', 'Bot created from template!');
        } catch (\Throwable $e) {
            \Log::error('Failed to clone bot template', [
                'template_id' => $template->id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Unable to copy template at the moment.');
        }
    }

    /**
     * Display the specified bot
     */
    public function show(BotTrading $bot)
    {
        if ($bot->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this bot.');
        }
        
        $bot->load(['trades' => function($query) {
            $query->latest()->limit(50);
        }]);
        
        $recentTrades = $bot->trades()->latest()->limit(10)->get();
        $tradeStats = $this->getTradeStats($bot);
        
        return view('dashboard.bot-trading.show', compact('bot', 'recentTrades', 'tradeStats'));
    }

    /**
     * Show the form for editing the specified bot
     */
    public function edit(BotTrading $bot)
    {
        if ($bot->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this bot.');
        }
        
        $tradingPairs = $this->getAvailableTradingPairs();
        $strategies = $this->getAvailableStrategies();
        
        return view('dashboard.bot-trading.edit', compact('bot', 'tradingPairs', 'strategies'));
    }

    /**
     * Update the specified bot
     */
    public function update(Request $request, BotTrading $bot)
    {
        if ($bot->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this bot.');
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'leverage' => 'required|numeric|min:1.00|max:100.00',
            'trade_duration' => 'required|string|in:1h,4h,24h,1w,2w,1m,2m',
            'target_yield_percentage' => 'nullable|numeric|min:0.1|max:100',
            'auto_close' => 'nullable',
            'max_investment' => 'required|numeric|min:10|max:1000000',
            'min_trade_amount' => 'required|numeric|min:1',
            'max_trade_amount' => 'required|numeric|min:1|gte:min_trade_amount',
            'max_open_trades' => 'required|integer|min:1|max:50',
            'stop_loss_percentage' => 'nullable|numeric|min:0.1|max:50',
            'take_profit_percentage' => 'nullable|numeric|min:0.1|max:1000',
            'daily_loss_limit' => 'nullable|numeric|min:1',
            'trading_24_7' => 'nullable',
            'auto_restart' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        
        // Check if user has sufficient balance for increased investment
        if ($request->max_investment > $bot->max_investment && 
            $request->max_investment > $user->balance) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient balance for increased investment amount.'
            ], 400);
        }

        // Update strategy configuration
        $strategyConfig = $this->updateStrategyConfig($bot, $request);

        $bot->update([
            'name' => $request->name,
            'leverage' => $request->leverage,
            'trade_duration' => $request->trade_duration,
            'target_yield_percentage' => $request->target_yield_percentage,
            'auto_close' => $request->boolean('auto_close', true),
            'strategy_config' => $strategyConfig,
            'max_investment' => $request->max_investment,
            'min_trade_amount' => $request->min_trade_amount,
            'max_trade_amount' => $request->max_trade_amount,
            'max_open_trades' => $request->max_open_trades,
            'stop_loss_percentage' => $request->stop_loss_percentage,
            'take_profit_percentage' => $request->take_profit_percentage,
            'daily_loss_limit' => $request->daily_loss_limit,
            'trading_24_7' => $request->boolean('trading_24_7', true),
            'auto_restart' => $request->boolean('auto_restart', false),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bot updated successfully!',
            'bot' => $bot
        ]);
    }

    /**
     * Start the bot
     */
    public function start(BotTrading $bot)
    {
        \Log::info('Bot start request:', ['bot_id' => $bot->id, 'user_id' => Auth::id()]);
        
        if ($bot->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this bot.');
        }
        
        if ($bot->isActive()) {
            \Log::warning('Bot start failed - already active:', ['bot_id' => $bot->id]);
            return response()->json([
                'success' => false,
                'message' => 'Bot is already running.'
            ], 400);
        }

        $user = Auth::user();
        \Log::info('Bot start balance check:', [
            'bot_investment' => $bot->max_investment,
            'user_trading_balance' => $user->trading_balance
        ]);
        
        if ($bot->max_investment > $user->trading_balance) {
            \Log::warning('Bot start failed - insufficient balance:', [
                'bot_id' => $bot->id,
                'required' => $bot->max_investment,
                'available' => $user->trading_balance
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Insufficient trading balance to start the bot.'
            ], 400);
        }

        try {
            $bot->update([
                'status' => 'active',
                'started_at' => now(),
                'stopped_at' => null,
            ]);

            \Log::info('Bot started successfully:', ['bot_id' => $bot->id]);

            return response()->json([
                'success' => true,
                'message' => 'Bot started successfully!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Bot start failed with exception:', [
                'bot_id' => $bot->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to start bot: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Pause the bot
     */
    public function pause(BotTrading $bot)
    {
        if ($bot->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this bot.');
        }
        
        if (!$bot->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'Bot is not currently running.'
            ], 400);
        }

        $bot->update([
            'status' => 'paused',
            'stopped_at' => now()
        ]);

        // Create notification for the user
        $user = Auth::user();
        $user->createNotification(
            'bot_paused',
            'Bot Paused',
            "Your bot '{$bot->name}' has been paused. You can resume it later.",
            [
                'bot_id' => $bot->id,
                'bot_name' => $bot->name,
                'action' => 'paused'
            ]
        );

        \Log::info("User paused bot {$bot->id} by user " . Auth::id());

        return response()->json([
            'success' => true,
            'message' => 'Bot paused successfully!'
        ]);
    }

    /**
     * Resume the bot
     */
    public function resume(BotTrading $bot)
    {
        if ($bot->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this bot.');
        }
        
        if (!$bot->isPaused()) {
            return response()->json([
                'success' => false,
                'message' => 'Bot is not currently paused.'
            ], 400);
        }

        $bot->update([
            'status' => 'active',
            'stopped_at' => null
        ]);

        // Create notification for the user
        $user = Auth::user();
        $user->createNotification(
            'bot_resumed',
            'Bot Resumed',
            "Your bot '{$bot->name}' has been resumed and is now active again.",
            [
                'bot_id' => $bot->id,
                'bot_name' => $bot->name,
                'action' => 'resumed'
            ]
        );

        \Log::info("User resumed bot {$bot->id} by user " . Auth::id());

        return response()->json([
            'success' => true,
            'message' => 'Bot resumed successfully!'
        ]);
    }

    /**
     * Stop the bot
     */
    public function stop(BotTrading $bot)
    {
        if ($bot->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this bot.');
        }
        
        if ($bot->isStopped()) {
            return response()->json([
                'success' => false,
                'message' => 'Bot is already stopped.'
            ], 400);
        }

        try {
            $profitTransferred = false;
            $profitAmount = 0;

            // Transfer profits to user's trading balance before stopping
            if ($bot->total_profit > 0) {
                $user = Auth::user();
                $profitAmount = $bot->total_profit;
                $user->increment('trading_balance', $profitAmount);
                $profitTransferred = true;
                
                \Log::info('Bot profits transferred to user on manual stop', [
                    'bot_id' => $bot->id,
                    'user_id' => $user->id,
                    'profit_transferred' => $profitAmount,
                    'new_trading_balance' => $user->fresh()->trading_balance
                ]);
            }

            $bot->update([
                'status' => 'stopped',
                'stopped_at' => now(),
            ]);

            // Create notification for the user
            $user = Auth::user();
            $profitMessage = $profitTransferred ? 
                " Profits of $" . number_format($profitAmount, 2) . " have been transferred to your trading balance." : 
                "";
            
            $user->createNotification(
                'bot_stopped',
                'Bot Stopped Permanently',
                "Your bot '{$bot->name}' has been permanently stopped.{$profitMessage}",
                [
                    'bot_id' => $bot->id,
                    'bot_name' => $bot->name,
                    'action' => 'stopped',
                    'profit_transferred' => $profitTransferred,
                    'profit_amount' => $profitAmount
                ]
            );

            \Log::info("User permanently stopped bot {$bot->id} by user " . Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Bot stopped successfully!' . ($profitTransferred ? ' Profits have been transferred to your trading balance.' : ''),
                'profit_transferred' => $profitTransferred,
                'profit_amount' => $profitAmount
            ]);
        } catch (\Exception $e) {
            \Log::error('Bot stop failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to stop bot: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete the bot
     */
    public function destroy(BotTrading $bot)
    {
        if ($bot->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this bot.');
        }
        
        if ($bot->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete an active bot. Please stop it first.'
            ], 400);
        }

        $bot->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bot deleted successfully!'
        ]);
    }

    /**
     * Get bot trades
     */
    public function trades(BotTrading $bot)
    {
        if ($bot->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this bot.');
        }
        
        $trades = $bot->trades()
            ->with('relatedTrade')
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'trades' => $trades
        ]);
    }

    /**
     * Get bot performance statistics
     */
    public function performance(BotTrading $bot)
    {
        if ($bot->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this bot.');
        }
        
        $stats = $this->getTradeStats($bot);
        
        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }


    /**
     * Manually trigger bot execution (for testing)
     */
    public function execute(BotTrading $bot)
    {
        if ($bot->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this bot.');
        }
        
        if ($bot->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Bot must be active to execute trades.'
            ], 400);
        }
        
        try {
            $engine = new \App\Services\SimpleBotTradingEngine();
            $trade = $engine->executeBot($bot);
            
            if ($trade) {
                return response()->json([
                    'success' => true,
                    'message' => 'Trade executed successfully!',
                    'trade' => $trade
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'No trade conditions met.',
                    'trade' => null
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to execute bot: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper methods
     */
    private function getAvailableTradingPairs(): array
    {
        return [
            'BTC/USDT' => ['base' => 'BTC', 'quote' => 'USDT'],
            'ETH/USDT' => ['base' => 'ETH', 'quote' => 'USDT'],
            'SOL/USDT' => ['base' => 'SOL', 'quote' => 'USDT'],
            'BNB/USDT' => ['base' => 'BNB', 'quote' => 'USDT'],
            'ADA/USDT' => ['base' => 'ADA', 'quote' => 'USDT'],
            'DOT/USDT' => ['base' => 'DOT', 'quote' => 'USDT'],
            'LINK/USDT' => ['base' => 'LINK', 'quote' => 'USDT'],
            'UNI/USDT' => ['base' => 'UNI', 'quote' => 'USDT'],
        ];
    }

    private function getAvailableStrategies(): array
    {
        return [
            'grid' => [
                'name' => 'Grid Trading',
                'description' => 'Buy low, sell high in predefined price ranges',
                'config' => ['grid_levels', 'grid_spacing', 'grid_range']
            ],
            'dca' => [
                'name' => 'Dollar Cost Averaging',
                'description' => 'Regular purchases at fixed intervals',
                'config' => ['interval', 'amount', 'duration']
            ],
            'scalping' => [
                'name' => 'Scalping',
                'description' => 'Quick small profits from price movements',
                'config' => ['profit_target', 'stop_loss', 'timeframe']
            ],
            'trend_following' => [
                'name' => 'Trend Following',
                'description' => 'Follow market trends with technical indicators',
                'config' => ['indicators', 'signal_strength', 'timeframe']
            ],
        ];
    }

    private function buildStrategyConfig(Request $request): array
    {
        $config = [];
        
        switch ($request->strategy) {
            case 'grid':
                $config = [
                    'grid_levels' => $request->input('grid_levels', 10),
                    'grid_spacing' => $request->input('grid_spacing', 2.0),
                    'grid_range' => $request->input('grid_range', 20.0),
                ];
                break;
                
            case 'dca':
                $config = [
                    'interval' => $request->input('interval', '1h'),
                    'amount' => $request->input('dca_amount', 100),
                    'duration' => $request->input('duration', '7d'),
                ];
                break;
                
            case 'scalping':
                $config = [
                    'profit_target' => $request->input('profit_target', 0.5),
                    'stop_loss' => $request->input('scalping_stop_loss', 0.3),
                    'timeframe' => $request->input('timeframe', '5m'),
                ];
                break;
                
            case 'trend_following':
                $config = [
                    'indicators' => $request->input('indicators', ['sma', 'rsi']),
                    'signal_strength' => $request->input('signal_strength', 0.7),
                    'timeframe' => $request->input('timeframe', '1h'),
                ];
                break;
        }
        
        return $config;
    }

    private function updateStrategyConfig(BotTrading $bot, Request $request): array
    {
        $currentConfig = $bot->strategy_config ?? [];
        
        // If strategy is not in request, use the bot's current strategy
        if (!$request->has('strategy')) {
            $request->merge(['strategy' => $bot->strategy]);
        }
        
        $newConfig = $this->buildStrategyConfig($request);
        
        return array_merge($currentConfig, $newConfig);
    }

    private function getTradeStats(BotTrading $bot): array
    {
        $trades = $bot->trades()->executed();
        
        $totalTrades = $trades->count();
        $profitableTrades = $trades->profitable()->count();
        $lossTrades = $trades->loss()->count();
        
        $totalProfit = $trades->sum('profit_loss');
        $totalVolume = $trades->sum('quote_amount');
        
        $avgProfit = $totalTrades > 0 ? $totalProfit / $totalTrades : 0;
        $successRate = $totalTrades > 0 ? ($profitableTrades / $totalTrades) * 100 : 0;
        
        return [
            'total_trades' => $totalTrades,
            'profitable_trades' => $profitableTrades,
            'loss_trades' => $lossTrades,
            'total_profit' => $totalProfit,
            'total_volume' => $totalVolume,
            'avg_profit' => $avgProfit,
            'success_rate' => $successRate,
        ];
    }
}
