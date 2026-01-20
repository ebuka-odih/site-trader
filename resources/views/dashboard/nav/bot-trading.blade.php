@extends('dashboard.new-layout')

@section('content')
<div class="space-y-8 text-white">
    <div class="flex flex-col gap-3">
        <p class="text-xs uppercase tracking-wide text-[#14b8a6]">Bot Automation</p>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-semibold tracking-tight">Bot Trading Control Center</h1>
                <p class="text-gray-400">Monitor active strategies, see subscribers, and launch new automations.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                <a href="{{ route('user.botTrading.create') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-[#00ff5f] px-6 py-3 text-sm font-semibold text-black">
                    <span class="text-lg">+</span>
                    Launch Bot
                </a>
                <a href="{{ route('user.botTrading.index') }}" class="inline-flex items-center justify-center rounded-full border border-[#1a1a1a] px-6 py-3 text-sm font-semibold text-gray-200 hover:border-[#14b8a6] hover:text-white">
                    Full Bot Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rounded-3xl border border-[#0f0f0f] bg-[#050505] p-5">
            <p class="text-xs uppercase text-gray-400">Active Bots</p>
            <p class="mt-2 text-3xl font-semibold">{{ $stats['active_bots'] }} / {{ $stats['total_bots'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Paused: {{ $stats['paused_bots'] }} • Stopped: {{ $stats['stopped_bots'] }}</p>
        </div>
        <div class="rounded-3xl border border-[#0f0f0f] bg-[#050505] p-5">
            <p class="text-xs uppercase text-gray-400">Bot Performance</p>
            <p class="mt-2 text-3xl font-semibold">{{ $user->formatAmount($stats['total_profit']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Invested: {{ $user->formatAmount($stats['total_invested']) }}</p>
        </div>
        <div class="rounded-3xl border border-[#0f0f0f] bg-[#050505] p-5">
            <p class="text-xs uppercase text-gray-400">Users in Bots</p>
            <p class="mt-2 text-3xl font-semibold">{{ number_format($stats['total_participants']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Across {{ $stats['total_bots'] }} strategies</p>
        </div>
    </div>

    <div class="rounded-[32px] border border-[#0f0f0f] bg-[#050505] p-6">
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold">Your Bots</h2>
                <p class="text-sm text-gray-400">Track performance and subscriber counts for each automation.</p>
            </div>
            <a href="{{ route('user.botTrading.create') }}" class="text-xs text-[#14b8a6] hover:text-white">Create bot</a>
        </div>

        <div class="space-y-4">
            @forelse ($bots as $bot)
                @php
                    $statusColor = [
                        'active' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
                        'paused' => 'bg-amber-500/10 text-amber-300 border border-amber-500/20',
                        'stopped' => 'bg-rose-500/10 text-rose-400 border border-rose-500/20',
                    ][$bot->status] ?? 'bg-gray-700/30 text-gray-300';
                @endphp
                <div class="rounded-3xl border border-[#151515] bg-[#030303] p-5">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-white">{{ $bot->name }}</p>
                            <p class="text-xs text-gray-500">{{ $bot->base_asset }}/{{ $bot->quote_asset }} • {{ ucfirst(str_replace('_', ' ', $bot->strategy)) }}</p>
                        </div>
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusColor }}">
                            <span class="mr-1 block h-2 w-2 rounded-full bg-current"></span>
                            {{ ucfirst($bot->status) }}
                        </span>
                    </div>
                    <div class="mt-4 grid grid-cols-2 lg:grid-cols-4 gap-3 text-sm">
                        <div>
                            <p class="text-xs text-gray-500">Invested</p>
                            <p class="font-semibold">{{ $user->formatAmount($bot->total_invested) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Profit</p>
                            <p class="font-semibold {{ $bot->total_profit >= 0 ? 'text-green-400' : 'text-red-400' }}">
                                {{ $user->formatAmount($bot->total_profit) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Trades</p>
                            <p class="font-semibold">{{ $bot->total_trades }} • {{ number_format($bot->success_rate, 1) }}%</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Users in Bot</p>
                            <p class="font-semibold">{{ number_format($bot->participants_count ?? 0) }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2 text-xs text-gray-400">
                        <span>Min Trade: {{ $user->formatAmount($bot->min_trade_amount) }}</span>
                        <span>•</span>
                        <span>Max Trade: {{ $user->formatAmount($bot->max_trade_amount) }}</span>
                        <span>•</span>
                        <span>Leverage: {{ $bot->leverage }}x</span>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-3">
                        <a href="{{ route('user.botTrading.show', $bot) }}" class="rounded-full border border-[#1fff9c]/30 px-4 py-2 text-xs font-semibold text-[#1fff9c] hover:border-[#1fff9c]">Manage Bot</a>
                        <a href="{{ route('user.botTrading.edit', $bot) }}" class="rounded-full border border-[#1a1a1a] px-4 py-2 text-xs font-semibold text-gray-300 hover:text-white">Edit Settings</a>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-dashed border-[#1a1a1a] bg-[#030303] p-6 text-center">
                    <p class="text-lg font-semibold">No bots yet</p>
                    <p class="text-sm text-gray-500">Spin up your first automated strategy to start tracking performance here.</p>
                    <a href="{{ route('user.botTrading.create') }}" class="mt-4 inline-flex items-center justify-center rounded-full bg-[#00ff5f] px-6 py-3 text-sm font-semibold text-black">Launch Bot</a>
                </div>
            @endforelse
        </div>
    </div>

    <div class="rounded-[32px] border border-[#0f0f0f] bg-[#050505] p-6">
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold">Recent Bot Trades</h2>
                <p class="text-sm text-gray-400">Latest executions across your automated strategies.</p>
            </div>
            <a href="{{ route('user.botTrading.index') }}" class="text-xs text-gray-400 hover:text-white">View history</a>
        </div>
        <div class="space-y-3">
            @forelse ($recentTrades as $trade)
                @php
                    $isProfit = $trade->profit_loss >= 0;
                    $bot = $trade->botTrading ?? $bots->firstWhere('id', $trade->bot_trading_id);
                @endphp
                <div class="flex flex-col gap-2 rounded-2xl border border-[#1a1a1a] bg-[#030303] p-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-white">{{ strtoupper($trade->type) }} • {{ $bot?->name ?? 'Bot #' . $trade->bot_trading_id }}</p>
                        <p class="text-xs text-gray-500">Amount: {{ $user->formatAmount($trade->quote_amount) }} • {{ $trade->created_at?->diffForHumans() }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold {{ $isProfit ? 'text-green-400' : 'text-red-400' }}">
                            {{ $isProfit ? '+' : '' }}{{ $user->formatAmount($trade->profit_loss) }}
                        </p>
                        <p class="text-xs text-gray-500">{{ ucfirst($trade->status ?? 'pending') }}</p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">No bot trades recorded yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
