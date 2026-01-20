@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Create Trading Signal</h1>
                    <p class="text-gray-400">Create a new trading signal for a signal plan</p>
                </div>
                <a href="{{ route('admin.signals.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                    Back to Signals
                </a>
            </div>
        </div>

        <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
            <div class="p-6">
                <form action="{{ route('admin.signals.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Plan Selection -->
                    <div>
                        <label for="plan_id" class="block text-sm font-medium text-gray-300 mb-2">Signal Plan <span class="text-red-500">*</span></label>
                        <select id="plan_id" name="plan_id" required class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select a Signal Plan</option>
                            @foreach($signalPlans as $plan)
                                <option value="{{ $plan->id }}" {{ $selectedPlan && $selectedPlan->id == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }} ({{ $plan->signal_market_type }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Signal Name <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., BTC/USD Bullish Breakout">
                        </div>

                        <div>
                            <label for="trade_pair" class="block text-sm font-medium text-gray-300 mb-2">Trading Pair <span class="text-red-500">*</span></label>
                            <input type="text" id="trade_pair" name="trade_pair" value="{{ old('trade_pair') }}" required class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., BTC/USD">
                        </div>
                    </div>

                    <!-- Signal Type and Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="signal_type" class="block text-sm font-medium text-gray-300 mb-2">Signal Type <span class="text-red-500">*</span></label>
                            <select id="signal_type" name="signal_type" required class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Signal Type</option>
                                <option value="buy" {{ old('signal_type') == 'buy' ? 'selected' : '' }}>Buy (Long)</option>
                                <option value="sell" {{ old('signal_type') == 'sell' ? 'selected' : '' }}>Sell (Short)</option>
                            </select>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-300 mb-2">Status <span class="text-red-500">*</span></label>
                            <select id="status" name="status" required class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                        </div>
                    </div>

                    <!-- Pricing Information -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="entry_price" class="block text-sm font-medium text-gray-300 mb-2">Entry Price <span class="text-red-500">*</span></label>
                            <input type="number" id="entry_price" name="entry_price" value="{{ old('entry_price') }}" step="0.000001" required class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 45000.00">
                        </div>

                        <div>
                            <label for="target_price" class="block text-sm font-medium text-gray-300 mb-2">Target Price <span class="text-red-500">*</span></label>
                            <input type="number" id="target_price" name="target_price" value="{{ old('target_price') }}" step="0.000001" required class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 48000.00">
                        </div>

                        <div>
                            <label for="stop_loss" class="block text-sm font-medium text-gray-300 mb-2">Stop Loss <span class="text-red-500">*</span></label>
                            <input type="number" id="stop_loss" name="stop_loss" value="{{ old('stop_loss') }}" step="0.000001" required class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 43000.00">
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                        <textarea id="description" name="description" rows="4" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Provide a detailed description of the signal...">{{ old('description') }}</textarea>
                    </div>

                    <!-- Chart Image -->
                    <div>
                        <label for="chart_image" class="block text-sm font-medium text-gray-300 mb-2">Chart Image</label>
                        <input type="file" id="chart_image" name="chart_image" accept="image/*" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-400 mt-1">Upload a chart image (PNG, JPG, JPEG)</p>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-700">
                        <a href="{{ route('admin.signals.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                            Create Signal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
