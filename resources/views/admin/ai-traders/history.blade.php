@extends('admin.layouts.app')

@section('title', 'AI Trader History - ' . $userAiTrader->aiTrader->name)

@section('content')
<div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">AI Trader History</h1>
                <p class="text-gray-600 dark:text-gray-400">{{ $userAiTrader->aiTrader->name }} - {{ $userAiTrader->user->name }}</p>
            </div>
            <a href="{{ route('admin.ai-traders.management') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Management
            </a>
        </div>

        <!-- Trader Information and Performance Summary -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Trader Information Card -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Trader Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">User</label>
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $userAiTrader->user->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $userAiTrader->user->email }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">AI Trader</label>
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $userAiTrader->aiTrader->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $userAiTrader->aiTrader->ai_model }} - {{ ucfirst($userAiTrader->aiTrader->trading_strategy) }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Plan</label>
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $userAiTrader->aiTraderPlan ? $userAiTrader->aiTraderPlan->name : 'No Plan' }}</div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Investment Amount</label>
                                    <div class="text-lg font-bold text-gray-900 dark:text-white">${{ number_format($userAiTrader->investment_amount, 2) }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Activated Date</label>
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $userAiTrader->activated_at->format('M d, Y H:i') }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                        Active
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Performance Summary Card -->
            <div>
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Performance Summary</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Current Balance:</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">${{ number_format($userAiTrader->current_balance, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Total P&L:</span>
                            <span class="text-sm font-bold {{ $userAiTrader->total_profit_loss >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                ${{ number_format($userAiTrader->total_profit_loss, 2) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Total Trades:</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $userAiTrader->total_trades_executed }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Winning Trades:</span>
                            <span class="text-sm font-bold text-green-600 dark:text-green-400">{{ $userAiTrader->winning_trades }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Win Rate:</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($userAiTrader->win_rate, 1) }}%</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Performance:</span>
                            <span class="text-sm font-bold {{ $userAiTrader->total_profit_loss >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $userAiTrader->investment_amount > 0 ? number_format(($userAiTrader->total_profit_loss / $userAiTrader->investment_amount) * 100, 2) : 0 }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Chart -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Performance Chart (Last 30 Days)</h3>
            </div>
            <div class="p-6">
                <div class="h-64">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Quick Actions</h3>
            </div>
            <div class="p-6">
                <div class="flex flex-wrap gap-3">
                    <button type="button" 
                            class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg flex items-center edit-performance-btn" 
                            data-trader-id="{{ $userAiTrader->id }}"
                            data-current-balance="{{ $userAiTrader->current_balance }}"
                            data-total-profit-loss="{{ $userAiTrader->total_profit_loss }}"
                            data-total-trades="{{ $userAiTrader->total_trades_executed }}"
                            data-winning-trades="{{ $userAiTrader->winning_trades }}"
                            data-win-rate="{{ $userAiTrader->win_rate }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Performance
                    </button>
                    <button type="button" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center create-trade-btn" 
                            data-trader-id="{{ $userAiTrader->id }}"
                            data-trader-name="{{ $userAiTrader->aiTrader->name }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create Trade
                    </button>
                </div>
            </div>
        </div>

        <!-- Recent Trades Table -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Trades</h3>
            </div>
            <div class="p-6">
                @if($recentTrades->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Date</th>
                                <th scope="col" class="px-6 py-3">Stock</th>
                                <th scope="col" class="px-6 py-3">Type</th>
                                <th scope="col" class="px-6 py-3">Quantity</th>
                                <th scope="col" class="px-6 py-3">Price</th>
                                <th scope="col" class="px-6 py-3">Amount</th>
                                <th scope="col" class="px-6 py-3">P&L</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTrades as $trade)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4">{{ $trade->created_at->format('M d, Y H:i') }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $trade->stock_symbol }}</td>
                                <td class="px-6 py-4">
                                    <span class="bg-{{ $trade->trade_type === 'buy' ? 'green' : 'red' }}-100 text-{{ $trade->trade_type === 'buy' ? 'green' : 'red' }}-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-{{ $trade->trade_type === 'buy' ? 'green' : 'red' }}-900 dark:text-{{ $trade->trade_type === 'buy' ? 'green' : 'red' }}-300">
                                        {{ ucfirst($trade->trade_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $trade->quantity }}</td>
                                <td class="px-6 py-4">${{ number_format($trade->price, 2) }}</td>
                                <td class="px-6 py-4">${{ number_format($trade->quantity * $trade->price, 2) }}</td>
                                <td class="px-6 py-4">
                                    <span class="font-medium {{ $trade->profit_loss >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        ${{ number_format($trade->profit_loss, 2) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Trades Yet</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">This AI trader hasn't executed any trades yet.</p>
                    <button type="button" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center mx-auto create-trade-btn" 
                            data-trader-id="{{ $userAiTrader->id }}"
                            data-trader-name="{{ $userAiTrader->aiTrader->name }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create First Trade
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Edit Performance Modal -->
<div id="editPerformanceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
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
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentTraderId = {{ $userAiTrader->id }};

    // Load performance chart
    loadPerformanceChart();

    // Edit Performance Modal
    const editButtons = document.querySelectorAll('.edit-performance-btn');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
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

    function loadPerformanceChart() {
        fetch(`/admin/ai-traders-performance/${currentTraderId}/data`)
        .then(response => response.json())
        .then(response => {
            if (response.success) {
                const ctx = document.getElementById('performanceChart').getContext('2d');
                const data = response.data;
                
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.map(item => item.date),
                        datasets: [{
                            label: 'Balance',
                            data: data.map(item => item.balance),
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    color: document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151'
                                }
                            },
                            title: {
                                display: true,
                                text: 'Balance Over Time',
                                color: document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: false,
                                grid: {
                                    color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                                },
                                ticks: {
                                    color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                                }
                            },
                            x: {
                                grid: {
                                    color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                                },
                                ticks: {
                                    color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                                }
                            }
                        }
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error loading chart:', error);
        });
    }
});

function closeEditModal() {
    document.getElementById('editPerformanceModal').classList.add('hidden');
}

function closeCreateModal() {
    document.getElementById('createTradeModal').classList.add('hidden');
}
</script>
@endpush