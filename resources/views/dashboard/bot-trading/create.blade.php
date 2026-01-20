@extends('dashboard.new-layout')

@section('content')
<div class="p-6 space-y-6">
    <div class="flex flex-col gap-4">
        <div>
            <p class="text-xs uppercase tracking-wide text-emerald-400">Automation</p>
            <h1 class="text-2xl font-bold text-white">Launch A Trading Bot</h1>
            <p class="text-gray-400 text-sm">Pick a strategy, configure risk, and let the system execute for you.</p>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <a href="{{ route('user.botTrading.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-600 px-4 py-2 text-gray-200 hover:bg-gray-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to bots
            </a>
            <button form="createBotForm" type="submit" class="inline-flex items-center justify-center rounded-lg bg-emerald-500 px-6 py-2 text-sm font-semibold text-black hover:bg-emerald-400 transition-colors">
                Launch bot
            </button>
        </div>
    </div>

    @if(($templates ?? collect())->isNotEmpty())
        <div class="rounded-2xl border border-gray-800 bg-gray-900 p-5 space-y-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-white">Admin Curated Bots</h2>
                    <p class="text-xs text-gray-400">Clone presets built by the team and go live instantly.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($templates as $template)
                    <div class="rounded-2xl border border-gray-800 bg-[#050505] p-4 flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-white">{{ $template->name }}</p>
                                <p class="text-xs text-gray-500">{{ $template->base_asset }}/{{ $template->quote_asset }} • {{ ucfirst(str_replace('_',' ', $template->strategy)) }}</p>
                            </div>
                            <span class="text-xs text-gray-500">Leverage {{ $template->leverage }}x</span>
                        </div>
                        <p class="text-xs text-gray-400">{{ \Illuminate\Support\Str::limit($template->description, 120) }}</p>
                        <div class="flex items-center justify-between text-xs text-gray-400">
                            <span>Cap: ${{ number_format($template->max_investment, 0) }}</span>
                            <span>Trade: ${{ number_format($template->min_trade_amount, 0) }} - ${{ number_format($template->max_trade_amount, 0) }}</span>
                        </div>
                        <form method="POST" action="{{ route('user.botTrading.cloneTemplate', $template) }}" class="mt-auto">
                            @csrf
                            <button type="submit" class="w-full rounded-xl bg-emerald-500/20 text-emerald-300 text-sm font-semibold py-2 hover:bg-emerald-500 hover:text-black transition-colors">
                                Copy this bot
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <form id="createBotForm" action="{{ route('user.botTrading.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <section class="space-y-4 rounded-2xl border border-gray-800 bg-gray-900 p-5">
                <div>
                    <h2 class="text-lg font-semibold text-white">Bot Basics</h2>
                    <p class="text-xs text-gray-400">Name, strategy and market to target.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-300 mb-2 block">Bot name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-white placeholder-gray-500 focus:border-emerald-400 focus:outline-none" placeholder="My BTC Grid Bot">
                    </div>
                    <div>
                        <label class="text-sm text-gray-300 mb-2 block">Trading type</label>
                        <select name="trading_type" class="w-full rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-white focus:border-emerald-400 focus:outline-none">
                            <option value="crypto" {{ old('trading_type') === 'forex' ? '' : 'selected' }}>Crypto</option>
                            <option value="forex" {{ old('trading_type') === 'forex' ? 'selected' : '' }}>Forex</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm text-gray-300 mb-2 block">Strategy</label>
                        <select name="strategy" required class="w-full rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-white focus:border-emerald-400 focus:outline-none">
                            @foreach($strategies as $key => $strategy)
                                <option value="{{ $key }}" {{ old('strategy') === $key ? 'selected' : '' }}>
                                    {{ $strategy['name'] }} — {{ $strategy['description'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm text-gray-300 mb-2 block">Trading pair</label>
                        <select id="tradingPairSelect" class="w-full rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-white focus:border-emerald-400 focus:outline-none">
                            @foreach($tradingPairs as $label => $pair)
                                <option value="{{ $pair['base'] }}:{{ $pair['quote'] }}" data-base="{{ $pair['base'] }}" data-quote="{{ $pair['quote'] }}" {{ old('base_asset') === $pair['base'] ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <div class="grid grid-cols-2 gap-3 mt-3">
                            <div>
                                <label class="text-xs text-gray-400 mb-1 block">Base asset</label>
                                <input type="text" name="base_asset" id="baseAssetInput" value="{{ old('base_asset', 'BTC') }}" required class="w-full rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-white focus:border-emerald-400 focus:outline-none">
                            </div>
                            <div>
                                <label class="text-xs text-gray-400 mb-1 block">Quote asset</label>
                                <input type="text" name="quote_asset" id="quoteAssetInput" value="{{ old('quote_asset', 'USDT') }}" required class="w-full rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-white focus:border-emerald-400 focus:outline-none">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="space-y-4 rounded-2xl border border-gray-800 bg-gray-900 p-5">
                <div>
                    <h2 class="text-lg font-semibold text-white">Investment & Risk</h2>
                    <p class="text-xs text-gray-400">Set how much capital the bot can touch.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-300 mb-2 block">Max investment (USD)</label>
                        <input type="number" min="10" step="0.01" name="max_investment" value="{{ old('max_investment', 500) }}" required class="w-full rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-white focus:border-emerald-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="text-sm text-gray-300 mb-2 block">Daily loss limit</label>
                        <input type="number" min="1" step="0.01" name="daily_loss_limit" value="{{ old('daily_loss_limit', 100) }}" class="w-full rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-white focus:border-emerald-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="text-sm text-gray-300 mb-2 block">Min trade amount</label>
                        <input type="number" min="1" step="0.01" name="min_trade_amount" value="{{ old('min_trade_amount', 25) }}" required class="w-full rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-white focus:border-emerald-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="text-sm text-gray-300 mb-2 block">Max trade amount</label>
                        <input type="number" min="1" step="0.01" name="max_trade_amount" value="{{ old('max_trade_amount', 150) }}" required class="w-full rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-white focus:border-emerald-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="text-sm text-gray-300 mb-2 block">Stop loss (%)</label>
                        <input type="number" min="0" step="0.1" name="stop_loss_percentage" value="{{ old('stop_loss_percentage', 5) }}" class="w-full rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-white focus:border-emerald-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="text-sm text-gray-300 mb-2 block">Take profit (%)</label>
                        <input type="number" min="0" step="0.1" name="take_profit_percentage" value="{{ old('take_profit_percentage', 12) }}" class="w-full rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-white focus:border-emerald-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="text-sm text-gray-300 mb-2 block">Leverage</label>
                        <input type="number" min="1" step="0.01" name="leverage" value="{{ old('leverage', 3) }}" required class="w-full rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-white focus:border-emerald-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="text-sm text-gray-300 mb-2 block">Max open trades</label>
                        <input type="number" min="1" max="50" name="max_open_trades" value="{{ old('max_open_trades', 5) }}" required class="w-full rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-white focus:border-emerald-400 focus:outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-2">
                    <label class="flex items-center gap-3 rounded-xl border border-gray-800 bg-gray-800/60 px-3 py-2 text-sm text-gray-200">
                        <input type="checkbox" name="auto_close" value="1" {{ old('auto_close', true) ? 'checked' : '' }} class="rounded border-gray-600 bg-gray-700 text-emerald-400 focus:ring-emerald-400">
                        Auto close trades
                    </label>
                    <label class="flex items-center gap-3 rounded-xl border border-gray-800 bg-gray-800/60 px-3 py-2 text-sm text-gray-200">
                        <input type="checkbox" name="trading_24_7" value="1" {{ old('trading_24_7', true) ? 'checked' : '' }} class="rounded border-gray-600 bg-gray-700 text-emerald-400 focus:ring-emerald-400">
                        Trade 24/7
                    </label>
                    <label class="flex items-center gap-3 rounded-xl border border-gray-800 bg-gray-800/60 px-3 py-2 text-sm text-gray-200">
                        <input type="checkbox" name="auto_restart" value="1" {{ old('auto_restart') ? 'checked' : '' }} class="rounded border-gray-600 bg-gray-700 text-emerald-400 focus:ring-emerald-400">
                        Auto restart
                    </label>
                </div>
            </section>
        </div>

        <section class="rounded-2xl border border-gray-800 bg-gray-900 p-5 space-y-4">
            <div>
                <h2 class="text-lg font-semibold text-white">Execution Preferences</h2>
                <p class="text-xs text-gray-400">Duration, targets, and fine-tuning per strategy.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="text-sm text-gray-300 mb-2 block">Trade duration</label>
                    <select name="trade_duration" class="w-full rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-white focus:border-emerald-400 focus:outline-none">
                        @foreach(['1h','4h','24h','1w','2w','1m','2m'] as $duration)
                            <option value="{{ $duration }}" {{ old('trade_duration', '24h') === $duration ? 'selected' : '' }}>
                                {{ strtoupper($duration) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm text-gray-300 mb-2 block">Target yield (%)</label>
                    <input type="number" min="0" step="0.1" name="target_yield_percentage" value="{{ old('target_yield_percentage', 8) }}" class="w-full rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-white focus:border-emerald-400 focus:outline-none">
                </div>
                <div>
                    <label class="text-sm text-gray-300 mb-2 block">DCA amount (optional)</label>
                    <input type="number" step="0.01" name="dca_amount" value="{{ old('dca_amount', 50) }}" class="w-full rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-white focus:border-emerald-400 focus:outline-none">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="text-sm text-gray-300 mb-2 block">Grid levels</label>
                    <input type="number" min="2" name="grid_levels" value="{{ old('grid_levels', 10) }}" class="w-full rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-white focus:border-emerald-400 focus:outline-none">
                </div>
                <div>
                    <label class="text-sm text-gray-300 mb-2 block">Grid spacing (%)</label>
                    <input type="number" step="0.1" name="grid_spacing" value="{{ old('grid_spacing', 1.5) }}" class="w-full rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-white focus:border-emerald-400 focus:outline-none">
                </div>
                <div>
                    <label class="text-sm text-gray-300 mb-2 block">Grid range (%)</label>
                    <input type="number" step="0.1" name="grid_range" value="{{ old('grid_range', 12) }}" class="w-full rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-white focus:border-emerald-400 focus:outline-none">
                </div>
            </div>
        </section>
    </form>

    <div class="rounded-2xl border border-amber-600/30 bg-amber-900/10 p-4 text-sm text-amber-100 flex gap-3">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2zm7-14v2"/>
        </svg>
        <p>
            Bots can take up to a few minutes to start executing live trades.
            Keep an eye on notifications for status updates and make sure you have enough trading balance to cover the max investment.
        </p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const pairSelect = document.getElementById('tradingPairSelect');
        const baseInput = document.getElementById('baseAssetInput');
        const quoteInput = document.getElementById('quoteAssetInput');

        const syncPair = () => {
            if (!pairSelect) return;
            const option = pairSelect.options[pairSelect.selectedIndex];
            if (!option) return;
            const base = option.dataset.base || '';
            const quote = option.dataset.quote || '';
            if (base && !baseInput.value) {
                baseInput.value = base;
            }
            if (quote && !quoteInput.value) {
                quoteInput.value = quote;
            }
        };

        pairSelect?.addEventListener('change', () => {
            const selected = pairSelect.options[pairSelect.selectedIndex];
            if (selected) {
                baseInput.value = selected.dataset.base || baseInput.value;
                quoteInput.value = selected.dataset.quote || quoteInput.value;
            }
        });

        syncPair();
    });
</script>
@endpush
