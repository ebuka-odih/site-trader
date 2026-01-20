@extends('dashboard.new-layout')

@section('content')
<div class="space-y-8 text-white">
    <div class="flex flex-col gap-4">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('user.copyTrading.index') }}" class="text-gray-400 hover:text-white transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-[#08f58d]">Copy Trading</p>
                    <h1 class="text-3xl font-semibold tracking-tight">Copy Trade Details</h1>
                    <p class="text-gray-400 text-sm">View performance and manage this mirrored position.</p>
                </div>
            </div>
            @if($copiedTrade->status == 1)
                <form action="{{ route('user.copyTrading.stop', $copiedTrade->id) }}" method="POST" onsubmit="return confirm('Stop copying {{ $trader->name }}?')">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 rounded-2xl bg-gradient-to-r from-[#ff4d4f] to-[#ff6b6b] px-6 py-3 text-sm font-semibold text-white shadow transition hover:brightness-110">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Stop Copying
                    </button>
                </form>
            @elseif($copiedTrade->status == 0 && $copiedTrade->stopped_at)
                <form action="{{ route('user.copyTrading.resume', $copiedTrade->id) }}" method="POST" onsubmit="return confirm('Resume copying {{ $trader->name }}? This will deduct ${{ number_format($copiedTrade->amount, 2) }} from your trading balance.')">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 rounded-2xl border border-[#1fff9c]/40 bg-[#071c11] px-6 py-3 text-sm font-semibold text-[#1fff9c] transition hover:border-[#1fff9c] hover:bg-[#0a2515]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Resume Bot
                    </button>
                </form>
            @endif
        </div>
        @if(session('success'))
            <div class="rounded-2xl border border-[#1fff9c]/30 bg-[#071c11] px-4 py-3 text-sm text-[#1fff9c]">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="rounded-2xl border border-red-500/40 bg-[#210404] px-4 py-3 text-sm text-red-300">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <div class="rounded-[32px] border border-[#111111] bg-[#050505] p-6 space-y-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <img src="{{ $trader->avatar_url }}" alt="{{ $trader->name }}" class="h-16 w-16 rounded-full border border-[#1a1a1a] object-cover" onerror="this.src='{{ asset('img/trader.jpg') }}'">
                    <span class="absolute -bottom-1 -right-1 h-4 w-4 rounded-full border border-[#050505] {{ $copiedTrade->status ? 'bg-[#1fff9c]' : 'bg-gray-500' }}"></span>
                </div>
                <div>
                    <p class="text-lg font-semibold">{{ $trader->name }}</p>
                    <p class="text-xs text-gray-500">Professional Trader • {{ $tradeCount }} total trades</p>
                    <span class="mt-1 inline-flex items-center gap-1 rounded-full bg-[#071c11] px-2.5 py-0.5 text-xs text-[#08f58d]">
                        <span class="h-2 w-2 rounded-full bg-[#08f58d]"></span>{{ $trader->win_rate }}% win rate
                    </span>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500">Status</p>
                <span class="inline-flex items-center gap-2 rounded-full border px-4 py-1 text-sm {{ $copiedTrade->status ? 'border-[#1fff9c]/40 text-[#1fff9c]' : 'border-gray-600 text-gray-400' }}">
                    <span class="h-2 w-2 rounded-full {{ $copiedTrade->status ? 'bg-[#1fff9c]' : 'bg-gray-500' }}"></span>
                    {{ $copiedTrade->status ? 'Active' : 'Stopped' }}
                </span>
            </div>
        </div>
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-5">
            <div class="rounded-2xl border border-[#0f0f0f] bg-[#030303] p-4">
                <p class="text-xs text-gray-500">Investment</p>
                <p class="mt-2 text-2xl font-semibold">{{ $user->formatAmount($copiedTrade->amount) }}</p>
            </div>
            <div class="rounded-2xl border border-[#0f0f0f] bg-[#030303] p-4">
                <p class="text-xs text-gray-500">Trade Count</p>
                <p class="mt-2 text-2xl font-semibold">{{ $tradeCount }}</p>
            </div>
            <div class="rounded-2xl border border-[#0f0f0f] bg-[#030303] p-4">
                <p class="text-xs text-gray-500">Profit / Loss</p>
                <p class="mt-2 text-2xl font-semibold {{ $pnl >= 0 ? 'text-[#1fff9c]' : 'text-red-400' }}">{{ $user->formatAmount($pnl) }}</p>
            </div>
            <div class="rounded-2xl border border-[#0f0f0f] bg-[#030303] p-4">
                <p class="text-xs text-gray-500">ROI</p>
                <p class="mt-2 text-2xl font-semibold {{ $roi >= 0 ? 'text-[#1fff9c]' : 'text-red-400' }}">{{ number_format($roi, 2) }}%</p>
            </div>
            <div class="rounded-2xl border border-[#0f0f0f] bg-[#030303] p-4">
                <p class="text-xs text-gray-500">Duration</p>
                <p class="mt-2 text-2xl font-semibold">{{ $copiedTrade->created_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>

    <div class="rounded-[32px] border border-[#111111] bg-[#050505] p-6 space-y-6">
        <div class="flex flex-col gap-2">
            <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Performance</p>
            <h2 class="text-2xl font-semibold">Trader Metrics</h2>
        </div>
        @php 
            $totalTrades = $wins + $losses;
            $winRate = $totalTrades > 0 ? round(($wins / $totalTrades) * 100, 1) : 0;
            $barPercent = min(100, max(0, $winRate));
        @endphp
        <div class="grid gap-4 md:grid-cols-4">
            <div class="rounded-2xl border border-[#0d0d0d] bg-[#030303] p-4 text-center">
                <p class="text-xs text-gray-500">Total Trades</p>
                <p class="mt-2 text-3xl font-semibold text-white">{{ $totalTrades }}</p>
            </div>
            <div class="rounded-2xl border border-[#0d0d0d] bg-[#030303] p-4 text-center">
                <p class="text-xs text-gray-500">Win Rate</p>
                <p class="mt-2 text-3xl font-semibold text-[#1fff9c]">{{ $winRate }}%</p>
                <div class="mt-3 h-1.5 w-full overflow-hidden rounded-full bg-[#111111]">
                    <div class="h-full rounded-full bg-gradient-to-r from-[#1fff9c] to-[#05c46b]" style="width: {{ $barPercent }}%"></div>
                </div>
            </div>
            <div class="rounded-2xl border border-[#0d0d0d] bg-[#030303] p-4 text-center">
                <p class="text-xs text-gray-500">Winning Trades</p>
                <p class="mt-2 text-3xl font-semibold text-[#1fff9c]">{{ $wins }}</p>
            </div>
            <div class="rounded-2xl border border-[#0d0d0d] bg-[#030303] p-4 text-center">
                <p class="text-xs text-gray-500">Losing Trades</p>
                <p class="mt-2 text-3xl font-semibold text-red-400">{{ $losses }}</p>
            </div>
        </div>
    </div>

    <div class="rounded-[32px] border border-[#111111] bg-[#050505] p-6 space-y-6">
        <div class="flex flex-col gap-2">
            <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Timeline</p>
            <h2 class="text-2xl font-semibold">Trade History</h2>
        </div>
        <div class="space-y-4">
            <div class="flex items-center gap-4 rounded-2xl border border-[#0e0e0e] bg-[#030303] px-4 py-3">
                <span class="h-3 w-3 rounded-full bg-[#1fff9c]"></span>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-white">Copy Trade Started</p>
                    <p class="text-xs text-gray-500">{{ $copiedTrade->created_at->format('M d, Y \\a\\t g:i A') }}</p>
                </div>
            </div>
            @if($copiedTrade->stopped_at)
                <div class="flex items-center gap-4 rounded-2xl border border-[#0e0e0e] bg-[#030303] px-4 py-3">
                    <span class="h-3 w-3 rounded-full bg-red-400"></span>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-white">Copy Trade Stopped</p>
                        <p class="text-xs text-gray-500">{{ $copiedTrade->stopped_at->format('M d, Y \\a\\t g:i A') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="rounded-[32px] border border-[#111111] bg-[#050505] p-6 space-y-6">
        <div class="flex flex-col gap-2">
            <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Performance</p>
            <h2 class="text-2xl font-semibold">PNL History</h2>
        </div>
        @if($copiedTrade->pnl_histories && $copiedTrade->pnl_histories->count() > 0)
            <div class="space-y-3">
                @foreach($copiedTrade->pnl_histories as $pnlHistory)
                    <div class="flex items-center gap-4 rounded-2xl border border-[#0e0e0e] bg-[#030303] px-4 py-3">
                        <span class="h-3 w-3 rounded-full {{ $pnlHistory->pnl >= 0 ? 'bg-[#1fff9c]' : 'bg-red-400' }}"></span>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-white">
                                    {{ $pnlHistory->pnl >= 0 ? 'Profit' : 'Loss' }}
                                </p>
                                <p class="text-sm font-semibold {{ $pnlHistory->pnl >= 0 ? 'text-[#1fff9c]' : 'text-red-400' }}">
                                    {{ $user->formatAmount($pnlHistory->pnl) }}
                                </p>
                            </div>
                            @if($pnlHistory->description)
                                <p class="text-xs text-gray-400 mt-1">{{ $pnlHistory->description }}</p>
                            @endif
                            <p class="text-xs text-gray-500 mt-1">{{ $pnlHistory->created_at->format('M d, Y \\a\\t g:i A') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-2xl border border-[#0e0e0e] bg-[#030303] px-4 py-8 text-center">
                <p class="text-sm text-gray-500">No PNL history entries yet.</p>
            </div>
        @endif
    </div>

    <div class="rounded-[32px] border border-[#111111] bg-[#050505] p-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <a href="{{ route('user.copyTrading.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-[#1a1a1a] px-6 py-3 text-sm font-semibold text-gray-300 hover:text-white">
            Back to Copy Trading
        </a>
        @if($copiedTrade->status)
            <p class="text-xs text-gray-500">This position mirrors {{ $trader->name }} and updates automatically.</p>
        @elseif($copiedTrade->stopped_at)
            <p class="text-xs text-gray-500">This copy trade is stopped. Use the Resume Bot button above to reactivate it.</p>
        @else
            <p class="text-xs text-gray-500">This copy trade is inactive. Start a new one from the marketplace.</p>
        @endif
    </div>
</div>
@endsection
