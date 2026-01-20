@extends('dashboard.layout.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Plan Subscription Details</h1>
                    <p class="text-gray-400">View details of your plan subscription</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('user.plans.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors text-center">
                        Back to Plans
                    </a>
                    @if($userPlan->isActive())
                        <form method="POST" action="{{ route('user.plans.cancel', $userPlan) }}" class="inline" onsubmit="return confirm('Are you sure you want to cancel this plan?')">
                            @csrf
                            <button type="submit" class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                                Cancel Plan
                            </button>
                        </form>
                    @elseif($userPlan->isCancelled())
                        <form method="POST" action="{{ route('user.plans.reactivate', $userPlan) }}" class="inline">
                            @csrf
                            <button type="submit" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                                Reactivate Plan
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

        <!-- Plan Details Card -->
        <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Plan Information -->
                    <div>
                        <h2 class="text-xl font-semibold text-white mb-4">Plan Information</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-400">Plan Name</label>
                                <div class="text-lg font-semibold text-white">{{ $userPlan->plan->name }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-400">Plan Type</label>
                                <div class="text-white">{{ ucfirst($userPlan->plan->type) }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-400">Description</label>
                                <div class="text-gray-300">{{ $userPlan->plan->description }}</div>
                            </div>
                            
                            @if($userPlan->plan->type === 'trading')
                                <div>
                                    <label class="block text-sm font-medium text-gray-400">Trading Features</label>
                                    <div class="space-y-2">
                                        @if($userPlan->plan->pairs)
                                            <div class="text-gray-300">• {{ $userPlan->plan->pairs }} Trading Pairs</div>
                                        @endif
                                        @if($userPlan->plan->leverage)
                                            <div class="text-gray-300">• Leverage up to 1:{{ number_format($userPlan->plan->leverage) }}</div>
                                        @endif
                                        @if($userPlan->plan->spreads)
                                            <div class="text-gray-300">• Spreads from {{ $userPlan->plan->spreads }} pips</div>
                                        @endif
                                        @if($userPlan->plan->swap_fees)
                                            <div class="text-gray-300">• Swap Fees: {{ $userPlan->plan->swap_fees }}%</div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Subscription Details -->
                    <div>
                        <h2 class="text-xl font-semibold text-white mb-4">Subscription Details</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-400">Status</label>
                                <div class="mt-1">
                                    @if($userPlan->isActive())
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @elseif($userPlan->isCancelled())
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Cancelled
                                        </span>
                                    @elseif($userPlan->isExpired())
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Expired
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ ucfirst($userPlan->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-400">Amount Paid</label>
                                <div class="text-lg font-semibold text-white">{{ $userPlan->formatted_amount_paid }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-400">Currency</label>
                                <div class="text-white">{{ $userPlan->currency }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-400">Start Date</label>
                                <div class="text-white">{{ $userPlan->start_date ? $userPlan->start_date->format('M d, Y H:i') : 'N/A' }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-400">End Date</label>
                                <div class="text-white">{{ $userPlan->end_date ? $userPlan->end_date->format('M d, Y H:i') : 'N/A' }}</div>
                            </div>
                            
                            @if($userPlan->isActive() && $userPlan->end_date)
                                <div>
                                    <label class="block text-sm font-medium text-gray-400">Remaining Days</label>
                                    <div class="text-white">{{ $userPlan->remaining_days }} days</div>
                                </div>
                            @endif
                            
                            @if($userPlan->notes)
                                <div>
                                    <label class="block text-sm font-medium text-gray-400">Notes</label>
                                    <div class="text-gray-300">{{ $userPlan->notes }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Signals Table for Signal Plans -->
        @if($userPlan->plan->type === 'signal' && $signals->count() > 0)
            <div class="mt-8 bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-white mb-4">Signal History</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Signal</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Pair</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Entry Price</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Target</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Stop Loss</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach($signals as $signal)
                                    <tr class="hover:bg-gray-700 transition-colors">
                                        <td class="px-4 py-4">
                                            <div class="text-sm font-medium text-white">{{ $signal->title }}</div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-300">
                                            {{ $signal->symbol }}
                                        </td>
                                        <td class="px-4 py-4">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $signal->type === 'buy' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($signal->type) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-300">
                                            {{ number_format($signal->entry_price, 2) }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-300">
                                            {{ number_format($signal->take_profit, 2) }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-300">
                                            {{ number_format($signal->stop_loss, 2) }}
                                        </td>
                                        <td class="px-4 py-4">
                                            @if($signal->status === 'active')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            @elseif($signal->status === 'completed')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    Completed
                                                </span>
                                            @elseif($signal->status === 'cancelled')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                    Cancelled
                                                </span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    {{ ucfirst($signal->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-300">
                                            {{ $signal->created_at->format('M d, Y H:i') }}
                                        </td>
                                        <td class="px-4 py-4 text-sm font-medium">
                                            <a href="{{ route('user.signals.show', $signal) }}" class="text-blue-400 hover:text-blue-300 transition-colors">
                                                View Details
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @elseif($userPlan->plan->type === 'signal')
            <div class="mt-8 bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
                <div class="p-6 text-center">
                    <div class="text-gray-400 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-white mb-2">No Signals Available</h3>
                    <p class="text-gray-400">No signals have been published for this plan yet.</p>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
