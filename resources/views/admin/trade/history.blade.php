@extends('admin.layouts.app')

@section('content')
<div class="px-4 pt-5">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Trade History</h1>
        <p class="text-gray-600 dark:text-gray-400">Manage all user trades from a single interface</p>
    </div>

    <!-- Success Message Container -->
    <div id="successMessage" class="hidden mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-800 dark:border-green-600 dark:text-green-300">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span id="successMessageText"></span>
            </div>
            <button onclick="hideSuccessMessage()" class="text-green-500 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Error Message Container -->
    <div id="errorMessage" class="hidden mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:bg-red-800 dark:border-red-600 dark:text-red-300">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span id="errorMessageText"></span>
            </div>
            <button onclick="hideErrorMessage()" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mb-6">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button id="openTradesTab" class="tab-button py-2 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm">
                    Open Trades
                    <span class="ml-2 bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">{{ $openTrades->count() }}</span>
                </button>
                <button id="closedTradesTab" class="tab-button py-2 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm">
                    Closed Trades
                    <span class="ml-2 bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">{{ $closedTrades->count() }}</span>
                </button>
            </nav>
        </div>
    </div>

    <!-- Open Trades Tab Content -->
    <div id="openTradesContent" class="tab-content" style="display: block;">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Open Trades</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pair</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Side</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PnL</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leverage</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($openTrades as $trade)
                        @php
                            $isLiveTrade = $trade instanceof \App\Models\LiveTrade;
                            $tradeType = $isLiveTrade ? 'live_trade' : 'trade';
                        @endphp
                        <tr data-trade-id="{{ $trade->id }}" data-trade-type="{{ $tradeType }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $trade->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $trade->user->fullname() ?? $trade->user->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                @if($isLiveTrade)
                                    {{ strtoupper($trade->symbol) }}
                                @else
                                    {{ $trade->trade_pair->pair ?? 'N/A' }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                @if($isLiveTrade)
                                    {{ ucfirst($trade->asset_type) }}
                                @else
                                    {{ $trade->trade_pair->type ?? 'N/A' }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $side = $isLiveTrade ? $trade->side : $trade->action_type;
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $side === 'buy' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($side ?? 'N/A') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                ${{ number_format($trade->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($trade->profit_loss !== null)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $trade->profit_loss >= 0 ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                        {{ $trade->profit_loss >= 0 ? '+' : '' }}${{ number_format($trade->profit_loss, 2) }}
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                        $0.00
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $trade->leverage ?? 'N/A' }}x
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                @if($isLiveTrade)
                                    N/A
                                @else
                                    {{ $trade->duration ?? 'N/A' }} min
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {!! $trade->adminStatus() !!}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button onclick="openCloseTradeModal('{{ $trade->id }}', '{{ $trade->amount }}', '{{ $tradeType }}')" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                    Close
                                </button>
                                <button onclick="openEditPnlModal('{{ $trade->id }}', '{{ $tradeType }}')" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                    Edit PnL
                                </button>
                                <button onclick="deleteTrade('{{ $trade->id }}', '{{ $tradeType }}')" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                No open trades found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <!-- Summary Row -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total PnL:</span>
                        <span class="text-lg font-bold {{ $openTrades->sum('profit_loss') >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ${{ number_format($openTrades->sum('profit_loss') ?? 0, 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Closed Trades Tab Content -->
    <div id="closedTradesContent" class="tab-content" style="display: none;">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Closed Trades</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pair</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Side</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PnL</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Closed At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($closedTrades as $trade)
                        @php
                            $isLiveTrade = $trade instanceof \App\Models\LiveTrade;
                            $tradeType = $isLiveTrade ? 'live_trade' : 'trade';
                        @endphp
                        <tr data-trade-id="{{ $trade->id }}" data-trade-type="{{ $tradeType }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $trade->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $trade->user->fullname() ?? $trade->user->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                @if($isLiveTrade)
                                    {{ strtoupper($trade->symbol) }}
                                @else
                                    {{ $trade->trade_pair->pair ?? 'N/A' }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                @if($isLiveTrade)
                                    {{ ucfirst($trade->asset_type) }}
                                @else
                                    {{ $trade->trade_pair->type ?? 'N/A' }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $side = $isLiveTrade ? $trade->side : $trade->action_type;
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $side === 'buy' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($side ?? 'N/A') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                ${{ number_format($trade->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($trade->profit_loss !== null)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $trade->profit_loss >= 0 ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                        {{ $trade->profit_loss >= 0 ? '+' : '' }}${{ number_format($trade->profit_loss, 2) }}
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                        $0.00
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $trade->updated_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button onclick="openEditPnlModal('{{ $trade->id }}', '{{ $tradeType }}')" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                    Edit PnL
                                </button>
                                <button onclick="deleteTrade('{{ $trade->id }}', '{{ $tradeType }}')" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                No closed trades found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <!-- Summary Row -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total PnL:</span>
                        <span class="text-lg font-bold {{ $closedTrades->sum('profit_loss') >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ${{ number_format($closedTrades->sum('profit_loss') ?? 0, 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Close Trade Modal -->
<div id="closeTradeModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-6" style="padding-top: 15vh;">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all w-full max-w-lg mx-auto">
            <form id="closeTradeForm" method="POST">
                @csrf
                <input type="hidden" name="trade_type" id="closeTradeType" value="trade">
                <div class="px-6 py-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Close Trade</h3>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Profit/Loss</label>
                        <input type="number" step="0.01" name="profit_loss" id="profitLossInput" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 flex justify-end space-x-3">
                    <button type="button" onclick="closeCloseTradeModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-600 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-500">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                        Close Trade
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit PnL Modal -->
<div id="editPnlModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-6" style="padding-top: 15vh;">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all w-full max-w-lg mx-auto">
            <form id="editPnlForm" method="POST">
                @csrf
                <input type="hidden" name="trade_type" id="editPnlTradeType" value="trade">
                <div class="px-6 py-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Edit Profit/Loss</h3>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Profit/Loss</label>
                        <input type="number" step="0.01" name="profit_loss" id="editPnlInput" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 flex justify-end space-x-3">
                    <button type="button" onclick="closeEditPnlModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-600 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-500">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700">
                        Update PnL
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteTradeModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-6" style="padding-top: 15vh;">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all w-full max-w-lg mx-auto">
            <div class="px-6 py-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Delete Trade</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Are you sure you want to delete this trade? This action cannot be undone.</p>
            </div>
            <form id="deleteTradeForm" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" name="trade_type" id="deleteTradeType" value="trade">
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 flex justify-end space-x-3">
                    <button type="button" onclick="closeDeleteTradeModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-600 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-500">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<style>
.modal-center {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
}

.modal-content {
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}
</style>
<script>
// Tab functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing tabs...');
    
    const openTradesTab = document.getElementById('openTradesTab');
    const closedTradesTab = document.getElementById('closedTradesTab');
    const openTradesContent = document.getElementById('openTradesContent');
    const closedTradesContent = document.getElementById('closedTradesContent');

    console.log('Elements found:', {
        openTradesTab: !!openTradesTab,
        closedTradesTab: !!closedTradesTab,
        openTradesContent: !!openTradesContent,
        closedTradesContent: !!closedTradesContent
    });
    
    console.log('Element details:', {
        openTradesTab: openTradesTab ? openTradesTab.id : 'null',
        closedTradesTab: closedTradesTab ? closedTradesTab.id : 'null',
        openTradesContent: openTradesContent ? openTradesContent.id : 'null',
        closedTradesContent: closedTradesContent ? closedTradesContent.id : 'null'
    });

    function switchTab(activeTab, activeContent, inactiveTab, inactiveContent) {
        console.log('Switching tab to:', activeTab.id);
        console.log('Active tab element:', activeTab);
        console.log('Active content element:', activeContent);
        
        // Reset both tabs to inactive state
        openTradesTab.classList.remove('border-blue-500', 'text-blue-600');
        openTradesTab.classList.add('border-transparent', 'text-gray-500');
        closedTradesTab.classList.remove('border-blue-500', 'text-blue-600');
        closedTradesTab.classList.add('border-transparent', 'text-gray-500');
        
        // Hide both content areas
        openTradesContent.style.display = 'none';
        closedTradesContent.style.display = 'none';
        
        // Set active tab styles
        activeTab.classList.add('border-blue-500', 'text-blue-600');
        activeTab.classList.remove('border-transparent', 'text-gray-500');
        
        // Show active content
        activeContent.style.display = 'block';
        
        console.log('Tab switch completed. Active tab classes:', activeTab.className);
        console.log('Active content display:', activeContent.style.display);
    }

    if (openTradesTab && closedTradesTab) {
        // Initialize first tab as active
        openTradesTab.classList.add('border-blue-500', 'text-blue-600');
        openTradesTab.classList.remove('border-transparent', 'text-gray-500');
        
        // Ensure first tab content is visible
        openTradesContent.style.display = 'block';
        closedTradesContent.style.display = 'none';
        
        openTradesTab.addEventListener('click', () => {
            console.log('Open trades tab clicked');
            switchTab(openTradesTab, openTradesContent, closedTradesTab, closedTradesContent);
        });

        closedTradesTab.addEventListener('click', () => {
            console.log('Closed trades tab clicked');
            switchTab(closedTradesTab, closedTradesContent, openTradesTab, openTradesContent);
        });
        
        console.log('Event listeners attached successfully');
        
    } else {
        console.error('Tab elements not found!');
    }
});

// Modal functions
function openCloseTradeModal(tradeId, amount, tradeType = 'trade') {
    document.getElementById('closeTradeForm').action = `{{ route('admin.trade.close', ':id') }}`.replace(':id', tradeId);
    document.getElementById('closeTradeType').value = tradeType;
    document.getElementById('closeTradeModal').classList.remove('hidden');
}

function closeCloseTradeModal() {
    document.getElementById('closeTradeModal').classList.add('hidden');
}

function openEditPnlModal(tradeId, tradeType = 'trade') {
    const actionUrl = `{{ route('admin.trade.edit-pnl', ':id') }}`.replace(':id', tradeId);
    console.log('Edit PnL URL:', actionUrl);
    document.getElementById('editPnlForm').action = actionUrl;
    document.getElementById('editPnlTradeType').value = tradeType;
    document.getElementById('editPnlModal').classList.remove('hidden');
}

function closeEditPnlModal() {
    document.getElementById('editPnlModal').classList.add('hidden');
}

function deleteTrade(tradeId, tradeType = 'trade') {
    document.getElementById('deleteTradeForm').action = `{{ route('admin.trade.destroy', ':id') }}`.replace(':id', tradeId);
    document.getElementById('deleteTradeType').value = tradeType;
    document.getElementById('deleteTradeModal').classList.remove('hidden');
}

function closeDeleteTradeModal() {
    document.getElementById('deleteTradeModal').classList.add('hidden');
}

// Message display functions
function showSuccessMessage(message) {
    const successMessage = document.getElementById('successMessage');
    const successMessageText = document.getElementById('successMessageText');
    
    successMessageText.textContent = message;
    successMessage.classList.remove('hidden');
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        successMessage.classList.add('hidden');
    }, 5000);
}

function showErrorMessage(message) {
    const errorMessage = document.getElementById('errorMessage');
    const errorMessageText = document.getElementById('errorMessageText');
    
    errorMessageText.textContent = message;
    errorMessage.classList.remove('hidden');
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        errorMessage.classList.add('hidden');
    }, 5000);
}

function hideSuccessMessage() {
    document.getElementById('successMessage').classList.add('hidden');
}

function hideErrorMessage() {
    document.getElementById('errorMessage').classList.add('hidden');
}

// Handle form submissions
document.getElementById('editPnlForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const tradeId = this.action.split('/').pop();
    const submitButton = this.querySelector('button[type="submit"]');
    
    console.log('Submitting form to:', this.action);
    console.log('Form data:', Object.fromEntries(formData));
    
    // Show loading state
    submitButton.disabled = true;
    submitButton.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Updating...';
    
    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            closeEditPnlModal();
            showSuccessMessage(data.message || 'PnL updated successfully!');
            
            // Update the PnL value in the table without reloading
            updateTradePnlInTable(tradeId, formData.get('profit_loss'));
        } else {
            showErrorMessage(data.message || 'Error updating PnL');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('Error updating PnL. Please try again.');
    })
    .finally(() => {
        // Reset button state
        submitButton.disabled = false;
        submitButton.innerHTML = 'Update PnL';
    });
});

