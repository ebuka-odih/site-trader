<?php

use App\Http\Controllers\BotTradingController;
use App\Http\Controllers\CopyTradingController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WithdrawalController;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UserPlanController;
use App\Http\Controllers\UserSignalController;
use App\Http\Controllers\SignalSubscriptionController;
use App\Http\Controllers\UserStakingController;
use App\Http\Controllers\UserMiningController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\HoldingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\LiveTradingController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\AiTraderController;
use App\Http\Controllers\ReferralController;
use Illuminate\Support\Facades\Route;

$enforceAuthLanding = function () {
    if (auth()->check()) {
        return redirect()->route('user.dashboard');
    }

    return redirect()->route('login');
};

// PWA Routes (must be before auth middleware)
Route::get('/manifest.json', [\App\Http\Controllers\PWAController::class, 'manifest'])->name('pwa.manifest');

Route::get('/', $enforceAuthLanding)->name('index');
Route::get('products', $enforceAuthLanding)->name('products');
Route::get('market', $enforceAuthLanding)->name('market');
Route::get('about', $enforceAuthLanding)->name('about');
Route::get('contact', $enforceAuthLanding)->name('contact');
Route::get('terms', $enforceAuthLanding)->name('terms');
Route::get('privacy', $enforceAuthLanding)->name('privacy');
Route::get('faq', $enforceAuthLanding)->name('faq');
Route::get('ref/{code}', [ReferralController::class, 'handle'])->name('referral.link');

// What we offer pages
Route::get('stocks', $enforceAuthLanding)->name('stocks');
Route::get('money-market-funds', $enforceAuthLanding)->name('money-market-funds');
Route::get('savings', $enforceAuthLanding)->name('savings');

// AI Trader Routes (Public)
Route::get('ai-traders', $enforceAuthLanding)->name('ai-traders.index');
Route::get('ai-traders/plan/{plan}', $enforceAuthLanding)->name('ai-traders.plan');
Route::get('ai-traders/trader/{trader}', $enforceAuthLanding)->name('ai-traders.trader');
Route::get('ai-traders/trader/{trader}/performance', $enforceAuthLanding)->name('ai-traders.performance');
Route::get('ai-traders/trader/{trader}/stats', $enforceAuthLanding)->name('ai-traders.stats');
Route::get('loading', $enforceAuthLanding)->name('loading');

// Email template preview route (for development/testing)
Route::view('email-preview', 'email-preview')->name('email.preview');

