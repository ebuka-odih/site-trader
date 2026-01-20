@extends('dashboard.layout.app')

@section('content')
<div class="min-h-screen bg-gray-900">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-white">My Signals</h1>
                    <p class="text-gray-400 text-sm">Signals available from your active signal plans</p>
                </div>
                <a href="{{ route('user.signal-subscriptions.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium text-center">Back to Subscriptions</a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-gray-800 rounded-xl overflow-hidden shadow">
                        <div class="px-6 py-4 border-b border-gray-700">
                            <h2 class="text-lg font-semibold text-white">Active Signals</h2>
                        </div>
                        <div class="divide-y divide-gray-700">
                            @forelse($activeSignals as $signal)
                                <div class="p-6 flex items-start justify-between">
                                    <div>
                                        <div class="text-white font-medium">{{ $signal->title }}</div>
                                        <div class="text-gray-400 text-sm">{{ ucfirst($signal->type) }} • {{ $signal->symbol }} • {{ optional($signal->expires_at)->diffForHumans() ?? 'No expiry' }}</div>
                                    </div>
                                    <a href="{{ route('user.signals.show', $signal) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">View</a>
                                </div>
                            @empty
                                <div class="p-6 text-gray-400">No active signals right now.</div>
                            @endforelse
                        </div>
                        <div class="px-6 py-4">{{ $activeSignals->links() }}</div>
                    </div>

                    <div class="bg-gray-800 rounded-xl overflow-hidden shadow">
                        <div class="px-6 py-4 border-b border-gray-700">
                            <h2 class="text-lg font-semibold text-white">Completed Signals</h2>
                        </div>
                        <div class="divide-y divide-gray-700">
                            @forelse($completedSignals as $signal)
                                <div class="p-6 flex items-start justify-between">
                                    <div>
                                        <div class="text-white font-medium">{{ $signal->title }}</div>
                                        <div class="text-gray-400 text-sm">{{ ucfirst($signal->type) }} • {{ $signal->symbol }} • Completed</div>
                                    </div>
                                    <a href="{{ route('user.signals.show', $signal) }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium">Details</a>
                                </div>
                            @empty
                                <div class="p-6 text-gray-400">No completed signals yet.</div>
                            @endforelse
                        </div>
                        <div class="px-6 py-4">{{ $completedSignals->links() }}</div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-gray-800 rounded-xl overflow-hidden shadow">
                        <div class="px-6 py-4 border-b border-gray-700">
                            <h2 class="text-lg font-semibold text-white">Your Plans</h2>
                        </div>
                        <ul class="p-4 space-y-3">
                            @forelse($userSignalPlans as $subscription)
                                <li class="flex items-center justify-between">
                                    <div>
                                        <div class="text-white font-medium">{{ $subscription->plan->name }}</div>
                                        <div class="text-gray-400 text-xs">Ends {{ optional($subscription->end_date)->diffForHumans() }}</div>
                                    </div>
                                    <span class="text-xs px-2 py-1 rounded-full {{ $subscription->isActive() ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-800' }}">{{ ucfirst($subscription->status) }}</span>
                                </li>
                            @empty
                                <li class="text-gray-400">No active signal plans.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
