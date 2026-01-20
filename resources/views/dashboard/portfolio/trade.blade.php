@extends('dashboard.layout.app')

@section('content')
<div class="space-y-6">
    <!-- Balance Card -->
    <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-white">Subscriptions</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gray-700 rounded-lg p-4">
                <div class="text-gray-400 text-sm">Total Investment</div>
                <div class="text-white font-semibold">${{ number_format($totalTradingInvestment, 2) }}</div>
            </div>
            <div class="bg-gray-700 rounded-lg p-4">
                <div class="text-gray-400 text-sm">Active Plans</div>
                <div class="text-green-400 font-semibold">{{ $activeTradingPlans }}</div>
            </div>
            <div class="bg-gray-700 rounded-lg p-4">
                <div class="text-gray-400 text-sm">Expired Plans</div>
                <div class="text-red-400 font-semibold">{{ $expiredTradingPlans }}</div>
            </div>
            <div class="bg-gray-700 rounded-lg p-4">
                <div class="text-gray-400 text-sm">Plan Types</div>
                <div class="text-purple-400 font-semibold">4</div>
            </div>
        </div>
    </div>

    <!-- All Subscriptions Table -->
    <div class="bg-gray-800 rounded-lg border border-gray-700">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-lg font-semibold text-white">All Subscriptions</h3>
        </div>
        <div class="overflow-x-auto">
            @if($tradingPlans->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Plan Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Plan Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Start Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">End Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-800 divide-y divide-gray-700">
                        @foreach($tradingPlans as $plan)
                        <tr class="hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($plan->plan->type === 'trading')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Trading
                                    </span>
                                @elseif($plan->plan->type === 'signal')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Signal
                                    </span>
                                @elseif($plan->plan->type === 'mining')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        Mining
                                    </span>
                                @elseif($plan->plan->type === 'staking')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Staking
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($plan->plan->type) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-white">{{ $plan->plan->name }}</div>
                                <div class="text-sm text-gray-400">{{ $plan->plan->description }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                ${{ number_format($plan->amount_paid, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($plan->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @elseif($plan->status === 'expired')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Expired
                                    </span>
                                @elseif($plan->status === 'cancelled')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Cancelled
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ ucfirst($plan->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ $plan->start_date ? $plan->start_date->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ $plan->end_date ? $plan->end_date->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('user.plans.show', $plan) }}" class="text-blue-400 hover:text-blue-300">View Details</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-6 text-center">
                    <div class="text-gray-400 text-lg">No subscriptions found</div>
                    <div class="mt-2">
                        <a href="{{ route('user.plans.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Subscribe to a Plan
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@include('dashboard.portfolio.partials.footer-menu')
@endsection
