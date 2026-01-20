@extends('dashboard.layout.app')

@section('content')
<div class="space-y-6">
    <!-- Balance Card -->
    <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-white">Signal Overview</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gray-700 rounded-lg p-4">
                <div class="text-gray-400 text-sm">Signal Investment</div>
                <div class="text-white font-semibold">${{ number_format($totalSignalInvestment, 2) }}</div>
            </div>
            <div class="bg-gray-700 rounded-lg p-4">
                <div class="text-gray-400 text-sm">Active Plans</div>
                <div class="text-green-400 font-semibold">{{ $activeSignalPlans }}</div>
            </div>
            <div class="bg-gray-700 rounded-lg p-4">
                <div class="text-gray-400 text-sm">Signals Remaining</div>
                <div class="text-blue-400 font-semibold">{{ $totalSignalsRemaining }}</div>
            </div>
            <div class="bg-gray-700 rounded-lg p-4">
                <div class="text-gray-400 text-sm">Signals Used</div>
                <div class="text-purple-400 font-semibold">{{ $totalSignalsUsed }}</div>
            </div>
        </div>
    </div>

    <!-- Signal Plans Table -->
    <div class="bg-gray-800 rounded-lg border border-gray-700">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-lg font-semibold text-white">Signal Plans</h3>
        </div>
        <div class="overflow-x-auto">
            @if($signalPlans->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Plan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Signals Remaining</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Daily Used</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Start Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">End Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-800 divide-y divide-gray-700">
                        @foreach($signalPlans as $plan)
                        <tr class="hover:bg-gray-700">
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-400">
                                {{ $plan->signal_quantity_remaining ?? 'Unlimited' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-purple-400">
                                {{ $plan->daily_signals_used ?? 0 }}
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
                    <div class="text-gray-400 text-lg">No signal plans found</div>
                    <div class="mt-2">
                        <a href="{{ route('user.plans.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Subscribe to a Signal Plan
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Signal Tips -->
    <div class="bg-gray-800 rounded-lg border border-gray-700">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-lg font-semibold text-white">Signal Trading Tips</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-700 rounded-lg p-4">
                    <h4 class="text-white font-medium mb-2">📊 Signal Analysis</h4>
                    <p class="text-sm text-gray-300">Always analyze signals before executing trades. Consider market conditions and your risk tolerance.</p>
                </div>
                <div class="bg-gray-700 rounded-lg p-4">
                    <h4 class="text-white font-medium mb-2">⚡ Quick Execution</h4>
                    <p class="text-sm text-gray-300">Signals are time-sensitive. Execute trades quickly to capture the best entry points.</p>
                </div>
                <div class="bg-gray-700 rounded-lg p-4">
                    <h4 class="text-white font-medium mb-2">🎯 Risk Management</h4>
                    <p class="text-sm text-gray-300">Set stop-loss orders and never risk more than you can afford to lose on any single trade.</p>
                </div>
                <div class="bg-gray-700 rounded-lg p-4">
                    <h4 class="text-white font-medium mb-2">📈 Portfolio Diversification</h4>
                    <p class="text-sm text-gray-300">Don't put all your funds into signals. Maintain a balanced portfolio across different strategies.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@include('dashboard.portfolio.partials.footer-menu')
@endsection
