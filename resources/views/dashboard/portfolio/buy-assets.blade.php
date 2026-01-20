@extends('dashboard.layout.app')

@section('content')
<style>
/* Price change animations */
.price-up {
    animation: priceUp 3s ease-in-out;
    background-color: rgba(34, 197, 94, 0.2);
    transform: scale(1.02);
    box-shadow: 0 0 10px rgba(34, 197, 94, 0.3);
}

.price-down {
    animation: priceDown 3s ease-in-out;
    background-color: rgba(239, 68, 68, 0.2);
    transform: scale(1.02);
    box-shadow: 0 0 10px rgba(239, 68, 68, 0.3);
}

@keyframes priceUp {
    0% { 
        background-color: rgba(34, 197, 94, 0.4); 
        transform: scale(1.05);
        box-shadow: 0 0 15px rgba(34, 197, 94, 0.5);
    }
    50% { 
        background-color: rgba(34, 197, 94, 0.2); 
        transform: scale(1.02);
        box-shadow: 0 0 10px rgba(34, 197, 94, 0.3);
    }
    100% { 
        background-color: transparent; 
        transform: scale(1);
        box-shadow: none;
    }
}

@keyframes priceDown {
    0% { 
        background-color: rgba(239, 68, 68, 0.4); 
        transform: scale(1.05);
        box-shadow: 0 0 15px rgba(239, 68, 68, 0.5);
    }
    50% { 
        background-color: rgba(239, 68, 68, 0.2); 
        transform: scale(1.02);
        box-shadow: 0 0 10px rgba(239, 68, 68, 0.3);
    }
    100% { 
        background-color: transparent; 
        transform: scale(1);
        box-shadow: none;
    }
}

/* Pulse animation for price changes */
.price-pulse {
    animation: pricePulse 1s ease-in-out;
}

@keyframes pricePulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

/* Light theme overrides for asset cards */
.light-theme .bg-gray-700 {
    background-color: var(--bg-tertiary) !important;
}

.light-theme .bg-gray-800 {
    background-color: var(--bg-secondary) !important;
}

.light-theme .text-white {
    color: var(--text-primary) !important;
}

.light-theme .text-gray-400 {
    color: var(--text-muted) !important;
}

.light-theme .text-gray-300 {
    color: var(--text-secondary) !important;
}

.light-theme .border-gray-600 {
    border-color: var(--border-color) !important;
}

.light-theme .border-gray-700 {
    border-color: var(--border-color) !important;
}

.light-theme .hover\\:border-gray-500:hover {
    border-color: var(--border-hover) !important;
}

.light-theme .bg-gray-600 {
    background-color: var(--border-hover) !important;
}

.light-theme .hover\\:bg-gray-500:hover {
    background-color: var(--bg-tertiary) !important;
}

.light-theme .hover\\:bg-gray-600:hover {
    background-color: var(--border-hover) !important;
}

.light-theme .hover\\:bg-gray-700:hover {
    background-color: var(--border-hover) !important;
}

/* Asset card specific overrides */
.light-theme .asset-price {
    color: var(--text-primary) !important;
}

.light-theme .asset-change {
    color: inherit !important;
}

/* Modal overrides */
.light-theme .bg-gray-800.border.border-gray-700 {
    background-color: var(--bg-secondary) !important;
    border-color: var(--border-color) !important;
}

.light-theme .bg-gray-700.rounded-lg {
    background-color: var(--bg-tertiary) !important;
}

.light-theme input.bg-gray-700 {
    background-color: var(--bg-tertiary) !important;
    color: var(--text-primary) !important;
}

.light-theme input.border-gray-600 {
    border-color: var(--border-color) !important;
}

.light-theme .text-green-400 {
    color: #16a34a !important; /* Keep green for positive changes */
}

.light-theme .text-red-400 {
    color: #dc2626 !important; /* Keep red for negative changes */
}

/* Search bar overrides */
.light-theme .bg-gray-800.rounded-lg {
    background-color: var(--bg-secondary) !important;
}

.light-theme input.bg-gray-700 {
    background-color: var(--bg-tertiary) !important;
    color: var(--text-primary) !important;
}

.light-theme input.placeholder-gray-400::placeholder {
    color: var(--text-muted) !important;
}

/* Tab overrides */
.light-theme .tab-button {
    color: var(--text-muted) !important;
}

.light-theme .tab-button.border-blue-500 {
    border-color: #3b82f6 !important;
}

.light-theme .tab-button.text-blue-400 {
    color: #3b82f6 !important;
}

.light-theme .tab-button:hover {
    color: var(--text-secondary) !important;
}

/* Additional modal and form overrides */
.light-theme .bg-gray-800.rounded-lg.shadow-xl {
    background-color: var(--bg-secondary) !important;
}

.light-theme .border-b.border-gray-700 {
    border-color: var(--border-color) !important;
}

.light-theme .text-gray-400 {
    color: var(--text-muted) !important;
}

.light-theme .text-gray-300 {
    color: var(--text-secondary) !important;
}

.light-theme .bg-gray-700.rounded-lg.p-4 {
    background-color: var(--bg-tertiary) !important;
}

.light-theme .text-2xl.font-bold.text-white {
    color: var(--text-primary) !important;
}

.light-theme .text-xl.font-bold.text-white {
    color: var(--text-primary) !important;
}

.light-theme .text-lg.font-medium.text-white {
    color: var(--text-primary) !important;
}

.light-theme .text-sm.font-medium.text-gray-300 {
    color: var(--text-secondary) !important;
}

.light-theme .text-xs.text-green-400 {
    color: #16a34a !important;
}

.light-theme .bg-blue-600 {
    background-color: #2563eb !important;
}

.light-theme .hover\\:bg-blue-700:hover {
    background-color: #1d4ed8 !important;
}

.light-theme .hover\\:bg-blue-600:hover {
    background-color: #2563eb !important;
}

/* Loading spinner overrides */
.light-theme .text-white.shadow {
    color: var(--text-primary) !important;
}

.light-theme .animate-spin.text-white {
    color: var(--text-primary) !important;
}

/* Empty state overrides */
.light-theme .text-gray-400.text-lg {
    color: var(--text-muted) !important;
}

.light-theme .text-gray-600 {
    color: var(--text-muted) !important;
}

.light-theme .text-gray-500 {
    color: var(--text-muted) !important;
}

/* Asset icon badge overrides for light theme */
.light-theme .w-10.h-10.rounded-full.flex.items-center.justify-center .text-white {
    color: white !important; /* Keep white text on colored backgrounds */
}

