@extends('dashboard.layout.app')

@section('content')
<div class="space-y-6">
    <!-- Balance Card -->
    <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-white">Mining Overview</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gray-700 rounded-lg p-4">
                <div class="text-gray-400 text-sm">Total Invested</div>
                <div class="text-white font-semibold">${{ number_format($totalInvested, 2) }}</div>
            </div>
            <div class="bg-gray-700 rounded-lg p-4">
                <div class="text-gray-400 text-sm">Active Mining</div>
                <div class="text-green-400 font-semibold">{{ $activeMinings }}</div>
            </div>
            <div class="bg-gray-700 rounded-lg p-4">
                <div class="text-gray-400 text-sm">Total Mined</div>
                <div class="text-blue-400 font-semibold">${{ number_format($totalMined, 2) }}</div>
            </div>
            <div class="bg-gray-700 rounded-lg p-4">
                <div class="text-gray-400 text-sm">Current Value</div>
                <div class="text-purple-400 font-semibold">${{ number_format($currentValue, 2) }}</div>
            </div>
        </div>
    </div>

    <!-- Mining Activities Table -->
    <div class="bg-gray-800 rounded-lg border border-gray-700">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-lg font-semibold text-white">Mining Activities</h3>
        </div>
        <div class="overflow-x-auto">
            @if($minings->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Plan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Amount Invested</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Hashrate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Total Mined</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Current Value</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-800 divide-y divide-gray-700">
                        @foreach($minings as $mining)
                        <tr class="hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-white">{{ $mining->plan->name }}</div>
                                <div class="text-sm text-gray-400">{{ $mining->plan->description }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                ${{ number_format($mining->amount_invested, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-400">
                                {{ $mining->hashrate ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($mining->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @elseif($mining->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Completed
                                    </span>
                                @elseif($mining->status === 'cancelled')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Cancelled
                                    </span>
                                @elseif($mining->status === 'suspended')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Suspended
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($mining->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-400">
                                ${{ number_format($mining->total_mined, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-purple-400">
                                ${{ number_format($mining->current_value, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('user.mining.show', $mining) }}" class="text-blue-400 hover:text-blue-300">View Details</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-6 text-center">
                    <div class="text-gray-400 text-lg">No mining activities found</div>
                    <div class="mt-2">
                        <a href="{{ route('user.mining.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Start Mining
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@include('dashboard.portfolio.partials.footer-menu')
@endsection
