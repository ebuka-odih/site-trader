@extends('admin.layouts.app')
@section('content')

<div class="p-6">
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Admin Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Welcome back! Here's what's happening with your platform today.</p>
    </div>

    <!-- Main Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Users</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalUsers) }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-sm {{ $userGrowthPercentage >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $userGrowthPercentage >= 0 ? '+' : '' }}{{ number_format($userGrowthPercentage, 1) }}%
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400 ml-1">vs last month</span>
                    </div>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Deposits Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Deposits</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">${{ number_format($totalDeposits, 2) }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-sm {{ $depositGrowthPercentage >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $depositGrowthPercentage >= 0 ? '+' : '' }}{{ number_format($depositGrowthPercentage, 1) }}%
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400 ml-1">vs last month</span>
                    </div>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Withdrawals Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Withdrawals</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">${{ number_format($totalWithdrawals, 2) }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">${{ number_format($pendingWithdrawals, 2) }} pending</p>
                </div>
                <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-lg">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Traded Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Traded</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">${{ number_format($totalTraded, 2) }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-sm {{ $tradeGrowthPercentage >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $tradeGrowthPercentage >= 0 ? '+' : '' }}{{ number_format($tradeGrowthPercentage, 1) }}%
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400 ml-1">vs last month</span>
                    </div>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- User Activity Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">User Activity</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Active Users</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($activeUsers) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">New Today</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($newUsersToday) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">New This Week</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($newUsersThisWeek) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">New This Month</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($newUsersThisMonth) }}</span>
                </div>
            </div>
        </div>

        <!-- Financial Overview Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Financial Overview</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Deposits Today</span>
                    <span class="font-semibold text-green-600 dark:text-green-400">${{ number_format($depositsToday, 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Withdrawals Today</span>
                    <span class="font-semibold text-orange-600 dark:text-orange-400">${{ number_format($withdrawalsToday, 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Pending Deposits</span>
                    <span class="font-semibold text-yellow-600 dark:text-yellow-400">${{ number_format($pendingDeposits, 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Pending Withdrawals</span>
                    <span class="font-semibold text-yellow-600 dark:text-yellow-400">${{ number_format($pendingWithdrawals, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Trading Activity Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Trading Activity</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Active Trades</span>
                    <span class="font-semibold text-blue-600 dark:text-blue-400">{{ number_format($activeTrades) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Closed Trades</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($closedTrades) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Trades Today</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($tradesToday) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Trades This Month</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($tradesThisMonth) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Features Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Copy Trading Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Copy Trading</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Total Traders</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($totalCopyTraders) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Active Traders</span>
                    <span class="font-semibold text-green-600 dark:text-green-400">{{ number_format($activeCopyTraders) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Total Copied Trades</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($totalCopiedTrades) }}</span>
                </div>
            </div>
        </div>

        <!-- Bot Trading Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Bot Trading</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Total Bot Trades</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($totalBotTrades) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Active Bot Trades</span>
                    <span class="font-semibold text-blue-600 dark:text-blue-400">{{ number_format($activeBotTrades) }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.transactions.deposits') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg transition-colors">
                    Manage Deposits
                </a>
                <a href="{{ route('admin.transactions.withdrawals') }}" class="block w-full bg-orange-600 hover:bg-orange-700 text-white text-center py-2 px-4 rounded-lg transition-colors">
                    Manage Withdrawals
                </a>
                <a href="{{ route('admin.user.index') }}" class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-lg transition-colors">
                    Manage Users
                </a>
                <a href="{{ route('admin.trade.history') }}" class="block w-full bg-purple-600 hover:bg-purple-700 text-white text-center py-2 px-4 rounded-lg transition-colors">
                    View Trades
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Users</h3>
                <a href="{{ route('admin.user.index') }}" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentUsers as $user)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium text-blue-600 dark:text-blue-400">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $user->created_at->diffForHumans() }}</span>
                </div>
                @empty
                <p class="text-gray-500 dark:text-gray-400 text-center py-4">No recent users</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Deposits -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Deposits</h3>
                <a href="{{ route('admin.transactions.deposits') }}" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentDeposits as $deposit)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">${{ number_format($deposit->amount, 2) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $deposit->user->name ?? 'Unknown' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        @php
                            $recentStatusClasses = [
                                \App\Models\Deposit::STATUS_APPROVED => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                \App\Models\Deposit::STATUS_PENDING => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                \App\Models\Deposit::STATUS_DECLINED => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                \App\Models\Deposit::STATUS_IN_REVIEW => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                            ];
                            $recentStatusClass = $recentStatusClasses[$deposit->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
                        @endphp
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $recentStatusClass }}">
                            {{ $deposit->status_label }}
                        </span>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $deposit->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 dark:text-gray-400 text-center py-4">No recent deposits</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