/* Ensure asset icon backgrounds remain colored in light theme */
.light-theme .bg-yellow-500,
.light-theme .bg-blue-500,
.light-theme .bg-blue-600,
.light-theme .bg-black,
.light-theme .bg-green-500,
.light-theme .bg-blue-400,
.light-theme .bg-yellow-400,
.light-theme .bg-purple-500,
.light-theme .bg-pink-500,
.light-theme .bg-red-500,
.light-theme .bg-purple-600,
.light-theme .bg-blue-700,
.light-theme .bg-pink-400,
.light-theme .bg-gray-500,
.light-theme .bg-orange-500,
.light-theme .bg-purple-400,
.light-theme .bg-indigo-500,
.light-theme .bg-green-400,
.light-theme .bg-blue-800,
.light-theme .bg-red-600,
.light-theme .bg-green-600,
.light-theme .bg-orange-600,
.light-theme .bg-gray-600,
.light-theme .bg-purple-700,
.light-theme .bg-gray-400,
.light-theme .bg-green-700,
.light-theme .bg-teal-500,
.light-theme .bg-pink-600,
.light-theme .bg-purple-800,
.light-theme .bg-blue-900,
.light-theme .bg-pink-300,
.light-theme .bg-orange-400,
.light-theme .bg-blue-300,
.light-theme .bg-yellow-600,
.light-theme .bg-pink-700,
.light-theme .bg-cyan-500,
.light-theme .bg-indigo-600,
.light-theme .bg-blue-200,
.light-theme .bg-red-400,
.light-theme .bg-gray-300,
.light-theme .bg-gray-700,
.light-theme .bg-purple-300,
.light-theme .bg-blue-100,
.light-theme .bg-orange-300,
.light-theme .bg-yellow-300,
.light-theme .bg-purple-200,
.light-theme .bg-red-300,
.light-theme .bg-green-300,
.light-theme .bg-orange-200,
.light-theme .bg-yellow-700,
.light-theme .bg-purple-100,
.light-theme .bg-green-200,
.light-theme .bg-orange-100,
.light-theme .bg-gray-200,
.light-theme .bg-purple-900,
.light-theme .bg-green-800,
.light-theme .bg-red-200,
.light-theme .bg-brown-600 {
    /* Keep original colors for asset icons - don't override */
    background-color: inherit !important;
}

/* Specific text color for asset icon badges */
.light-theme .w-10.h-10.rounded-full span {
    color: white !important;
    font-weight: bold !important;
}

/* Override any text color inheritance for asset icon badges */
.light-theme .w-10.h-10.rounded-full .text-sm.font-bold.text-white {
    color: white !important;
}

/* Ensure asset icon containers maintain their colored backgrounds */
.light-theme .w-10.h-10.rounded-full.flex.items-center.justify-center {
    /* Don't override background colors - let them inherit from their specific color classes */
}

/* Fix for any text color inheritance issues - override global light theme color inheritance */
.light-theme .w-10.h-10.rounded-full * {
    color: white !important;
}

/* More specific override to counter global light theme color inheritance */
.light-theme .w-10.h-10.rounded-full.flex.items-center.justify-center * {
    color: white !important;
}

/* Override the global light theme color inheritance for asset icon badges */
.light-theme .w-10.h-10.rounded-full span.text-sm.font-bold.text-white {
    color: white !important;
}

/* Ensure asset icon text is always white regardless of theme */
.light-theme .w-10.h-10.rounded-full .text-white {
    color: white !important;
}
</style>

<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Buy Assets</h1>
            <p class="text-gray-400 mt-1">Browse and purchase crypto and stock assets</p>
        </div>
        <a href="{{ route('user.holding.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center sm:justify-start space-x-2 w-full sm:w-auto mt-4 sm:mt-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Back to Portfolio</span>
        </a>
    </div>

    <!-- Search Bar -->
    <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" id="assetSearch" placeholder="Search assets by name or symbol..." 
                   class="block w-full pl-10 pr-3 py-2 border border-gray-600 rounded-md leading-5 bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:placeholder-gray-500 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-gray-800 rounded-lg border border-gray-700">
        <div class="border-b border-gray-700">
            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                <button id="cryptoTab" class="tab-button border-b-2 border-blue-500 py-4 px-1 text-sm font-medium text-blue-400">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Crypto</span>
                    </div>
                </button>
                <button id="stockTab" class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-400 hover:text-gray-300">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Stocks</span>
                    </div>
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Crypto Assets -->
            <div id="cryptoContent" class="tab-content">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4" id="cryptoAssetsGrid">
                    <!-- Crypto assets will be loaded here -->
                </div>
                <div id="cryptoLoading" class="text-center py-8">
                    <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-white shadow rounded-md">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Loading crypto assets...
                    </div>
                </div>
            </div>

            <!-- Stock Assets -->
            <div id="stockContent" class="tab-content hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4" id="stockAssetsGrid">
                    <!-- Stock assets will be loaded here -->
                </div>
                <div id="stockLoading" class="text-center py-8">
                    <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-white shadow rounded-md">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Loading stock assets...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Buy Asset Modal -->
<div id="buyAssetModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-gray-800 rounded-lg shadow-xl max-w-4xl w-full border border-gray-700 max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-700">
                <div>
                    <h3 class="text-xl font-semibold text-white" id="modalAssetName">Buy Asset</h3>
                    <p class="text-gray-400 text-sm mt-1" id="modalAssetSymbol">Asset Symbol</p>
                </div>
                <button id="closeBuyModal" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto flex-1">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column - Chart -->
                    <div>
                        <h4 class="text-lg font-medium text-white mb-4">Price Chart</h4>
                        <div class="bg-gray-700 rounded-lg p-4 h-64">
                            <div id="tradingViewChart" class="w-full h-full"></div>
                        </div>
                    </div>

                    <!-- Right Column - Buy Form -->
                    <div>
                        <h4 class="text-lg font-medium text-white mb-4">Purchase Details</h4>
                        <form id="buyAssetForm" class="space-y-4">
                            @csrf
                            <input type="hidden" id="selectedAssetId">
                            
                            <!-- Current Price Display -->
                            <div class="bg-gray-700 rounded-lg p-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-300">Current Price:</span>
                                    <span class="text-xl font-bold text-white" id="currentPrice">$0.00</span>
                                </div>
                                <div class="flex justify-between items-center mt-2">
                                    <span class="text-gray-300">24h Change:</span>
                                    <span class="text-sm" id="priceChange">0.00%</span>
                                </div>
                            </div>

                            <!-- Quantity Input -->
                            <div>
                                <label for="buyQuantity" class="block text-sm font-medium text-gray-300 mb-2">Quantity</label>
                                <input type="number" id="buyQuantity" step="0.00000001" min="0.00000001"
                                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="0.00000000">
                                <div class="mt-1 text-xs text-green-400">
                                    Available Balance: $<span id="userHoldingBalance">{{ number_format(auth()->user()->balance, 2) }}</span>
                                </div>
                            </div>

                            <!-- Price per Unit -->
                            <div>
                                <label for="buyPrice" class="block text-sm font-medium text-gray-300 mb-2">Price per Unit</label>
                                <input type="number" id="buyPrice" step="0.00000001" min="0.00000001"
                                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="0.00">
                            </div>

                            <!-- Total Amount -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Total Amount</label>
                                <div class="bg-gray-700 rounded-lg p-4">
                                    <div class="text-2xl font-bold text-white" id="totalAmount">$0.00</div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-3 pt-4">
                                <button type="button" id="cancelBuyBtn" 
                                        class="flex-1 px-4 py-2 border border-gray-600 rounded-lg text-gray-300 hover:bg-gray-700 transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" id="submitBuyBtn"
                                        class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                                    Buy Asset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Global variables
