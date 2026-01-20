@extends('dashboard.layout.app')

@section('title', 'Trading Overview')

@section('content')
<div class="min-h-screen bg-gray-900 text-white">
    <!-- Header -->
    <div class="bg-gray-800 border-b border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('user.dashboard') }}" class="text-gray-400 hover:text-white mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold text-white">Trading Overview</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Overall Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Trading Volume -->
            <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg p-6 border border-blue-500">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Total Volume</h3>
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-white mb-2">{{ auth()->user()->formatAmount($totalTradingVolume) }}</div>
                <div class="text-blue-100 text-sm">All Trading Activities</div>
            </div>

            <!-- Total Profit/Loss -->
            <div class="bg-gradient-to-br from-green-600 to-green-700 rounded-lg p-6 border border-green-500">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Total P&L</h3>
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-white mb-2">{{ $totalProfitLoss >= 0 ? '+' : '' }}{{ auth()->user()->formatAmount($totalProfitLoss) }}</div>
                <div class="text-green-100 text-sm">{{ $totalProfitLoss >= 0 ? 'Profit' : 'Loss' }}</div>
            </div>

            <!-- Total Assets -->
            <div class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-lg p-6 border border-purple-500">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Portfolio Value</h3>
                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-white mb-2">{{ auth()->user()->formatAmount($totalHoldingsValue) }}</div>
                <div class="text-purple-100 text-sm">{{ $totalAssets }} Assets</div>
            </div>

            <!-- Active Trading -->
            <div class="bg-gradient-to-br from-orange-600 to-orange-700 rounded-lg p-6 border border-orange-500">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Active Trading</h3>
                    <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-white mb-2">{{ $openLiveTrades + $activeBots + $activeCopyTrades }}</div>
                <div class="text-orange-100 text-sm">Active Positions</div>
            </div>
        </div>

        <!-- Trading Sections Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Live Trading Stats -->
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Live Trading</h3>
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                        </svg>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Open Trades</span>
                        <span class="text-white font-semibold">{{ $openLiveTrades }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Closed Trades</span>
                        <span class="text-white font-semibold">{{ $closedLiveTrades }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Total Volume</span>
                        <span class="text-white font-semibold">{{ auth()->user()->formatAmount($liveTradingVolume) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Total Trades</span>
                        <span class="text-white font-semibold">{{ $totalLiveTrades }}</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-700">
                    <a href="{{ route('user.liveTrading.index') }}" class="text-blue-400 hover:text-blue-300 text-sm font-medium">
                        View Live Trading →
                    </a>
                </div>
            </div>

            <!-- Bot Trading Stats -->
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
                        <span class="text-gray-400">Total Bots</span>
                        <span class="text-white font-semibold">{{ $totalBots }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Total Profit</span>
                        <span class="{{ $totalBotProfit >= 0 ? 'text-green-400' : 'text-red-400' }} font-semibold">
                            {{ $totalBotProfit >= 0 ? '+' : '' }}{{ auth()->user()->formatAmount($totalBotProfit) }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Trading Volume</span>
                        <span class="text-white font-semibold">{{ auth()->user()->formatAmount($botTradingVolume) }}</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-700">
                    <a href="{{ route('user.botTrading.index') }}" class="text-purple-400 hover:text-purple-300 text-sm font-medium">
                        Manage Bots →
                    </a>
                </div>
            </div>

            <!-- Copy Trading Stats -->
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Copy Trading</h3>
                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
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
                        <span class="text-white font-semibold">{{ $totalCopyTrades }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Total Trades</span>
                        <span class="text-white font-semibold">{{ $totalCopyTradeCount }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Win/Loss</span>
                        <span class="text-white font-semibold">
                            <span class="text-green-400">{{ $totalCopyWins }}</span> / 
                            <span class="text-red-400">{{ $totalCopyLosses }}</span>
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Total PnL</span>
                        <span class="{{ $totalCopyPnL >= 0 ? 'text-green-400' : 'text-red-400' }} font-semibold">
                            {{ $totalCopyPnL >= 0 ? '+' : '' }}{{ auth()->user()->formatAmount($totalCopyPnL) }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Trading Volume</span>
                        <span class="text-white font-semibold">{{ auth()->user()->formatAmount($copyTradingVolume) }}</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-700">
                    <a href="{{ route('user.copyTrading.index') }}" class="text-green-400 hover:text-green-300 text-sm font-medium">
                        View Copy Trades →
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-white">Recent Trading Activity</h3>
                <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4 bg-gray-700 rounded-lg">
                    <div class="text-2xl font-bold text-blue-400">{{ $totalLiveTrades }}</div>
                    <div class="text-sm text-gray-300">Live Trades</div>
                </div>
                <div class="text-center p-4 bg-gray-700 rounded-lg">
                    <div class="text-2xl font-bold text-purple-400">{{ $totalBots }}</div>
                    <div class="text-sm text-gray-300">Trading Bots</div>
                </div>
                <div class="text-center p-4 bg-gray-700 rounded-lg">
                    <div class="text-2xl font-bold text-green-400">{{ $totalCopyTrades }}</div>
                    <div class="text-sm text-gray-300">Copy Trades</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Overview Footer -->
@include('dashboard.overview.partials.footer-menu')

@endsection