// Debug notification route (for cPanel testing)
Route::get('debug-notifications', function () {
    echo "<h2>Notification System Debug</h2>";
    
    try {
        
        // Test 1: Database Connection
        echo "<h3>1. Database Connection Test</h3>";
        $connection = DB::connection()->getPdo();
        echo "✅ Database connection successful<br>";
        
        // Test 2: Check if user_notifications table exists
        echo "<h3>2. Table Existence Test</h3>";
        $tableExists = DB::getSchemaBuilder()->hasTable('user_notifications');
        if ($tableExists) {
            echo "✅ user_notifications table exists<br>";
        } else {
            echo "❌ user_notifications table does NOT exist<br>";
            echo "Run: php artisan migrate<br>";
        }
        
        // Test 3: Check table structure
        if ($tableExists) {
            echo "<h3>3. Table Structure Test</h3>";
            $columns = DB::getSchemaBuilder()->getColumnListing('user_notifications');
            $requiredColumns = ['id', 'user_id', 'type', 'title', 'message', 'data', 'read_at', 'created_at', 'updated_at'];
            
            foreach ($requiredColumns as $column) {
                if (in_array($column, $columns)) {
                    echo "✅ Column '{$column}' exists<br>";
                } else {
                    echo "❌ Column '{$column}' is MISSING<br>";
                }
            }
        }
        
        // Test 4: Check if we have users
        echo "<h3>4. Users Test</h3>";
        $userCount = \App\Models\User::count();
        echo "Total users: {$userCount}<br>";
        
        if ($userCount > 0) {
            $testUser = \App\Models\User::first();
            echo "Test user ID: {$testUser->id}<br>";
            
            // Test 5: Try to create a notification
            echo "<h3>5. Notification Creation Test</h3>";
            try {
                $notification = $testUser->createNotification(
                    'test_notification',
                    'Test Notification',
                    'This is a test notification to verify the system works.',
                    ['test' => true]
                );
                
                if ($notification) {
                    echo "✅ Notification created successfully!<br>";
                    echo "Notification ID: {$notification->id}<br>";
                    
                    // Test 6: Check if notification was saved
                    $savedNotification = \App\Models\UserNotification::find($notification->id);
                    if ($savedNotification) {
                        echo "✅ Notification saved to database<br>";
                        echo "Title: {$savedNotification->title}<br>";
                        echo "Message: {$savedNotification->message}<br>";
                    } else {
                        echo "❌ Notification not found in database<br>";
                    }
                } else {
                    echo "❌ Failed to create notification<br>";
                }
            } catch (Exception $e) {
                echo "❌ Error creating notification: " . $e->getMessage() . "<br>";
                echo "Error details: " . $e->getFile() . " (Line: " . $e->getLine() . ")<br>";
                echo "<pre>" . $e->getTraceAsString() . "</pre>";
            }
        }
        
        // Test 7: Direct Database Insert Test
        echo "<h3>6. Direct Database Insert Test</h3>";
        try {
            $directInsertId = DB::table('user_notifications')->insertGetId([
                'user_id' => $testUser->id,
                'type' => 'test_direct',
                'title' => 'Direct Database Test',
                'message' => 'This notification was created directly in the database',
                'data' => json_encode(['test' => true]),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "✅ Direct database insert successful! ID: {$directInsertId}<br>";
        } catch (Exception $e) {
            echo "❌ Direct database insert failed: " . $e->getMessage() . "<br>";
        }
        
        // Test 8: Check existing notifications
        echo "<h3>7. Existing Notifications Test</h3>";
        $notificationCount = \App\Models\UserNotification::count();
        echo "Total notifications in database: {$notificationCount}<br>";
        
        if ($notificationCount > 0) {
            $recentNotifications = \App\Models\UserNotification::latest()->take(5)->get();
            echo "<h4>Recent Notifications:</h4>";
            foreach ($recentNotifications as $notif) {
                echo "- ID: {$notif->id}, Type: {$notif->type}, Title: {$notif->title}, Created: {$notif->created_at}<br>";
            }
        }
        
        // Test 8: Check Foreign Key Constraints
        echo "<h3>7. Foreign Key Constraints Test</h3>";
        try {
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'user_notifications' 
                AND COLUMN_NAME = 'user_id' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
            
            if (count($foreignKeys) > 0) {
                echo "Found foreign key constraints:<br>";
                foreach ($foreignKeys as $fk) {
                    echo "- Constraint Name: {$fk->CONSTRAINT_NAME}<br>";
                }
            } else {
                echo "No foreign key constraints found on user_id column<br>";
            }
        } catch (Exception $e) {
            echo "Error checking foreign keys: " . $e->getMessage() . "<br>";
        }
        
        // Test 9: Check Laravel logs
        echo "<h3>8. Log File Test</h3>";
        $logPath = storage_path('logs/laravel.log');
        if (file_exists($logPath)) {
            echo "✅ Log file exists: {$logPath}<br>";
            $logSize = filesize($logPath);
            echo "Log file size: " . number_format($logSize) . " bytes<br>";
            
            if ($logSize > 0) {
                $lastLines = file_get_contents($logPath);
                $lines = explode("\n", $lastLines);
                $recentLines = array_slice($lines, -10);
                echo "<h4>Recent Log Entries:</h4>";
                foreach ($recentLines as $line) {
                    if (trim($line)) {
                        echo htmlspecialchars($line) . "<br>";
                    }
                }
            }
        } else {
            echo "❌ Log file does not exist: {$logPath}<br>";
        }
        
    } catch (Exception $e) {
        echo "❌ Critical Error: " . $e->getMessage() . "<br>";
        echo "Stack trace: <pre>" . $e->getTraceAsString() . "</pre>";
    }
    
    echo "<h3>Environment Info</h3>";
    echo "PHP Version: " . PHP_VERSION . "<br>";
    echo "Laravel Version: " . app()->version() . "<br>";
    echo "Environment: " . app()->environment() . "<br>";
    echo "Debug Mode: " . (config('app.debug') ? 'ON' : 'OFF') . "<br>";
    echo "Database Driver: " . config('database.default') . "<br>";
    echo "Database Host: " . config('database.connections.mysql.host') . "<br>";
    echo "Database Name: " . config('database.connections.mysql.database') . "<br>";
    
})->name('debug.notifications');

Route::group(['middleware' => ['auth'], 'prefix' => 'user', 'as' => 'user.'], function(){
    Route::get('dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('trade-hub', [UserController::class, 'tradeHub'])->name('nav.trade');
    Route::get('assets-directory', [UserController::class, 'assetsDirectory'])->name('nav.assets');
    Route::get('wallet', [UserController::class, 'wallet'])->name('nav.wallet');
    Route::get('profile-overview', [UserController::class, 'profileOverview'])->name('nav.profile');
    Route::get('bot-trading-hub', [UserController::class, 'botTradingHub'])->name('nav.bot-trading');
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::post('update/profile/{id}', [UserController::class, 'updateProfile'])->name('updateProfile');
    Route::post('update/password/', [UserController::class, 'updatePassword'])->name('updatePassword');
    
    // Portfolio Chart API
    Route::get('portfolio-chart/data', [\App\Http\Controllers\PortfolioChartController::class, 'getChartData'])->name('portfolioChart.data');

    // Favorite routes
    Route::post('favorites/{assetId}/toggle', [\App\Http\Controllers\FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('favorites', [\App\Http\Controllers\FavoriteController::class, 'index'])->name('favorites.index');

    // Notification routes
    Route::get('notifications', [\App\Http\Controllers\User\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/mark-all-read', [\App\Http\Controllers\User\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::delete('notifications/clear-all', [\App\Http\Controllers\User\NotificationController::class, 'clearAll'])->name('notifications.clearAll');
    Route::get('notifications/unread-count', [\App\Http\Controllers\User\NotificationController::class, 'getUnreadCount'])->name('notifications.unreadCount');
    Route::get('notifications/recent', [\App\Http\Controllers\User\NotificationController::class, 'getRecent'])->name('notifications.recent');
    Route::post('notifications/{id}/read', [\App\Http\Controllers\User\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::delete('notifications/{id}', [\App\Http\Controllers\User\NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Copy Trading Routes
    Route::get('copy-trading', [CopyTradingController::class, 'index'])->name('copyTrading.index');
    Route::post('store/copy-trading', [CopyTradingController::class, 'store'])->name('copyTrading.store');
    Route::get('copy-trading/{id}', [CopyTradingController::class, 'detail'])->name('copyTrading.detail');
    Route::post('copy-trading/{id}/stop', [CopyTradingController::class, 'stop'])->name('copyTrading.stop');
    Route::post('copy-trading/{id}/resume', [CopyTradingController::class, 'resume'])->name('copyTrading.resume');
    

    // Support routes
    Route::get('support', [\App\Http\Controllers\SupportController::class, 'index'])->name('support.index');

    Route::get('trade', [TradeController::class, 'index'])->name('trade.index');
    Route::get('trade/{id}', [TradeController::class, 'trade'])->name('trade');
    Route::post('place/buy/trade', [TradeController::class, 'placeBuyTrade'])->name('placeBuyTrade');
    Route::post('place/sell/trade', [TradeController::class, 'placeSellTrade'])->name('placeSellTrade');
    Route::get('close/trade/{id}', [TradeController::class, 'closeTrade'])->name('closeTrade');

    Route::get('deposit', [DepositController::class, 'deposit'])->name('deposit');
    Route::post('deposit/payment/', [DepositController::class, 'payment'])->name('payment');
    Route::post('deposit/cancel/{id}', [DepositController::class, 'cancelDeposit'])->name('deposit.cancel');
    Route::get('deposit/proof/{id}', [DepositController::class, 'viewProof'])->name('deposit.proof');
    Route::post('generate/qrcode', [DepositController::class, 'generateQRCode'])->name('generate.qrcode');

    Route::get('withdrawal/', [WithdrawalController::class, 'withdrawal'])->name('withdrawal');
    Route::post('store/withdrawal/', [WithdrawalController::class, 'withdrawalStore'])->name('withdrawalStore')->middleware('force.json');
    Route::get('store/withdrawal/', function() {
        return redirect()->route('user.withdrawal')->with('error', 'Invalid request method. Please use the withdrawal form.');
    })->name('withdrawalStore.get');
    Route::post('transfer-funds', [WithdrawalController::class, 'transferFunds'])->name('transfer.funds')->middleware('force.json');
    
    // Simple debug route
    Route::get('debug-simple', function() {
        return response()->json(['status' => 'working', 'time' => now()]);
    })->name('debug.simple');
    
    // Test route for debugging AJAX requests
    Route::post('test-ajax', function(Request $request) {
        \Log::info('Test AJAX request received', [
            'headers' => $request->headers->all(),
            'expects_json' => $request->expectsJson(),
            'ajax' => $request->ajax(),
            'data' => $request->all()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'AJAX test successful',
            'debug' => [
                'expects_json' => $request->expectsJson(),
                'ajax' => $request->ajax(),
                'accept_header' => $request->header('Accept'),
                'content_type' => $request->header('Content-Type')
            ]
        ]);
    })->name('test.ajax')->middleware('force.json');
    Route::get('withdrawal/history', [WithdrawalController::class, 'getWithdrawalHistory'])->name('withdrawal.history');
    Route::get('transfer/history', [WithdrawalController::class, 'getTransferHistory'])->name('transfer.history');
    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('kyc', [KycController::class, 'index'])->name('kyc.index');
    Route::post('kyc', [KycController::class, 'store'])->name('kyc.store');

    Route::post('activate/plan', [SubscriptionController::class, 'store'])->name('activatePlan');

    // Plan routes
    Route::get('plan/trading', [SubscriptionController::class, 'trading'])->name('plan.trading');
    Route::get('plan/signal', [SubscriptionController::class, 'signal'])->name('plan.signal');
    // Route::get('plan/mining', [SubscriptionController::class, 'mining'])->name('plan.mining'); // Removed - using new mining module
    // Route::get('plan/staking', [SubscriptionController::class, 'staking'])->name('plan.staking'); // Removed - using new staking module

    // User Plan Management Routes
    Route::get('plans', [UserPlanController::class, 'index'])->name('plans.index');
    Route::get('plans/create', [UserPlanController::class, 'create'])->name('plans.create');
    Route::post('plans', [UserPlanController::class, 'store'])->name('plans.store');
    Route::get('plans/history', [UserPlanController::class, 'history'])->name('plans.history');
    Route::get('plans/{userPlan}', [UserPlanController::class, 'show'])->name('plans.show');
    Route::get('plans/{userPlan}/edit', [UserPlanController::class, 'edit'])->name('plans.edit');
    Route::put('plans/{userPlan}', [UserPlanController::class, 'update'])->name('plans.update');
    Route::delete('plans/{userPlan}', [UserPlanController::class, 'destroy'])->name('plans.destroy');
    Route::post('plans/{userPlan}/cancel', [UserPlanController::class, 'cancel'])->name('plans.cancel');
    Route::post('plans/{userPlan}/reactivate', [UserPlanController::class, 'reactivate'])->name('plans.reactivate');
    Route::post('plans/subscribe/{plan}', [UserPlanController::class, 'subscribe'])->name('plans.subscribe');
    Route::get('my-plans', [UserPlanController::class, 'myPlans'])->name('plans.my-plans');

    // Signal Routes
    Route::get('signals', [UserSignalController::class, 'index'])->name('signals.index');
    Route::get('signals/{signal}', [UserSignalController::class, 'show'])->name('signals.show');
    Route::post('signals/{signal}/copy', [UserSignalController::class, 'copySignal'])->name('signals.copy');
    Route::get('signals/statistics', [UserSignalController::class, 'statistics'])->name('signals.statistics');

    // Signal Subscription Routes
    Route::get('signal-subscriptions', [SignalSubscriptionController::class, 'index'])->name('signal-subscriptions.index');
    Route::get('signal-subscriptions/create', [SignalSubscriptionController::class, 'create'])->name('signal-subscriptions.create');
    Route::post('signal-subscriptions', [SignalSubscriptionController::class, 'store'])->name('signal-subscriptions.store');
    Route::get('signal-subscriptions/{subscription}', [SignalSubscriptionController::class, 'show'])->name('signal-subscriptions.show');
    Route::get('signal-subscriptions/{subscription}/edit', [SignalSubscriptionController::class, 'edit'])->name('signal-subscriptions.edit');
    Route::put('signal-subscriptions/{subscription}', [SignalSubscriptionController::class, 'update'])->name('signal-subscriptions.update');
    Route::delete('signal-subscriptions/{subscription}', [SignalSubscriptionController::class, 'cancel'])->name('signal-subscriptions.cancel');
    Route::post('signal-subscriptions/{subscription}/renew', [SignalSubscriptionController::class, 'renew'])->name('signal-subscriptions.renew');
    Route::get('signal-subscriptions/active', [SignalSubscriptionController::class, 'active'])->name('signal-subscriptions.active');
    Route::get('signal-subscriptions/history', [SignalSubscriptionController::class, 'history'])->name('signal-subscriptions.history');

    // Staking Routes
    Route::get('staking', [UserStakingController::class, 'index'])->name('staking.index');
    Route::get('staking/create', [UserStakingController::class, 'create'])->name('staking.create');
    Route::post('staking', [UserStakingController::class, 'store'])->name('staking.store');
    Route::get('staking/{staking}', [UserStakingController::class, 'show'])->name('staking.show');
    Route::post('staking/{staking}/cancel', [UserStakingController::class, 'cancel'])->name('staking.cancel');
    Route::post('staking/{staking}/withdraw', [UserStakingController::class, 'withdraw'])->name('staking.withdraw');
    Route::get('staking/statistics', [UserStakingController::class, 'statistics'])->name('staking.statistics');

    // Mining Routes
    Route::get('mining', [UserMiningController::class, 'index'])->name('mining.index');
    Route::get('mining/create', [UserMiningController::class, 'create'])->name('mining.create');
    Route::post('mining', [UserMiningController::class, 'store'])->name('mining.store');
    Route::get('mining/{mining}', [UserMiningController::class, 'show'])->name('mining.show');
    Route::post('mining/{mining}/cancel', [UserMiningController::class, 'cancel'])->name('mining.cancel');
    Route::post('mining/{mining}/suspend', [UserMiningController::class, 'suspend'])->name('mining.suspend');
    Route::post('mining/{mining}/resume', [UserMiningController::class, 'resume'])->name('mining.resume');
    Route::post('mining/{mining}/withdraw', [UserMiningController::class, 'withdraw'])->name('mining.withdraw');
    Route::get('mining/statistics', [UserMiningController::class, 'statistics'])->name('mining.statistics');

    // Portfolio Routes
    Route::get('portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
    Route::get('portfolio/trade', [PortfolioController::class, 'index'])->name('portfolio.trade');
    Route::get('portfolio/staking', [PortfolioController::class, 'staking'])->name('portfolio.staking');
    Route::get('portfolio/mining', [PortfolioController::class, 'mining'])->name('portfolio.mining');
    Route::get('portfolio/holding', [HoldingController::class, 'index'])->name('portfolio.holding');
    Route::get('portfolio/signal', [PortfolioController::class, 'signal'])->name('portfolio.signal');

    // Holding Routes
    Route::get('holding', [HoldingController::class, 'index'])->name('holding.index');
    Route::get('holding/buy-assets', [HoldingController::class, 'buyAssets'])->name('holding.buy-assets');
    Route::get('holding/chart/{symbol}', [HoldingController::class, 'assetChart'])->name('holding.asset-chart');
    Route::post('holding/buy', [HoldingController::class, 'buy'])->name('holding.buy');
    Route::post('holding/sell', [HoldingController::class, 'sell'])->name('holding.sell');
    Route::get('holding/search', [HoldingController::class, 'searchAssets'])->name('holding.search');
    Route::get('holding/list', [HoldingController::class, 'getHoldings'])->name('holding.list');
    Route::get('holding/assets', [HoldingController::class, 'getAssets'])->name('holding.assets');
    Route::get('holding/transactions', [HoldingController::class, 'getTransactions'])->name('holding.transactions');
    Route::get('holding/balance', [HoldingController::class, 'getBalance'])->name('holding.balance');




    // Bot Trading Routes
    Route::get('bot-trading', [BotTradingController::class, 'index'])->name('botTrading.index');
    Route::get('bot-trading/create', [BotTradingController::class, 'create'])->name('botTrading.create');
    Route::post('bot-trading', [BotTradingController::class, 'store'])->name('botTrading.store');
    Route::post('bot-trading/templates/{template}/clone', [BotTradingController::class, 'cloneTemplate'])->name('botTrading.cloneTemplate');
    Route::get('bot-trading/{bot}', [BotTradingController::class, 'show'])->name('botTrading.show');
    Route::get('bot-trading/{bot}/edit', [BotTradingController::class, 'edit'])->name('botTrading.edit');
    Route::put('bot-trading/{bot}', [BotTradingController::class, 'update'])->name('botTrading.update');
    Route::delete('bot-trading/{bot}', [BotTradingController::class, 'destroy'])->name('botTrading.destroy');
    
    // Bot Control Routes
    Route::post('bot-trading/{bot}/start', [BotTradingController::class, 'start'])->name('botTrading.start');
    Route::post('bot-trading/{bot}/pause', [BotTradingController::class, 'pause'])->name('botTrading.pause');
    Route::post('bot-trading/{bot}/resume', [BotTradingController::class, 'resume'])->name('botTrading.resume');
    Route::post('bot-trading/{bot}/stop', [BotTradingController::class, 'stop'])->name('botTrading.stop');
    
    // Bot Data Routes
    Route::get('bot-trading/{bot}/trades', [BotTradingController::class, 'trades'])->name('botTrading.trades');
    Route::get('bot-trading/{bot}/performance', [BotTradingController::class, 'performance'])->name('botTrading.performance');
    Route::post('bot-trading/{bot}/execute', [BotTradingController::class, 'execute'])->name('botTrading.execute');

    // AI Trader Routes
    Route::get('ai-traders', [\App\Http\Controllers\User\AiTraderController::class, 'index'])->name('aiTraders.index');
    Route::get('ai-traders/plan/{plan}', [\App\Http\Controllers\User\AiTraderController::class, 'showPlan'])->name('aiTraders.plan');
    Route::get('ai-traders/trader/{trader}', [\App\Http\Controllers\User\AiTraderController::class, 'showTrader'])->name('aiTraders.trader');
    Route::get('ai-traders/trader/{trader}/performance', [\App\Http\Controllers\User\AiTraderController::class, 'getPerformanceData'])->name('aiTraders.performance');
    Route::get('ai-traders/trader/{trader}/stats', [\App\Http\Controllers\User\AiTraderController::class, 'getTraderStats'])->name('aiTraders.stats');
    Route::post('ai-traders/trader/{trader}/activate', [\App\Http\Controllers\User\AiTraderController::class, 'activateTrader'])->name('aiTraders.activate');
    Route::post('ai-traders/plan/{plan}/subscribe', [\App\Http\Controllers\User\AiTraderController::class, 'subscribeToPlan'])->name('aiTraders.subscribe');

    // Live Trading Routes
    Route::get("live-trading", [LiveTradingController::class, "index"])->name("liveTrading.index");
    Route::get("live-trading/trade", [LiveTradingController::class, "tradeNew"])->name("liveTrading.trade");
    Route::get("live-trading/trade-old", [LiveTradingController::class, "trade"])->name("liveTrading.tradeOld");
    Route::get("advanced-trading/trade", [LiveTradingController::class, "advancedTrade"])->name("advancedTrading.trade");
    Route::post("live-trading", [LiveTradingController::class, "store"])->name("liveTrading.store");
    Route::post("live-trading/{liveTrade}/cancel", [LiveTradingController::class, "cancel"])->name("liveTrading.cancel");
    Route::get("live-trading/price", [LiveTradingController::class, "getPrice"])->name("liveTrading.price");
    Route::post("live-trading/refresh-prices", [LiveTradingController::class, "refreshPrices"])->name("liveTrading.refreshPrices");
    Route::get("live-trading/history", [LiveTradingController::class, "history"])->name("liveTrading.history");

    // Overview Routes
    Route::get("overview", [OverviewController::class, "index"])->name("overview.index");

});

Route::get('/dashboard', function () {
    return redirect()->route('user.dashboard');
})->middleware(['auth'])->name('dashboard');

// Debug route to check profit balance
Route::get('/debug-profit', function() {
    $user = auth()->user();
    $freshUser = $user->fresh();
    
    return response()->json([
        'user_id' => $user->id,
        'cached_profit' => $user->profit,
        'fresh_profit' => $freshUser->profit,
        'raw_profit' => $freshUser->getRawOriginal('profit'),
        'database_profit' => \DB::table('users')->where('id', $user->id)->value('profit')
    ]);
})->middleware(['auth']);

    // Test email route
    Route::get('/test-email', function () {
        try {
            Mail::send('emails.verification', [
                'name' => 'Test User',
                'code' => '123456',
                'expires_at' => now()->addMinutes(10)->format('H:i'),
            ], function ($message) {
                $message->to('test@example.com')
                        ->subject('Test Verification Email');
            });
            return 'Email sent successfully!';
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    });

    // Test notification route
    Route::get('/test-notification', function () {
        if (!auth()->check()) {
            return 'Please login first';
        }
        
        $user = auth()->user();
        
        // Create a test notification
        \App\Models\UserNotification::create([
            'user_id' => $user->id,
            'type' => 'system',
            'title' => 'Test Notification',
            'message' => 'This is a test notification created at ' . now()->format('H:i:s') . ' for user: ' . $user->name,
            'data' => ['test' => true, 'timestamp' => now()->toISOString(), 'user_id' => $user->id]
        ]);
        
        return 'Test notification created successfully for ' . $user->name . '! Check your notification dropdown.';
    });

    // Test notification count route (no auth required for testing)
    Route::get('/test-notification-count', function () {
        $adminUserId = '916b7fbf-290b-4fb8-9383-bc7d159571b4';
        $count = \App\Models\UserNotification::where('user_id', $adminUserId)
            ->whereNull('read_at')
            ->count();
        
        return response()->json([
            'user_id' => $adminUserId,
            'unread_count' => $count,
            'total_notifications' => \App\Models\UserNotification::where('user_id', $adminUserId)->count()
        ]);
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Debug route for deposit issues (only in debug mode)
    if (config('app.debug')) {
        Route::get('/debug/deposit', [DepositController::class, 'debugDeposit'])->name('debug.deposit');
    }
});


require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