let currentTab = 'crypto';
let cryptoAssets = [];
let stockAssets = [];
let selectedAsset = null;
let searchTimeout = null;

// Function to format asset price with appropriate decimals
function formatAssetPrice(price) {
    const numPrice = parseFloat(price);
    
    // For prices >= $1, show 2 decimal places
    if (numPrice >= 1) {
        return numPrice.toFixed(2);
    }
    // For prices >= $0.01, show 4 decimal places
    else if (numPrice >= 0.01) {
        return numPrice.toFixed(4);
    }
    // For prices >= $0.0001, show 6 decimal places
    else if (numPrice >= 0.0001) {
        return numPrice.toFixed(6);
    }
    // For very small prices, show 8 decimal places
    else {
        return numPrice.toFixed(8);
    }
}

// Tab functionality
document.getElementById('cryptoTab').addEventListener('click', () => switchTab('crypto'));
document.getElementById('stockTab').addEventListener('click', () => switchTab('stock'));

function switchTab(tab) {
    currentTab = tab;
    
    // Update tab buttons
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('border-blue-500', 'text-blue-400');
        btn.classList.add('border-transparent', 'text-gray-400');
    });
    
    if (tab === 'crypto') {
        document.getElementById('cryptoTab').classList.add('border-blue-500', 'text-blue-400');
        document.getElementById('cryptoTab').classList.remove('border-transparent', 'text-gray-400');
    } else {
        document.getElementById('stockTab').classList.add('border-blue-500', 'text-blue-400');
        document.getElementById('stockTab').classList.remove('border-transparent', 'text-gray-400');
    }
    
    // Update content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    if (tab === 'crypto') {
        document.getElementById('cryptoContent').classList.remove('hidden');
        if (cryptoAssets.length === 0) {
            loadCryptoAssets();
        }
    } else {
        document.getElementById('stockContent').classList.remove('hidden');
        if (stockAssets.length === 0) {
            loadStockAssets();
        }
    }
    
    // Apply search filter
    applySearchFilter();
}

// Load assets
function loadCryptoAssets() {
    fetch('/user/holding/assets?type=crypto', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP ' + response.status + ': ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            cryptoAssets = data.assets || [];
            renderAssets('crypto', cryptoAssets);
            document.getElementById('cryptoLoading').classList.add('hidden');
        })
        .catch(error => {
            console.error('Error loading crypto assets:', error);
            document.getElementById('cryptoLoading').innerHTML = '<p class="text-red-400">Error loading assets: ' + error.message + '</p>';
        });
}

function loadStockAssets() {
    fetch('/user/holding/assets?type=stock', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP ' + response.status + ': ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            stockAssets = data.assets || [];
            renderAssets('stock', stockAssets);
            document.getElementById('stockLoading').classList.add('hidden');
        })
        .catch(error => {
            console.error('Error loading stock assets:', error);
            document.getElementById('stockLoading').innerHTML = '<p class="text-red-400">Error loading assets: ' + error.message + '</p>';
        });
}

function renderAssets(type, assets) {
    const grid = document.getElementById(type + 'AssetsGrid');
    grid.innerHTML = '';
    
    if (assets.length === 0) {
        grid.innerHTML = `
            <div class="col-span-full text-center py-8 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-lg font-medium">No ${type} assets found</p>
            </div>
        `;
        return;
    }
    
    assets.forEach((asset, index) => {
        const card = createAssetCard(asset, type);
        grid.appendChild(card);
    });
}

function createAssetCard(asset, type) {
    const card = document.createElement('div');
    card.className = 'bg-gray-700 rounded-lg p-4 border border-gray-600 hover:border-gray-500 transition-colors cursor-pointer';
    card.setAttribute('data-asset-id', asset.id);
    
    const priceChange = asset.price_change_percentage_24h || 0;
    const priceChangeClass = priceChange >= 0 ? 'text-green-400' : 'text-red-400';
    const priceChangeIcon = priceChange >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6';
    
    // Get asset icon with color
    const assetIcon = getAssetIcon(asset.symbol, asset.name);
    
    // Format price with appropriate decimals
    const formattedPrice = formatAssetPrice(asset.current_price);
    
    card.innerHTML = `
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center ${assetIcon.bgClass}">
                    <span class="text-sm font-bold text-white">${assetIcon.initial}</span>
                </div>
                <div>
                    <h3 class="text-white font-medium">${asset.symbol}</h3>
                    <p class="text-gray-400 text-sm">${asset.name}</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-white font-medium asset-price">$${formattedPrice}</div>
                <div class="text-sm ${priceChangeClass} flex items-center asset-change">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${priceChangeIcon}"></path>
                    </svg>
                    ${Math.abs(priceChange).toFixed(2)}%
                </div>
            </div>
        </div>
        <div class="flex space-x-2">
            <button class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-2 py-1.5 rounded text-xs font-medium transition-colors" onclick="openBuyModal(${asset.id}, '${asset.symbol}', '${asset.name}', ${asset.current_price}, ${priceChange}, '${asset.type}')">
                Buy
            </button>
            <button class="bg-gray-600 hover:bg-gray-500 text-white px-2 py-1.5 rounded text-xs font-medium transition-colors" onclick="viewAssetChart('${asset.symbol}', '${asset.name}', '${asset.type}')">
                Chart
            </button>
        </div>
    `;
    
    return card;
}

