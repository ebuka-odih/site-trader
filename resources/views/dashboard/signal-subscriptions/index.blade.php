@extends('dashboard.layout.app')

@section('content')
<div class="min-h-screen bg-gray-900">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-white">My Signal Subscriptions</h1>
                        <p class="text-gray-400 mt-1">Manage your signal plan subscriptions</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('user.plan.signal') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors text-center">
                            Browse Signal Plans
                        </a>
                        <a href="{{ route('user.signal-subscriptions.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors text-center">
                            Subscribe to New Plan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gray-800 rounded-xl p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-500 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-400">Active Subscriptions</p>
                            <p class="text-2xl font-bold text-white">{{ $signalSubscriptions->where('status', 'active')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-xl p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-500 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-400">Total Signals</p>
                            <p class="text-2xl font-bold text-white">{{ $signalSubscriptions->sum('signal_quantity_remaining') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-xl p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-yellow-500 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-400">Total Spent</p>
                            <p class="text-2xl font-bold text-white">{{ auth()->user()->formatAmount($signalSubscriptions->sum('amount_paid')) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-xl p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-500 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-400">Success Rate</p>
                            <p class="text-2xl font-bold text-white">
                                @php
                                    $avgSuccessRate = $signalSubscriptions->where('plan.success_rate', '>', 0)->avg('plan.success_rate');
                                @endphp
                                {{ $avgSuccessRate ? number_format($avgSuccessRate, 1) : '0' }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subscriptions List -->
            @if($signalSubscriptions->count() > 0)
                <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-700">
                        <h2 class="text-lg font-semibold text-white">Your Subscriptions</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Plan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Signals</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Duration</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach($signalSubscriptions as $subscription)
                                    <tr class="hover:bg-gray-700 transition-colors duration-150">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-white">{{ $subscription->plan->name }}</div>
                                                    <div class="text-sm text-gray-400">{{ ucfirst($subscription->plan->signal_market_type ?? 'N/A') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800' : 
                                                   ($subscription->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                                {{ ucfirst($subscription->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-300">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-white font-medium">{{ $subscription->signal_quantity_remaining ?? 0 }}</span>
                                                <span class="text-gray-400">/ {{ $subscription->plan->signal_quantity ?? 0 }}</span>
                                            </div>
                                            <div class="text-xs text-gray-400">Used today: {{ $subscription->daily_signals_used ?? 0 }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-300">
                                            <div class="text-white font-medium">
                                                {{ $subscription->start_date ? $subscription->start_date->format('M d') : 'N/A' }} - 
                                                {{ $subscription->end_date ? $subscription->end_date->format('M d') : 'N/A' }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                @if($subscription->end_date && $subscription->end_date->isFuture())
                                                    {{ $subscription->end_date->diffForHumans() }} left
                                                @elseif($subscription->end_date && $subscription->end_date->isPast())
                                                    Expired {{ $subscription->end_date->diffForHumans() }}
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-300">
                                            <div class="text-white font-medium">${{ number_format($subscription->amount_paid, 2) }}</div>
                                            <div class="text-xs text-gray-400">{{ $subscription->created_at->format('M d, Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium">
                                            <div class="flex flex-wrap gap-2">
                                                <a href="{{ route('user.signal-subscriptions.show', $subscription) }}" class="text-blue-400 hover:text-blue-300 transition-colors">
                                                    View Details
                                                </a>
                                                @if($subscription->status === 'active')
                                                    <a href="{{ route('signals.index') }}" class="text-green-400 hover:text-green-300 transition-colors">
                                                        View Signals
                                                    </a>
                                                @endif
                                                @if($subscription->status === 'cancelled' || $subscription->status === 'expired')
                                                    <form action="{{ route('user.signal-subscriptions.renew', $subscription) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-yellow-400 hover:text-yellow-300 transition-colors">
                                                            Renew
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-12 text-center">
                        <div class="text-gray-400 mb-6">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-white mb-2">No Signal Subscriptions</h3>
                        <p class="text-gray-400 mb-6">You haven't subscribed to any signal plans yet.</p>
                        <div class="flex flex-col sm:flex-row justify-center gap-3">
                            <a href="{{ route('user.plan.signal') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors text-center">
                                Browse Signal Plans
                            </a>
                            <a href="{{ route('user.signal-subscriptions.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors text-center">
                                Subscribe Now
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
