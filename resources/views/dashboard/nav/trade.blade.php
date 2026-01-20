@extends('dashboard.new-layout')

@section('content')
@php
    $holdingsBySymbol = $holdingsBySymbol ?? collect();
@endphp
<div class="space-y-8 text-white">
    <div>
        <p class="text-xs uppercase tracking-wide text-[#08f58d]">Trading Hub</p>
        <h1 class="text-3xl font-semibold tracking-tight">Ready to trade, {{ $user->name }}?</h1>
        <p class="text-gray-400">Launch live trading or explore curated market ideas.</p>
    </div>

    <div class="rounded-[32px] bg-[#050505] p-6 border border-[#0f0f0f]">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-xs uppercase text-gray-400">Live Trading</p>
                <p class="text-2xl font-semibold">Trade crypto & stocks</p>
                <p class="text-gray-500 text-sm">Use the advanced trading workstation for real-time execution.</p>
            </div>
            <a href="{{ route('user.nav.assets', ['type' => 'stock']) }}" class="rounded-full bg-[#00ff5f] px-6 py-3 text-black font-semibold text-sm text-center">Open Trading Desk</a>
        </div>
    </div>

    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 rounded-full border border-[#1a1a1a] bg-[#050505] p-1">
                <button class="trade-tab rounded-full px-4 py-1 text-xs font-semibold bg-[#00ff5f] text-black" data-target="#stock-list">Stocks</button>
                <button class="trade-tab rounded-full px-4 py-1 text-xs font-semibold text-gray-400" data-target="#crypto-list">Crypto</button>
                <button class="trade-tab rounded-full px-4 py-1 text-xs font-semibold text-gray-400" data-target="#history-list">History</button>
            </div>
            <a href="{{ route('user.nav.assets', ['type' => 'stock']) }}" class="text-xs text-[#08f58d] hover:text-white">See market</a>
        </div>
        <div id="stock-list" class="grid gap-3 md:grid-cols-2">
            @forelse($stockAssets as $asset)
                @php $positive = $asset->price_change_24h >= 0; @endphp
                <a href="{{ route('user.liveTrading.trade', ['asset_type' => 'stock', 'symbol' => $asset->symbol]) }}" class="rounded-3xl border border-[#151515] bg-[#040404] p-4 flex items-center justify-between hover:border-[#1fff9c]/40">
                    <div>
                        <p class="text-sm font-semibold text-white">{{ $asset->symbol }}</p>
                        <p class="text-xs text-gray-500">{{ $asset->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-white text-lg font-semibold">${{ number_format($asset->current_price, 2) }}</p>
                        <p class="text-xs {{ $positive ? 'text-green-400' : 'text-red-400' }}">{{ $positive ? '+' : '' }}{{ number_format($asset->price_change_24h, 2) }}%</p>
                    </div>
                </a>
            @empty
                <p class="text-xs text-gray-500">No stock data available.</p>
            @endforelse
        </div>
        <div id="crypto-list" class="grid gap-3 md:grid-cols-2 hidden">
            @forelse($cryptoAssets as $asset)
                @php $positive = $asset->price_change_24h >= 0; @endphp
                <a href="{{ route('user.liveTrading.trade', ['asset_type' => 'crypto', 'symbol' => $asset->symbol]) }}" class="rounded-3xl border border-[#151515] bg-[#040404] p-4 flex items-center justify-between hover:border-[#00b7ff]/40">
                    <div>
                        <p class="text-sm font-semibold text-white">{{ strtoupper($asset->symbol) }}</p>
                        <p class="text-xs text-gray-500">{{ $asset->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-white text-lg font-semibold">${{ number_format($asset->current_price, 2) }}</p>
                        <p class="text-xs {{ $positive ? 'text-green-400' : 'text-red-400' }}">{{ $positive ? '+' : '' }}{{ number_format($asset->price_change_24h, 2) }}%</p>
                    </div>
                </a>
            @empty
                <p class="text-xs text-gray-500">No crypto data available.</p>
            @endforelse
        </div>
        <div id="history-list" class="grid gap-3 md:grid-cols-2 hidden">
            @forelse($tradeHistory as $trade)
                @php
                    $isBuy = strtolower($trade->side) === 'buy';
                    $statusColor = match($trade->status) {
                        'completed', 'closed' => 'text-green-400',
                        'cancelled' => 'text-red-400',
                        default => 'text-yellow-400',
                    };
                    $tradeSymbol = strtoupper($trade->symbol);
                    $holding = $holdingsBySymbol[$tradeSymbol] ?? null;
                    $pnlValue = $holding?->unrealized_pnl ?? null;
                    $pnlPercent = $holding?->unrealized_pnl_percentage ?? null;
                    $pnlPositive = $pnlValue >= 0;
                @endphp
                <div class="rounded-3xl border border-[#151515] bg-[#040404] p-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold {{ $isBuy ? 'text-green-400' : 'text-red-400' }}">
                            {{ strtoupper($trade->side) }} • {{ strtoupper($trade->symbol) }}
                        </p>
                        <p class="text-xs text-gray-500">{{ ucfirst($trade->order_type) }} • {{ $trade->created_at?->diffForHumans() }}</p>
                        @if(!is_null($pnlValue))
                            <p class="text-xs {{ $pnlPositive ? 'text-green-400' : 'text-red-400' }}">
                                Gain/Loss: {{ $pnlPositive ? '+' : '' }}{{ $user->formatAmount(abs($pnlValue)) }}
                                ({{ number_format($pnlPercent ?? 0, 2) }}%)
                            </p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-white text-lg font-semibold">${{ number_format($trade->amount, 2) }}</p>
                        <p class="text-xs {{ $statusColor }}">{{ ucfirst($trade->status) }}</p>
                    </div>
                </div>
            @empty
                <p class="text-xs text-gray-500">No trading activity yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const buttons = document.querySelectorAll('.trade-tab');
        const lists = ['#stock-list', '#crypto-list', '#history-list'].map(id => document.querySelector(id));
        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                buttons.forEach(b => b.classList.remove('bg-[#00ff5f]', 'text-black'));
                buttons.forEach(b => b.classList.add('text-gray-400'));
                btn.classList.add('bg-[#00ff5f]', 'text-black');
                btn.classList.remove('text-gray-400');
                lists.forEach(list => list.classList.add('hidden'));
                const target = document.querySelector(btn.dataset.target);
                target?.classList.remove('hidden');
            });
        });
    });
</script>
@endpush