function getAssetIcon(symbol, name) {
    // Define colors for popular cryptocurrencies
    const cryptoColors = {
        'BTC': { bg: 'bg-yellow-500', initial: 'B' },
        'ETH': { bg: 'bg-blue-500', initial: 'E' },
        'ADA': { bg: 'bg-blue-600', initial: 'A' },
        'XRP': { bg: 'bg-black', initial: 'X' },
        'USDT': { bg: 'bg-green-500', initial: 'T' },
        'USDC': { bg: 'bg-blue-400', initial: 'U' },
        'BNB': { bg: 'bg-yellow-400', initial: 'B' },
        'SOL': { bg: 'bg-purple-500', initial: 'S' },
        'DOT': { bg: 'bg-pink-500', initial: 'D' },
        'AVAX': { bg: 'bg-red-500', initial: 'A' },
        'MATIC': { bg: 'bg-purple-600', initial: 'M' },
        'LINK': { bg: 'bg-blue-700', initial: 'L' },
        'UNI': { bg: 'bg-pink-400', initial: 'U' },
        'LTC': { bg: 'bg-gray-500', initial: 'L' },
        'BCH': { bg: 'bg-orange-500', initial: 'B' },
        'XLM': { bg: 'bg-purple-400', initial: 'X' },
        'ATOM': { bg: 'bg-indigo-500', initial: 'A' },
        'FTT': { bg: 'bg-green-400', initial: 'F' },
        'FIL': { bg: 'bg-blue-800', initial: 'F' },
        'TRX': { bg: 'bg-red-600', initial: 'T' },
        'ETC': { bg: 'bg-green-600', initial: 'E' },
        'XMR': { bg: 'bg-orange-600', initial: 'X' },
        'EOS': { bg: 'bg-gray-600', initial: 'E' },
        'AAVE': { bg: 'bg-purple-700', initial: 'A' },
        'ALGO': { bg: 'bg-gray-400', initial: 'A' },
        'NEO': { bg: 'bg-green-700', initial: 'N' },
        'VET': { bg: 'bg-teal-500', initial: 'V' },
        'ICP': { bg: 'bg-pink-600', initial: 'I' },
        'THETA': { bg: 'bg-purple-800', initial: 'T' },
        'XTZ': { bg: 'bg-blue-900', initial: 'X' },
        'CAKE': { bg: 'bg-pink-300', initial: 'C' },
        'MKR': { bg: 'bg-orange-400', initial: 'M' },
        'COMP': { bg: 'bg-blue-300', initial: 'C' },
        'YFI': { bg: 'bg-yellow-600', initial: 'Y' },
        'SUSHI': { bg: 'bg-pink-700', initial: 'S' },
        'SNX': { bg: 'bg-cyan-500', initial: 'S' },
        'BAL': { bg: 'bg-indigo-600', initial: 'B' },
        'CRV': { bg: 'bg-blue-200', initial: 'C' },
        '1INCH': { bg: 'bg-red-400', initial: '1' },
        'ZRX': { bg: 'bg-gray-300', initial: 'Z' },
        'REN': { bg: 'bg-gray-700', initial: 'R' },
        'BAND': { bg: 'bg-purple-300', initial: 'B' },
        'STORJ': { bg: 'bg-blue-100', initial: 'S' },
        'MANA': { bg: 'bg-orange-300', initial: 'M' },
        'SAND': { bg: 'bg-yellow-300', initial: 'S' },
        'ENJ': { bg: 'bg-purple-200', initial: 'E' },
        'CHZ': { bg: 'bg-red-300', initial: 'C' },
        'HOT': { bg: 'bg-green-300', initial: 'H' },
        'BAT': { bg: 'bg-orange-200', initial: 'B' },
        'ZEC': { bg: 'bg-yellow-700', initial: 'Z' },
        'DASH': { bg: 'bg-blue-600', initial: 'D' },
        'WAVES': { bg: 'bg-purple-100', initial: 'W' },
        'QTUM': { bg: 'bg-green-200', initial: 'Q' },
        'IOTA': { bg: 'bg-orange-100', initial: 'I' },
        'NANO': { bg: 'bg-gray-200', initial: 'N' },
        'ICX': { bg: 'bg-purple-900', initial: 'I' },
        'ONT': { bg: 'bg-green-800', initial: 'O' },
        'ZIL': { bg: 'bg-blue-500', initial: 'Z' },
        'VTHO': { bg: 'bg-red-200', initial: 'V' },
        'HBAR': { bg: 'bg-purple-600', initial: 'H' },
        'CRO': { bg: 'bg-blue-700', initial: 'C' },
        'XDC': { bg: 'bg-green-600', initial: 'X' },
        'ONE': { bg: 'bg-purple-500', initial: 'O' },
        'IOTX': { bg: 'bg-blue-800', initial: 'I' },
        'ANKR': { bg: 'bg-blue-600', initial: 'A' },
        'BTT': { bg: 'bg-blue-500', initial: 'B' },
        'WIN': { bg: 'bg-green-500', initial: 'W' },
        'CHR': { bg: 'bg-purple-400', initial: 'C' },
        'MASK': { bg: 'bg-blue-400', initial: 'M' },
        'AR': { bg: 'bg-red-500', initial: 'A' },
        'FLOW': { bg: 'bg-blue-600', initial: 'F' },
        'RUNE': { bg: 'bg-yellow-500', initial: 'R' },
        'KSM': { bg: 'bg-purple-500', initial: 'K' },
        'DYDX': { bg: 'bg-gray-500', initial: 'D' },
        'IMX': { bg: 'bg-blue-500', initial: 'I' },
        'GALA': { bg: 'bg-purple-400', initial: 'G' },
        'ROSE': { bg: 'bg-pink-400', initial: 'R' },
        'OP': { bg: 'bg-red-400', initial: 'O' },
        'ARB': { bg: 'bg-blue-500', initial: 'A' },
        'INJ': { bg: 'bg-blue-600', initial: 'I' },
        'TIA': { bg: 'bg-purple-500', initial: 'T' },
        'SEI': { bg: 'bg-blue-500', initial: 'S' },
        'SUI': { bg: 'bg-blue-600', initial: 'S' },
        'APT': { bg: 'bg-blue-500', initial: 'A' },
        'NEAR': { bg: 'bg-black', initial: 'N' },
        'FTM': { bg: 'bg-blue-500', initial: 'F' },
        'HEDERA': { bg: 'bg-purple-600', initial: 'H' }
    };
    
    // Define colors for popular stocks
    const stockColors = {
        'AAPL': { bg: 'bg-gray-800', initial: 'A' },
        'MSFT': { bg: 'bg-blue-600', initial: 'M' },
        'GOOGL': { bg: 'bg-blue-500', initial: 'G' },
        'AMZN': { bg: 'bg-orange-500', initial: 'A' },
        'TSLA': { bg: 'bg-red-500', initial: 'T' },
        'META': { bg: 'bg-blue-700', initial: 'M' },
        'NVDA': { bg: 'bg-green-600', initial: 'N' },
        'NFLX': { bg: 'bg-red-600', initial: 'N' },
        'ADBE': { bg: 'bg-red-400', initial: 'A' },
        'CRM': { bg: 'bg-blue-400', initial: 'C' },
        'PYPL': { bg: 'bg-blue-500', initial: 'P' },
        'INTC': { bg: 'bg-blue-600', initial: 'I' },
        'AMD': { bg: 'bg-red-500', initial: 'A' },
        'ORCL': { bg: 'bg-red-600', initial: 'O' },
        'CSCO': { bg: 'bg-blue-500', initial: 'C' },
        'IBM': { bg: 'bg-blue-600', initial: 'I' },
        'QCOM': { bg: 'bg-green-500', initial: 'Q' },
        'TXN': { bg: 'bg-red-500', initial: 'T' },
        'AVGO': { bg: 'bg-blue-500', initial: 'A' },
        'ACN': { bg: 'bg-blue-600', initial: 'A' },
        'WMT': { bg: 'bg-blue-500', initial: 'W' },
        'JPM': { bg: 'bg-blue-600', initial: 'J' },
        'V': { bg: 'bg-blue-500', initial: 'V' },
        'JNJ': { bg: 'bg-red-500', initial: 'J' },
        'PG': { bg: 'bg-blue-500', initial: 'P' },
        'UNH': { bg: 'bg-blue-600', initial: 'U' },
        'HD': { bg: 'bg-orange-500', initial: 'H' },
        'MA': { bg: 'bg-orange-600', initial: 'M' },
        'DIS': { bg: 'bg-blue-500', initial: 'D' },
        'BAC': { bg: 'bg-red-500', initial: 'B' },
        'WFC': { bg: 'bg-red-600', initial: 'W' },
        'GS': { bg: 'bg-blue-700', initial: 'G' },
        'MS': { bg: 'bg-blue-600', initial: 'M' },
        'C': { bg: 'bg-blue-500', initial: 'C' },
        'BLK': { bg: 'bg-gray-700', initial: 'B' },
        'AXP': { bg: 'bg-green-500', initial: 'A' },
        'SPGI': { bg: 'bg-blue-600', initial: 'S' },
        'PFE': { bg: 'bg-blue-500', initial: 'P' },
        'ABBV': { bg: 'bg-purple-600', initial: 'A' },
        'MRK': { bg: 'bg-blue-500', initial: 'M' },
        'TMO': { bg: 'bg-red-500', initial: 'T' },
        'ABT': { bg: 'bg-blue-600', initial: 'A' },
        'DHR': { bg: 'bg-purple-500', initial: 'D' },
        'LLY': { bg: 'bg-blue-500', initial: 'L' },
        'BMY': { bg: 'bg-blue-600', initial: 'B' },
        'KO': { bg: 'bg-red-500', initial: 'K' },
        'PEP': { bg: 'bg-blue-500', initial: 'P' },
        'COST': { bg: 'bg-blue-600', initial: 'C' },
        'TGT': { bg: 'bg-red-500', initial: 'T' },
        'LOW': { bg: 'bg-blue-500', initial: 'L' },
        'SBUX': { bg: 'bg-green-600', initial: 'S' },
        'MCD': { bg: 'bg-yellow-500', initial: 'M' },
        'NKE': { bg: 'bg-black', initial: 'N' },
        'XOM': { bg: 'bg-red-600', initial: 'X' },
        'CVX': { bg: 'bg-blue-500', initial: 'C' },
        'BA': { bg: 'bg-blue-600', initial: 'B' },
        'CAT': { bg: 'bg-yellow-600', initial: 'C' },
        'GE': { bg: 'bg-blue-500', initial: 'G' },
        'MMM': { bg: 'bg-red-500', initial: 'M' },
        'HON': { bg: 'bg-blue-600', initial: 'H' },
        'CMCSA': { bg: 'bg-blue-500', initial: 'C' },
        'VZ': { bg: 'bg-red-500', initial: 'V' },
        'T': { bg: 'bg-blue-600', initial: 'T' },
        'TMUS': { bg: 'bg-pink-500', initial: 'T' },
        'CHTR': { bg: 'bg-blue-500', initial: 'C' },
        'PARA': { bg: 'bg-purple-500', initial: 'P' },
        'UPS': { bg: 'bg-brown-600', initial: 'U' },
        'FDX': { bg: 'bg-orange-500', initial: 'F' },
        'RTX': { bg: 'bg-blue-600', initial: 'R' },
        'LMT': { bg: 'bg-green-500', initial: 'L' },
        'DE': { bg: 'bg-green-600', initial: 'D' },
        'ISRG': { bg: 'bg-blue-500', initial: 'I' },
        'GILD': { bg: 'bg-blue-600', initial: 'G' },
        'REGN': { bg: 'bg-purple-500', initial: 'R' },
        'AMGN': { bg: 'bg-blue-500', initial: 'A' },
        'MDLZ': { bg: 'bg-purple-600', initial: 'M' },
        'KHC': { bg: 'bg-red-500', initial: 'K' },
        'PM': { bg: 'bg-blue-600', initial: 'P' },
        'MO': { bg: 'bg-red-600', initial: 'M' },
        'DUK': { bg: 'bg-blue-500', initial: 'D' },
        'SO': { bg: 'bg-blue-600', initial: 'S' },
        'NEE': { bg: 'bg-green-500', initial: 'N' }
    };
    
    // Check if we have a predefined color for this crypto
    if (cryptoColors[symbol]) {
        return {
            bgClass: cryptoColors[symbol].bg,
            initial: cryptoColors[symbol].initial
        };
    }
    
    // Check if we have a predefined color for this stock
    if (stockColors[symbol]) {
        return {
            bgClass: stockColors[symbol].bg,
            initial: stockColors[symbol].initial
        };
    }
    
    // For unknown assets, use a default color based on the first letter
    const defaultColors = [
        'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-red-500', 'bg-purple-500',
        'bg-pink-500', 'bg-indigo-500', 'bg-teal-500', 'bg-orange-500', 'bg-gray-500'
    ];
    
    const firstChar = symbol.charAt(0).toUpperCase();
    const colorIndex = firstChar.charCodeAt(0) % defaultColors.length;
    
    return {
        bgClass: defaultColors[colorIndex],
        initial: firstChar
    };
}

