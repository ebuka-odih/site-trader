@extends('dashboard.layout.app')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Bot Trading</h1>
            <p class="text-gray-400 mt-1">Automate your trading with intelligent bots</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button id="createBotBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                </svg>
                Create New Bot
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Bots -->
        <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Total Bots</p>
                    <p class="text-white text-xl font-bold">{{ $totalBots }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Bots -->
        <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Active Bots</p>
                    <p class="text-white text-xl font-bold">{{ $activeBots }}</p>
                </div>
                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Profit -->
        <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Total Profit</p>
                    <p class="text-white text-xl font-bold {{ $totalProfit >= 0 ? 'text-green-400' : 'text-red-400' }}">
                        ${{ number_format($totalProfit, 2) }}
                    </p>
                </div>
                <div class="w-10 h-10 {{ $totalProfit >= 0 ? 'bg-green-500' : 'bg-red-500' }} rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Trades -->
        <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Total Trades</p>
                    <p class="text-white text-xl font-bold">{{ $totalTrades }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Bots List -->
    <div class="bg-gray-800 rounded-lg border border-gray-700">
        <div class="p-6 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-white">Your Trading Bots</h2>
                <button onclick="refreshPage()" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1.5 rounded text-sm transition-colors">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>
        
        <div class="p-6">
            @if($bots->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">
                    @foreach($bots as $bot)
                        <div class="bg-gray-700 rounded-lg p-4 border border-gray-600 hover:border-gray-500 transition-colors">
                            <!-- Bot Header -->
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h3 class="text-white font-semibold">{{ $bot->name }}</h3>
                                    <p class="text-gray-400 text-sm">{{ $bot->trading_pair }}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($bot->started_at && $bot->isActive())
                                        <span class="px-2 py-1 bg-blue-600 text-blue-100 text-xs rounded-full">
                                            @php
                                                $duration = $bot->started_at->diff(now());
                                                if ($duration->days > 0) {
                                                    echo $duration->days . 'd';
                                                } elseif ($duration->h > 0) {
                                                    echo $duration->h . 'h';
                                                } else {
                                                    echo $duration->i . 'm';
                                                }
                                            @endphp
                                        </span>
                                    @endif
                                    @if($bot->isActive())
                                        <span class="px-2 py-1 bg-green-600 text-green-100 text-xs rounded-full">Active</span>
                                    @elseif($bot->isPaused())
                                        <span class="px-2 py-1 bg-yellow-600 text-yellow-100 text-xs rounded-full">Paused</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-600 text-gray-100 text-xs rounded-full">Stopped</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Bot Stats -->
                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-400">Strategy:</span>
                                    <span class="text-white">{{ ucfirst(str_replace('_', ' ', $bot->strategy)) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-400">Investment:</span>
                                    <span class="text-white">${{ number_format($bot->max_investment, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-400">Profit:</span>
                                    <span class="{{ $bot->total_profit >= 0 ? 'text-green-400' : 'text-red-400' }}">
                                        ${{ number_format($bot->total_profit, 2) }}
                                    </span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-400">Trades:</span>
                                    <span class="text-white">{{ $bot->total_trades }}</span>
                                </div>
                            </div>

                            <!-- Bot Actions -->
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('user.botTrading.show', $bot) }}" class="flex-1 bg-gray-600 hover:bg-gray-500 text-white text-center py-2 px-3 rounded text-sm transition-colors">
                                    View Details
                                </a>
                                
                                @if($bot->isActive())
                                    <button onclick="pauseBot({{ $bot->id }})" class="bg-yellow-600 hover:bg-yellow-700 text-white py-2 px-3 rounded text-sm transition-colors">
                                        Pause
                                    </button>
                                    <button onclick="stopBot({{ $bot->id }})" class="bg-red-600 hover:bg-red-700 text-white py-2 px-3 rounded text-sm transition-colors">
                                        Stop
                                    </button>
                                @elseif($bot->isPaused())
                                    <button onclick="startBot({{ $bot->id }})" class="bg-green-600 hover:bg-green-700 text-white py-2 px-3 rounded text-sm transition-colors">
                                        Resume
                                    </button>
                                    <button onclick="stopBot({{ $bot->id }})" class="bg-red-600 hover:bg-red-700 text-white py-2 px-3 rounded text-sm transition-colors">
                                        Stop
                                    </button>
                                @else
                                    <!-- Bot is stopped - no action buttons available -->
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-white mb-2">No bots created yet</h3>
                    <p class="text-gray-400 mb-6">Create your first trading bot to start automating your trades</p>
                    <button id="createFirstBotBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        Create Your First Bot
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Create Bot Modal -->
<div id="createBotModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full border border-gray-700 max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-700">
                <div>
                    <h3 class="text-xl font-semibold text-white">Create New Trading Bot</h3>
                    <p class="text-gray-400 text-sm mt-1">Configure your automated trading strategy</p>
                </div>
                <button id="closeCreateModal" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto flex-1">
                <!-- Trading Type Tabs -->
                <div class="mb-6">
                    <div class="border-b border-gray-700">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <button type="button" class="trading-tab active border-b-2 border-blue-500 py-2 px-1 text-sm font-medium text-blue-400" data-tab="crypto">
                                <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                Crypto Trading
                            </button>
                            <button type="button" class="trading-tab border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-400 hover:text-gray-300 hover:border-gray-300" data-tab="forex">
                                <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Forex Trading
                            </button>
                        </nav>
                    </div>
                </div>

                <form id="createBotForm" class="space-y-6">
                    @csrf
                    
                    <!-- Hidden field for trading type -->
                    <input type="hidden" name="trading_type" value="crypto">
                    
                    <!-- Basic Information -->
                    <div>
                        <h4 class="text-lg font-medium text-white mb-4">Basic Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Bot Name</label>
                                <input type="text" name="name" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="My BTC Bot">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Trading Pair</label>
                                <!-- Crypto Trading Pairs -->
                                <select name="trading_pair" class="crypto-trading-pairs w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Select Trading Pair</option>
                                    <option value="BTC/USDT">BTC/USDT</option>
                                    <option value="ETH/USDT">ETH/USDT</option>
                                    <option value="SOL/USDT">SOL/USDT</option>
                                    <option value="BNB/USDT">BNB/USDT</option>
                                    <option value="ADA/USDT">ADA/USDT</option>
                                    <option value="DOT/USDT">DOT/USDT</option>
                                    <option value="LINK/USDT">LINK/USDT</option>
                                    <option value="UNI/USDT">UNI/USDT</option>
                                </select>
                                
                                <!-- Forex Trading Pairs -->
                                <select name="trading_pair" class="forex-trading-pairs hidden w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Select Trading Pair</option>
                                    <option value="EUR/USD">EUR/USD</option>
                                    <option value="GBP/USD">GBP/USD</option>
                                    <option value="USD/JPY">USD/JPY</option>
                                    <option value="USD/CHF">USD/CHF</option>
                                    <option value="AUD/USD">AUD/USD</option>
                                    <option value="USD/CAD">USD/CAD</option>
                                    <option value="NZD/USD">NZD/USD</option>
                                    <option value="EUR/GBP">EUR/GBP</option>
                                    <option value="EUR/JPY">EUR/JPY</option>
                                    <option value="GBP/JPY">GBP/JPY</option>
                                </select>
                            </div>
                        </div>
                    </div>



                    <!-- Strategy Selection -->
                    <div>
                        <h4 class="text-lg font-medium text-white mb-4">Trading Strategy</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="strategy-option border border-gray-600 rounded-lg p-4 cursor-pointer hover:border-blue-500 transition-colors" data-strategy="grid">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" name="strategy" value="grid" class="text-blue-600">
                                    <div>
                                        <h5 class="text-white font-medium">Grid Trading</h5>
                                        <p class="text-gray-400 text-sm">Buy low, sell high in predefined ranges</p>
                                    </div>
                                </div>
                            </div>
                            <div class="strategy-option border border-gray-600 rounded-lg p-4 cursor-pointer hover:border-blue-500 transition-colors" data-strategy="dca">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" name="strategy" value="dca" class="text-blue-600">
                                    <div>
                                        <h5 class="text-white font-medium">DCA</h5>
                                        <p class="text-gray-400 text-sm">Regular purchases at fixed intervals</p>
                                    </div>
                                </div>
                            </div>
                            <div class="strategy-option border border-gray-600 rounded-lg p-4 cursor-pointer hover:border-blue-500 transition-colors" data-strategy="scalping">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" name="strategy" value="scalping" class="text-blue-600">
                                    <div>
                                        <h5 class="text-white font-medium">Scalping</h5>
                                        <p class="text-gray-400 text-sm">Quick small profits from movements</p>
                                    </div>
                                </div>
                            </div>
                            <div class="strategy-option border border-gray-600 rounded-lg p-4 cursor-pointer hover:border-blue-500 transition-colors" data-strategy="trend_following">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" name="strategy" value="trend_following" class="text-blue-600">
                                    <div>
                                        <h5 class="text-white font-medium">Trend Following</h5>
                                        <p class="text-gray-400 text-sm">Follow market trends with indicators</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Investment Settings -->
                    <div>
                        <h4 class="text-lg font-medium text-white mb-4">Investment Settings</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Max Investment ({{ auth()->user()->currency ?? 'USD' }})</label>
                                <input type="number" name="max_investment" step="0.01" min="10" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="1000.00">
                                <div class="mt-1 text-xs text-green-400">
                                    Trading Balance: {{ auth()->user()->formatAmount(auth()->user()->trading_balance) }}
                                </div>
                                <div class="mt-1 text-xs text-blue-400">
                                    💡 Total USD limit for this bot's trading activities.
                                </div>
                                <div class="mt-1 text-xs text-yellow-400">
                                    ⚠️ Must be greater than Max Trade Amount
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Min Trade Amount ({{ auth()->user()->currency ?? 'USD' }})</label>
                                <input type="number" name="min_trade_amount" step="0.01" min="1" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="10.00">
                                <div class="mt-1 text-xs text-blue-400">
                                    💡 Bot will buy/sell the equivalent value in the base currency.
                                </div>
                                <div class="mt-1 text-xs text-yellow-400">
                                    ⚠️ Must be less than Max Trade Amount
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Max Trade Amount ({{ auth()->user()->currency ?? 'USD' }})</label>
                                <input type="number" name="max_trade_amount" step="0.01" min="1" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="100.00">
                                <div class="mt-1 text-xs text-blue-400">
                                    💡 Bot will buy/sell the equivalent value in the base currency.
                                </div>
                                <div class="mt-1 text-xs text-yellow-400">
                                    ⚠️ Must be greater than Min Trade Amount
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Risk Management -->
                    <div>
                        <h4 class="text-lg font-medium text-white mb-4">Risk Management</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Leverage & Duration -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Leverage</label>
                                <select name="leverage" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="1.00">1x (No Leverage)</option>
                                    <option value="2.00">2x</option>
                                    <option value="5.00">5x</option>
                                    <option value="10.00">10x</option>
                                    <option value="20.00">20x</option>
                                    <option value="50.00">50x</option>
                                    <option value="100.00">100x</option>
                                </select>
                                <div class="mt-1 text-xs text-red-400">
                                    ⚠️ Higher leverage = Higher risk
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Trade Duration</label>
                                <select name="trade_duration" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="1h">1 Hour</option>
                                    <option value="4h">4 Hours</option>
                                    <option value="24h" selected>24 Hours</option>
                                    <option value="1w">1 Week</option>
                                    <option value="2w">2 Weeks</option>
                                    <option value="1m">1 Month</option>
                                    <option value="2m">2 Months</option>
                                </select>

                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Target Yield (%)</label>
                                <input type="number" name="target_yield_percentage" step="0.1" min="0.1" max="100" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="5.0">
                                <div class="mt-1 text-xs text-green-400">
                                    🎯 Bot will stop when this profit target is reached
                                </div>
                            </div>
                            
                            <!-- Stop Loss & Take Profit -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Stop Loss (%)</label>
                                <input type="number" name="stop_loss_percentage" step="0.1" min="0.1" max="50" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="5.0">
                                <div class="mt-1 text-xs text-red-400">
                                    🛑 Stop trading if loss exceeds this percentage
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Take Profit (%)</label>
                                <input type="number" name="take_profit_percentage" step="0.1" min="0.1" max="1000" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="10.0">
                                <div class="mt-1 text-xs text-green-400">
                                    ✅ Close trade when profit reaches this percentage
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Daily Loss Limit ({{ auth()->user()->currency ?? 'USD' }})</label>
                                <input type="number" name="daily_loss_limit" step="0.01" min="1" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="100.00">
                                <div class="mt-1 text-xs text-red-400">
                                    📊 Maximum daily loss before stopping
                                </div>
                            </div>
                            
                            <!-- Auto Settings -->
                            <div class="flex items-center">
                                <input type="checkbox" name="auto_close" checked class="text-blue-600 bg-gray-700 border-gray-600 rounded">
                                <span class="ml-2 text-sm text-gray-300">Auto-close trades at duration</span>
                            </div>
                        </div>
                    </div>

                    <!-- Trading Settings -->
                    <div>
                        <h4 class="text-lg font-medium text-white mb-4">Trading Settings</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Max Open Trades</label>
                                <input type="number" name="max_open_trades" min="1" max="50" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="5" oninput="if(this.value > 50) this.value = 50; if(this.value < 1) this.value = 1;">
                                <div class="mt-1 text-xs text-yellow-400">
                                    ⚠️ Maximum allowed: 50 trades
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="trading_24_7" checked class="text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-300">Trade 24/7</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="auto_restart" class="text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-300">Auto Restart</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-gray-700">
                <button id="cancelCreateBtn" class="px-4 py-2 border border-gray-600 rounded-lg text-gray-300 hover:bg-gray-700 transition-colors">
                    Cancel
                </button>
                <button id="submitCreateBtn" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                    Create Bot
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Modals -->
<div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-gray-800 rounded-lg shadow-xl max-w-md w-full border border-gray-700">
            <div class="p-6 text-center">
                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-white mb-2" id="successTitle">Success!</h3>
                <p class="text-gray-400 mb-6" id="successMessage">Operation completed successfully.</p>
                <button id="closeSuccessModal" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-medium transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<div id="errorModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-gray-800 rounded-lg shadow-xl max-w-md w-full border border-gray-700">
            <div class="p-6 text-center">
                <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-white mb-2" id="errorTitle">Error!</h3>
                <p class="text-gray-400 mb-6" id="errorMessage">An error occurred. Please try again.</p>
                <button id="closeErrorModal" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg font-medium transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .forex-trading-pairs.hidden {
        display: none !important;
    }
    
    /* Tab highlighting styles */
    .trading-tab {
        transition: all 0.2s ease-in-out;
    }
    
    .trading-tab.active {
        border-bottom-color: #3b82f6 !important;
        color: #60a5fa !important;
    }
    
    .trading-tab:not(.active) {
        border-bottom-color: transparent !important;
        color: #9ca3af !important;
    }
    
    .trading-tab:hover:not(.active) {
        color: #d1d5db !important;
        border-bottom-color: #d1d5db !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const createBotBtn = document.getElementById('createBotBtn');
    const createFirstBotBtn = document.getElementById('createFirstBotBtn');
    const createBotModal = document.getElementById('createBotModal');
    const closeCreateModal = document.getElementById('closeCreateModal');
    const cancelCreateBtn = document.getElementById('cancelCreateBtn');
    const createBotForm = document.getElementById('createBotForm');
    const submitCreateBtn = document.getElementById('submitCreateBtn');
    const successModal = document.getElementById('successModal');
    const errorModal = document.getElementById('errorModal');
    const closeSuccessModal = document.getElementById('closeSuccessModal');
    const closeErrorModal = document.getElementById('closeErrorModal');

    // Initialize tabs on page load
    initializeTabs();

    // Open create modal
    [createBotBtn, createFirstBotBtn].forEach(btn => {
        if (btn) {
            btn.addEventListener('click', () => {
                createBotModal.classList.remove('hidden');
                // Initialize tabs to default state
                initializeTabs();
            });
        }
    });

    // Close create modal
    [closeCreateModal, cancelCreateBtn].forEach(btn => {
        btn.addEventListener('click', () => {
            createBotModal.classList.add('hidden');
        });
    });

    // Close modals when clicking backdrop
    [createBotModal, successModal, errorModal].forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });

    // Close success/error modals
    [closeSuccessModal, closeErrorModal].forEach(btn => {
        btn.addEventListener('click', () => {
            successModal.classList.add('hidden');
            errorModal.classList.add('hidden');
        });
    });

    // Initialize tabs function
    function initializeTabs() {
        // Reset to crypto tab by default
        const cryptoTab = document.querySelector('[data-tab="crypto"]');
        const forexTab = document.querySelector('[data-tab="forex"]');
        const cryptoTradingPairs = document.querySelector('.crypto-trading-pairs');
        const forexTradingPairs = document.querySelector('.forex-trading-pairs');
        const botNameInput = document.querySelector('input[name="name"]');
        
        // Set crypto as active
        cryptoTab.classList.add('active', 'border-blue-500', 'text-blue-400');
        forexTab.classList.remove('active', 'border-blue-500', 'text-blue-400');
        forexTab.classList.add('border-transparent', 'text-gray-400');
        
        // Show crypto pairs, hide forex pairs
        cryptoTradingPairs.classList.remove('hidden');
        forexTradingPairs.classList.add('hidden');
        
        // Reset values
        cryptoTradingPairs.value = '';
        forexTradingPairs.value = '';
        botNameInput.placeholder = 'My BTC Bot';
        
        // Set trading type
        document.querySelector('input[name="trading_type"]').value = 'crypto';
        
        console.log('Tabs initialized');
    }

    // Tab switching functionality
    document.querySelectorAll('.trading-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            const tabType = tab.getAttribute('data-tab');
            console.log('Tab clicked:', tabType);
            
            // Update tab styling
            document.querySelectorAll('.trading-tab').forEach(t => {
                t.classList.remove('active', 'border-blue-500', 'text-blue-400');
                t.classList.add('border-transparent', 'text-gray-400');
            });
            tab.classList.add('active', 'border-blue-500', 'text-blue-400');
            
            console.log('Active tab classes:', tab.classList.toString());
            
            // Update trading type hidden field
            document.querySelector('input[name="trading_type"]').value = tabType;
            
            // Show/hide trading pairs based on tab
            const cryptoTradingPairs = document.querySelector('.crypto-trading-pairs');
            const forexTradingPairs = document.querySelector('.forex-trading-pairs');
            const botNameInput = document.querySelector('input[name="name"]');
            
            if (tabType === 'crypto') {
                cryptoTradingPairs.classList.remove('hidden');
                forexTradingPairs.classList.add('hidden');
                // Reset trading pair selection
                cryptoTradingPairs.value = '';
                // Update bot name placeholder
                botNameInput.placeholder = 'My BTC Bot';
                console.log('Switched to crypto tab');
            } else if (tabType === 'forex') {
                cryptoTradingPairs.classList.add('hidden');
                forexTradingPairs.classList.remove('hidden');
                // Reset trading pair selection
                forexTradingPairs.value = '';
                // Update bot name placeholder
                botNameInput.placeholder = 'My EUR/USD Bot';
                console.log('Switched to forex tab');
            }
        });
    });

    // Strategy selection
    document.querySelectorAll('.strategy-option').forEach(option => {
        option.addEventListener('click', () => {
            const radio = option.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Update visual selection
            document.querySelectorAll('.strategy-option').forEach(opt => {
                opt.classList.remove('border-blue-500', 'bg-blue-900');
                opt.classList.add('border-gray-600');
            });
            option.classList.remove('border-gray-600');
            option.classList.add('border-blue-500', 'bg-blue-900');
        });
    });

    // Create bot form submission
    submitCreateBtn.addEventListener('click', async () => {
        const formData = new FormData(createBotForm);
        
        // Add trading pair parsing
        const activeTab = document.querySelector('.trading-tab.active').getAttribute('data-tab');
        let tradingPair;
        
        if (activeTab === 'crypto') {
            tradingPair = document.querySelector('.crypto-trading-pairs').value;
        } else if (activeTab === 'forex') {
            tradingPair = document.querySelector('.forex-trading-pairs').value;
        }
        
        if (tradingPair) {
            const [base, quote] = tradingPair.split('/');
            formData.set('base_asset', base);
            formData.set('quote_asset', quote);
            // Also set the trading_pair field for validation
            formData.set('trading_pair', tradingPair);
        }

        // Add trading type
        const tradingType = formData.get('trading_type');
        if (tradingType) {
            formData.set('trading_type', tradingType);
        }

        // Debug: Log form data
        console.log('Form data before validation:');
        for (let [key, value] of formData.entries()) {
            console.log(key + ': ' + value);
        }

        // Validate required fields
        const requiredFields = ['name', 'trading_type', 'trading_pair', 'leverage', 'trade_duration', 'strategy', 'max_investment', 'min_trade_amount', 'max_trade_amount', 'max_open_trades'];
        const missingFields = [];
        
        console.log('Checking required fields:');
        requiredFields.forEach(field => {
            const value = formData.get(field);
            console.log(`${field}: ${value}`);
            if (!value) {
                missingFields.push(field.replace('_', ' '));
            }
        });
        
        if (missingFields.length > 0) {
            console.log('Missing fields:', missingFields);
            showErrorModal('Validation Error', `Please fill in the following fields: ${missingFields.join(', ')}`);
            return;
        }

        // Validate trade amount logic
        const minTradeAmount = parseFloat(formData.get('min_trade_amount'));
        const maxTradeAmount = parseFloat(formData.get('max_trade_amount'));
        
        if (maxTradeAmount < minTradeAmount) {
            showErrorModal('Validation Error', 'Max Trade Amount must be greater than or equal to Min Trade Amount.');
            return;
        }

        // Validate max open trades
        const maxOpenTrades = parseInt(formData.get('max_open_trades'));
        if (maxOpenTrades > 50) {
            showErrorModal('Validation Error', 'Max Open Trades cannot exceed 50.');
            return;
        }
        if (maxOpenTrades < 1) {
            showErrorModal('Validation Error', 'Max Open Trades must be at least 1.');
            return;
        }

        // Validate investment amount
        const maxInvestment = parseFloat(formData.get('max_investment'));
        if (maxInvestment < maxTradeAmount) {
            showErrorModal('Validation Error', 'Max Investment must be greater than or equal to Max Trade Amount.');
            return;
        }

        submitCreateBtn.disabled = true;
        submitCreateBtn.textContent = 'Creating...';

        try {
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                throw new Error('CSRF token not found');
            }

            const response = await fetch('{{ route("user.botTrading.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);

            const result = await response.json();
            console.log('Response result:', result);

            if (!response.ok) {
                let errorMessage = 'Failed to create bot';
                
                if (result.message) {
                    errorMessage = result.message;
                } else if (result.errors) {
                    const errorList = Object.values(result.errors).flat();
                    errorMessage = errorList.join(', ');
                }
                
                showErrorModal('Validation Error', errorMessage);
                return;
            }

            if (result.success) {
                showSuccessModal('Bot Created!', result.message);
                createBotModal.classList.add('hidden');
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                let errorMessage = 'Failed to create bot';
                
                if (result.message) {
                    errorMessage = result.message;
                } else if (result.errors) {
                    const errorList = Object.values(result.errors).flat();
                    errorMessage = errorList.join(', ');
                }
                
                showErrorModal('Creation Failed', errorMessage);
            }
        } catch (error) {
            console.error('Error creating bot:', error);
            showErrorModal('Error', `An error occurred: ${error.message}`);
        } finally {
            submitCreateBtn.disabled = false;
            submitCreateBtn.textContent = 'Create Bot';
        }
    });

    // Bot control functions
    window.startBot = async (botId) => {
        console.log('Starting bot:', botId);
        try {
            const response = await fetch(`/user/bot-trading/${botId}/start`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });

            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();
            console.log('Response result:', result);

            if (result.success) {
                showSuccessModal('Bot Started!', result.message);
                setTimeout(() => window.location.reload(), 2000);
            } else {
                showErrorModal('Start Failed', result.message);
            }
        } catch (error) {
            console.error('Error starting bot:', error);
            showErrorModal('Error', 'Failed to start bot: ' + error.message);
        }
    };

    window.pauseBot = async (botId) => {
        try {
            const response = await fetch(`/user/bot-trading/${botId}/pause`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });

            const result = await response.json();

            if (result.success) {
                showSuccessModal('Bot Paused!', result.message);
                setTimeout(() => window.location.reload(), 2000);
            } else {
                showErrorModal('Pause Failed', result.message);
            }
        } catch (error) {
            showErrorModal('Error', 'Failed to pause bot');
        }
    };

    window.stopBot = async (botId) => {
        if (!confirm('Are you sure you want to stop this bot?')) return;

        try {
            const response = await fetch(`/user/bot-trading/${botId}/stop`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });

            const result = await response.json();

            if (result.success) {
                showSuccessModal('Bot Stopped!', result.message);
                setTimeout(() => window.location.reload(), 2000);
            } else {
                showErrorModal('Stop Failed', result.message);
            }
        } catch (error) {
            showErrorModal('Error', 'Failed to stop bot');
        }
    };

    window.deleteBot = async (botId) => {
        if (!confirm('Are you sure you want to delete this bot? This action cannot be undone.')) return;

        try {
            const response = await fetch(`/user/bot-trading/${botId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });

            const result = await response.json();

            if (result.success) {
                showSuccessModal('Bot Deleted!', result.message);
                setTimeout(() => window.location.reload(), 2000);
            } else {
                showErrorModal('Delete Failed', result.message);
            }
        } catch (error) {
            showErrorModal('Error', 'Failed to delete bot');
        }
    };

    function showSuccessModal(title, message) {
        document.getElementById('successTitle').textContent = title;
        document.getElementById('successMessage').textContent = message;
        successModal.classList.remove('hidden');
    }

    function showErrorModal(title, message) {
        document.getElementById('errorTitle').textContent = title;
        document.getElementById('errorMessage').textContent = message;
        errorModal.classList.remove('hidden');
    }

    // Refresh page function
    window.refreshPage = function() {
        // Show loading state
        const refreshBtn = document.querySelector('button[onclick="refreshPage()"]');
        const originalContent = refreshBtn.innerHTML;
        refreshBtn.innerHTML = `
            <svg class="w-5 h-5 inline mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Refreshing...
        `;
        refreshBtn.disabled = true;

        // Reload the page to get fresh data
        setTimeout(() => {
            window.location.reload();
        }, 500);
    };
});
</script>
@endpush