// Function to update PnL value in the table without reloading
function updateTradePnlInTable(tradeId, newPnl) {
    // Find the trade row in both open and closed trades tables
    const openTradesTable = document.querySelector('#openTradesContent table tbody');
    const closedTradesTable = document.querySelector('#closedTradesContent table tbody');
    
    // Update in open trades table
    if (openTradesTable) {
        const openTradeRow = openTradesTable.querySelector(`tr[data-trade-id="${tradeId}"]`);
        if (openTradeRow) {
            const pnlCell = openTradeRow.querySelector('td:nth-child(7)'); // PnL column
            if (pnlCell) {
                const pnlValue = parseFloat(newPnl);
                const badgeClass = pnlValue >= 0 ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200';
                const sign = pnlValue >= 0 ? '+' : '';
                
                pnlCell.innerHTML = `
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${badgeClass}">
                        ${sign}$${pnlValue.toFixed(2)}
                    </span>
                `;
            }
        }
    }
    
    // Update in closed trades table
    if (closedTradesTable) {
        const closedTradeRow = closedTradesTable.querySelector(`tr[data-trade-id="${tradeId}"]`);
        if (closedTradeRow) {
            const pnlCell = closedTradeRow.querySelector('td:nth-child(7)'); // PnL column
            if (pnlCell) {
                const pnlValue = parseFloat(newPnl);
                const badgeClass = pnlValue >= 0 ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200';
                const sign = pnlValue >= 0 ? '+' : '';
                
                pnlCell.innerHTML = `
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${badgeClass}">
                        ${sign}$${pnlValue.toFixed(2)}
                    </span>
                `;
            }
        }
    }
    
    // Update summary totals
    updateSummaryTotals();
}

// Function to update summary totals
function updateSummaryTotals() {
    // This function would recalculate and update the summary totals
    // For now, we'll just note that totals need updating
    console.log('Summary totals updated');
}
</script>
@endpush
