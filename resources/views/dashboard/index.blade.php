@extends('dashboard.layout.app')
@section('content')

<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-2xl font-bold text-white">Dashboard</h1>
            <p class="text-gray-400 mt-1">Welcome back, {{ auth()->user()->name }}!</p>
        </div>
    </div>

        <!-- First Row: Balance & Trading Strength -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Balance Card -->
        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700 h-full">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-white">Wallet Overview</h3>
                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"></path>
                    </svg>
                </div>
            </div>
            
            @php
                // Force refresh the user data from database
                $user = auth()->user()->fresh();
                $walletBalance = $user->balance ?? 0;
                $tradingBalance = $user->trading_balance ?? 0;
                $holdingsBalance = $user->holding_balance ?? 0;
                $staking = $user->staking_balance ?? 0;
                $profitBalance = $user->profit ?? 0;
                $totalPortfolio = $walletBalance + $tradingBalance + $holdingsBalance + $staking + $profitBalance;
                
                // Debug: Log the profit balance
                \Log::info('Dashboard Profit Balance Debug', [
                    'user_id' => $user->id,
                    'profit_balance' => $profitBalance,
                    'raw_profit' => $user->getRawOriginal('profit'),
                    'user_name' => $user->name
                ]);
            @endphp

            <!-- Total Balance -->
            <div class="mb-6">
                <div class="text-3xl font-bold text-white animate-pulse">{{ $user->formatAmount($totalPortfolio) }}</div>
                <div class="text-sm text-gray-400">Total Portfolio Value</div>
            </div>

            <!-- Balance Breakdown -->
            <div class="space-y-3 mb-4">
                <div class="flex justify-between items-center py-2 px-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition-all duration-300 transform hover:scale-[1.02] cursor-pointer">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-sm text-gray-300">Wallet Balance</span>
                    </div>
                    <span class="text-sm font-semibold text-white">{{ $user->formatAmount($walletBalance) }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2 px-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition-all duration-300 transform hover:scale-[1.02] cursor-pointer">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse" style="animation-delay: 0.5s;"></div>
                        <span class="text-sm text-gray-300">Trading Balance</span>
                    </div>
                    <span class="text-sm font-semibold text-white">{{ $user->formatAmount($tradingBalance) }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2 px-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition-all duration-300 transform hover:scale-[1.02] cursor-pointer">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
                        <span class="text-sm text-gray-300">Holdings</span>
                    </div>
                    <span class="text-sm font-semibold text-white">{{ $user->formatAmount($holdingsBalance) }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2 px-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition-all duration-300 transform hover:scale-[1.02] cursor-pointer">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-purple-500 rounded-full animate-pulse" style="animation-delay: 1.5s;"></div>
                        <span class="text-sm text-gray-300">Staking</span>
                    </div>
                    <span class="text-sm font-semibold text-white">{{ $user->formatAmount($staking) }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2 px-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition-all duration-300 transform hover:scale-[1.02] cursor-pointer">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse" style="animation-delay: 2s;"></div>
                        <span class="text-sm text-gray-300">Profit Balance</span>
                    </div>
                    <span class="text-sm font-semibold text-white">{{ $user->formatAmount($profitBalance) }}</span>
                </div>
            </div>

            <!-- Wallet Status -->
            <div class="border-t border-gray-700 pt-3">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-400">Wallet Status</span>
                    <span class="text-green-400 font-medium flex items-center">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse mr-1"></span>
                        Active
                    </span>
                </div>
                <div class="flex items-center justify-between text-xs mt-1">
                    <span class="text-gray-400">Last Updated</span>
                    <span class="text-gray-300">{{ now()->format('M d, H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Trading Strength Card -->
        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700 h-full flex flex-col">
            <!-- Top Section: Trading Strength (Mobile: Full, Desktop: Top Half) -->
            <div class="lg:flex-1">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Trading Strength</h3>
                    <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="text-3xl font-bold text-purple-400">{{ number_format($winRate, 1) }}%</div>
                    <div class="text-sm text-gray-400">{{ $winRate >= 70 ? 'Strong Performance' : ($winRate >= 50 ? 'Good Performance' : 'Learning Phase') }}</div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Win Rate</span>
                        <span class="text-white">{{ number_format($winRate, 1) }}%</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Total Trades</span>
                        <span class="text-white">{{ $totalTrades }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Avg. Profit</span>
                        <span class="{{ $avgProfit >= 0 ? 'text-green-400' : 'text-red-400' }}">
                            {{ $avgProfit >= 0 ? '+' : '' }}{{ auth()->user()->formatAmount($avgProfit) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Bottom Section: Subscription State (Desktop Only) -->
            <div class="hidden lg:block lg:flex-1 lg:mt-6 lg:pt-6 lg:border-t lg:border-gray-700">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm font-semibold text-gray-300">Subscription Status</h4>
                    <div class="w-6 h-6 bg-blue-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-400">Active Plans</span>
                        <span class="text-green-400 font-semibold">{{ $totalPlans }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-400">Premium Status</span>
                        <span class="text-blue-400 font-semibold">{{ $totalPlans > 0 ? 'Active' : 'Inactive' }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-400">Next Renewal</span>
                        <span class="text-white">
                            @if($activePlans->count() > 0)
                                {{ $activePlans->first()->end_date ? $activePlans->first()->end_date->format('M d, Y') : 'N/A' }}
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row: My Subscriptions -->
    <div class="mb-6 lg:hidden">
        <!-- User Subscriptions Card -->
        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-white">My Subscriptions</h3>
                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Trading Plans</span>
                    <span class="text-blue-400 font-semibold">{{ $tradingPlans }} Active</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Signal Plans</span>
                    <span class="text-green-400 font-semibold">{{ $signalPlans }} Active</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Staking Plans</span>
                    <span class="text-purple-400 font-semibold">{{ $stakingPlans }} Active</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Mining Plans</span>
                    <span class="text-orange-400 font-semibold">{{ $miningPlans }} Active</span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-700">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Total Subscriptions</span>
                    <span class="text-white font-semibold">{{ $totalPlans }} Active</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row: Additional Insights -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Holdings Overview -->
        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-white">Holdings Overview</h3>
                <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                    </svg>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Total Value</span>
                    <span class="text-white font-semibold">{{ auth()->user()->formatAmount($totalHoldingsValue) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Assets Held</span>
                    <span class="text-white">{{ $holdings->count() }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Recent Activity</span>
                    <span class="text-white">{{ $recentTransactions->count() }}</span>
                </div>
            </div>
            @if($holdings->count() > 0)
            <div class="mt-4 pt-3 border-t border-gray-700">
                <a href="{{ route('user.holding.index') }}" class="text-blue-400 hover:text-blue-300 text-sm font-medium">
                    View All Holdings →
                </a>
            </div>
            @endif
        </div>

        <!-- Bot Trading Overview -->
        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-white">Bot Trading</h3>
                <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Active Bots</span>
                    <span class="text-white font-semibold">{{ $activeBots }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Total Profit</span>
                    <span class="{{ $totalBotProfit >= 0 ? 'text-green-400' : 'text-red-400' }} font-semibold">
                        {{ $totalBotProfit >= 0 ? '+' : '' }}{{ auth()->user()->formatAmount($totalBotProfit) }}
                    </span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Total Bots</span>
                    <span class="text-white">{{ $botTradings->count() }}</span>
                </div>
            </div>
            @if($botTradings->count() > 0)
            <div class="mt-4 pt-3 border-t border-gray-700">
                <a href="{{ route('user.botTrading.index') }}" class="text-purple-400 hover:text-purple-300 text-sm font-medium">
                    Manage Bots →
                </a>
            </div>
            @endif
        </div>

        <!-- Copy Trading Overview -->
        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-white">Copy Trading</h3>
                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Active Copies</span>
                    <span class="text-white font-semibold">{{ $activeCopyTrades }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Total Copies</span>
                    <span class="text-white">{{ $copyTrades->count() }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Status</span>
                    <span class="text-green-400 font-semibold">{{ $activeCopyTrades > 0 ? 'Active' : 'Inactive' }}</span>
                </div>
            </div>
            @if($copyTrades->count() > 0)
            <div class="mt-4 pt-3 border-t border-gray-700">
                <a href="{{ route('user.copyTrading.index') }}" class="text-green-400 hover:text-green-300 text-sm font-medium">
                    View Copy Trades →
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Third Row: Trades Tabs -->
    <div class="bg-gray-800 rounded-lg border border-gray-700 mb-8">
                <!-- Tabs Header -->
                <div class="border-b border-gray-700">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        <button id="openTradesTab" class="tab-button active py-4 px-1 border-b-2 border-blue-500 text-blue-400 font-medium text-sm">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Open Trades</span>
                            </div>
                        </button>
                        <button id="closedTradesTab" class="tab-button py-4 px-1 border-b-2 border-transparent text-gray-400 hover:text-gray-300 font-medium text-sm">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Closed Trades</span>
                            </div>
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Open Trades Tab -->
                    <div id="openTradesContent" class="tab-content active">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Pair</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Leverage</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Entry Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Current P&L</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Action</th>
                                </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-700">
                                    @forelse($openTrades as $trade)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            {{ $trade->trade_pair->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $trade->action_type === 'buy' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($trade->action_type ?? 'N/A') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            {{ auth()->user()->formatAmount($trade->amount) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            {{ $trade->leverage ?? 'N/A' }}x
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            {{ auth()->user()->formatAmount($trade->entry_price ?? 0, 8) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm {{ ($trade->profit_loss ?? 0) >= 0 ? 'text-green-400' : 'text-red-400' }}">
                                            {{ ($trade->profit_loss ?? 0) >= 0 ? '+' : '' }}{{ auth()->user()->formatAmount($trade->profit_loss ?? 0) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button class="text-red-400 hover:text-red-300">Close</button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center space-y-3">
                                                <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                                </svg>
                                                <div class="text-gray-400">
                                                    <p class="text-lg font-medium">No open trades</p>
                                                    <p class="text-sm">Start trading to see your open positions here</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Closed Trades Tab -->
                    <div id="closedTradesContent" class="tab-content hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Pair</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Entry Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Exit Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">P&L</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Date</th>
                                </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-700">
                                    @forelse($closedTrades as $trade)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            {{ $trade->trade_pair->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $trade->action_type === 'buy' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($trade->action_type ?? 'N/A') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            {{ auth()->user()->formatAmount($trade->amount) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            {{ auth()->user()->formatAmount($trade->entry_price ?? 0, 8) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            {{ auth()->user()->formatAmount($trade->exit_price ?? 0, 8) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm {{ ($trade->profit_loss ?? 0) >= 0 ? 'text-green-400' : 'text-red-400' }}">
                                            {{ ($trade->profit_loss ?? 0) >= 0 ? '+' : '' }}{{ auth()->user()->formatAmount($trade->profit_loss ?? 0) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            {{ $trade->updated_at ? $trade->updated_at->format('M d, Y') : 'N/A' }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center space-y-3">
                                                <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <div class="text-gray-400">
                                                    <p class="text-lg font-medium">No closed trades</p>
                                                    <p class="text-sm">Your completed trades will appear here</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

                                                </div>
                                            </div>

    <!-- Bottom spacing for fixed menu -->
    <div class="h-20"></div>

    <!-- Bottom Menu (Mobile & Desktop) -->
    <div class="fixed bottom-0 left-0 right-0 bg-gray-900 border-t border-gray-700 z-50">
        <div class="flex justify-around items-center py-2 px-3">
            <!-- Trade -->
            <a href="{{ route('user.liveTrading.index') }}" class="flex flex-col items-center space-y-0.5 text-gray-400 hover:text-blue-400 transition-colors">
                <div class="w-7 h-7 bg-gray-700 rounded-lg flex items-center justify-center hover:bg-blue-600 transition-colors">
                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium">Trade</span>
            </a>

            <!-- Deposit -->
            @if(auth()->user()->isSuspended())
                <div class="flex flex-col items-center space-y-0.5 text-gray-500 opacity-50 cursor-not-allowed" title="Account suspended">
                    <div class="w-7 h-7 bg-gray-700 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="w-4 h-4 text-gray-500" fill="currentColor">
                            <path d="M17.12 9.88a2.997 2.997 0 1 0-4.24 4.24a2.997 2.997 0 1 0 4.24-4.24M7 6v12h16V6zm14 8c-.53 0-1.04.21-1.41.59c-.38.37-.59.88-.59 1.41h-8c0-.53-.21-1.04-.59-1.41c-.37-.38-.88-.59-1.41-.59v-4c.53 0 1.04-.21 1.41-.59c.38-.37.59-.88.59-1.41h8c0 .53.21 1.04.59 1.41c.37.38.88.59 1.41.59zM5 8H3c-.55 0-1-.45-1-1s.45-1 1-1h2zm0 5H2c-.55 0-1-.45-1-1s.45-1 1-1h3zm0 5H1c-.552 0-1-.45-1-1s.448-1 1-1h4z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium">Deposit</span>
                </div>
            @else
                <a href="{{ route('user.deposit') }}" class="flex flex-col items-center space-y-0.5 text-gray-400 hover:text-green-400 transition-colors">
                    <div class="w-7 h-7 bg-gray-700 rounded-lg flex items-center justify-center hover:bg-green-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="w-4 h-4 text-gray-300" fill="currentColor">
                            <path d="M17.12 9.88a2.997 2.997 0 1 0-4.24 4.24a2.997 2.997 0 1 0 4.24-4.24M7 6v12h16V6zm14 8c-.53 0-1.04.21-1.41.59c-.38.37-.59.88-.59 1.41h-8c0-.53-.21-1.04-.59-1.41c-.37-.38-.88-.59-1.41-.59v-4c.53 0 1.04-.21 1.41-.59c.38-.37.59-.88.59-1.41h8c0 .53.21 1.04.59 1.41c.37.38.88.59 1.41.59zM5 8H3c-.55 0-1-.45-1-1s.45-1 1-1h2zm0 5H2c-.55 0-1-.45-1-1s.45-1 1-1h3zm0 5H1c-.552 0-1-.45-1-1s.448-1 1-1h4z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium">Deposit</span>
                </a>
            @endif

            <!-- Portfolio -->
            <a href="{{ route('user.portfolio.index') }}" class="flex flex-col items-center space-y-0.5 text-gray-400 hover:text-purple-400 transition-colors">
                <div class="w-7 h-7 bg-gray-700 rounded-lg flex items-center justify-center hover:bg-purple-600 transition-colors">
                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium">Portfolio</span>
            </a>

            <!-- Holdings -->
            <a href="{{ route('user.holding.index') }}" class="flex flex-col items-center space-y-0.5 text-gray-400 hover:text-orange-400 transition-colors">
                <div class="w-7 h-7 bg-gray-700 rounded-lg flex items-center justify-center hover:bg-orange-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="w-4 h-4 text-gray-300" fill="currentColor">
                        <path d="M12.753 4a.752.752 0 0 0-1.505 0v.464h-.051c-1.05 0-1.66.795-1.781 1.56c-.121.755.2 1.7 1.165 2.048l2.362.851c.082.03.113.07.129.1c.02.04.033.1.02.17a.27.27 0 0 1-.076.152c-.025.023-.069.051-.162.051h-1.657a.324.324 0 0 1-.32-.328a.752.752 0 0 0-1.504 0c0 1.006.81 1.833 1.824 1.833h.076v.599a.752.752 0 0 0 1.505 0v-.599h.076c1.964 0 2.426-2.735.599-3.394l-2.361-.85a.26.26 0 0 1-.157-.133a.44.44 0 0 1-.034-.263a.4.4 0 0 1 .107-.225c.036-.036.089-.067.189-.067h1.607c.17 0 .319.14.319.328a.752.752 0 0 0 1.505 0c0-1.006-.81-1.833-1.824-1.833h-.051zm-3.32 8.998c-.863 0-1.522.206-2.063.544c-.5.312-.865.72-1.157 1.046l-.031.035c-.316.352-.559.614-.873.806c-.292.179-.685.319-1.309.319a.75.75 0 0 0-.752.752V20c0 .416.337.752.752.752h9.502c1.744 0 3.426-.94 4.603-1.784a16 16 0 0 0 1.983-1.695l.004-.004c.364-.3.59-.688.647-1.117c.058-.436-.07-.85-.3-1.17a1.7 1.7 0 0 0-2.335-.42q-.296.21-.579.416c-.571.414-1.122.814-1.713 1.149c-.77.437-1.523.712-2.31.712H10.5c-.2 0-.283-.062-.312-.092a.2.2 0 0 1-.06-.151a.2.2 0 0 1 .06-.152c.03-.03.112-.092.312-.092h2.002c.533 0 1.006-.167 1.354-.489a1.616 1.616 0 0 0 0-2.376c-.348-.322-.821-.49-1.354-.49z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium">Holdings</span>
            </a>
        </div>
    </div>


        </div>
    </div>

</div>

    <script>
        // Tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            const openTradesTab = document.getElementById('openTradesTab');
            const closedTradesTab = document.getElementById('closedTradesTab');
            const openTradesContent = document.getElementById('openTradesContent');
            const closedTradesContent = document.getElementById('closedTradesContent');

            // Open Trades Tab
            openTradesTab.addEventListener('click', function() {
                // Update tab buttons
                openTradesTab.classList.add('active', 'border-blue-500', 'text-blue-400');
                openTradesTab.classList.remove('border-transparent', 'text-gray-400');
                closedTradesTab.classList.remove('active', 'border-blue-500', 'text-blue-400');
                closedTradesTab.classList.add('border-transparent', 'text-gray-400');

                // Update content
                openTradesContent.classList.remove('hidden');
                openTradesContent.classList.add('active');
                closedTradesContent.classList.add('hidden');
                closedTradesContent.classList.remove('active');
            });

            // Closed Trades Tab
            closedTradesTab.addEventListener('click', function() {
                // Update tab buttons
                closedTradesTab.classList.add('active', 'border-blue-500', 'text-blue-400');
                closedTradesTab.classList.remove('border-transparent', 'text-gray-400');
                openTradesTab.classList.remove('active', 'border-blue-500', 'text-blue-400');
                openTradesTab.classList.add('border-transparent', 'text-gray-400');

                // Update content
                closedTradesContent.classList.remove('hidden');
                closedTradesContent.classList.add('active');
                openTradesContent.classList.add('hidden');
                openTradesContent.classList.remove('active');
            });


        });
    </script>
@endsection