// Search functionality
document.getElementById('assetSearch').addEventListener('input', function() {
    const query = this.value.toLowerCase();
    
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }
    
    searchTimeout = setTimeout(() => {
        applySearchFilter();
    }, 300);
});

function applySearchFilter() {
    const query = document.getElementById('assetSearch').value.toLowerCase();
    const assets = currentTab === 'crypto' ? cryptoAssets : stockAssets;
    
    const filteredAssets = assets.filter(asset => 
        asset.name.toLowerCase().includes(query) || 
        asset.symbol.toLowerCase().includes(query)
    );
    
    renderAssets(currentTab, filteredAssets);
}

// Modal functions
function openBuyModal(assetId, symbol, name, currentPrice, priceChange, assetType) {
    selectedAsset = { id: assetId, symbol, name, current_price: currentPrice, type: assetType };
    
    document.getElementById('selectedAssetId').value = assetId;
    document.getElementById('modalAssetName').textContent = name;
    document.getElementById('modalAssetSymbol').textContent = symbol;
    const formattedPrice = formatAssetPrice(currentPrice);
    document.getElementById('currentPrice').textContent = `$${formattedPrice}`;
    document.getElementById('priceChange').textContent = `${priceChange >= 0 ? '+' : ''}${priceChange.toFixed(2)}%`;
    document.getElementById('priceChange').className = priceChange >= 0 ? 'text-sm text-green-400' : 'text-sm text-red-400';
    document.getElementById('buyPrice').value = currentPrice;
    
    // Update user balance display
    updateUserBalanceDisplay();
    
    document.getElementById('buyAssetModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Initialize TradingView chart
    initTradingViewChart(symbol, assetType);
}

function updateUserBalanceDisplay() {
    // Fetch current user balance
    fetch('/user/holding/balance', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('userHoldingBalance').textContent = parseFloat(data.balance).toFixed(2);
        }
    })
    .catch(error => {
        console.error('Error fetching balance:', error);
    });
}

