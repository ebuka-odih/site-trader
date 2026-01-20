@extends('dashboard.layout.app')

@section('content')
<div class="min-h-screen bg-gray-900">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-white">Staking Dashboard</h1>
                    <p class="text-gray-400 text-sm">Earn passive income through crypto staking</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('user.staking.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium text-center transition-colors">
                        Start Staking
                    </a>
                    <a href="{{ route('user.staking.statistics') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium text-center transition-colors">
                        View Statistics
                    </a>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-900/20 border border-green-500/30 rounded-lg">
                    <div class="text-green-400">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-900/20 border border-red-500/30 rounded-lg">
                    <div class="text-red-400">{{ session('error') }}</div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <!-- Available Staking Plans -->
                    <div class="bg-gray-800 rounded-xl overflow-hidden shadow">
                        <div class="px-6 py-4 border-b border-gray-700">
                            <h2 class="text-lg font-semibold text-white">Available Staking Plans</h2>
                        </div>
                        <div class="p-6">
                            @if($stakingPlans->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($stakingPlans as $plan)
                                        <div class="bg-gray-700 rounded-lg p-4 border border-gray-600">
                                            <div class="flex items-center justify-between mb-3">
                                                <h3 class="text-white font-semibold">{{ $plan->name }}</h3>
                                                <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800">{{ $plan->staking_currency }}</span>
                                            </div>
                                            
                                            <div class="space-y-2 mb-4">
                                                @if($plan->apy_rate)
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-400 text-sm">APY Rate:</span>
                                                        <span class="text-green-400 font-semibold">{{ number_format($plan->apy_rate, 2) }}%</span>
                                                    </div>
                                                @endif
                                                
                                                @if($plan->minimum_amount)
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-400 text-sm">Min Amount:</span>
                                                        <span class="text-white">{{ number_format($plan->minimum_amount, 8) }} {{ $plan->staking_currency }}</span>
                                                    </div>
                                                @endif
                                                
                                                @if($plan->lock_period)
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-400 text-sm">Lock Period:</span>
                                                        <span class="text-white">{{ $plan->lock_period }} days</span>
                                                    </div>
                                                @endif
                                                
                                                @if($plan->reward_frequency)
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-400 text-sm">Rewards:</span>
                                                        <span class="text-white">{{ $plan->reward_frequency }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="flex space-x-2">
                                                <a href="{{ route('user.staking.create') }}?plan={{ $plan->id }}" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm font-medium text-center transition-colors">
                                                    Stake Now
                                                </a>
                                                @if($plan->features)
                                                    <button class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded text-sm font-medium transition-colors">
                                                        Details
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="text-gray-400 mb-4">
                                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-white mb-2">No Staking Plans Available</h3>
                                    <p class="text-gray-400">Check back later for new staking opportunities.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Active Staking Subscriptions -->
                    <div class="bg-gray-800 rounded-xl overflow-hidden shadow">
                        <div class="px-6 py-4 border-b border-gray-700">
                            <h2 class="text-lg font-semibold text-white">Active Staking</h2>
                        </div>
                        <div class="divide-y divide-gray-700">
                            @forelse($activeStakings as $staking)
                                <div class="p-6 flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="text-white font-medium">{{ $staking->plan->name }}</div>
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        </div>
                                        <div class="text-gray-400 text-sm space-y-1">
                                            <div>Staked: {{ $staking->formatted_amount_staked }}</div>
                                            <div>APY: {{ $staking->formatted_apy_rate }}</div>
                                            <div>Current Value: {{ $staking->formatted_current_value }}</div>
                                            @if($staking->end_date)
                                                <div>Ends: {{ $staking->end_date->format('M d, Y') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap gap-2 ml-4">
                                        <a href="{{ route('user.staking.show', $staking) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm font-medium transition-colors">
                                            View
                                        </a>
                                        <form method="POST" action="{{ route('user.staking.withdraw', $staking) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm font-medium transition-colors">
                                                Withdraw
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-gray-400 text-center">
                                    <div class="mb-4">
                                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                    </div>
                                    <p>No active staking subscriptions.</p>
                                    <a href="{{ route('user.staking.create') }}" class="text-blue-400 hover:text-blue-300 mt-2 inline-block">
                                        Start your first staking
                                    </a>
                                </div>
                            @endforelse
                        </div>
                        @if($activeStakings->hasPages())
                            <div class="px-6 py-4 border-t border-gray-700">
                                {{ $activeStakings->links() }}
                            </div>
                        @endif
                    </div>

                    <!-- Completed Staking -->
                    <div class="bg-gray-800 rounded-xl overflow-hidden shadow">
                        <div class="px-6 py-4 border-b border-gray-700">
                            <h2 class="text-lg font-semibold text-white">Completed Staking</h2>
                        </div>
                        <div class="divide-y divide-gray-700">
                            @forelse($completedStakings as $staking)
                                <div class="p-6 flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="text-white font-medium">{{ $staking->plan->name }}</div>
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Completed
                                            </span>
                                        </div>
                                        <div class="text-gray-400 text-sm space-y-1">
                                            <div>Staked: {{ $staking->formatted_amount_staked }}</div>
                                            <div>Total Rewards: {{ $staking->formatted_total_rewards }}</div>
                                            <div>Ended: {{ $staking->end_date ? $staking->end_date->format('M d, Y') : 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <a href="{{ route('user.staking.show', $staking) }}" class="bg-gray-700 hover:bg-gray-600 text-white px-3 py-2 rounded text-sm font-medium transition-colors">
                                        Details
                                    </a>
                                </div>
                            @empty
                                <div class="p-6 text-gray-400 text-center">
                                    <p>No completed staking subscriptions yet.</p>
                                </div>
                            @endforelse
                        </div>
                        @if($completedStakings->hasPages())
                            <div class="px-6 py-4 border-t border-gray-700">
                                {{ $completedStakings->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Stats -->
                    <div class="bg-gray-800 rounded-xl overflow-hidden shadow">
                        <div class="px-6 py-4 border-b border-gray-700">
                            <h2 class="text-lg font-semibold text-white">Quick Stats</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Active Staking:</span>
                                <span class="text-white font-semibold">{{ $activeStakings->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Total Staked:</span>
                                <span class="text-white font-semibold">
                                    @php
                                        $totalStaked = $activeStakings->sum('amount_staked');
                                        echo number_format($totalStaked, 8);
                                    @endphp
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Total Rewards:</span>
                                <span class="text-green-400 font-semibold">
                                    @php
                                        $totalRewards = $activeStakings->sum('total_rewards') + $completedStakings->sum('total_rewards');
                                        echo number_format($totalRewards, 8);
                                    @endphp
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Popular Staking Platforms -->
                    <div class="bg-gray-800 rounded-xl overflow-hidden shadow">
                        <div class="px-6 py-4 border-b border-gray-700">
                            <h2 class="text-lg font-semibold text-white">Popular Platforms</h2>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="#" class="flex items-center justify-between p-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition-colors">
                                <div>
                                    <div class="text-white font-medium">Binance</div>
                                    <div class="text-gray-400 text-sm">Up to 12% APY</div>
                                </div>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                            </a>
                            <a href="#" class="flex items-center justify-between p-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition-colors">
                                <div>
                                    <div class="text-white font-medium">Coinbase</div>
                                    <div class="text-gray-400 text-sm">Up to 5% APY</div>
                                </div>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                            </a>
                            <a href="#" class="flex items-center justify-between p-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition-colors">
                                <div>
                                    <div class="text-white font-medium">Kraken</div>
                                    <div class="text-gray-400 text-sm">Up to 23% APY</div>
                                </div>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
