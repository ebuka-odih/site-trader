@extends('dashboard.layout.app')

@section('content')
<div class="min-h-screen bg-gray-900">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Mining Statistics</h1>
                        <p class="text-gray-400">Track your mining performance and earnings</p>
                    </div>
                    <a href="{{ route('user.mining.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Back to Mining
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

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-400 text-sm">Total Mining</p>
                            <p class="text-white text-2xl font-semibold">{{ $totalMinings }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-400 text-sm">Active Mining</p>
                            <p class="text-white text-2xl font-semibold">{{ $activeMinings }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-400 text-sm">Total Invested</p>
                            <p class="text-white text-2xl font-semibold">${{ number_format($totalInvested, 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-400 text-sm">Total Mined</p>
                            <p class="text-green-400 text-2xl font-semibold">{{ number_format($totalMined, 8) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Mining Activity -->
                <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg">
                    <div class="px-6 py-4 border-b border-gray-700">
                        <h2 class="text-xl font-semibold text-white">Recent Mining Activity</h2>
                    </div>
                    <div class="divide-y divide-gray-700">
                        @forelse($recentMinings as $mining)
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="text-white font-medium">{{ $mining->plan->name }}</div>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $mining->status_badge_class }}">
                                        {{ ucfirst($mining->status) }}
                                    </span>
                                </div>
                                <div class="text-gray-400 text-sm space-y-1">
                                    <div>Invested: {{ $mining->formatted_amount_invested }}</div>
                                    <div>Mined: {{ $mining->formatted_total_mined }}</div>
                                    <div>Started: {{ $mining->start_date->format('M d, Y') }}</div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('user.mining.show', $mining) }}" class="text-blue-400 hover:text-blue-300 text-sm font-medium">
                                        View Details →
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-gray-400 text-center">
                                <p>No mining activity yet.</p>
                                <a href="{{ route('user.mining.create') }}" class="text-blue-400 hover:text-blue-300 mt-2 inline-block">
                                    Start your first mining subscription
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Performance Chart -->
                <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg">
                    <div class="px-6 py-4 border-b border-gray-700">
                        <h2 class="text-xl font-semibold text-white">Mining Performance</h2>
                    </div>
                    <div class="p-6">
                        <div class="text-center py-12">
                            <div class="text-gray-400 mb-4">
                                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-400">Performance chart coming soon</p>
                            <p class="text-gray-500 text-sm mt-2">We're working on detailed analytics</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
