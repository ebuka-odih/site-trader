@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Trading Signals</h1>
                    <p class="text-gray-400">Manage trading signals for signal plans</p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Create Signal</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-900/20 border border-green-500/30 rounded-lg">
                <div class="text-green-400">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-900/20 border border-red-500/30 rounded-lg">
                <div class="text-red-400">{{ session('error') }}</div>
            </div>
        @endif

        <!-- Filters and Tabs -->
        <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg mb-6">
            <div class="p-6">
                <!-- Plan Filter -->
                <div class="mb-6">
                    <label for="plan_filter" class="block text-sm font-medium text-gray-300 mb-2">Filter by Plan</label>
                    <select id="plan_filter" class="bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="filterByPlan(this.value)">
                        <option value="">All Signal Plans</option>
                        @foreach($signalPlans as $plan)
                            <option value="{{ $plan->id }}" {{ $planId == $plan->id ? 'selected' : '' }}>
                                {{ $plan->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Tabs -->
                <div class="border-b border-gray-700">
                    <nav class="-mb-px flex space-x-8">
                        <a href="{{ route('admin.signals.index', ['tab' => 'active'] + ($planId ? ['plan_id' => $planId] : [])) }}" 
                           class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'active' ? 'border-blue-500 text-blue-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300' }}">
                            Active
                        </a>
                        <a href="{{ route('admin.signals.index', ['tab' => 'completed'] + ($planId ? ['plan_id' => $planId] : [])) }}" 
                           class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'completed' ? 'border-blue-500 text-blue-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300' }}">
                            Completed
                        </a>
                        <a href="{{ route('admin.signals.index', ['tab' => 'cancelled'] + ($planId ? ['plan_id' => $planId] : [])) }}" 
                           class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'cancelled' ? 'border-blue-500 text-blue-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300' }}">
                            Cancelled
                        </a>
                        <a href="{{ route('admin.signals.index', ['tab' => 'expired'] + ($planId ? ['plan_id' => $planId] : [])) }}" 
                           class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'expired' ? 'border-blue-500 text-blue-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300' }}">
                            Expired
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Signals Table -->
        <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
            @if($signals->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Signal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Plan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Pair</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Entry Price</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Target</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Stop Loss</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Created</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($signals as $signal)
                                <tr class="hover:bg-gray-700 transition-colors">
                                    <td class="px-4 py-4">
                                        <div class="text-sm font-medium text-white">{{ $signal->title }}</div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-300">
                                        {{ $signal->plan->name }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-300">
                                        {{ $signal->symbol }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $signal->type === 'buy' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($signal->type) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-300">
                                        {{ number_format($signal->entry_price, 2) }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-300">
                                        {{ number_format($signal->take_profit, 2) }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-300">
                                        {{ number_format($signal->stop_loss, 2) }}
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($signal->status === 'active')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @elseif($signal->status === 'completed')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Completed
                                            </span>
                                        @elseif($signal->status === 'cancelled')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                Cancelled
                                            </span>
                                        @elseif($signal->status === 'expired')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Expired
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ ucfirst($signal->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-300">
                                        {{ $signal->created_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-4 py-4 text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.signals.show', $signal) }}" class="text-blue-400 hover:text-blue-300 transition-colors">
                                                View
                                            </a>
                                            <a href="{{ route('admin.signals.edit', $signal) }}" class="text-yellow-400 hover:text-yellow-300 transition-colors">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.signals.destroy', $signal) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this signal?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-300 transition-colors">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-700">
                    {{ $signals->appends(request()->query())->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <div class="text-gray-400 mb-6">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-white mb-2">No Signals Found</h3>
                    <p class="text-gray-400 mb-6">No trading signals match your current filters.</p>
                    <a href="{{ route('admin.signals.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        Create First Signal
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

        </div>
    </div>
</div>

<!-- Create Signal Modal -->
<div id="createSignalModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-gray-800 rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">
            <div class="p-6 flex-1 overflow-y-auto max-h-[calc(90vh-120px)]">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-white">Create New Trading Signal</h3>
                    <button onclick="closeCreateModal()" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="createSignalForm" action="{{ route('admin.signals.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <!-- Plan Selection -->
                    <div>
                        <label for="modal_plan_id" class="block text-sm font-medium text-gray-300 mb-2">Signal Plan <span class="text-red-500">*</span></label>
                        <select id="modal_plan_id" name="plan_id" required class="w-full bg-white border border-gray-600 text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select a Signal Plan</option>
                            @foreach($signalPlans as $plan)
                                <option value="{{ $plan->id }}">
                                    {{ $plan->name }} ({{ $plan->signal_market_type }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Market Type and Trading Pair Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="modal_market_type" class="block text-sm font-medium text-gray-300 mb-2">Market Type <span class="text-red-500">*</span></label>
                            <select id="modal_market_type" name="market_type" required class="w-full bg-white border border-gray-600 text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="filterTradePairs()">
                                <option value="">Select Market Type</option>
                                <option value="crypto">Crypto</option>
                                <option value="stock">Stock</option>
                                <option value="forex">Forex</option>
                            </select>
                        </div>
                        <div>
                            <label for="modal_trade_pair" class="block text-sm font-medium text-gray-300 mb-2">Trading Pair <span class="text-red-500">*</span></label>
                            <select id="modal_trade_pair" name="trade_pair" required class="w-full bg-white border border-gray-600 text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select a market type first</option>
                                @foreach($tradePairs as $pair)
                                    <option value="{{ $pair->pair }}" data-type="{{ $pair->type }}" style="display: none;">{{ $pair->pair }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Signal Direction and Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="modal_signal_direction" class="block text-sm font-medium text-gray-300 mb-2">Signal Direction <span class="text-red-500">*</span></label>
                            <select id="modal_signal_direction" name="signal_direction" required class="w-full bg-white border border-gray-600 text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Signal Direction</option>
                                <option value="buy">Buy (Long)</option>
                                <option value="sell">Sell (Short)</option>
                            </select>
                        </div>

                        <div>
                            <label for="modal_status" class="block text-sm font-medium text-gray-300 mb-2">Status <span class="text-red-500">*</span></label>
                            <select id="modal_status" name="status" required class="w-full bg-white border border-gray-600 text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="active">Active</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="expired">Expired</option>
                            </select>
                        </div>
                    </div>

                    <!-- Pricing Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="modal_entry_price" class="block text-sm font-medium text-gray-300 mb-2">Entry Price <span class="text-red-500">*</span></label>
                            <input type="number" id="modal_entry_price" name="entry_price" step="0.000001" required class="w-full bg-white border border-gray-600 text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 45000.00">
                        </div>

                        <div>
                            <label for="modal_target_price" class="block text-sm font-medium text-gray-300 mb-2">Target Price <span class="text-red-500">*</span></label>
                            <input type="number" id="modal_target_price" name="target_price" step="0.000001" required class="w-full bg-white border border-gray-600 text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 48000.00">
                        </div>

                        <div>
                            <label for="modal_stop_loss" class="block text-sm font-medium text-gray-300 mb-2">Stop Loss <span class="text-red-500">*</span></label>
                            <input type="number" id="modal_stop_loss" name="stop_loss" step="0.000001" required class="w-full bg-white border border-gray-600 text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 43000.00">
                        </div>

                        <div>
                            <label for="modal_expiry_time" class="block text-sm font-medium text-gray-300 mb-2">Signal Expiry <span class="text-red-500">*</span></label>
                            <input type="datetime-local" id="modal_expiry_time" name="expiry_time" required class="w-full bg-white border border-gray-600 text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-gray-400 mt-1">Set when this signal will expire and become invalid</p>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="p-6 bg-gray-700 flex justify-end space-x-3">
                <button type="button" onclick="closeCreateModal()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Cancel
                </button>
                <button type="button" onclick="submitSignalForm()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Create Signal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function filterByPlan(planId) {
    const url = new URL(window.location);
    if (planId) {
        url.searchParams.set('plan_id', planId);
    } else {
        url.searchParams.delete('plan_id');
    }
    window.location.href = url.toString();
}

function openCreateModal() {
    document.getElementById('createSignalModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeCreateModal() {
    document.getElementById('createSignalModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    // Reset form
    document.getElementById('createSignalForm').reset();
}

function submitSignalForm() {
    const form = document.getElementById('createSignalForm');
    const formData = new FormData(form);
    
    // Show loading state
    const submitBtn = document.querySelector('button[onclick="submitSignalForm()"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Creating...';
    submitBtn.disabled = true;
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal and reload page
            closeCreateModal();
            window.location.reload();
        } else {
            // Show error message
            alert(data.message || 'Failed to create signal');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while creating the signal');
    })
    .finally(() => {
        // Reset button state
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
}

// Close modal when clicking outside
document.getElementById('createSignalModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCreateModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCreateModal();
    }
});

// Filter trade pairs based on selected type
function filterTradePairs() {
    const marketType = document.getElementById('modal_market_type').value;
    const tradePairSelect = document.getElementById('modal_trade_pair');
    const tradePairOptions = tradePairSelect.options;

    // Reset trade pair dropdown
    tradePairSelect.value = '';

    // Loop through options and show/hide based on market type
    for (let i = 0; i < tradePairOptions.length; i++) {
        const option = tradePairOptions[i];
        if (option.value === '') {
            option.style.display = '';
        } else {
            if (option.dataset.type === marketType) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        }
    }
}
</script>
@endsection
