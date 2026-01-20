@extends('admin.layouts.app')

@section('title', 'AI Trader Management')

@section('content')
<div class="p-4">
    <div class="max-w-7xl mx-auto p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">AI Trader Management</h1>
                <p class="text-gray-600 dark:text-gray-400">Monitor and manage active AI traders</p>
            </div>
            <a href="{{ route('admin.ai-traders.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to AI Traders
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Active Traders Card -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wide">Active Traders</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalActiveTraders }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Investment Card -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wide">Total Investment</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($totalInvestment, 2) }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total P&L Card -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border-l-4 border-indigo-500">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wide">Total P&L</p>
                        <p class="text-2xl font-bold {{ $totalProfitLoss >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            ${{ number_format($totalProfitLoss, 2) }}
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Average Performance Card -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wide">Avg. Performance</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $totalActiveTraders > 0 ? number_format(($totalProfitLoss / $totalInvestment) * 100, 2) : 0 }}%
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Traders Table -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Active AI Traders</h3>
            </div>
            
            @if($activeTraders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 table-auto">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">User</th>
                            <th scope="col" class="px-6 py-3">AI Trader</th>
                            <th scope="col" class="px-6 py-3">Plan</th>
                            <th scope="col" class="px-6 py-3">Investment</th>
                            <th scope="col" class="px-6 py-3">Current Balance</th>
                            <th scope="col" class="px-6 py-3">P&L</th>
                            <th scope="col" class="px-6 py-3">Win Rate</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeTraders as $trader)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $trader->user->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $trader->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $trader->aiTrader->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $trader->aiTrader->ai_model }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                    {{ $trader->aiTraderPlan ? $trader->aiTraderPlan->name : 'No Plan' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 dark:text-white">${{ number_format($trader->investment_amount, 2) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 dark:text-white">${{ number_format($trader->current_balance, 2) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium {{ $trader->total_profit_loss >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    ${{ number_format($trader->total_profit_loss, 2) }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $trader->investment_amount > 0 ? number_format(($trader->total_profit_loss / $trader->investment_amount) * 100, 2) : 0 }}%
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 dark:text-white">{{ number_format($trader->win_rate, 1) }}%</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $trader->winning_trades }}/{{ $trader->total_trades_executed }} trades</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.ai-traders.history', $trader) }}" 
                                       class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" 
                                       title="View History">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </a>
                                    <button type="button" 
                                            class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 edit-performance-btn" 
                                            data-trader-id="{{ $trader->id }}"
                                            data-current-balance="{{ $trader->current_balance }}"
                                            data-total-profit-loss="{{ $trader->total_profit_loss }}"
                                            data-total-trades="{{ $trader->total_trades_executed }}"
                                            data-winning-trades="{{ $trader->winning_trades }}"
                                            data-win-rate="{{ $trader->win_rate }}"
                                            title="Edit Performance">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button type="button" 
                                            class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 create-trade-btn" 
                                            data-trader-id="{{ $trader->id }}"
                                            data-trader-name="{{ $trader->aiTrader->name }}"
                                            title="Create Trade">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($activeTraders->hasPages())
            <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $activeTraders->links() }}
            </div>
            @endif
            @else
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Active AI Traders</h3>
                <p class="text-gray-500 dark:text-gray-400">There are currently no active AI traders to manage.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Edit Performance Modal -->
<div id="editPerformanceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800 max-w-2xl">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Edit Trader Performance</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="editPerformanceForm" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="current_balance" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Current Balance</label>
                        <input type="number" id="current_balance" name="current_balance" step="0.01" required 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label for="total_profit_loss" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total Profit/Loss</label>
                        <input type="number" id="total_profit_loss" name="total_profit_loss" step="0.01" required 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label for="total_trades_executed" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total Trades Executed</label>
                        <input type="number" id="total_trades_executed" name="total_trades_executed" min="0" required 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label for="winning_trades" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Winning Trades</label>
                        <input type="number" id="winning_trades" name="winning_trades" min="0" required 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                </div>
                <div>
                    <label for="win_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Win Rate (%)</label>
                    <input type="number" id="win_rate" name="win_rate" min="0" max="100" step="0.1" required 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                </div>
                
                <div class="flex items-center justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                        Update Performance
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Trade Modal -->
<div id="createTradeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800 max-w-2xl">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 id="createTradeModalTitle" class="text-lg font-medium text-gray-900 dark:text-white">Create New Trade</h3>
                <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="createTradeForm" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="stock_symbol" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stock Symbol</label>
                        <input type="text" id="stock_symbol" name="stock_symbol" maxlength="10" required 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label for="trade_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Trade Type</label>
                        <select id="trade_type" name="trade_type" required 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <option value="">Select Type</option>
                            <option value="buy">Buy</option>
                            <option value="sell">Sell</option>
                        </select>
                    </div>
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantity</label>
                        <input type="number" id="quantity" name="quantity" min="1" required 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Price per Share</label>
                        <input type="number" id="price" name="price" step="0.01" min="0.01" required 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="profit_loss" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Profit/Loss</label>
                        <input type="number" id="profit_loss" name="profit_loss" step="0.01" required 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Positive for profit, negative for loss</p>
                    </div>
                    <div>
                        <label for="trade_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Trade Date</label>
                        <input type="date" id="trade_date" name="trade_date" value="{{ date('Y-m-d') }}" required 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                </div>
                
                <div class="flex items-center justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg">
                        Create Trade
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentTraderId = null;

// Edit Performance Modal
document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.edit-performance-btn');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            currentTraderId = this.dataset.traderId;
            
            document.getElementById('current_balance').value = this.dataset.currentBalance;
            document.getElementById('total_profit_loss').value = this.dataset.totalProfitLoss;
            document.getElementById('total_trades_executed').value = this.dataset.totalTrades;
            document.getElementById('winning_trades').value = this.dataset.winningTrades;
            document.getElementById('win_rate').value = this.dataset.winRate;
            
            document.getElementById('editPerformanceModal').classList.remove('hidden');
        });
    });

    // Create Trade Modal
    const createButtons = document.querySelectorAll('.create-trade-btn');
    createButtons.forEach(button => {
        button.addEventListener('click', function() {
            currentTraderId = this.dataset.traderId;
            const traderName = this.dataset.traderName;
            
            document.getElementById('createTradeModalTitle').textContent = `Create New Trade - ${traderName}`;
            document.getElementById('createTradeModal').classList.remove('hidden');
        });
    });

    // Edit Performance Form Submit
    document.getElementById('editPerformanceForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        
        fetch(`/admin/ai-traders-performance/${currentTraderId}/update`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    alert('Performance updated successfully!');
                    location.reload();
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred while updating performance'
                });
            } else {
                alert('Error updating performance');
            }
        });
    });

    // Create Trade Form Submit
    document.getElementById('createTradeForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        
        fetch(`/admin/ai-traders-trade/${currentTraderId}/create`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    alert('Trade created successfully!');
                    location.reload();
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred while creating trade'
                });
            } else {
                alert('Error creating trade');
            }
        });
    });
});

function closeEditModal() {
    document.getElementById('editPerformanceModal').classList.add('hidden');
}

function closeCreateModal() {
    document.getElementById('createTradeModal').classList.add('hidden');
}
</script>
@endpush