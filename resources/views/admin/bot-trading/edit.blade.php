@extends('admin.layouts.app')

@section('content')
<div class="p-4 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.bot-trading.show', $bot) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Bot
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Bot Configuration</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $bot->name }} - {{ $bot->user->name }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Bot Settings</h3>
            </div>
            
            <form action="{{ route('admin.bot-trading.update', $bot) }}" method="POST" class="p-6">
                @csrf
                @method('PATCH')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Settings -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Basic Settings</h4>
                        
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bot Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $bot->name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        </div>

                        <div>
                            <label for="strategy" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Strategy</label>
                            <select name="strategy" id="strategy" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                <option value="grid" {{ $bot->strategy === 'grid' ? 'selected' : '' }}>Grid Trading</option>
                                <option value="dca" {{ $bot->strategy === 'dca' ? 'selected' : '' }}>DCA</option>
                                <option value="scalping" {{ $bot->strategy === 'scalping' ? 'selected' : '' }}>Scalping</option>
                                <option value="trend_following" {{ $bot->strategy === 'trend_following' ? 'selected' : '' }}>Trend Following</option>
                            </select>
                        </div>

                        <div>
                            <label for="base_asset" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Base Asset</label>
                            <input type="text" name="base_asset" id="base_asset" value="{{ old('base_asset', $bot->base_asset) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        </div>

                        <div>
                            <label for="quote_asset" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Quote Asset</label>
                            <input type="text" name="quote_asset" id="quote_asset" value="{{ old('quote_asset', $bot->quote_asset) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        </div>

                        <div>
                            <label for="participants_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Users in Bot</label>
                            <input
                                type="number"
                                min="0"
                                name="participants_count"
                                id="participants_count"
                                value="{{ old('participants_count', $bot->participants_count) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="e.g. 120"
                            >
                        </div>
                    </div>

                    <!-- Investment Settings -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Investment Settings</h4>
                        
                        <div>
                            <label for="max_investment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Max Investment ({{ auth()->user()->currency ?? 'USD' }})</label>
                            <input type="number" step="0.01" name="max_investment" id="max_investment" value="{{ old('max_investment', $bot->max_investment) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        </div>

                        <div>
                            <label for="min_trade_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Min Trade Amount ({{ auth()->user()->currency ?? 'USD' }})</label>
                            <input type="number" step="0.01" name="min_trade_amount" id="min_trade_amount" value="{{ old('min_trade_amount', $bot->min_trade_amount) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        </div>

                        <div>
                            <label for="max_trade_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Max Trade Amount ({{ auth()->user()->currency ?? 'USD' }})</label>
                            <input type="number" step="0.01" name="max_trade_amount" id="max_trade_amount" value="{{ old('max_trade_amount', $bot->max_trade_amount) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        </div>
                    </div>
                </div>

                <!-- Risk Management -->
                <div class="mt-8">
                    <h4 class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-4">Risk Management</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="daily_loss_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Daily Loss Limit ({{ auth()->user()->currency ?? 'USD' }})</label>
                            <input type="number" step="0.01" name="daily_loss_limit" id="daily_loss_limit" value="{{ old('daily_loss_limit', $bot->daily_loss_limit) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        </div>

                        <div>
                            <label for="leverage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Leverage</label>
                            <input type="number" step="0.01" name="leverage" id="leverage" value="{{ old('leverage', $bot->leverage) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        </div>
                    </div>
                </div>

                <!-- Advanced Settings -->
                <div class="mt-8">
                    <h4 class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-4">Advanced Settings</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="trade_duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Trade Duration</label>
                            <input type="text" name="trade_duration" id="trade_duration" value="{{ old('trade_duration', $bot->trade_duration) }}" placeholder="e.g., 24h, 7d" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>

                        <div>
                            <label for="target_yield_percentage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Target Yield (%)</label>
                            <input type="number" step="0.01" name="target_yield_percentage" id="target_yield_percentage" value="{{ old('target_yield_percentage', $bot->target_yield_percentage) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                    </div>

                    <div class="mt-6 space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="auto_close" id="auto_close" value="1" {{ $bot->auto_close ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="auto_close" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Auto Close Trades</label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="trading_24_7" id="trading_24_7" value="1" {{ $bot->trading_24_7 ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="trading_24_7" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">24/7 Trading</label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="auto_restart" id="auto_restart" value="1" {{ $bot->auto_restart ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="auto_restart" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Auto Restart</label>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.bot-trading.show', $bot) }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Update Bot
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
