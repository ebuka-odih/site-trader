@extends('dashboard.layout.app')

@section('content')
<div class="min-h-screen bg-gray-900">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Mining Dashboard</h1>
                        <p class="text-gray-400">Manage your cryptocurrency mining subscriptions</p>
                    </div>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('user.mining.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            Start Mining
                        </a>
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

            <!-- Tabs -->
            <div class="mb-8">
                <div class="border-b border-gray-700">
                    <nav class="-mb-px flex space-x-8">
                        <button onclick="showTab('mining')" id="miningTab" class="tab-button py-2 px-1 border-b-2 border-blue-500 text-blue-400 font-medium text-sm">
                            Mining Plans
                        </button>
                        <button onclick="showTab('statistics')" id="statisticsTab" class="tab-button py-2 px-1 border-b-2 border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300 font-medium text-sm">
                            Statistics
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Mining Tab Content -->
            <div id="miningContent" class="tab-content">
                <div class="space-y-6">
                        <!-- Available Mining Plans -->
                        <div class="bg-gray-800 rounded-xl overflow-hidden shadow">
                            <div class="px-6 py-4 border-b border-gray-700">
                                <h2 class="text-lg font-semibold text-white">Available Mining Plans</h2>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($miningPlans as $plan)
                                        <div class="bg-gray-700 rounded-lg p-4 border border-gray-600">
                                            <div class="flex items-center justify-between mb-3">
                                                <h3 class="text-white font-semibold">{{ $plan->name }}</h3>
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ ucfirst($plan->type) }}
                                                </span>
                                            </div>
                                            <div class="space-y-2 mb-4">
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-400">Hashrate:</span>
                                                    <span class="text-white">{{ $plan->hashrate ?? 'N/A' }}</span>
                                                </div>
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-400">Equipment:</span>
                                                    <span class="text-white">{{ $plan->equipment ?? 'N/A' }}</span>
                                                </div>
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-400">Uptime:</span>
                                                    <span class="text-white">{{ $plan->downtime ?? 'N/A' }}</span>
                                                </div>
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-400">Electricity:</span>
                                                    <span class="text-white">{{ $plan->electricity_costs ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between mb-4">
                                                <div>
                                                    <div class="text-2xl font-bold text-white">{{ auth()->user()->formatAmount($plan->price) }}</div>
                                                    <div class="text-sm text-gray-400">per month</div>
                                                </div>
                                            </div>
                                            <a href="{{ route('user.mining.create', ['plan' => $plan->id]) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition-colors text-center block">
                                                Start Mining
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Statistics Tab Content -->
            <div id="statisticsContent" class="tab-content hidden">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Statistics Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Performance Overview -->
                        <div class="bg-gray-800 rounded-xl overflow-hidden shadow">
                            <div class="px-6 py-4 border-b border-gray-700">
                                <h2 class="text-lg font-semibold text-white">Performance Overview</h2>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                    <div class="text-center">
                                        <div class="text-3xl font-bold text-white mb-2">{{ $activeMinings->count() + $completedMinings->count() }}</div>
                                        <div class="text-gray-400 text-sm">Total Mining</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-3xl font-bold text-green-400 mb-2">
                                            @php
                                                $totalMined = $activeMinings->sum('total_mined') + $completedMinings->sum('total_mined');
                                                echo number_format($totalMined, 8);
                                            @endphp
                                        </div>
                                        <div class="text-gray-400 text-sm">Total Mined</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-3xl font-bold text-blue-400 mb-2">
                                            @php
                                                $totalInvested = $activeMinings->sum('amount_invested') + $completedMinings->sum('amount_invested');
                                                echo '$' . number_format($totalInvested, 2);
                                            @endphp
                                        </div>
                                        <div class="text-gray-400 text-sm">Total Invested</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-3xl font-bold text-yellow-400 mb-2">
                                            @php
                                                $totalValue = $activeMinings->sum('current_value') + $completedMinings->sum('current_value');
                                                echo '$' . number_format($totalValue, 2);
                                            @endphp
                                        </div>
                                        <div class="text-gray-400 text-sm">Current Value</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mining History -->
                        <div class="bg-gray-800 rounded-xl overflow-hidden shadow">
                            <div class="px-6 py-4 border-b border-gray-700">
                                <h2 class="text-lg font-semibold text-white">Mining History</h2>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-700">
                                    <thead class="bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Plan</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Invested</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Mined</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Value</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Start Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-gray-800 divide-y divide-gray-700">
                                        @foreach($activeMinings->concat($completedMinings) as $mining)
                                        <tr class="hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                                                {{ $mining->plan->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($mining->status === 'active')
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                        Active
                                                    </span>
                                                @elseif($mining->status === 'completed')
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        Completed
                                                    </span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        {{ ucfirst($mining->status) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                                ${{ number_format($mining->amount_invested, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                                {{ number_format($mining->total_mined, 8) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                                ${{ number_format($mining->current_value, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                                {{ $mining->start_date->format('M d, Y') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Sidebar -->
                    <div class="space-y-6">
                        <!-- Quick Stats -->
                        <div class="bg-gray-800 rounded-xl overflow-hidden shadow">
                            <div class="px-6 py-4 border-b border-gray-700">
                                <h2 class="text-lg font-semibold text-white">Quick Stats</h2>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Active Mining:</span>
                                    <span class="text-white font-semibold">{{ $activeMinings->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Completed:</span>
                                    <span class="text-white font-semibold">{{ $completedMinings->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Total Invested:</span>
                                    <span class="text-white font-semibold">
                                        @php
                                            $totalInvested = $activeMinings->sum('amount_invested') + $completedMinings->sum('amount_invested');
                                            echo '$' . number_format($totalInvested, 2);
                                        @endphp
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Total Mined:</span>
                                    <span class="text-green-400 font-semibold">
                                        @php
                                            $totalMined = $activeMinings->sum('total_mined') + $completedMinings->sum('total_mined');
                                            echo number_format($totalMined, 8);
                                        @endphp
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Current Value:</span>
                                    <span class="text-yellow-400 font-semibold">
                                        @php
                                            $totalValue = $activeMinings->sum('current_value') + $completedMinings->sum('current_value');
                                            echo '$' . number_format($totalValue, 2);
                                        @endphp
                                    </span>
                                </div>
                                <hr class="border-gray-700">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Total PnL:</span>
                                    <span class="font-semibold {{ ($totalValue - $totalInvested) >= 0 ? 'text-green-400' : 'text-red-400' }}">
                                        @php
                                            $totalPnL = $totalValue - $totalInvested;
                                            echo ($totalPnL >= 0 ? '+' : '') . '$' . number_format($totalPnL, 2);
                                        @endphp
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Performance Tips -->
                        <div class="bg-gray-800 rounded-xl overflow-hidden shadow">
                            <div class="px-6 py-4 border-b border-gray-700">
                                <h2 class="text-lg font-semibold text-white">Performance Tips</h2>
                            </div>
                            <div class="p-6 space-y-3">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-medium">Track Your ROI</h4>
                                        <p class="text-gray-400 text-sm">Monitor your return on investment regularly</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-medium">Optimize Timing</h4>
                                        <p class="text-gray-400 text-sm">Start mining during favorable market conditions</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-medium">Diversify Portfolio</h4>
                                        <p class="text-gray-400 text-sm">Spread investments across different mining plans</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });

    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-blue-500', 'text-blue-400');
        button.classList.add('border-transparent', 'text-gray-400');
    });

    // Show selected tab content
    if (tabName === 'mining') {
        document.getElementById('miningContent').classList.remove('hidden');
        document.getElementById('miningTab').classList.add('border-blue-500', 'text-blue-400');
        document.getElementById('miningTab').classList.remove('border-transparent', 'text-gray-400');
    } else if (tabName === 'statistics') {
        document.getElementById('statisticsContent').classList.remove('hidden');
        document.getElementById('statisticsTab').classList.add('border-blue-500', 'text-blue-400');
        document.getElementById('statisticsTab').classList.remove('border-transparent', 'text-gray-400');
    }
}
</script>
@endsection
