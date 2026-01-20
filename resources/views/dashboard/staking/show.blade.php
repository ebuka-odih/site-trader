@extends('dashboard.layout.app')

@section('content')
<div class="min-h-screen bg-gray-900">
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Staking Details</h1>
                        <p class="text-gray-400">View your staking subscription information</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('user.staking.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                            Back to Staking
                        </a>
                        @if($staking->isActive())
                            <form method="POST" action="{{ route('user.staking.withdraw', $staking) }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                                    Withdraw Rewards
                                </button>
                            </form>
                        @endif
                    </div>
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

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Staking Information -->
                <div class="space-y-6">
                    <!-- Basic Info -->
                    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold text-white mb-4">Staking Information</h2>
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Plan Name:</span>
                                    <span class="text-white font-medium">{{ $staking->plan->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Currency:</span>
                                    <span class="text-white">{{ $staking->currency }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Status:</span>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $staking->status_badge_class }}">
                                        {{ ucfirst($staking->status) }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">APY Rate:</span>
                                    <span class="text-green-400 font-semibold">{{ $staking->formatted_apy_rate }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Amount Details -->
                    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold text-white mb-4">Amount Details</h2>
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Amount Staked:</span>
                                    <span class="text-white font-medium">{{ $staking->formatted_amount_staked }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Current Value:</span>
                                    <span class="text-green-400 font-semibold">{{ $staking->formatted_current_value }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Total Rewards:</span>
                                    <span class="text-green-400 font-semibold">{{ $staking->formatted_total_rewards }}</span>
                                </div>
                                @if($staking->last_reward_date)
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Last Reward:</span>
                                    <span class="text-white">{{ $staking->last_reward_date->format('M d, Y H:i') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    @if($staking->isActive())
                        <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
                            <div class="p-6">
                                <h2 class="text-xl font-semibold text-white mb-4">Actions</h2>
                                <div class="space-y-3">
                                    <form method="POST" action="{{ route('user.staking.withdraw', $staking) }}" class="inline w-full">
                                        @csrf
                                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg font-medium transition-colors">
                                            Withdraw Rewards
                                        </button>
                                    </form>
                                    
                                    <form method="POST" action="{{ route('user.staking.cancel', $staking) }}" class="inline w-full" onsubmit="return confirm('Are you sure you want to cancel this staking? This action cannot be undone.')">
                                        @csrf
                                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg font-medium transition-colors">
                                            Cancel Staking
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Timeline and Details -->
                <div class="space-y-6">
                    <!-- Timeline -->
                    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold text-white mb-4">Timeline</h2>
                            <div class="space-y-4">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-medium">Staking Started</h4>
                                        <p class="text-gray-400 text-sm">{{ $staking->start_date->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                                
                                @if($staking->end_date)
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-medium">Staking Ends</h4>
                                        <p class="text-gray-400 text-sm">{{ $staking->end_date->format('M d, Y H:i') }}</p>
                                        @if($staking->isExpiringSoon())
                                            <p class="text-yellow-400 text-sm">Expiring soon!</p>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                
                                @if($staking->last_reward_date)
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-medium">Last Reward</h4>
                                        <p class="text-gray-400 text-sm">{{ $staking->last_reward_date->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Plan Details -->
                    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold text-white mb-4">Plan Details</h2>
                            <div class="space-y-4">
                                @if($staking->plan->minimum_amount)
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Min Amount:</span>
                                    <span class="text-white">{{ number_format($staking->plan->minimum_amount, 8) }} {{ $staking->plan->staking_currency }}</span>
                                </div>
                                @endif
                                
                                @if($staking->plan->lock_period)
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Lock Period:</span>
                                    <span class="text-white">{{ $staking->plan->lock_period }} days</span>
                                </div>
                                @endif
                                
                                @if($staking->plan->reward_frequency)
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Reward Frequency:</span>
                                    <span class="text-white">{{ $staking->plan->reward_frequency }}</span>
                                </div>
                                @endif
                                
                                @if($staking->plan->staking_duration)
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Duration:</span>
                                    <span class="text-white">{{ $staking->plan->staking_duration }} days</span>
                                </div>
                                @endif
                                
                                @if($staking->plan->description)
                                <div>
                                    <span class="text-gray-400 block mb-2">Description:</span>
                                    <p class="text-white text-sm">{{ $staking->plan->description }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($staking->notes)
                    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold text-white mb-4">Notes</h2>
                            <p class="text-gray-300">{{ $staking->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