function initTradingViewChart(symbol, assetType) {
    // Remove existing chart
    const chartContainer = document.getElementById('tradingViewChart');
    chartContainer.innerHTML = '';
    
    // If it's a stock, use NASDAQ
    if (assetType === 'stock') {
        const tradingViewSymbol = `NASDAQ:${symbol}`;
        
        new TradingView.widget({
            "width": "100%",
            "height": "100%",
            "symbol": tradingViewSymbol,
            "interval": "D",
            "timezone": "Etc/UTC",
            "theme": "dark",
            "style": "1",
            "locale": "en",
            "toolbar_bg": "#f1f3f6",
            "enable_publishing": false,
            "hide_side_toolbar": false,
            "allow_symbol_change": true,
            "container_id": "tradingViewChart",
            "studies": [
                "RSI@tv-basicstudies",
                "MACD@tv-basicstudies",
                "Volume@tv-basicstudies"
            ]
        });
        return;
    }
    
    // For crypto assets, use the existing logic
    // Determine the correct symbol format for TradingView with multiple exchange fallbacks
    let tradingViewSymbol = symbol;
    let exchange = 'BINANCE';
    
    // Define exchange priorities: Binance first, then Coinbase, then others
    const exchangeFallbacks = [
        'BINANCE',
        'COINBASE',
        'KUCOIN',
        'GATEIO',
        'HUOBI'
    ];
    
    // Handle special cases for popular cryptos with exchange preferences
    const symbolMappings = {
        // Binance preferred symbols (most comprehensive)
        'BTC': 'BINANCE:BTCUSDT',
        'ETH': 'BINANCE:ETHUSDT',
        'ADA': 'BINANCE:ADAUSDT',
        'XRP': 'BINANCE:XRPUSDT',
        'SOL': 'BINANCE:SOLUSDT',
        'DOT': 'BINANCE:DOTUSDT',
        'AVAX': 'BINANCE:AVAXUSDT',
        'MATIC': 'BINANCE:MATICUSDT',
        'LINK': 'BINANCE:LINKUSDT',
        'UNI': 'BINANCE:UNIUSDT',
        'LTC': 'BINANCE:LTCUSDT',
        'BCH': 'BINANCE:BCHUSDT',
        'XLM': 'BINANCE:XLMUSDT',
        'ATOM': 'BINANCE:ATOMUSDT',
        'FTT': 'BINANCE:FTTUSDT',
        'FIL': 'BINANCE:FILUSDT',
        'TRX': 'BINANCE:TRXUSDT',
        'ETC': 'BINANCE:ETCUSDT',
        'XMR': 'BINANCE:XMRUSDT',
        'EOS': 'BINANCE:EOSUSDT',
        'AAVE': 'BINANCE:AAVEUSDT',
        'ALGO': 'BINANCE:ALGOUSDT',
        'NEO': 'BINANCE:NEOUSDT',
        'VET': 'BINANCE:VETUSDT',
        'ICP': 'BINANCE:ICPUSDT',
        'THETA': 'BINANCE:THETAUSDT',
        'XTZ': 'BINANCE:XTZUSDT',
        'CAKE': 'BINANCE:CAKEUSDT',
        'MKR': 'BINANCE:MKRUSDT',
        'COMP': 'BINANCE:COMPUSDT',
        'YFI': 'BINANCE:YFIUSDT',
        'SUSHI': 'BINANCE:SUSHIUSDT',
        'SNX': 'BINANCE:SNXUSDT',
        'BAL': 'BINANCE:BALUSDT',
        'CRV': 'BINANCE:CRVUSDT',
        '1INCH': 'BINANCE:1INCHUSDT',
        'ZRX': 'BINANCE:ZRXUSDT',
        'REN': 'BINANCE:RENUSDT',
        'BAND': 'BINANCE:BANDUSDT',
        'STORJ': 'BINANCE:STORJUSDT',
        'MANA': 'BINANCE:MANAUSDT',
        'SAND': 'BINANCE:SANDUSDT',
        'ENJ': 'BINANCE:ENJUSDT',
        'CHZ': 'BINANCE:CHZUSDT',
        'HOT': 'BINANCE:HOTUSDT',
        'BAT': 'BINANCE:BATUSDT',
        'ZEC': 'BINANCE:ZECUSDT',
        'DASH': 'BINANCE:DASHUSDT',
        'WAVES': 'BINANCE:WAVESUSDT',
        'QTUM': 'BINANCE:QTUMUSDT',
        'IOTA': 'BINANCE:IOTAUSDT',
        'NANO': 'BINANCE:NANOUSDT',
        'ICX': 'BINANCE:ICXUSDT',
        'ONT': 'BINANCE:ONTUSDT',
        'ZIL': 'BINANCE:ZILUSDT',
        'VTHO': 'BINANCE:VTHOUSDT',
        'HBAR': 'BINANCE:HBARUSDT',
        'CRO': 'BINANCE:CROUSDT',
        'XDC': 'BINANCE:XDCUSDT',
        'ONE': 'BINANCE:ONEUSDT',
        'IOTX': 'BINANCE:IOTXUSDT',
        'ANKR': 'BINANCE:ANKRUSDT',
        'BTT': 'BINANCE:BTTUSDT',
        'WIN': 'BINANCE:WINUSDT',
        'CHR': 'BINANCE:CHRUSDT',
        'MASK': 'BINANCE:MASKUSDT',
        'AR': 'BINANCE:ARUSDT',
        'FLOW': 'BINANCE:FLOWUSDT',
        'RUNE': 'BINANCE:RUNEUSDT',
        'KSM': 'BINANCE:KSMUSDT',
        'DYDX': 'BINANCE:DYDXUSDT',
        'IMX': 'BINANCE:IMXUSDT',
        'GALA': 'BINANCE:GALAUSDT',
        'ROSE': 'BINANCE:ROSEUSDT',
        'OP': 'BINANCE:OPUSDT',
        'ARB': 'BINANCE:ARBUSDT',
        'INJ': 'BINANCE:INJUSDT',
        'TIA': 'BINANCE:TIAUSDT',
        'SEI': 'BINANCE:SEIUSDT',
        'SUI': 'BINANCE:SUIUSDT',
        'APT': 'BINANCE:APTUSDT',
        'NEAR': 'BINANCE:NEARUSDT',
        'FTM': 'BINANCE:FTMUSDT',
        'HEDERA': 'BINANCE:HBARUSDT',
        
        // Special cases for newer or less common tokens
        'VAULTA': 'KUCOIN:VAULTAUSDT',
        'PEPE': 'BINANCE:PEPEUSDT',
        'SHIB': 'BINANCE:SHIBUSDT',
        'DOGE': 'BINANCE:DOGEUSDT',
        'BONK': 'BINANCE:BONKUSDT',
        'WIF': 'BINANCE:WIFUSDT',
        'FLOKI': 'BINANCE:FLOKIUSDT',
        'BABYDOGE': 'BINANCE:BABYDOGEUSDT',
        'SAFEMOON': 'BINANCE:SAFEMOONUSDT',
        'ELON': 'BINANCE:ELONUSDT',
        'MOON': 'BINANCE:MOONUSDT',
        'CAT': 'BINANCE:CATUSDT',
        'DOG': 'BINANCE:DOGUSDT',
        'WOJAK': 'BINANCE:WOJAKUSDT',
        'BOOK': 'BINANCE:BOOKUSDT',
        'TURBO': 'BINANCE:TURBOUSDT',
        'MYRO': 'BINANCE:MYROUSDT',
        'POPCAT': 'BINANCE:POPCATUSDT',
        'BOME': 'BINANCE:BOMEUSDT',
        'SLERF': 'BINANCE:SLERFUSDT',
        
        // Stablecoins - use USD pairs instead of USDT pairs
        'USDT': 'BINANCE:USDTUSD',
        'USDC': 'BINANCE:USDCUSD',
        'BUSD': 'BINANCE:BUSDUSD',
        'DAI': 'BINANCE:DAIUSD',
        'TUSD': 'BINANCE:TUSDUSD'
    };
    
    // Use mapped symbol if available
    if (symbolMappings[symbol]) {
        tradingViewSymbol = symbolMappings[symbol];
        exchange = tradingViewSymbol.split(':')[0];
    } else {
        // Handle special cases for stablecoins and other tokens
        if (symbol === 'USDT' || symbol === 'USDC' || symbol === 'BUSD' || symbol === 'DAI' || symbol === 'TUSD') {
            // For stablecoins, use USD pairs instead of USDT pairs
            tradingViewSymbol = `BINANCE:${symbol}USD`;
            exchange = 'BINANCE';
        } else {
            // Try to find the symbol on different exchanges
            for (const exchangeName of exchangeFallbacks) {
                let testSymbol = '';
                
                if (exchangeName === 'BINANCE') {
                    testSymbol = `BINANCE:${symbol}USDT`;
                } else if (exchangeName === 'COINBASE') {
                    testSymbol = `COINBASE:${symbol}USD`;
                } else if (exchangeName === 'KUCOIN') {
                    testSymbol = `KUCOIN:${symbol}USDT`;
                } else if (exchangeName === 'GATEIO') {
                    testSymbol = `GATEIO:${symbol}USDT`;
                } else if (exchangeName === 'HUOBI') {
                    testSymbol = `HUOBI:${symbol}USDT`;
                }
                
                // Use Binance as default since it has the most comprehensive list
                tradingViewSymbol = testSymbol;
                exchange = exchangeName;
                break;
            }
        }
    }
    
    // Add error handling for invalid symbols
    const handleChartError = () => {
        const chartContainer = document.getElementById('tradingViewChart');
        chartContainer.innerHTML = `
            <div class="flex items-center justify-center h-full">
                <div class="text-center">
                    <div class="text-gray-400 text-lg mb-2">Chart Not Available</div>
                    <div class="text-gray-500 text-sm">${symbol} chart is not available on ${exchange}</div>
                    <div class="text-gray-500 text-xs mt-2">Try viewing on TradingView directly</div>
                    <a href="https://www.tradingview.com/symbols/${tradingViewSymbol}/" target="_blank" 
                       class="inline-block mt-3 px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 transition-colors">
                        View on TradingView
                    </a>
                </div>
            </div>
        `;
    };
    
    // Create the TradingView mini symbol overview widget
    const widgetContainer = document.createElement('div');
    widgetContainer.className = 'tradingview-widget-container';
    widgetContainer.style.height = '100%';
    
    const widgetDiv = document.createElement('div');
    widgetDiv.className = 'tradingview-widget-container__widget';
    widgetDiv.style.height = '100%';
    
    const copyrightDiv = document.createElement('div');
    copyrightDiv.className = 'tradingview-widget-copyright';
    copyrightDiv.innerHTML = `<a href="https://www.tradingview.com/symbols/${tradingViewSymbol}/?exchange=${exchange}" rel="noopener nofollow" target="_blank"><span class="blue-text">${symbol}</span></a>`;
    
    widgetContainer.appendChild(widgetDiv);
    widgetContainer.appendChild(copyrightDiv);
    chartContainer.appendChild(widgetContainer);
    
    // Create and load the TradingView script
    const script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = 'https://s3.tradingview.com/external-embedding/embed-widget-mini-symbol-overview.js';
    script.async = true;
    script.innerHTML = JSON.stringify({
        "symbol": tradingViewSymbol,
        "chartOnly": false,
        "dateRange": "12M",
        "noTimeScale": false,
        "colorTheme": "dark",
        "isTransparent": false,
        "locale": "en",
        "width": "100%",
        "autosize": true,
        "height": "100%"
    });
    
    widgetDiv.appendChild(script);
    
    // Add timeout to detect chart loading failures
    setTimeout(() => {
        const widget = chartContainer.querySelector('.tradingview-widget-container__widget');
        if (widget && widget.children.length === 0) {
            handleChartError();
        }
    }, 5000); // 5 second timeout
}

