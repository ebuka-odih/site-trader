@extends('dashboard.layout.app')

@section('content')
<div class="min-h-screen bg-gray-900">
    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">
                <h1 class="text-xl sm:text-2xl font-bold text-white">Signal Statistics</h1>
                <a href="{{ route('user.signals.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium text-center">Back to Signals</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-gray-800 rounded-xl p-6">
                    <div class="text-gray-400 text-sm">Total Signals</div>
                    <div class="text-2xl font-bold text-white">{{ $totalSignals }}</div>
                </div>
                <div class="bg-gray-800 rounded-xl p-6">
                    <div class="text-gray-400 text-sm">Active</div>
                    <div class="text-2xl font-bold text-white">{{ $activeSignals }}</div>
                </div>
                <div class="bg-gray-800 rounded-xl p-6">
                    <div class="text-gray-400 text-sm">Completed</div>
                    <div class="text-2xl font-bold text-white">{{ $completedSignals }}</div>
                </div>
                <div class="bg-gray-800 rounded-xl p-6">
                    <div class="text-gray-400 text-sm">Success Rate</div>
                    <div class="text-2xl font-bold text-white">{{ number_format($successRate, 1) }}%</div>
                </div>
            </div>

            <div class="bg-gray-800 rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-700">
                    <h2 class="text-lg font-semibold text-white">Recent Signals</h2>
                </div>
                <div class="divide-y divide-gray-700">
                    @forelse($recentSignals as $signal)
                        <div class="p-6 flex items-center justify-between">
                            <div>
                                <div class="text-white font-medium">{{ $signal->title }}</div>
                                <div class="text-gray-400 text-sm">{{ ucfirst($signal->type) }} • {{ $signal->symbol }}</div>
                            </div>
                            <a href="{{ route('user.signals.show', $signal) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">View</a>
                        </div>
                    @empty
                        <div class="p-6 text-gray-400">No recent signals.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
