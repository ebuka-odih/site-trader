@extends('dashboard.layout.app')

@section('content')
<div class="min-h-screen bg-gray-900">
    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">
                <h1 class="text-xl sm:text-2xl font-bold text-white">Signal Details</h1>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('user.signals.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium text-center">Back to Signals</a>
                    <button id="copySignalBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Copy</button>
                </div>
            </div>

            <div class="bg-gray-800 rounded-xl shadow overflow-hidden">
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-gray-400 text-sm">Title</div>
                            <div class="text-white font-medium">{{ $signal->title }}</div>
                        </div>
                        <div>
                            <div class="text-gray-400 text-sm">Pair</div>
                            <div class="text-white font-medium">{{ $signal->symbol }}</div>
                        </div>
                        <div>
                            <div class="text-gray-400 text-sm">Type</div>
                            <div class="text-white font-medium">{{ ucfirst($signal->type) }}</div>
                        </div>
                        <div>
                            <div class="text-gray-400 text-sm">Strength</div>
                            <div class="text-white font-medium">{{ $signal->signal_strength_stars }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <div class="text-gray-400 text-sm">Entry</div>
                            <div class="text-white font-medium">{{ $signal->formatted_entry_price }}</div>
                        </div>
                        <div>
                            <div class="text-gray-400 text-sm">Stop Loss</div>
                            <div class="text-white font-medium">{{ $signal->formatted_stop_loss ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="text-gray-400 text-sm">Take Profit</div>
                            <div class="text-white font-medium">{{ $signal->formatted_take_profit ?? '—' }}</div>
                        </div>
                    </div>

                    @if($signal->tradingview_link)
                        <div>
                            <div class="text-gray-400 text-sm mb-2">TradingView</div>
                            <a href="{{ $signal->tradingview_link }}" target="_blank" class="text-blue-400 hover:text-blue-300">Open Chart</a>
                        </div>
                    @endif

                    @if($signal->analysis)
                        <div>
                            <div class="text-gray-400 text-sm mb-2">Analysis</div>
                            <div class="text-gray-200 whitespace-pre-line">{{ $signal->analysis }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Copy via controller (AJAX) for the same formatting used elsewhere
document.getElementById('copySignalBtn').addEventListener('click', async () => {
    try {
        const res = await fetch("{{ route('user.signals.copy', $signal) }}", {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}});
        const data = await res.json();
        if (data.success) {
            await navigator.clipboard.writeText(data.signal_text);
            alert('Signal copied to clipboard');
        } else {
            alert(data.error || 'Failed to copy');
        }
    } catch (e) {
        alert('Failed to copy');
    }
});
</script>
@endsection