function viewAssetChart(symbol, name, type) {
    // Redirect to the dedicated chart page
    window.location.href = `/user/holding/chart/${symbol}`;
}

// Close modal functions
function closeBuyModal() {
    document.getElementById('buyAssetModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    selectedAsset = null;
    resetBuyForm();
}

function resetBuyForm() {
    document.getElementById('buyAssetForm').reset();
    document.getElementById('totalAmount').textContent = '$0.00';
}

// Calculate total amount
function calculateTotal() {
    const quantity = parseFloat(document.getElementById('buyQuantity').value) || 0;
    const price = parseFloat(document.getElementById('buyPrice').value) || 0;
    const total = quantity * price;
    document.getElementById('totalAmount').textContent = `$${total.toFixed(2)}`;
}

// Event listeners
document.getElementById('closeBuyModal').addEventListener('click', closeBuyModal);
document.getElementById('cancelBuyBtn').addEventListener('click', closeBuyModal);
document.getElementById('buyQuantity').addEventListener('input', calculateTotal);
document.getElementById('buyPrice').addEventListener('input', calculateTotal);

// Close modal when clicking outside
document.getElementById('buyAssetModal').addEventListener('click', (e) => {
    if (e.target === document.getElementById('buyAssetModal')) {
        closeBuyModal();
    }
});

// Form submission
document.getElementById('buyAssetForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!selectedAsset) {
        showErrorModal('Validation Error', 'Please select an asset first');
        return;
    }
    
    const quantity = parseFloat(document.getElementById('buyQuantity').value);
    const price = parseFloat(document.getElementById('buyPrice').value);
    
    if (!quantity || !price) {
        showErrorModal('Validation Error', 'Please fill in all fields');
        return;
    }
    
    // Show loading state
    const submitBtn = document.getElementById('submitBuyBtn');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Processing...';
    submitBtn.disabled = true;
    
    fetch('/user/holding/buy', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            asset_id: selectedAsset.id,
            quantity: quantity,
            price_per_unit: price
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessModal('Success!', 'Asset purchased successfully!');
            // Update balance display with new balance
            if (data.new_balance !== undefined) {
                document.getElementById('userHoldingBalance').textContent = parseFloat(data.new_balance).toFixed(2);
            }
            closeBuyModal();
            // Redirect back to portfolio after showing success message
            setTimeout(() => {
                window.location.href = '/user/holding';
            }, 2000);
        } else {
            showErrorModal('Error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorModal('Error', 'An error occurred while processing your request');
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
});

// Load initial data
document.addEventListener('DOMContentLoaded', function() {
    loadCryptoAssets();
    
    // Initialize Pusher for real-time updates
    initializePusher();
    
    // Initialize modal functionality
    initializeModals();
});

// Initialize modal functionality
function initializeModals() {
    const successModal = document.getElementById('successModal');
    const errorModal = document.getElementById('errorModal');
    const closeErrorModal = document.getElementById('closeErrorModal');
    const portfolioBtn = document.getElementById('portfolioBtn');
    const buyAgainBtn = document.getElementById('buyAgainBtn');

    // Close modals when clicking backdrop
    [successModal, errorModal].forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });

    // Portfolio button - redirect to holdings
    portfolioBtn.addEventListener('click', () => {
        successModal.classList.add('hidden');
        window.location.href = '/user/holding';
    });

    // Buy Again button - stay on current page
    buyAgainBtn.addEventListener('click', () => {
        successModal.classList.add('hidden');
    });

    // Close error modal
    closeErrorModal.addEventListener('click', () => {
        errorModal.classList.add('hidden');
    });
}

