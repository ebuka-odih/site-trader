@extends('dashboard.layout.app')
@section('content')

<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-2xl font-bold text-white">Transaction History</h1>
            <p class="text-gray-400 mt-1">View all your financial activities and transaction history</p>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-gray-800 rounded-lg border border-gray-700">
        <div class="border-b border-gray-700">
            <!-- Mobile: Dropdown/Select for small screens -->
            <div class="block md:hidden px-6 py-4">
                <label for="mobileTabSelect" class="block text-sm font-medium text-gray-300 mb-2">Select Transaction Type</label>
                <select id="mobileTabSelect" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="deposits">📥 Deposits</option>
                    <option value="withdrawals">📤 Withdrawals</option>
                    <option value="transfers">🔄 Transfers</option>
                    <option value="trades">📊 Trades</option>
                    <option value="holdings">💼 Holdings</option>
                </select>
            </div>
            
            <!-- Desktop: Horizontal tabs for larger screens -->
            <nav class="hidden md:flex space-x-8 px-6" aria-label="Tabs">
                <button id="depositsTab" class="tab-button py-4 px-1 border-b-2 border-transparent text-gray-400 hover:text-gray-300 font-medium text-sm whitespace-nowrap">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Deposits</span>
                    </div>
                </button>
                <button id="withdrawalsTab" class="tab-button py-4 px-1 border-b-2 border-transparent text-gray-400 hover:text-gray-300 font-medium text-sm whitespace-nowrap">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Withdrawals</span>
                    </div>
                </button>
                <button id="transfersTab" class="tab-button py-4 px-1 border-b-2 border-transparent text-gray-400 hover:text-gray-300 font-medium text-sm whitespace-nowrap">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" clip-rule="evenodd"></path>
                        </svg>
                        <span>Transfers</span>
                    </div>
                </button>
                <button id="tradesTab" class="tab-button py-4 px-1 border-b-2 border-transparent text-gray-400 hover:text-gray-300 font-medium text-sm whitespace-nowrap">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Trades</span>
                    </div>
                </button>
                <button id="holdingsTab" class="tab-button py-4 px-1 border-b-2 border-transparent text-gray-400 hover:text-gray-300 font-medium text-sm whitespace-nowrap">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6z"></path>
                        </svg>
                        <span>Holdings</span>
                    </div>
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Deposits Tab -->
            <div id="depositsContent" class="tab-content">
                <!-- Mobile Card View -->
                <div class="block md:hidden space-y-4 mb-6">
                    @forelse($deposits ?? [] as $deposit)
                    <div class="bg-gray-700 rounded-lg p-4 space-y-3">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="text-sm text-gray-400">Date</div>
                                <div class="text-white font-medium">{{ $deposit->created_at->format('M d, Y H:i') }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-400">Amount</div>
                                <div class="text-white font-bold">{{ auth()->user()->formatAmount($deposit->amount) }}</div>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-sm text-gray-400">Payment Method</div>
                                <div class="text-white">{{ $deposit->payment_method ? $deposit->payment_method->crypto_display_name ?? 'N/A' : 'N/A' }}</div>
                            </div>
                            <div class="text-right">
                                @php
                                    $depositStatusClasses = [
                                        \App\Models\Deposit::STATUS_APPROVED => 'bg-green-100 text-green-800',
                                        \App\Models\Deposit::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
                                        \App\Models\Deposit::STATUS_DECLINED => 'bg-red-100 text-red-800',
                                        \App\Models\Deposit::STATUS_IN_REVIEW => 'bg-blue-100 text-blue-800',
                                    ];
                                    $depositStatusClass = $depositStatusClasses[$deposit->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $depositStatusClass }}">
                                    {{ $deposit->status_label }}
                                </span>
                            </div>
                        </div>
                        @if($deposit->reference)
                        <div>
                            <div class="text-sm text-gray-400">Reference</div>
                            <div class="text-gray-300 text-sm">{{ $deposit->reference }}</div>
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        <div class="text-gray-400">
                            <p class="text-lg font-medium">No deposits found</p>
                            <p class="text-sm">Your deposit history will appear here</p>
                        </div>
                    </div>
                    @endforelse
                </div>
                
                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700 bg-gray-800 rounded-lg">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Payment Method</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Reference</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            @forelse($deposits ?? [] as $deposit)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    {{ $deposit->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    {{ auth()->user()->formatAmount($deposit->amount) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    {{ $deposit->payment_method ? $deposit->payment_method->crypto_display_name ?? 'N/A' : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $depositStatusClasses = [
                                            \App\Models\Deposit::STATUS_APPROVED => 'bg-green-100 text-green-800',
                                            \App\Models\Deposit::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
                                            \App\Models\Deposit::STATUS_DECLINED => 'bg-red-100 text-red-800',
                                            \App\Models\Deposit::STATUS_IN_REVIEW => 'bg-blue-100 text-blue-800',
                                        ];
                                        $depositStatusClass = $depositStatusClasses[$deposit->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $depositStatusClass }}">
                                        {{ $deposit->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                    {{ $deposit->reference ?? 'N/A' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center space-y-3">
                                        <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        <div class="text-gray-400">
                                            <p class="text-lg font-medium">No deposits found</p>
                                            <p class="text-sm">Your deposit history will appear here</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Withdrawals Tab -->
            <div id="withdrawalsContent" class="tab-content hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700 bg-gray-800 rounded-lg">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">From Account</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Payment Method</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            @forelse($withdrawals ?? [] as $withdrawal)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    {{ $withdrawal->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    {{ auth()->user()->formatAmount($withdrawal->amount) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    {{ ucfirst(str_replace('_', ' ', $withdrawal->from_account ?? 'N/A')) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    {{ ucfirst($withdrawal->payment_method ?? 'N/A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $withdrawal->status == 1 ? 'bg-green-100 text-green-800' : 
                                           ($withdrawal->status == 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $withdrawal->status == 1 ? 'Completed' : ($withdrawal->status == 0 ? 'Pending' : 'Rejected') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center space-y-3">
                                        <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        <div class="text-gray-400">
                                            <p class="text-lg font-medium">No withdrawals found</p>
                                            <p class="text-sm">Your withdrawal history will appear here</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Transfers Tab -->
            <div id="transfersContent" class="tab-content hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700 bg-gray-800 rounded-lg">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">From</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">To</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            @forelse($transfers ?? [] as $transfer)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    {{ $transfer->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    ${{ number_format($transfer->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    {{ ucfirst(str_replace('_', ' ', $transfer->from_account ?? 'N/A')) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    {{ ucfirst(str_replace('_', ' ', $transfer->to_account ?? 'N/A')) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $transfer->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                           ($transfer->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($transfer->status ?? 'N/A') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center space-y-3">
                                        <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                        <div class="text-gray-400">
                                            <p class="text-lg font-medium">No transfers found</p>
                                            <p class="text-sm">Your transfer history will appear here</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Trades Tab -->
            <div id="tradesContent" class="tab-content hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700 bg-gray-800 rounded-lg">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Pair</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">P&L</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            @forelse($trades ?? [] as $trade)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    {{ $trade->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    {{ $trade->trade_pair->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $trade->action_type === 'buy' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($trade->action_type ?? 'N/A') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    ${{ number_format($trade->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm {{ ($trade->profit_loss ?? 0) >= 0 ? 'text-green-400' : 'text-red-400' }}">
                                    {{ ($trade->profit_loss ?? 0) >= 0 ? '+' : '' }}${{ number_format($trade->profit_loss ?? 0, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $trade->status === 'closed' ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ ucfirst($trade->status ?? 'N/A') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center space-y-3">
                                        <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        <div class="text-gray-400">
                                            <p class="text-lg font-medium">No trades found</p>
                                            <p class="text-sm">Your trading history will appear here</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Holdings Tab -->
            <div id="holdingsContent" class="tab-content hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700 bg-gray-800 rounded-lg">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Asset</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            @forelse($holdingTransactions ?? [] as $transaction)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    {{ $transaction->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    {{ $transaction->asset->symbol ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->type === 'buy' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($transaction->type ?? 'N/A') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    {{ number_format($transaction->quantity, 8) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    ${{ number_format($transaction->price_per_unit, 8) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    ${{ number_format($transaction->total_amount, 2) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center space-y-3">
                                        <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                        </svg>
                                        <div class="text-gray-400">
                                            <p class="text-lg font-medium">No holding transactions found</p>
                                            <p class="text-sm">Your asset purchase/sale history will appear here</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.tab-button.active {
    @apply border-blue-500 text-blue-400;
}

.tab-button:not(.active) {
    @apply border-transparent text-gray-400 hover:text-gray-300;
}

.tab-content {
    @apply hidden;
}

.tab-content.active {
    @apply block;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = ['deposits', 'withdrawals', 'transfers', 'trades', 'holdings'];
    
    // Get the tab parameter from URL
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab') || 'deposits';
    
    // Function to activate a tab
    function activateTab(tabName) {
        // Update mobile dropdown
        const mobileSelect = document.getElementById('mobileTabSelect');
        if (mobileSelect) {
            mobileSelect.value = tabName;
        }
        
        // Update desktop tabs
        tabs.forEach(name => {
            const btn = document.getElementById(name + 'Tab');
            const content = document.getElementById(name + 'Content');
            if (btn) btn.classList.remove('active', 'border-blue-500', 'text-blue-400');
            if (btn) btn.classList.add('border-transparent', 'text-gray-400');
            if (content) content.classList.remove('active');
            if (content) content.classList.add('hidden');
        });
        
        const activeBtn = document.getElementById(tabName + 'Tab');
        const activeContent = document.getElementById(tabName + 'Content');
        if (activeBtn) {
            activeBtn.classList.add('active', 'border-blue-500', 'text-blue-400');
            activeBtn.classList.remove('border-transparent', 'text-gray-400');
        }
        if (activeContent) {
            activeContent.classList.add('active');
            activeContent.classList.remove('hidden');
        }
    }
    
    // Activate the tab from URL parameter
    activateTab(activeTab);
    
    // Add click event listeners for desktop tabs
    tabs.forEach(tabName => {
        const tabButton = document.getElementById(tabName + 'Tab');
        if (tabButton) {
            tabButton.addEventListener('click', function() {
                activateTab(tabName);
            });
        }
    });
    
    // Add change event listener for mobile dropdown
    const mobileSelect = document.getElementById('mobileTabSelect');
    if (mobileSelect) {
        mobileSelect.addEventListener('change', function() {
            activateTab(this.value);
        });
    }
});
</script>

@endsection
