@extends('dashboard.layout.app')

@section('content')
<div class="min-h-screen bg-gray-900">
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Mining Details</h1>
                        <p class="text-gray-400">{{ $mining->plan->name }} - {{ $mining->status }}</p>
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Mining Information -->
                    <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg">
                        <div class="px-6 py-4 border-b border-gray-700">
                            <h2 class="text-xl font-semibold text-white">Mining Information</h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">Plan Name:</span>
                                        <span class="text-white font-medium">{{ $mining->plan->name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">Status:</span>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $mining->status_badge_class }}">
                                            {{ ucfirst($mining->status) }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">Hashrate:</span>
                                        <span class="text-blue-400 font-semibold">{{ $mining->hashrate }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">Equipment:</span>
                                        <span class="text-white">{{ $mining->equipment }}</span>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">Amount Invested:</span>
                                        <span class="text-white font-semibold">{{ $mining->formatted_amount_invested }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">Total Mined:</span>
                                        <span class="text-green-400 font-semibold">{{ $mining->formatted_total_mined }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">ROI:</span>
                                        <span class="text-green-400 font-semibold">{{ $mining->roi_percentage }}%</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">Started:</span>
                                        <span class="text-white">{{ $mining->start_date->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mining Progress -->
                    @if($mining->isActive())
                    <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg">
                        <div class="px-6 py-4 border-b border-gray-700">
                            <h2 class="text-xl font-semibold text-white">Mining Progress</h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-400">Progress</span>
                                    <span class="text-white">{{ $mining->mining_progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-700 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $mining->mining_progress }}%"></div>
                                </div>
                                @if($mining->end_date)
                                    <div class="text-gray-400 text-sm">
                                        <p>Time remaining: {{ $mining->end_date->diffForHumans() }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Actions -->
                    <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg">
                        <div class="px-6 py-4 border-b border-gray-700">
                            <h2 class="text-lg font-semibold text-white">Actions</h2>
                        </div>
                        <div class="p-6 space-y-3">
                            @if($mining->isActive() && $mining->total_mined > 0)
                                <form method="POST" action="{{ route('user.mining.withdraw', $mining) }}">
                                    @csrf
                                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg font-medium transition-colors">
                                        Withdraw Mined Crypto
                                    </button>
                                </form>
                            @endif

                            @if($mining->isActive() && $mining->canBeCancelled())
                                <form method="POST" action="{{ route('user.mining.cancel', $mining) }}">
                                    @csrf
                                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg font-medium transition-colors">
                                        Cancel Mining
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($mining->notes)
                    <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg">
                        <div class="px-6 py-4 border-b border-gray-700">
                            <h2 class="text-lg font-semibold text-white">Notes</h2>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-300">{{ $mining->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
