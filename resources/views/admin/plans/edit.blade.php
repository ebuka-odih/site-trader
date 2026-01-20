@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-white">Edit Plan</h1>
            <p class="text-gray-400 mt-1">Update plan information</p>
        </div>
        
        <a href="{{ route('admin.plans.index', ['tab' => $plan->type]) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
            Back to Plans
        </a>
    </div>

    <!-- Form -->
    <div class="bg-gray-800 rounded-lg p-6">
        <form action="{{ route('admin.plans.update', $plan) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Plan Type Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-300 mb-2">Plan Type</label>
                    <select id="type" name="type" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="showTypeSpecificFields()">
                        @foreach($planTypes as $value => $label)
                            <option value="{{ $value }}" {{ $plan->type == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Plan Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $plan->name) }}" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Gold Plan">
                    @error('name')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                <textarea id="description" name="description" rows="3" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Plan description...">{{ old('description', $plan->description) }}</textarea>
                @error('description')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pricing -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-300 mb-2">Plan Price</label>
                    <input type="number" id="price" name="price" value="{{ old('price', $plan->price) }}" step="0.01" min="0" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0.00">
                    @error('price')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="original_price" class="block text-sm font-medium text-gray-300 mb-2">Original Price (Optional)</label>
                    <input type="number" id="original_price" name="original_price" value="{{ old('original_price', $plan->original_price) }}" step="0.01" min="0" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0.00">
                    @error('original_price')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="min_funding" class="block text-sm font-medium text-gray-300 mb-2">Min Funding</label>
                    <input type="number" id="min_funding" name="min_funding" value="{{ old('min_funding', $plan->min_funding) }}" step="0.01" min="0" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0.00">
                    @error('min_funding')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="max_funding" class="block text-sm font-medium text-gray-300 mb-2">Max Funding (Optional)</label>
                    <input type="number" id="max_funding" name="max_funding" value="{{ old('max_funding', $plan->max_funding) }}" step="0.01" min="0" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0.00 for unlimited">
                    <p class="text-xs text-gray-400 mt-1">Leave empty or set to 0 for unlimited funding</p>
                    @error('max_funding')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="currency" class="block text-sm font-medium text-gray-300 mb-2">Currency</label>
                    <select id="currency" name="currency" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="USD" {{ old('currency', $plan->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="EUR" {{ old('currency', $plan->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                        <option value="GBP" {{ old('currency', $plan->currency) == 'GBP' ? 'selected' : '' }}>GBP</option>
                        <option value="JPY" {{ old('currency', $plan->currency) == 'JPY' ? 'selected' : '' }}>JPY</option>
                        <option value="CAD" {{ old('currency', $plan->currency) == 'CAD' ? 'selected' : '' }}>CAD</option>
                        <option value="AUD" {{ old('currency', $plan->currency) == 'AUD' ? 'selected' : '' }}>AUD</option>
                        <option value="CHF" {{ old('currency', $plan->currency) == 'CHF' ? 'selected' : '' }}>CHF</option>
                        <option value="CNY" {{ old('currency', $plan->currency) == 'CNY' ? 'selected' : '' }}>CNY</option>
                        <option value="INR" {{ old('currency', $plan->currency) == 'INR' ? 'selected' : '' }}>INR</option>
                        <option value="BRL" {{ old('currency', $plan->currency) == 'BRL' ? 'selected' : '' }}>BRL</option>
                    </select>
                    @error('currency')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Trading Plan Specific Fields -->
            <div id="trading-fields" class="type-specific-fields {{ $plan->type == 'trading' ? '' : 'hidden' }}">
                <h3 class="text-lg font-semibold text-white mb-4">Trading Plan Settings</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label for="pairs" class="block text-sm font-medium text-gray-300 mb-2">Trading Pairs</label>
                        <input type="text" id="pairs" name="pairs" value="{{ old('pairs', $plan->pairs) }}" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 50+ Trading Pairs">
                        @error('pairs')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="leverage" class="block text-sm font-medium text-gray-300 mb-2">Leverage</label>
                        <input type="number" id="leverage" name="leverage" value="{{ old('leverage', $plan->leverage) }}" step="0.01" min="0" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 100.00">
                        @error('leverage')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="spreads" class="block text-sm font-medium text-gray-300 mb-2">Spreads</label>
                        <input type="number" id="spreads" name="spreads" value="{{ old('spreads', $plan->spreads) }}" step="0.01" min="0" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 1.5">
                        @error('spreads')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="swap_fees" class="block text-sm font-medium text-gray-300 mb-2">Swap Fees (%)</label>
                        <input type="number" id="swap_fees" name="swap_fees" value="{{ old('swap_fees', $plan->swap_fees) }}" step="0.01" min="0" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0.00">
                        @error('swap_fees')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="minimum_deposit" class="block text-sm font-medium text-gray-300 mb-2">Minimum Deposit</label>
                        <input type="number" id="minimum_deposit" name="minimum_deposit" value="{{ old('minimum_deposit', $plan->minimum_deposit) }}" step="0.01" min="0" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0.00">
                        @error('minimum_deposit')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="max_lot_size" class="block text-sm font-medium text-gray-300 mb-2">Max Lot Size</label>
                        <input type="text" id="max_lot_size" name="max_lot_size" value="{{ old('max_lot_size', $plan->max_lot_size) }}" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 10 lots">
                        @error('max_lot_size')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Signal Plan Specific Fields -->
            <div id="signal-fields" class="type-specific-fields {{ $plan->type == 'signal' ? '' : 'hidden' }}">
                <h3 class="text-lg font-semibold text-white mb-4">Signal Plan Settings</h3>
                
                <!-- Basic Signal Settings -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div>
                        <label for="signal_strength" class="block text-sm font-medium text-gray-300 mb-2">Signal Strength (1-5)</label>
                        <input type="number" id="signal_strength" name="signal_strength" value="{{ old('signal_strength', $plan->signal_strength) }}" min="1" max="5" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 4">
                        @error('signal_strength')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="daily_signals" class="block text-sm font-medium text-gray-300 mb-2">Daily Signals</label>
                        <input type="number" id="daily_signals" name="daily_signals" value="{{ old('daily_signals', $plan->daily_signals) }}" min="0" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 10">
                        @error('daily_signals')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="success_rate" class="block text-sm font-medium text-gray-300 mb-2">Success Rate (%)</label>
                        <input type="number" id="success_rate" name="success_rate" value="{{ old('success_rate', $plan->success_rate) }}" step="0.01" min="0" max="100" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 85.5">
                        @error('success_rate')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="signal_duration" class="block text-sm font-medium text-gray-300 mb-2">Duration (Days)</label>
                        <input type="number" id="signal_duration" name="signal_duration" value="{{ old('signal_duration', $plan->signal_duration) }}" min="0" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 30">
                        @error('signal_duration')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Market Type and Trading Settings -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div>
                        <label for="signal_market_type" class="block text-sm font-medium text-gray-300 mb-2">Market Type</label>
                        <select id="signal_market_type" name="signal_market_type" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Market Type</option>
                            <option value="crypto" {{ old('signal_market_type', $plan->signal_market_type) == 'crypto' ? 'selected' : '' }}>Cryptocurrency</option>
                            <option value="forex" {{ old('signal_market_type', $plan->signal_market_type) == 'forex' ? 'selected' : '' }}>Forex</option>
                            <option value="stock" {{ old('signal_market_type', $plan->signal_market_type) == 'stock' ? 'selected' : '' }}>Stocks</option>
                            <option value="commodities" {{ old('signal_market_type', $plan->signal_market_type) == 'commodities' ? 'selected' : '' }}>Commodities</option>
                            <option value="indices" {{ old('signal_market_type', $plan->signal_market_type) == 'indices' ? 'selected' : '' }}>Indices</option>
                        </select>
                        @error('signal_market_type')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="signal_pairs" class="block text-sm font-medium text-gray-300 mb-2">Trading Pairs (JSON Array)</label>
                        <textarea id="signal_pairs" name="signal_pairs" rows="3" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder='["BTC/USDT", "ETH/USDT", "BNB/USDT"]'>{{ old('signal_pairs', json_encode($plan->signal_pairs)) }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">Enter as JSON array: ["Feature 1", "Feature 2"]</p>
                        @error('signal_pairs')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="signal_leverage" class="block text-sm font-medium text-gray-300 mb-2">Max Leverage</label>
                        <input type="number" id="signal_leverage" name="signal_leverage" value="{{ old('signal_leverage', $plan->signal_leverage) }}" step="0.01" min="0" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 100.00">
                        @error('signal_leverage')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="signal_expiry_duration" class="block text-sm font-medium text-gray-300 mb-2">Signal Expiry</label>
                        <select id="signal_expiry_duration" name="signal_expiry_duration" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Expiry</option>
                            <option value="1h" {{ old('signal_expiry_duration', $plan->signal_expiry_duration) == '1h' ? 'selected' : '' }}>1 Hour</option>
                            <option value="4h" {{ old('signal_expiry_duration', $plan->signal_expiry_duration) == '4h' ? 'selected' : '' }}>4 Hours</option>
                            <option value="1d" {{ old('signal_expiry_duration', $plan->signal_expiry_duration) == '1d' ? 'selected' : '' }}>1 Day</option>
                            <option value="1w" {{ old('signal_expiry_duration', $plan->signal_expiry_duration) == '1w' ? 'selected' : '' }}>1 Week</option>
                            <option value="1m" {{ old('signal_expiry_duration', $plan->signal_expiry_duration) == '1m' ? 'selected' : '' }}>1 Month</option>
                        </select>
                        @error('signal_expiry_duration')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Features and Delivery -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div>
                        <label for="signal_features" class="block text-sm font-medium text-gray-300 mb-2">Features (JSON Array)</label>
                        <textarea id="signal_features" name="signal_features" rows="3" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder='["Chart Analysis", "Risk Management", "TradingView Links"]'>{{ old('signal_features', json_encode($plan->signal_features)) }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">Enter as JSON array: ["Feature 1", "Feature 2"]</p>
                        @error('signal_features')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="signal_delivery" class="block text-sm font-medium text-gray-300 mb-2">Delivery Method</label>
                        <select id="signal_delivery" name="signal_delivery" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Method</option>
                            <option value="email" {{ old('signal_delivery', $plan->signal_delivery) == 'email' ? 'selected' : '' }}>Email</option>
                            <option value="telegram" {{ old('signal_delivery', $plan->signal_delivery) == 'telegram' ? 'selected' : '' }}>Telegram</option>
                            <option value="sms" {{ old('signal_delivery', $plan->signal_delivery) == 'sms' ? 'selected' : '' }}>SMS</option>
                            <option value="push" {{ old('signal_delivery', $plan->signal_delivery) == 'push' ? 'selected' : '' }}>Push Notification</option>
                            <option value="webhook" {{ old('signal_delivery', $plan->signal_delivery) == 'webhook' ? 'selected' : '' }}>Webhook</option>
                        </select>
                        @error('signal_delivery')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="max_daily_signals" class="block text-sm font-medium text-gray-300 mb-2">Max Daily Signals</label>
                        <input type="number" id="max_daily_signals" name="max_daily_signals" value="{{ old('max_daily_signals', $plan->max_daily_signals) }}" min="0" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 5">
                        @error('max_daily_signals')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Mining Plan Specific Fields -->
            <div id="mining-fields" class="type-specific-fields {{ $plan->type == 'mining' ? '' : 'hidden' }}">
                <h3 class="text-lg font-semibold text-white mb-4">Mining Plan Settings</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label for="hashrate" class="block text-sm font-medium text-gray-300 mb-2">Hashrate</label>
                        <input type="text" id="hashrate" name="hashrate" value="{{ old('hashrate', $plan->hashrate) }}" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 1000 TH/s">
                        @error('hashrate')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="equipment" class="block text-sm font-medium text-gray-300 mb-2">Equipment</label>
                        <input type="text" id="equipment" name="equipment" value="{{ old('equipment', $plan->equipment) }}" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., ~ 10 Antminer S19">
                        @error('equipment')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="downtime" class="block text-sm font-medium text-gray-300 mb-2">Downtime</label>
                        <input type="text" id="downtime" name="downtime" value="{{ old('downtime', $plan->downtime) }}" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., No Downtime">
                        @error('downtime')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="electricity_costs" class="block text-sm font-medium text-gray-300 mb-2">Electricity Costs</label>
                        <input type="text" id="electricity_costs" name="electricity_costs" value="{{ old('electricity_costs', $plan->electricity_costs) }}" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., No Electricity Costs">
                        @error('electricity_costs')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="mining_duration" class="block text-sm font-medium text-gray-300 mb-2">Duration (Days)</label>
                        <input type="number" id="mining_duration" name="mining_duration" value="{{ old('mining_duration', $plan->mining_duration) }}" min="0" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 30">
                        @error('mining_duration')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Staking Plan Specific Fields -->
            <div id="staking-fields" class="type-specific-fields {{ $plan->type == 'staking' ? '' : 'hidden' }}">
                <h3 class="text-lg font-semibold text-white mb-4">Staking Plan Settings</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label for="apy_rate" class="block text-sm font-medium text-gray-300 mb-2">APY Rate (%)</label>
                        <input type="number" id="apy_rate" name="apy_rate" value="{{ old('apy_rate', $plan->apy_rate) }}" step="0.01" min="0" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 12.5">
                        @error('apy_rate')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="minimum_amount" class="block text-sm font-medium text-gray-300 mb-2">Minimum Amount</label>
                        <input type="number" id="minimum_amount" name="minimum_amount" value="{{ old('minimum_amount', $plan->minimum_amount) }}" step="0.01" min="0" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 100.00">
                        @error('minimum_amount')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="reward_frequency" class="block text-sm font-medium text-gray-300 mb-2">Reward Frequency</label>
                        <input type="text" id="reward_frequency" name="reward_frequency" value="{{ old('reward_frequency', $plan->reward_frequency) }}" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Daily">
                        @error('reward_frequency')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="lock_period" class="block text-sm font-medium text-gray-300 mb-2">Lock Period (Days)</label>
                        <input type="number" id="lock_period" name="lock_period" value="{{ old('lock_period', $plan->lock_period) }}" min="0" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 30">
                        @error('lock_period')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="staking_duration" class="block text-sm font-medium text-gray-300 mb-2">Staking Duration (Days)</label>
                        <input type="number" id="staking_duration" name="staking_duration" value="{{ old('staking_duration', $plan->staking_duration) }}" min="0" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 365">
                        @error('staking_duration')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Settings -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-300 mb-2">Sort Order</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $plan->sort_order) }}" min="0" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0">
                    @error('sort_order')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $plan->is_active) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500 focus:ring-2">
                    <label for="is_active" class="ml-2 text-sm font-medium text-gray-300">Active Plan</label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.plans.index', ['tab' => $plan->type]) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    Update Plan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showTypeSpecificFields() {
    const type = document.getElementById('type').value;
    
    // Hide all type-specific fields
    document.querySelectorAll('.type-specific-fields').forEach(field => {
        field.classList.add('hidden');
    });
    
    // Show fields for selected type
    if (type) {
        const targetField = document.getElementById(`${type}-fields`);
        if (targetField) {
            targetField.classList.remove('hidden');
        }
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    showTypeSpecificFields();
});
</script>
@endsection
