@extends('dashboard.layout.app')

@section('content')
<div class="min-h-screen bg-gray-900">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-white">Signal Subscription Details</h1>
                        <p class="text-gray-400 mt-1">View and manage your signal subscription</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('user.signal-subscriptions.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors text-center">
                            Back to Subscriptions
                        </a>
                        @if($subscription->status === 'active')
                            <form action="{{ route('user.signal-subscriptions.cancel', $subscription) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to cancel this subscription?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    Cancel Subscription
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Subscription Details Card -->
            <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-700">
                    <h2 class="text-lg font-semibold text-white">Subscription Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Plan Details -->
                        <div class="space-y-4">
                            <h3 class="text-md font-medium text-gray-300 border-b border-gray-600 pb-2">Plan Details</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm text-gray-400">Plan Name</label>
                                    <p class="text-white font-medium">{{ $subscription->plan->name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-400">Plan Type</label>
                                    <p class="text-white font-medium">{{ ucfirst($subscription->plan->type) }}</p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-400">Market Type</label>
                                    <p class="text-white font-medium">{{ ucfirst($subscription->plan->signal_market_type ?? 'N/A') }}</p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-400">Signal Strength</label>
                                    <div class="flex items-center">
                                        <span class="text-yellow-400 mr-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= ($subscription->plan->signal_strength ?? 0))
                                                    ★
                                                @else
                                                    ☆
                                                @endif
                                            @endfor
                                        </span>
                                        <span class="text-white">({{ $subscription->plan->signal_strength ?? 0 }}/5)</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Subscription Status -->
                        <div class="space-y-4">
                            <h3 class="text-md font-medium text-gray-300 border-b border-gray-600 pb-2">Subscription Status</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm text-gray-400">Status</label>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800' : 
                                           ($subscription->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($subscription->status) }}
                                    </span>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-400">Start Date</label>
                                    <p class="text-white font-medium">{{ $subscription->start_date ? $subscription->start_date->format('M d, Y') : 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-400">End Date</label>
                                    <p class="text-white font-medium">{{ $subscription->end_date ? $subscription->end_date->format('M d, Y') : 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-400">Amount Paid</label>
                                    <p class="text-white font-medium">${{ number_format($subscription->amount_paid, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Signal Usage -->
                        <div class="space-y-4">
                            <h3 class="text-md font-medium text-gray-300 border-b border-gray-600 pb-2">Signal Usage</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm text-gray-400">Total Signals</label>
                                    <p class="text-white font-medium">{{ $subscription->plan->signal_quantity ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-400">Remaining Signals</label>
                                    <p class="text-white font-medium">{{ $subscription->signal_quantity_remaining ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-400">Daily Signals Used</label>
                                    <p class="text-white font-medium">{{ $subscription->daily_signals_used ?? 0 }}</p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-400">Success Rate</label>
                                    <p class="text-white font-medium">{{ number_format($subscription->plan->success_rate ?? 0, 1) }}%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Plan Features -->
            @if($subscription->plan->signal_features && is_array($subscription->plan->signal_features))
            <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-700">
                    <h2 class="text-lg font-semibold text-white">Plan Features</h2>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-2">
                        @foreach($subscription->plan->signal_features as $feature)
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $feature }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-700">
                    <h2 class="text-lg font-semibold text-white">Actions</h2>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-3">
                        @if($subscription->status === 'active')
                            <a href="{{ route('user.signals.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                View Signals
                            </a>
                        @endif
                        
                        @if($subscription->status === 'cancelled' || $subscription->status === 'expired')
                            <form action="{{ route('user.signal-subscriptions.renew', $subscription) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                    Renew Subscription
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