// Modal functions
function showSuccessModal(title, message) {
    document.getElementById('successTitle').textContent = title;
    document.getElementById('successMessage').textContent = message;
    document.getElementById('successModal').classList.remove('hidden');
}

function showErrorModal(title, message) {
    document.getElementById('errorTitle').textContent = title;
    document.getElementById('errorMessage').textContent = message;
    document.getElementById('errorModal').classList.remove('hidden');
}

// Initialize Pusher for real-time price updates
function initializePusher() {
    // Check if Pusher is available
    if (typeof Pusher === 'undefined') {
        console.warn('Pusher is not loaded. Real-time updates will be disabled.');
        return;
    }

    // Enable pusher logging for debugging
    Pusher.logToConsole = true;

    try {
        // Use the actual Pusher credentials from your test
        const pusher = new Pusher('f8a3ce7115e96cd715fa', {
            cluster: 'mt1'
        });

        // Subscribe to price updates channel
        const channel = pusher.subscribe('price-updates');
        
        // Listen for price updates (new event name)
        channel.bind('price.updated', function(data) {
            console.log('Price update received (new):', data);
            updateAssetPrice(data);
        });

        // Also listen for the full class name (fallback)
        channel.bind('App\\Events\\PriceUpdated', function(data) {
            console.log('Price update received (fallback):', data);
            updateAssetPrice(data);
        });

        // Also listen for test events
        channel.bind('pusher:subscription_succeeded', function() {
            console.log('Successfully subscribed to price-updates channel');
        });

        // Test event listener for any event
        channel.bind_global(function(eventName, data) {
            console.log('Global event received:', eventName, data);
        });

        console.log('Pusher initialized successfully for real-time updates');
    } catch (error) {
        console.error('Failed to initialize Pusher:', error);
    }
}

// Function to update asset price in real-time
function updateAssetPrice(data) {
    console.log('updateAssetPrice called with data:', data);
    
    const assetId = data.asset_id;
    const newPrice = data.current_price;
    const priceChange = data.price_change_24h;
    
    console.log('Looking for asset with ID:', assetId);
    console.log('New price:', newPrice);
    console.log('Price change:', priceChange);
    
    // Update price in asset cards
    const priceElements = document.querySelectorAll(`[data-asset-id="${assetId}"] .asset-price`);
    console.log('Found price elements:', priceElements.length);
    
    priceElements.forEach(element => {
        const oldPrice = parseFloat(element.textContent.replace('$', '').replace(',', ''));
        console.log('Old price from element:', oldPrice);
        const formattedPrice = formatAssetPrice(newPrice);
        
        // Add pulse animation first
        element.classList.add('price-pulse');
        
        // Update the price with a slight delay for better visibility
        setTimeout(() => {
            element.textContent = `$${formattedPrice}`;
            console.log('Updated element text to:', `$${formattedPrice}`);
            
            // Add visual feedback for price changes
            if (newPrice > oldPrice) {
                element.classList.add('price-up');
                setTimeout(() => element.classList.remove('price-up'), 3000);
                console.log('Added price-up animation');
            } else if (newPrice < oldPrice) {
                element.classList.add('price-down');
                setTimeout(() => element.classList.remove('price-down'), 3000);
                console.log('Added price-down animation');
            }
            
            // Remove pulse animation
            setTimeout(() => element.classList.remove('price-pulse'), 1000);
        }, 200);
    });
    
    // Update price change percentage
    const changeElements = document.querySelectorAll(`[data-asset-id="${assetId}"] .asset-change`);
    console.log('Found change elements:', changeElements.length);
    
    changeElements.forEach(element => {
        const isPositive = priceChange >= 0;
        element.textContent = `${isPositive ? '+' : ''}${parseFloat(priceChange).toFixed(2)}%`;
        element.className = `asset-change text-sm font-medium ${isPositive ? 'text-green-400' : 'text-red-400'}`;
        console.log('Updated change element to:', `${isPositive ? '+' : ''}${parseFloat(priceChange).toFixed(2)}%`);
    });
    
    // Update modal if open
    if (selectedAsset && selectedAsset.id == assetId) {
        console.log('Updating modal for selected asset');
        const formattedPrice = formatAssetPrice(newPrice);
        document.getElementById('currentPrice').textContent = `$${formattedPrice}`;
        document.getElementById('priceChange').textContent = `${priceChange >= 0 ? '+' : ''}${parseFloat(priceChange).toFixed(2)}%`;
        document.getElementById('priceChange').className = priceChange >= 0 ? 'text-sm text-green-400' : 'text-sm text-red-400';
        document.getElementById('buyPrice').value = newPrice;
    }
    
    console.log('updateAssetPrice completed');
}
</script>

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
                <p class="text-gray-400 mb-6" id="successMessage">Operation completed successfully.</p>
                <div class="flex space-x-3">
                    <button id="portfolioBtn" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-medium transition-colors">
                        Portfolio
                    </button>
                    <button id="buyAgainBtn" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-2 rounded-lg font-medium transition-colors">
                        Buy Again
                    </button>
                </div>
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