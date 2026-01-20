@extends('dashboard.layout.app')

@section('content')
<div class="p-6">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col gap-4">
            <!-- Title Section -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-white">Edit Bot: {{ $bot->name }}</h1>
                    <p class="text-gray-400">{{ $bot->base_asset }}/{{ $bot->quote_asset }} • {{ ucfirst($bot->strategy) }} Strategy</p>
                </div>
            </div>
            
            <!-- Buttons Section -->
            <div class="flex flex-col sm:flex-row sm:justify-end gap-3">
                <a href="{{ route('user.botTrading.show', $bot) }}" class="w-full sm:w-auto px-4 py-3 border border-gray-600 rounded-lg text-gray-300 hover:bg-gray-700 transition-colors text-center flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span>Back to Bot</span>
                </a>
                <button id="editBotBtn" class="w-full sm:w-auto px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors text-center">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Bot Settings
                </button>
            </div>
        </div>
    </div>

    <!-- Bot Information Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Current Settings -->
        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <h3 class="text-lg font-medium text-white mb-4">Current Settings</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-400">Bot Name:</span>
                    <span class="text-white">{{ $bot->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Trading Pair:</span>
                    <span class="text-white">{{ $bot->base_asset }}/{{ $bot->quote_asset }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Strategy:</span>
                    <span class="text-white">{{ ucfirst(str_replace('_', ' ', $bot->strategy)) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Leverage:</span>
                    <span class="text-white">{{ $bot->leverage }}x</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Max Investment:</span>
                    <span class="text-white">${{ number_format($bot->max_investment, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Trade Duration:</span>
                    <span class="text-white">{{ $bot->trade_duration }}</span>
                </div>
                @if($bot->target_yield_percentage)
                <div class="flex justify-between">
                    <span class="text-gray-400">Target Yield:</span>
                    <span class="text-white">{{ $bot->target_yield_percentage }}%</span>
                </div>
                @endif
                @if($bot->stop_loss_percentage)
                <div class="flex justify-between">
                    <span class="text-gray-400">Stop Loss:</span>
                    <span class="text-white">{{ $bot->stop_loss_percentage }}%</span>
                </div>
                @endif
                @if($bot->take_profit_percentage)
                <div class="flex justify-between">
                    <span class="text-gray-400">Take Profit:</span>
                    <span class="text-white">{{ $bot->take_profit_percentage }}%</span>
                </div>
                @endif
                @if($bot->daily_loss_limit)
                <div class="flex justify-between">
                    <span class="text-gray-400">Daily Loss Limit:</span>
                    <span class="text-white">${{ number_format($bot->daily_loss_limit, 2) }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Performance Summary -->
        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <h3 class="text-lg font-medium text-white mb-4">Performance Summary</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-400">Status:</span>
                    <span class="px-2 py-1 rounded text-sm font-medium 
                        @if($bot->status === 'active') bg-green-900 text-green-300
                        @elseif($bot->status === 'paused') bg-yellow-900 text-yellow-300
                        @else bg-red-900 text-red-300 @endif">
                        {{ ucfirst($bot->status) }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Total Trades:</span>
                    <span class="text-white">{{ $bot->total_trades }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Total Invested:</span>
                    <span class="text-white">${{ number_format($bot->total_invested, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Total Profit:</span>
                    <span class="text-white {{ $bot->total_profit >= 0 ? 'text-green-400' : 'text-red-400' }}">
                        ${{ number_format($bot->total_profit, 2) }}
                    </span>
                </div>
                @if($bot->success_rate)
                <div class="flex justify-between">
                    <span class="text-gray-400">Success Rate:</span>
                    <span class="text-white">{{ number_format($bot->success_rate, 1) }}%</span>
                </div>
                @endif
                @if($bot->started_at)
                <div class="flex justify-between">
                    <span class="text-gray-400">Started:</span>
                    <span class="text-white">{{ $bot->started_at->format('M d, Y H:i') }}</span>
                </div>
                @endif
                @if($bot->last_trade_at)
                <div class="flex justify-between">
                    <span class="text-gray-400">Last Trade:</span>
                    <span class="text-white">{{ $bot->last_trade_at->format('M d, Y H:i') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Important Notice -->
    <div class="bg-blue-900/20 border border-blue-700 rounded-lg p-4 mb-6">
        <div class="flex items-start space-x-3">
            <svg class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
            <div>
                <h4 class="text-blue-400 font-medium mb-1">Editing Restrictions</h4>
                <p class="text-blue-300 text-sm">
                    For security and consistency reasons, some bot settings cannot be modified after creation. 
                    You can edit the bot name, investment amounts, risk management settings, and trading parameters, 
                    but the trading pair, strategy type, and leverage cannot be changed.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Edit Bot Modal -->
<div id="editBotModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full border border-gray-700 max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-700">
                <div>
                    <h3 class="text-xl font-semibold text-white">Edit Bot Settings</h3>
                    <p class="text-gray-400 text-sm mt-1">Update your bot configuration</p>
                </div>
                <button id="closeEditModal" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto flex-1">
                <form id="editBotForm" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information -->
                    <div>
                        <h4 class="text-lg font-medium text-white mb-4">Basic Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Bot Name</label>
                                <input type="text" name="name" value="{{ $bot->name }}" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="My BTC Bot">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Trading Pair</label>
                                <input type="text" value="{{ $bot->base_asset }}/{{ $bot->quote_asset }}" class="w-full px-3 py-2 bg-gray-600 border border-gray-600 rounded text-gray-400 cursor-not-allowed" disabled>
                                <div class="mt-1 text-xs text-gray-500">Cannot be changed after creation</div>
                            </div>
                        </div>
                    </div>

                    <!-- Investment Settings -->
                    <div>
                        <h4 class="text-lg font-medium text-white mb-4">Investment Settings</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Max Investment ({{ auth()->user()->currency ?? 'USD' }})</label>
                                <input type="number" name="max_investment" value="{{ $bot->max_investment }}" step="0.01" min="10" max="1000000" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="1000.00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Leverage</label>
                                <input type="text" value="{{ $bot->leverage }}x" class="w-full px-3 py-2 bg-gray-600 border border-gray-600 rounded text-gray-400 cursor-not-allowed" disabled>
                                <div class="mt-1 text-xs text-gray-500">Cannot be changed after creation</div>
                            </div>
                        </div>
                    </div>

                    <!-- Trading Parameters -->
                    <div>
                        <h4 class="text-lg font-medium text-white mb-4">Trading Parameters</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Trade Duration</label>
                                <select name="trade_duration" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="1h" {{ $bot->trade_duration === '1h' ? 'selected' : '' }}>1 Hour</option>
                                    <option value="4h" {{ $bot->trade_duration === '4h' ? 'selected' : '' }}>4 Hours</option>
                                    <option value="24h" {{ $bot->trade_duration === '24h' ? 'selected' : '' }}>24 Hours</option>
                                    <option value="1w" {{ $bot->trade_duration === '1w' ? 'selected' : '' }}>1 Week</option>
                                    <option value="1m" {{ $bot->trade_duration === '1m' ? 'selected' : '' }}>1 Month</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Target Yield (%)</label>
                                <input type="number" name="target_yield_percentage" value="{{ $bot->target_yield_percentage }}" step="0.1" min="0.1" max="100" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="5.0">
                            </div>
                        </div>
                    </div>

                    <!-- Risk Management -->
                    <div>
                        <h4 class="text-lg font-medium text-white mb-4">Risk Management</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Stop Loss (%)</label>
                                <input type="number" name="stop_loss_percentage" value="{{ $bot->stop_loss_percentage }}" step="0.1" min="0.1" max="50" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="10.0">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Take Profit (%)</label>
                                <input type="number" name="take_profit_percentage" value="{{ $bot->take_profit_percentage }}" step="0.1" min="0.1" max="1000" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="20.0">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Daily Loss Limit ({{ auth()->user()->currency ?? 'USD' }})</label>
                                <input type="number" name="daily_loss_limit" value="{{ $bot->daily_loss_limit }}" step="0.01" min="1" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="100.00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Max Open Trades</label>
                                <input type="number" name="max_open_trades" value="{{ $bot->max_open_trades }}" min="1" max="50" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="5">
                            </div>
                        </div>
                    </div>

                    <!-- Trade Amounts -->
                    <div>
                        <h4 class="text-lg font-medium text-white mb-4">Trade Amounts</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Min Trade Amount ({{ auth()->user()->currency ?? 'USD' }})</label>
                                <input type="number" name="min_trade_amount" value="{{ $bot->min_trade_amount }}" step="0.01" min="1" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="10.00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Max Trade Amount ({{ auth()->user()->currency ?? 'USD' }})</label>
                                <input type="number" name="max_trade_amount" value="{{ $bot->max_trade_amount }}" step="0.01" min="1" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="100.00">
                            </div>
                        </div>
                    </div>

                    <!-- Auto Settings -->
                    <div>
                        <h4 class="text-lg font-medium text-white mb-4">Auto Settings</h4>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" name="auto_close" {{ $bot->auto_close ? 'checked' : '' }} class="text-blue-600 bg-gray-700 border-gray-600 rounded">
                                <span class="ml-2 text-sm text-gray-300">Auto-close trades at duration</span>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="trading_24_7" {{ $bot->trading_24_7 ? 'checked' : '' }} class="text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-300">Trade 24/7</span>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="auto_restart" {{ $bot->auto_restart ? 'checked' : '' }} class="text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-300">Auto Restart</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-gray-700">
                <button id="cancelEditBtn" class="px-4 py-2 border border-gray-600 rounded-lg text-gray-300 hover:bg-gray-700 transition-colors">
                    Cancel
                </button>
                <button id="submitEditBtn" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                    Update Bot
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Modals -->
<div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-gray-800 rounded-lg shadow-xl max-w-md w-full border border-gray-700">
            <div class="p-6 text-center">
                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-white mb-2" id="successTitle">Success!</h3>
                <p class="text-gray-400 mb-6" id="successMessage">Bot updated successfully.</p>
                <button id="closeSuccessModal" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-medium transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<div id="errorModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-gray-800 rounded-lg shadow-xl max-w-md w-full border border-gray-700">
            <div class="p-6 text-center">
                <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-white mb-2" id="errorTitle">Error!</h3>
                <p class="text-gray-400 mb-6" id="errorMessage">An error occurred. Please try again.</p>
                <button id="closeErrorModal" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg font-medium transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const editBotBtn = document.getElementById('editBotBtn');
    const editBotModal = document.getElementById('editBotModal');
    const closeEditModal = document.getElementById('closeEditModal');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    const submitEditBtn = document.getElementById('submitEditBtn');
    const editBotForm = document.getElementById('editBotForm');
    const successModal = document.getElementById('successModal');
    const errorModal = document.getElementById('errorModal');
    const closeSuccessModal = document.getElementById('closeSuccessModal');
    const closeErrorModal = document.getElementById('closeErrorModal');

    // Open edit modal
    editBotBtn.addEventListener('click', function() {
        editBotModal.classList.remove('hidden');
    });

    // Close edit modal
    function closeEditModalFunc() {
        editBotModal.classList.add('hidden');
    }

    closeEditModal.addEventListener('click', closeEditModalFunc);
    cancelEditBtn.addEventListener('click', closeEditModalFunc);

    // Close modal when clicking outside
    editBotModal.addEventListener('click', function(e) {
        if (e.target === editBotModal) {
            closeEditModalFunc();
        }
    });

    // Submit edit form
    submitEditBtn.addEventListener('click', async function() {
        const formData = new FormData(editBotForm);
        
        // Show loading state
        submitEditBtn.disabled = true;
        submitEditBtn.innerHTML = `
            <svg class="w-4 h-4 inline mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Updating...
        `;

        try {
            // Add leverage value to form data since it's disabled in the form
            formData.append('leverage', '{{ $bot->leverage }}');
            
            const response = await fetch('{{ route("user.botTrading.update", $bot) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams(formData)
            });

            const result = await response.json();

            if (result.success) {
                closeEditModalFunc();
                showSuccessModal('Bot Updated!', result.message);
                setTimeout(() => window.location.reload(), 2000);
            } else {
                let errorMessage = result.message || 'Failed to update bot';
                if (result.errors) {
                    const errorList = Object.values(result.errors).flat();
                    errorMessage = errorList.join(', ');
                }
                showErrorModal('Update Failed', errorMessage);
            }
        } catch (error) {
            console.error('Error:', error);
            showErrorModal('Error', 'Failed to update bot: ' + error.message);
        } finally {
            // Reset button state
            submitEditBtn.disabled = false;
            submitEditBtn.innerHTML = 'Update Bot';
        }
    });

    // Close success/error modals
    closeSuccessModal.addEventListener('click', function() {
        successModal.classList.add('hidden');
    });

    closeErrorModal.addEventListener('click', function() {
        errorModal.classList.add('hidden');
    });

    // Close modals when clicking outside
    successModal.addEventListener('click', function(e) {
        if (e.target === successModal) {
            successModal.classList.add('hidden');
        }
    });

    errorModal.addEventListener('click', function(e) {
        if (e.target === errorModal) {
            errorModal.classList.add('hidden');
        }
    });

    function showSuccessModal(title, message) {
        document.getElementById('successTitle').textContent = title;
        document.getElementById('successMessage').textContent = message;
        successModal.classList.remove('hidden');
    }

    function showErrorModal(title, message) {
        document.getElementById('errorTitle').textContent = title;
        document.getElementById('errorMessage').textContent = message;
        errorModal.classList.remove('hidden');
    }
});
</script>
@endpush
