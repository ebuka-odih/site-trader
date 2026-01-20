<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BotTrading;
use App\Models\BotTrade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BotTradingController extends Controller
{
    /**
     * Display a listing of all bots
     */
    public function index()
    {
        $bots = BotTrading::with(['user', 'trades'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total_bots' => BotTrading::count(),
            'active_bots' => BotTrading::where('status', 'active')->count(),
            'paused_bots' => BotTrading::where('status', 'paused')->count(),
            'stopped_bots' => BotTrading::where('status', 'stopped')->count(),
            'total_profit' => BotTrading::sum('total_profit'),
            'total_invested' => BotTrading::sum('total_invested'),
            'total_trades' => BotTrade::count(),
            'profitable_trades' => BotTrade::where('profit_loss', '>', 0)->count(),
            'loss_trades' => BotTrade::where('profit_loss', '<', 0)->count(),
        ];

        return view('admin.bot-trading.index', compact('bots', 'stats'));
    }

    /**
     * Display the specified bot
     */
    public function show(BotTrading $bot)
    {
        // Add debugging
        \Log::info('Admin BotTrading show method called', [
            'bot_id' => $bot->id,
            'bot_name' => $bot->name,
            'user_id' => $bot->user_id,
            'bot_exists' => $bot->exists,
        ]);

        $bot->load(['user', 'trades' => function($query) {
            $query->latest()->limit(50);
        }]);

        // Check if user relationship loaded properly
        \Log::info('Bot loaded with relationships', [
            'user_loaded' => $bot->relationLoaded('user'),
            'user_exists' => $bot->user ? $bot->user->exists : false,
            'user_name' => $bot->user ? $bot->user->name : 'null',
        ]);

        $recentTrades = $bot->trades()->latest()->limit(20)->get();
        
        // Get current asset price
        $asset = \App\Models\Asset::where('symbol', $bot->base_asset)->first();
        
        // Calculate additional stats
        $stats = [
            'total_trades' => $bot->trades()->count(),
            'buy_trades' => $bot->trades()->where('type', 'buy')->count(),
            'sell_trades' => $bot->trades()->where('type', 'sell')->count(),
            'profitable_trades' => $bot->trades()->where('profit_loss', '>', 0)->count(),
            'loss_trades' => $bot->trades()->where('profit_loss', '<', 0)->count(),
            'avg_trade_amount' => $bot->trades()->avg('quote_amount'),
            'largest_profit' => $bot->trades()->max('profit_loss'),
            'largest_loss' => $bot->trades()->min('profit_loss'),
        ];

        return view('admin.bot-trading.show', compact('bot', 'recentTrades', 'asset', 'stats'));
    }

    /**
     * Show the form for editing the specified bot
     */
    public function edit(BotTrading $bot)
    {
        $bot->load('user');
        return view('admin.bot-trading.edit', compact('bot'));
    }

    /**
     * Update the specified bot
     */
    public function update(Request $request, BotTrading $bot)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'strategy' => 'required|string|max:255',
            'base_asset' => 'required|string|max:10',
            'quote_asset' => 'required|string|max:10',
            'leverage' => 'required|numeric|min:1.00|max:100.00',
            'trade_duration' => 'nullable|string|max:10',
            'target_yield_percentage' => 'nullable|numeric|min:0.1|max:100',
            'auto_close' => 'nullable',
            'max_investment' => 'required|numeric|min:10|max:1000000',
            'min_trade_amount' => 'required|numeric|min:1',
            'max_trade_amount' => 'required|numeric|min:1|gte:min_trade_amount',
            'daily_loss_limit' => 'nullable|numeric|min:1',
            'trading_24_7' => 'nullable',
            'auto_restart' => 'nullable',
            'participants_count' => 'nullable|integer|min:0|max:1000000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $bot->update([
                'name' => $request->name,
                'strategy' => $request->strategy,
                'base_asset' => $request->base_asset,
                'quote_asset' => $request->quote_asset,
                'leverage' => $request->leverage,
                'trade_duration' => $request->trade_duration,
                'target_yield_percentage' => $request->target_yield_percentage,
                'auto_close' => $request->boolean('auto_close', true),
                'max_investment' => $request->max_investment,
                'min_trade_amount' => $request->min_trade_amount,
                'max_trade_amount' => $request->max_trade_amount,
                'daily_loss_limit' => $request->daily_loss_limit,
                'trading_24_7' => $request->boolean('trading_24_7', true),
                'auto_restart' => $request->boolean('auto_restart', false),
                'participants_count' => $request->input('participants_count', $bot->participants_count),
            ]);

            Log::info("Admin updated bot {$bot->id} by user " . auth()->id());

            return redirect()->route('admin.bot-trading.show', $bot)
                ->with('success', 'Bot updated successfully!');
        } catch (\Exception $e) {
            Log::error("Admin bot update failed: " . $e->getMessage());
            return back()->with('error', 'Failed to update bot: ' . $e->getMessage());
        }
    }

    /**
     * Pause the specified bot
     */
    public function pause(BotTrading $bot)
    {
        try {
            // Check if bot is active
            if ($bot->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Bot must be active to pause'
                ], 400);
            }

            $bot->update([
                'status' => 'paused',
                'stopped_at' => now()
            ]);

            // Create notification for the user
            $user = $bot->user;
            $user->createNotification(
                'bot_paused',
                'Bot Paused',
                "Your bot '{$bot->name}' has been paused by an administrator. You can resume it later.",
                [
                    'bot_id' => $bot->id,
                    'bot_name' => $bot->name,
                    'action' => 'paused'
                ]
            );

            Log::info("Admin paused bot {$bot->id} by user " . auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Bot paused successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error("Admin bot pause failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to pause bot: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Resume the specified bot
     */
    public function resume(BotTrading $bot)
    {
        try {
            // Check if bot is paused
            if ($bot->status !== 'paused') {
                return response()->json([
                    'success' => false,
                    'message' => 'Bot must be paused to resume'
                ], 400);
            }

            $bot->update([
                'status' => 'active',
                'stopped_at' => null
            ]);

            // Create notification for the user
            $user = $bot->user;
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

            Log::info("Admin resumed bot {$bot->id} by user " . auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Bot resumed successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error("Admin bot resume failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to resume bot: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Stop the specified bot permanently
     */
    public function stop(BotTrading $bot)
    {
        try {
            // Check if bot is already stopped
            if ($bot->status === 'stopped') {
                return response()->json([
                    'success' => false,
                    'message' => 'Bot is already stopped'
                ], 400);
            }

            $profitTransferred = false;
            $profitAmount = 0;

            // Transfer profits to user's trading balance before stopping
            if ($bot->total_profit > 0) {
                $user = $bot->user;
                $profitAmount = $bot->total_profit;
                $user->increment('trading_balance', $profitAmount);
                $profitTransferred = true;
                
                Log::info("Bot profits transferred to user on permanent stop", [
                    'bot_id' => $bot->id,
                    'user_id' => $user->id,
                    'profit_transferred' => $profitAmount,
                    'new_trading_balance' => $user->fresh()->trading_balance
                ]);
            }

            $bot->update([
                'status' => 'stopped',
                'stopped_at' => now()
            ]);

            // Create notification for the user
            $user = $bot->user;
            $profitMessage = $profitTransferred ? 
                " Profits of $" . number_format($profitAmount, 2) . " have been transferred to your trading balance." : 
                "";
            
            $user->createNotification(
                'bot_stopped',
                'Bot Stopped Permanently',
                "Your bot '{$bot->name}' has been permanently stopped by an administrator.{$profitMessage}",
                [
                    'bot_id' => $bot->id,
                    'bot_name' => $bot->name,
                    'action' => 'stopped',
                    'profit_transferred' => $profitTransferred,
                    'profit_amount' => $profitAmount
                ]
            );

            Log::info("Admin permanently stopped bot {$bot->id} by user " . auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Bot stopped permanently!' . ($profitTransferred ? ' Profits have been transferred to your trading balance.' : ''),
                'profit_transferred' => $profitTransferred,
                'profit_amount' => $profitAmount
            ]);
        } catch (\Exception $e) {
            Log::error("Admin bot stop failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to stop bot: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Execute the specified bot manually
     */
    public function execute(BotTrading $bot)
    {
        try {
            // Check if bot is active
            if ($bot->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Bot must be active to execute manually'
                ], 400);
            }

            // Import the bot trading engine
            $engine = new \App\Services\SimpleBotTradingEngine();
            
            // Execute the bot
            $result = $engine->executeBot($bot);

            Log::info("Admin manually executed bot {$bot->id} by user " . auth()->id(), [
                'result' => $result
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bot executed successfully!',
                'result' => $result
            ]);
        } catch (\Exception $e) {
            Log::error("Admin bot execution failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to execute bot: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Edit PnL for a specific bot
     */
    public function editPnl(Request $request, BotTrading $bot)
    {
        $validator = Validator::make($request->all(), [
            'total_profit' => 'required|numeric',
            'total_invested' => 'required|numeric|min:0',
            'successful_trades' => 'required|integer|min:0',
            'success_rate' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $bot->update([
                'total_profit' => $request->total_profit,
                'total_invested' => $request->total_invested,
                'successful_trades' => $request->successful_trades,
                'success_rate' => $request->success_rate,
            ]);

            Log::info("Admin updated PnL for bot {$bot->id} by user " . auth()->id(), [
                'old_profit' => $bot->getOriginal('total_profit'),
                'new_profit' => $request->total_profit,
                'old_invested' => $bot->getOriginal('total_invested'),
                'new_invested' => $request->total_invested,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'PnL updated successfully!',
                'bot' => $bot->fresh()
            ]);
        } catch (\Exception $e) {
            Log::error("Admin PnL update failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update PnL: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Edit PnL for a specific trade
     */
    public function editTradePnl(Request $request, BotTrade $trade)
    {
        $validator = Validator::make($request->all(), [
            'profit_loss' => 'required|numeric',
            'profit_loss_percentage' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $oldProfit = $trade->profit_loss;
            
            $trade->update([
                'profit_loss' => $request->profit_loss,
                'profit_loss_percentage' => $request->profit_loss_percentage,
            ]);

            // Update bot's total profit
            $bot = $trade->botTrading;
            $profitDifference = $request->profit_loss - $oldProfit;
            $bot->increment('total_profit', $profitDifference);

            Log::info("Admin updated trade PnL for trade {$trade->id} by user " . auth()->id(), [
                'old_profit' => $oldProfit,
                'new_profit' => $request->profit_loss,
                'difference' => $profitDifference,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Trade PnL updated successfully!',
                'trade' => $trade->fresh(),
                'bot' => $bot->fresh()
            ]);
        } catch (\Exception $e) {
            Log::error("Admin trade PnL update failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update trade PnL: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified bot
     */
    public function destroy(BotTrading $bot)
    {
        try {
            // Delete all associated trades first
            $bot->trades()->delete();
            
            // Delete the bot
            $bot->delete();

            Log::info("Admin deleted bot {$bot->id} by user " . auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Bot deleted successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error("Admin bot deletion failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete bot: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new trade for the specified bot
     */
    public function storeTrade(Request $request, BotTrading $bot)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:buy,sell',
            'status' => 'required|in:pending,executed,cancelled,failed',
            'base_asset' => 'required|string|max:10',
            'quote_asset' => 'required|string|max:10',
            'base_amount' => 'required|numeric|min:0.00000001',
            'price' => 'required|numeric|min:0.01',
            'quote_amount' => 'required|numeric|min:0.01',
            'execution_type' => 'nullable|in:market,limit',
            'profit_loss' => 'nullable|numeric',
            'profit_loss_percentage' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Generate unique trade ID
            $tradeId = BotTrade::generateTradeId();

            // Create the trade
            $trade = BotTrade::create([
                'bot_trading_id' => $bot->id,
                'user_id' => $bot->user_id,
                'trade_id' => $tradeId,
                'type' => $request->type,
                'base_asset' => $request->base_asset,
                'quote_asset' => $request->quote_asset,
                'base_amount' => $request->base_amount,
                'quote_amount' => $request->quote_amount,
                'price' => $request->price,
                'status' => $request->status,
                'execution_type' => $request->execution_type ?? 'market',
                'profit_loss' => $request->profit_loss ?? 0,
                'profit_loss_percentage' => $request->profit_loss_percentage ?? 0,
                'executed_at' => $request->status === 'executed' ? now() : null,
            ]);

            // Update bot statistics if trade is executed
            if ($request->status === 'executed') {
                $bot->increment('total_trades');
                
                if ($request->profit_loss > 0) {
                    $bot->increment('successful_trades');
                }
                
                if ($request->profit_loss) {
                    $bot->increment('total_profit', $request->profit_loss);
                }
                
                // Recalculate success rate
                $totalTrades = $bot->total_trades;
                $successfulTrades = $bot->successful_trades;
                $bot->update([
                    'success_rate' => $totalTrades > 0 ? ($successfulTrades / $totalTrades) * 100 : 0,
                    'last_trade_at' => now()
                ]);

                // Create notification for the user
                $user = $bot->user;
                $profitLossText = $request->profit_loss > 0 ? 
                    "Profit: $" . number_format($request->profit_loss, 2) : 
                    ($request->profit_loss < 0 ? "Loss: $" . number_format(abs($request->profit_loss), 2) : "Break Even");
                
                $user->createNotification(
                    'bot_trade_executed',
                    'Bot Trade Executed',
                    "Your bot '{$bot->name}' executed a {$request->type} trade for {$request->base_amount} {$request->base_asset} at $" . number_format($request->price, 2) . ". {$profitLossText}.",
                    [
                        'bot_id' => $bot->id,
                        'bot_name' => $bot->name,
                        'trade_id' => $trade->id,
                        'trade_type' => $request->type,
                        'base_asset' => $request->base_asset,
                        'quote_asset' => $request->quote_asset,
                        'base_amount' => $request->base_amount,
                        'price' => $request->price,
                        'profit_loss' => $request->profit_loss,
                        'execution_type' => $request->execution_type
                    ]
                );
            }

            Log::info("Admin created trade for bot {$bot->id} by user " . auth()->id(), [
                'trade_id' => $trade->id,
                'trade_type' => $trade->type,
                'trade_amount' => $trade->quote_amount,
                'trade_profit' => $trade->profit_loss,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Trade created successfully!',
                'trade' => $trade,
                'bot' => $bot->fresh()
            ]);
        } catch (\Exception $e) {
            Log::error("Admin trade creation failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create trade: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get bot statistics for dashboard
     */
    public function stats()
    {
        $stats = [
            'total_bots' => BotTrading::count(),
            'active_bots' => BotTrading::where('status', 'active')->count(),
            'paused_bots' => BotTrading::where('status', 'paused')->count(),
            'stopped_bots' => BotTrading::where('status', 'stopped')->count(),
            'total_profit' => BotTrading::sum('total_profit'),
            'total_invested' => BotTrading::sum('total_invested'),
            'total_trades' => BotTrade::count(),
            'profitable_trades' => BotTrade::where('profit_loss', '>', 0)->count(),
            'loss_trades' => BotTrade::where('profit_loss', '<', 0)->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Start a stopped bot (Admin only)
     */
    public function start(BotTrading $bot)
    {
        if ($bot->isStopped()) {
            return response()->json([
                'success' => false,
                'message' => 'Bot is permanently stopped and cannot be restarted.'
            ], 400);
        }

        if ($bot->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'Bot is already active.'
            ], 400);
        }

        $bot->update([
            'status' => 'active',
            'stopped_at' => null
        ]);

        // Create notification for the user
        $user = $bot->user;
        $user->createNotification(
            'bot_started',
            'Bot Started by Admin',
            "Your bot '{$bot->name}' has been started by an administrator and is now active.",
            [
                'bot_id' => $bot->id,
                'bot_name' => $bot->name,
                'action' => 'started',
                'started_by' => 'admin'
            ]
        );

        \Log::info("Admin started bot {$bot->id} for user {$user->id}");

        return response()->json([
            'success' => true,
            'message' => 'Bot started successfully!'
        ]);
    }
}
