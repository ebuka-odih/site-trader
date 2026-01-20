@extends('dashboard.layout.app')

@section('content')
<div class="min-h-screen bg-gray-900">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Staking Statistics</h1>
                        <p class="text-gray-400">Track your staking performance and earnings</p>
                    </div>
                    <a href="{{ route('user.staking.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Back to Staking
                    </a>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-400">Total Stakings</p>
                                <p class="text-2xl font-bold text-white">{{ $totalStakings }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-400">Active Stakings</p>
                                <p class="text-2xl font-bold text-white">{{ $activeStakings }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-400">Total Staked</p>
                                <p class="text-2xl font-bold text-white">{{ number_format($totalStaked, 8) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-400">Total Rewards</p>
                                <p class="text-2xl font-bold text-green-400">{{ number_format($totalRewards, 8) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Stakings -->
            <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
                <div class="px-6 py-4 border-b border-gray-700">
                    <h2 class="text-lg font-semibold text-white">Recent Stakings</h2>
                </div>
                <div class="divide-y divide-gray-700">
                    @forelse($recentStakings as $staking)
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="text-white font-medium">{{ $staking->plan->name }}</div>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $staking->status_badge_class }}">
                                            {{ ucfirst($staking->status) }}
                                        </span>
                                    </div>
                                    <div class="text-gray-400 text-sm space-y-1">
                                        <div>Staked: {{ $staking->formatted_amount_staked }}</div>
                                        <div>APY: {{ $staking->formatted_apy_rate }}</div>
                                        <div>Started: {{ $staking->start_date->format('M d, Y') }}</div>
                                    </div>
                                </div>
                                <a href="{{ route('user.staking.show', $staking) }}" class="ml-4 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm font-medium transition-colors">
                                    View
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center">
                            <p class="text-gray-400">No staking subscriptions yet.</p>
                            <a href="{{ route('user.staking.create') }}" class="text-blue-400 hover:text-blue-300 mt-2 inline-block">
                                Start your first staking
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
