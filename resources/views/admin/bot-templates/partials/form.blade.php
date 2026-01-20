@php
    $isEdit = isset($template);
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="space-y-6">
        <div>
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Basic Info</h3>
            <p class="text-sm text-gray-400">Define the bot identity and strategy.</p>
        </div>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Template Name</label>
                <input type="text" name="name" value="{{ old('name', $template->name ?? '') }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Short Description</label>
                <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('description', $template->description ?? '') }}</textarea>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Trading Type</label>
                    <select name="trading_type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="crypto" {{ old('trading_type', $template->trading_type ?? 'crypto') === 'crypto' ? 'selected' : '' }}>Crypto</option>
                        <option value="forex" {{ old('trading_type', $template->trading_type ?? 'crypto') === 'forex' ? 'selected' : '' }}>Forex</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Strategy</label>
                    <select name="strategy" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @foreach($strategies as $key => $strategy)
                            <option value="{{ $key }}" {{ old('strategy', $template->strategy ?? '') === $key ? 'selected' : '' }}>
                                {{ $strategy['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Trading Pair Preset</label>
                <select id="templatePairSelect" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Select quick pair</option>
                    @foreach($tradingPairs as $label => $pair)
                        <option value="{{ $pair['base'] }}:{{ $pair['quote'] }}" data-base="{{ $pair['base'] }}" data-quote="{{ $pair['quote'] }}">
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <div class="grid grid-cols-2 gap-3 mt-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Base Asset</label>
                        <input type="text" name="base_asset" id="templateBaseAsset" value="{{ old('base_asset', $template->base_asset ?? 'BTC') }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white uppercase">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Quote Asset</label>
                        <input type="text" name="quote_asset" id="templateQuoteAsset" value="{{ old('quote_asset', $template->quote_asset ?? 'USDT') }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white uppercase">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div>
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Capital & Risk</h3>
            <p class="text-sm text-gray-400">Limit exposure and define guardrails.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Leverage</label>
                <input type="number" name="leverage" step="0.01" min="1" value="{{ old('leverage', $template->leverage ?? 1) }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Trade Duration</label>
                <input type="text" name="trade_duration" value="{{ old('trade_duration', $template->trade_duration ?? '24h') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="e.g. 1h, 24h">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Target Yield (%)</label>
                <input type="number" name="target_yield_percentage" step="0.1" value="{{ old('target_yield_percentage', $template->target_yield_percentage ?? null) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Max Investment</label>
                <input type="number" name="max_investment" min="10" step="0.01" value="{{ old('max_investment', $template->max_investment ?? 500) }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Daily Loss Limit</label>
                <input type="number" name="daily_loss_limit" step="0.01" value="{{ old('daily_loss_limit', $template->daily_loss_limit ?? null) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Min Trade</label>
                    <input type="number" name="min_trade_amount" min="1" step="0.01" value="{{ old('min_trade_amount', $template->min_trade_amount ?? 25) }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Max Trade</label>
                    <input type="number" name="max_trade_amount" min="1" step="0.01" value="{{ old('max_trade_amount', $template->max_trade_amount ?? 150) }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Stop Loss (%)</label>
                    <input type="number" name="stop_loss_percentage" step="0.1" value="{{ old('stop_loss_percentage', $template->stop_loss_percentage ?? null) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Take Profit (%)</label>
                    <input type="number" name="take_profit_percentage" step="0.1" value="{{ old('take_profit_percentage', $template->take_profit_percentage ?? null) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Max Open Trades</label>
                <input type="number" name="max_open_trades" min="1" max="50" value="{{ old('max_open_trades', $template->max_open_trades ?? 5) }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>
        </div>
    </div>
</div>

<div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="space-y-4">
        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Behaviour</h3>
        <div class="space-y-3">
            <label class="flex items-center gap-3 text-sm text-gray-700 dark:text-gray-300">
                <input type="checkbox" name="auto_close" value="1" {{ old('auto_close', $template->auto_close ?? true) ? 'checked' : '' }} class="rounded border-gray-400 text-indigo-600">
                Auto close trades
            </label>
            <label class="flex items-center gap-3 text-sm text-gray-700 dark:text-gray-300">
                <input type="checkbox" name="trading_24_7" value="1" {{ old('trading_24_7', $template->trading_24_7 ?? true) ? 'checked' : '' }} class="rounded border-gray-400 text-indigo-600">
                Trade 24/7
            </label>
            <label class="flex items-center gap-3 text-sm text-gray-700 dark:text-gray-300">
                <input type="checkbox" name="auto_restart" value="1" {{ old('auto_restart', $template->auto_restart ?? false) ? 'checked' : '' }} class="rounded border-gray-400 text-indigo-600">
                Auto restart if stopped
            </label>
            <label class="flex items-center gap-3 text-sm text-gray-700 dark:text-gray-300">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $template->is_active ?? true) ? 'checked' : '' }} class="rounded border-gray-400 text-indigo-600">
                Visible to users
            </label>
        </div>
    </div>

    <div class="space-y-4">
        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Advanced Config</h3>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Strategy Config (JSON)</label>
            <textarea name="strategy_config" rows="4" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder='{"grid_levels":10,"grid_spacing":1.5}'>{{ old('strategy_config', isset($template) && $template->strategy_config ? json_encode($template->strategy_config) : '') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Metadata (JSON)</label>
            <textarea name="metadata" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder='{"tags":["starter","low-risk"]}'>{{ old('metadata', isset($template) && $template->metadata ? json_encode($template->metadata) : '') }}</textarea>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const pairSelect = document.getElementById('templatePairSelect');
        const baseInput = document.getElementById('templateBaseAsset');
        const quoteInput = document.getElementById('templateQuoteAsset');

        pairSelect?.addEventListener('change', () => {
            const selected = pairSelect.options[pairSelect.selectedIndex];
            if (!selected) return;
            const base = selected.dataset.base;
            const quote = selected.dataset.quote;
            if (base) baseInput.value = base;
            if (quote) quoteInput.value = quote;
        });
    });
</script>
@endpush
