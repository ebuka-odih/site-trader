@extends('dashboard.new-layout')

@section('content')
@php
    $holdingsBySymbol = $holdingsBySymbol ?? collect();
@endphp
<div class="space-y-6 text-white">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs uppercase tracking-wide text-[#08f58d]">Live Trading</p>
            <h1 class="text-2xl font-semibold tracking-tight">{{ is_array($asset) ? $asset['symbol'] : $asset->symbol }}</h1>
            <p class="text-sm text-gray-400">{{ strtoupper($assetType) }} Trading</p>
        </div>
        <a href="{{ route('user.nav.trade') }}" class="rounded-full border border-[#1f1f1f] px-4 py-2 text-sm text-gray-400 hover:border-[#1fff9c]/30 hover:text-white transition">
            ← Back
        </a>
    </div>

    <!-- Trading Interface -->
    <div class="grid grid-cols-1 lg:grid-cols-6 gap-6">
        <!-- Chart Section -->
        <div class="lg:col-span-4">
            <div class="rounded-[32px] border border-[#101010] bg-[#040404] overflow-hidden">
                <div class="border-b border-[#121212] px-6 py-4">
                    <h2 class="text-lg font-semibold">Price Chart</h2>
                </div>
                
                <!-- TradingView Chart Container -->
                <div class="relative w-full p-0">
                    <div id="tradingViewChart" class="w-full h-[500px] lg:h-[700px] bg-[#030303] rounded-xl overflow-hidden border border-[#0f0f0f]">
                        <div class="flex items-center justify-center h-full text-gray-500">
                            <div class="text-center">
                                <svg class="w-12 h-12 mx-auto mb-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                                </svg>
                                <p class="text-sm">Loading chart...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trading Panel -->
        <div class="lg:col-span-2">
            <div class="rounded-[32px] border border-[#101010] bg-[#040404] overflow-hidden">
                <!-- Modal Header -->
                <div class="px-6 py-5 border-b border-[#121212]">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="text-xs font-normal text-gray-400 mb-1.5" id="tradeSideHeader">BUY</div>
                            <h2 id="assetNameHeader" class="text-2xl font-bold text-white leading-tight">{{ is_array($asset) ? $asset['symbol'] : $asset->symbol }}</h2>
                        </div>
                        <a href="{{ route('user.nav.trade') }}" class="ml-4 p-1.5 rounded-lg border border-gray-700 text-gray-400 hover:text-white hover:border-gray-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="p-6 space-y-5">
                @if(session('success'))
                    <div class="rounded-2xl border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-300">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300">
                        {{ $errors->first('trade') ?? $errors->first() }}
                    </div>
                @endif
                
                    <!-- Trading Form -->
                    <form id="tradingForm" class="space-y-6" data-current-price="{{ is_array($asset) ? $asset['current_price'] : $asset->current_price }}">
                        @csrf
                        <input type="hidden" name="asset_type" id="assetTypeInput" value="{{ $assetType }}">
                        <input type="hidden" name="symbol" id="symbolInput" value="{{ is_array($asset) ? $asset['symbol'] : $asset->symbol }}">
                        
                        <!-- Trading Balance -->
                        <div class="flex items-center justify-between py-2">
                            <label class="text-sm text-gray-400">Trading Balance</label>
                            <span class="text-base font-bold text-white">${{ number_format(auth()->user()->trading_balance ?? 0, 2) }}</span>
                        </div>

                        <!-- Asset Selection Dropdown -->
                        <div class="space-y-2">
                            <div class="relative">
                                <button type="button" id="assetSelectBtn" class="w-full rounded-2xl border border-[#191919] bg-[#0a0a0a] px-4 py-3.5 text-left text-white flex items-center justify-between hover:border-[#1fff9c]/30 transition">
                                    <span id="selectedAssetDisplay" class="font-medium">{{ is_array($asset) ? $asset['symbol'] : $asset->symbol }}</span>
                                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                
                                <!-- Asset Dropdown Modal -->
                                <div id="assetDropdown" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4">
                                    <div class="w-full max-w-md rounded-2xl border border-[#191919] bg-[#040404] max-h-[80vh] overflow-hidden flex flex-col">
                                        <!-- Dropdown Header -->
                                        <div class="p-4 border-b border-[#121212]">
                                            <div class="flex items-center justify-between mb-4">
                                                <h3 class="text-lg font-semibold text-white">Select Asset</h3>
                                                <button type="button" id="closeAssetDropdown" class="text-gray-400 hover:text-white">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <!-- Search Bar -->
                                            <div class="relative">
                                                <input type="text" id="assetSearchInput" placeholder="Search by symbol or name..." class="w-full rounded-xl border border-[#191919] bg-[#0a0a0a] px-4 py-2 pl-10 text-white placeholder-gray-500 focus:border-[#1fff9c] focus:outline-none">
                                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                            </div>
                </div>

                                        <!-- Asset List -->
                                        <div class="flex-1 overflow-y-auto p-4 space-y-4">
                                            <!-- Quick Picks -->
                                            @php
                                                $quickPicks = $quickPicks ?? collect();
                                                $allAssets = $allAssets ?? collect();
                                                $currentSymbol = is_array($asset) ? $asset['symbol'] : $asset->symbol;
                                            @endphp
                                            @if($quickPicks->isNotEmpty())
                                                <div>
                                                    <h4 class="text-sm font-semibold text-gray-400 mb-3">Quick Picks</h4>
                                                    <div id="quickPicksList" class="space-y-2">
                                                        @foreach($quickPicks as $quickAsset)
                                                            <button type="button" class="asset-item w-full rounded-xl border border-[#191919] bg-[#0a0a0a] px-4 py-3 text-left hover:bg-[#1fff9c]/10 hover:border-[#1fff9c]/30 transition flex items-center justify-between {{ $quickAsset->symbol === $currentSymbol ? 'border-[#1fff9c] bg-[#1fff9c]/10' : '' }}" data-symbol="{{ $quickAsset->symbol }}" data-name="{{ $quickAsset->name }}" data-type="{{ $quickAsset->type }}">
                                                                <div class="flex-1">
                                                                    <div class="font-semibold text-white">{{ $quickAsset->symbol }} {{ $quickAsset->name }}</div>
                                                                </div>
                                                                <div class="flex items-center gap-2">
                                                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $quickAsset->type === 'crypto' ? 'bg-[#1fff9c]/20 text-[#1fff9c]' : 'bg-gray-700 text-gray-300' }}">{{ $quickAsset->type }}</span>
                                                                    @if($quickAsset->symbol === $currentSymbol)
                                                                        <svg class="w-5 h-5 text-[#1fff9c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                        </svg>
                                                                    @endif
                                                                </div>
                                                            </button>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <!-- All Assets -->
                                            @if($allAssets->isNotEmpty())
                                                <div>
                                                    <h4 class="text-sm font-semibold text-gray-400 mb-3">All Assets</h4>
                                                    <div id="allAssetsList" class="space-y-2">
                                                        @php
                                                            $groupedAssets = $allAssets->groupBy('type');
                                                        @endphp
                                                        @foreach($groupedAssets as $type => $assets)
                                                            <div class="mb-4">
                                                                <h5 class="text-xs font-semibold text-gray-500 mb-2 uppercase">{{ ucfirst($type) }}s</h5>
                                                                @foreach($assets as $assetItem)
                                                                    <button type="button" class="asset-item w-full rounded-xl border border-[#191919] bg-[#0a0a0a] px-4 py-3 text-left hover:bg-[#1fff9c]/10 hover:border-[#1fff9c]/30 transition flex items-center justify-between {{ $assetItem->symbol === $currentSymbol ? 'border-[#1fff9c] bg-[#1fff9c]/10' : '' }}" data-symbol="{{ $assetItem->symbol }}" data-name="{{ $assetItem->name }}" data-type="{{ $assetItem->type }}">
                                                                        <div class="flex-1">
                                                                            <div class="font-semibold text-white">{{ $assetItem->symbol }} {{ $assetItem->name }}</div>
                                                                        </div>
                                                                        <div class="flex items-center gap-2">
                                                                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $assetItem->type === 'crypto' ? 'bg-[#1fff9c]/20 text-[#1fff9c]' : 'bg-gray-700 text-gray-300' }}">{{ $assetItem->type }}</span>
                                                                            @if($assetItem->symbol === $currentSymbol)
                                                                                <svg class="w-5 h-5 text-[#1fff9c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                                </svg>
                                                                            @endif
                                                                        </div>
                                                                    </button>
                                                                @endforeach
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-center py-8 text-gray-500 text-sm">
                                                    No assets available
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>

                        <!-- Buy/Sell Toggle -->
                    <div class="space-y-2">
                        <div class="grid grid-cols-2 gap-3">
                                <button type="button" class="side-btn buy-btn active rounded-2xl bg-[#00ff5f] px-4 py-3.5 font-semibold text-black transition hover:bg-[#05d454]" data-side="buy">
                                Buy
                            </button>
                                <button type="button" class="side-btn sell-btn rounded-2xl border border-[#1f1f1f] bg-[#0a0a0a] px-4 py-3.5 font-semibold text-gray-400 transition hover:bg-red-600 hover:text-white hover:border-red-600" data-side="sell">
                                Sell
                            </button>
                        </div>
                        <input type="hidden" name="side" value="buy">
                    </div>

                        <!-- Order Type Toggle -->
                        <div class="space-y-2">
                            <label class="text-sm text-gray-400">Order Type</label>
                            <div class="grid grid-cols-2 gap-3">
                                <button type="button" class="order-type-btn market-btn active rounded-2xl border-2 border-[#00ff5f] bg-transparent px-4 py-3.5 font-semibold text-[#00ff5f] transition hover:bg-[#00ff5f]/10" data-type="market">
                                    Market
                                </button>
                                <button type="button" class="order-type-btn limit-btn rounded-2xl border border-[#1f1f1f] bg-[#0a0a0a] px-4 py-3.5 font-semibold text-white transition hover:border-[#1fff9c]/30 hover:text-[#1fff9c]" data-type="limit">
                                    Limit
                                </button>
                            </div>
                            <input type="hidden" name="order_type" value="market">
                        </div>

                        <!-- Amount and Quantity Fields -->
                        <div class="space-y-4">
                        <div class="space-y-2">
                                <label class="text-sm text-gray-400">Amount</label>
                                <input type="number" name="amount" step="0.01" min="1" class="w-full rounded-2xl border border-[#191919] bg-[#0a0a0a] px-4 py-3.5 text-white placeholder-gray-500 focus:border-[#1fff9c] focus:outline-none" placeholder="Enter amount" id="marketAmountInput">
                        </div>
                            
                        <div class="space-y-2">
                                <label class="text-sm text-gray-400">Quantity</label>
                                <input type="number" name="quantity" step="0.00000001" class="w-full rounded-2xl border border-[#191919] bg-[#0a0a0a] px-4 py-3.5 text-white placeholder-gray-500 focus:border-[#1fff9c] focus:outline-none" placeholder="Enter quantity" id="quantityInput">
                        </div>
                    </div>

                        <!-- Limit Order Fields (Hidden by default) -->
                        <div id="limitOrderFields" class="hidden space-y-4">
                        <div class="space-y-2">
                                <label class="text-sm text-gray-400">Price</label>
                                <input type="number" name="price" step="0.00000001" class="w-full rounded-2xl border border-[#191919] bg-[#0a0a0a] px-4 py-3 text-white placeholder-gray-500 focus:border-[#1fff9c] focus:outline-none" placeholder="Enter price" id="limitPriceInput">
                            </div>
                        </div>

                        <!-- Leverage Slider -->
                        <div class="space-y-3 pt-2">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-sm text-gray-400">Leverage</label>
                                <span id="leverageValue" class="text-lg font-bold text-[#00ff5f]">1x</span>
                            </div>
                            <div class="relative px-1">
                                <input type="range" name="leverage" id="leverageSlider" min="1" max="100" value="1" class="w-full h-2 bg-[#191919] rounded-lg appearance-none cursor-pointer slider">
                                <div class="flex justify-between text-xs text-gray-500 mt-2 px-1">
                                    <span>1x</span>
                                    <span>50x</span>
                                    <span>100x</span>
                                </div>
                            </div>
                            <!-- Leverage Warning -->
                            <div id="leverageWarning" class="hidden rounded-xl border border-orange-500/30 bg-orange-500/10 px-4 py-3 text-sm text-orange-300">
                                Leverage ×<span id="leverageWarningValue">1</span> will create a futures position. Liquidation risk applies.
                    </div>
                    </div>

                    <!-- Place Order Button -->
                        <button type="submit" class="w-full rounded-2xl bg-[#00ff5f] py-4 text-base font-bold text-black hover:bg-[#05d454] transition flex items-center justify-center gap-2 shadow-lg shadow-[#00ff5f]/20">
                            <span id="submitText">Place Buy Order</span>
                            <svg id="submitSpinner" class="hidden h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </button>
                    
                    <!-- Success/Error Messages -->
                    <div id="tradeMessage" class="hidden rounded-2xl px-4 py-3 text-sm"></div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Trade History -->
<div class="rounded-[32px] border border-[#101010] bg-[#040404] p-6 text-white space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs uppercase tracking-wide text-[#08f58d]">Activity</p>
            <h2 class="text-lg font-semibold">Recent Trades</h2>
        </div>
        <span class="text-xs text-gray-500">{{ $tradeHistory->count() }} shown</span>
    </div>
    @if($tradeHistory->isNotEmpty())
        <div class="space-y-3">
            @foreach($tradeHistory as $trade)
                @php
                    $isBuy = strtolower($trade->side) === 'buy';
                    $statusColor = match($trade->status) {
                        'filled', 'completed', 'closed' => 'text-green-400',
                        'cancelled' => 'text-red-400',
                        default => 'text-yellow-400',
                    };
                    $tradeSymbol = strtoupper($trade->symbol);
                    $holding = $holdingsBySymbol[$tradeSymbol] ?? null;
                    $pnlValue = $holding?->unrealized_pnl ?? null;
                    $pnlPercent = $holding?->unrealized_pnl_percentage ?? null;
                    $pnlPositive = $pnlValue >= 0;
                @endphp
                <div class="rounded-2xl border border-[#121212] bg-[#050505] px-4 py-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <p class="text-sm font-semibold {{ $isBuy ? 'text-green-400' : 'text-red-400' }}">
                            {{ strtoupper($trade->side) }} • {{ strtoupper($trade->symbol) }}
                        </p>
                        <p class="text-xs text-gray-500">{{ ucfirst($trade->order_type) }} • {{ $trade->created_at?->diffForHumans() }}</p>
                        @if(!is_null($pnlValue))
                            <p class="text-xs {{ $pnlPositive ? 'text-green-400' : 'text-red-400' }}">
                                Gain/Loss: {{ $pnlPositive ? '+' : '' }}{{ $user->formatAmount(abs($pnlValue)) }}
                                ({{ number_format($pnlPercent ?? 0, 2) }}%)
                            </p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-base font-semibold text-white">${{ number_format($trade->amount, 2) }}</p>
                        <p class="text-xs {{ $statusColor }}">{{ ucfirst($trade->status) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="rounded-2xl border border-dashed border-[#1a1a1a] bg-[#050505] px-4 py-8 text-center text-sm text-gray-500">
            No trades yet for this asset. Your activity will appear here once you place an order.
        </div>
    @endif
</div>

<style>
/* Leverage Slider Styling */
.slider {
    -webkit-appearance: none;
    appearance: none;
    background: #191919;
    outline: none;
    height: 8px;
    border-radius: 8px;
}

.slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #00ff5f;
    cursor: pointer;
    border: 2px solid #000;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.slider::-moz-range-thumb {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #00ff5f;
    cursor: pointer;
    border: 2px solid #000;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.slider::-webkit-slider-runnable-track {
    background: linear-gradient(to right, #00ff5f 0%, #00ff5f var(--slider-progress, 0%), #191919 var(--slider-progress, 0%), #191919 100%);
    height: 8px;
    border-radius: 8px;
}

.slider::-moz-range-track {
    background: #191919;
    height: 8px;
    border-radius: 8px;
}

.slider::-moz-range-progress {
    background: #00ff5f;
    height: 8px;
    border-radius: 8px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize TradingView Chart
    initTradingViewChart('{{ is_array($asset) ? $asset["symbol"] : $asset->symbol }}', '{{ $assetType }}');
    initLivePrice();
    
    // Asset Dropdown
    const assetSelectBtn = document.getElementById('assetSelectBtn');
    const assetDropdown = document.getElementById('assetDropdown');
    const closeAssetDropdown = document.getElementById('closeAssetDropdown');
    const assetSearchInput = document.getElementById('assetSearchInput');
    const assetItems = document.querySelectorAll('.asset-item');
    const selectedAssetDisplay = document.getElementById('selectedAssetDisplay');
    const assetTypeInput = document.getElementById('assetTypeInput');
    const symbolInput = document.getElementById('symbolInput');
    
    assetSelectBtn.addEventListener('click', function() {
        assetDropdown.classList.remove('hidden');
        assetDropdown.classList.add('flex');
    });
    
    closeAssetDropdown.addEventListener('click', function() {
        assetDropdown.classList.add('hidden');
        assetDropdown.classList.remove('flex');
    });
    
    // Close dropdown when clicking outside
    assetDropdown.addEventListener('click', function(e) {
        if (e.target === assetDropdown) {
            assetDropdown.classList.add('hidden');
            assetDropdown.classList.remove('flex');
        }
    });
    
    // Asset search
    assetSearchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        let hasResults = false;
        
        assetItems.forEach(item => {
            const symbol = item.dataset.symbol.toLowerCase();
            const name = item.dataset.name.toLowerCase();
            if (symbol.includes(searchTerm) || name.includes(searchTerm)) {
                item.style.display = 'flex';
                hasResults = true;
            } else {
                item.style.display = 'none';
            }
        });
        
        // Hide/show section headers based on results
        const quickPicksList = document.getElementById('quickPicksList');
        const allAssetsList = document.getElementById('allAssetsList');
        
        if (quickPicksList) {
            const quickPicksItems = quickPicksList.querySelectorAll('.asset-item');
            const hasQuickPicks = Array.from(quickPicksItems).some(item => item.style.display !== 'none');
            const quickPicksSection = quickPicksList.closest('div');
            if (quickPicksSection && quickPicksSection.previousElementSibling) {
                quickPicksSection.previousElementSibling.style.display = hasQuickPicks ? 'block' : 'none';
                quickPicksSection.style.display = hasQuickPicks ? 'block' : 'none';
            }
        }
        
        if (allAssetsList) {
            const allAssetsItems = allAssetsList.querySelectorAll('.asset-item');
            const hasAllAssets = Array.from(allAssetsItems).some(item => item.style.display !== 'none');
            const allAssetsSection = allAssetsList.closest('div');
            if (allAssetsSection && allAssetsSection.previousElementSibling) {
                allAssetsSection.previousElementSibling.style.display = hasAllAssets ? 'block' : 'none';
                allAssetsSection.style.display = hasAllAssets ? 'block' : 'none';
            }
        }
    });
    
    // Asset selection
    assetItems.forEach(item => {
        item.addEventListener('click', function() {
            const symbol = this.dataset.symbol;
            const name = this.dataset.name;
            const type = this.dataset.type;
            
            // Update display with symbol only
            selectedAssetDisplay.textContent = symbol;
            
            // Update hidden inputs
            assetTypeInput.value = type;
            symbolInput.value = symbol;
            
            // Update header with symbol
            document.getElementById('assetNameHeader').textContent = symbol;
            
            // Close dropdown
            assetDropdown.classList.add('hidden');
            assetDropdown.classList.remove('flex');
            
            // Reload page to update chart and price
            window.location.href = `{{ route('user.liveTrading.trade') }}?asset_type=${type}&symbol=${symbol}`;
        });
    });
    
    // Order type switching
    const orderTypeBtns = document.querySelectorAll('.order-type-btn');
    const orderTypeInput = document.querySelector('input[name="order_type"]');
    const limitFields = document.getElementById('limitOrderFields');
    const limitPriceInput = document.getElementById('limitPriceInput');
    const marketAmountInput = document.getElementById('marketAmountInput');
    const quantityInput = document.getElementById('quantityInput');
    
    orderTypeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            orderTypeBtns.forEach(b => {
                b.classList.remove('active', 'bg-[#00ff5f]', 'text-black');
                b.classList.add('bg-[#0a0a0a]', 'text-gray-400', 'border-[#1f1f1f]');
            });
            
            if (this.classList.contains('market-btn')) {
                this.classList.add('active', 'border-[#00ff5f]', 'text-[#00ff5f]', 'bg-transparent');
                this.classList.remove('border-[#1f1f1f]', 'bg-[#0a0a0a]', 'text-white');
                document.querySelector('.limit-btn').classList.remove('border-[#00ff5f]', 'text-[#00ff5f]', 'bg-transparent');
                document.querySelector('.limit-btn').classList.add('border-[#1f1f1f]', 'bg-[#0a0a0a]', 'text-white');
            } else {
                this.classList.add('active', 'border-[#00ff5f]', 'text-[#00ff5f]', 'bg-transparent');
                this.classList.remove('border-[#1f1f1f]', 'bg-[#0a0a0a]', 'text-white');
                document.querySelector('.market-btn').classList.remove('border-[#00ff5f]', 'text-[#00ff5f]', 'bg-transparent');
                document.querySelector('.market-btn').classList.add('border-[#1f1f1f]', 'bg-[#0a0a0a]', 'text-white');
            }
            
            const type = this.dataset.type;
            orderTypeInput.value = type;
            
            if (type === 'limit') {
                limitFields.classList.remove('hidden');
        } else {
            limitFields.classList.add('hidden');
            }
        });
    });
    
    // Update quantity based on amount and price
    const updateQuantity = () => {
        const amount = parseFloat(marketAmountInput.value) || 0;
        const currentPrice = parseFloat(document.getElementById('tradingForm').dataset.currentPrice) || 0;
        if (amount > 0 && currentPrice > 0) {
            quantityInput.value = (amount / currentPrice).toFixed(8);
        }
    };
    
    marketAmountInput?.addEventListener('input', updateQuantity);
    
    // Side switching
    const sideBtns = document.querySelectorAll('.side-btn');
    const sideInput = document.querySelector('input[name="side"]');
    const tradeSideHeader = document.getElementById('tradeSideHeader');
    const submitText = document.getElementById('submitText');
    
    sideBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            sideBtns.forEach(b => {
                b.classList.remove('active', 'bg-[#00ff5f]', 'text-black');
                b.classList.add('bg-[#0a0a0a]', 'text-gray-400', 'border-[#1f1f1f]');
            });
            
            this.classList.add('active', 'bg-[#00ff5f]', 'text-black');
                this.classList.remove('bg-[#0a0a0a]', 'text-gray-400', 'border-[#1f1f1f]');
            
            const side = this.dataset.side;
            sideInput.value = side;
            tradeSideHeader.textContent = side.toUpperCase();
            if (side === 'buy') {
                tradeSideHeader.className = 'text-xs font-normal text-gray-400 mb-1.5';
            } else {
                tradeSideHeader.className = 'text-xs font-normal text-red-400 mb-1.5';
            }
            submitText.textContent = `Place ${side.charAt(0).toUpperCase() + side.slice(1)} Order`;
        });
    });
    
    // Leverage Slider
    const leverageSlider = document.getElementById('leverageSlider');
    const leverageValue = document.getElementById('leverageValue');
    const leverageWarning = document.getElementById('leverageWarning');
    const leverageWarningValue = document.getElementById('leverageWarningValue');
    
    leverageSlider.addEventListener('input', function() {
        const value = parseInt(this.value);
        leverageValue.textContent = `${value}x`;
        
        // Update slider progress
        const progress = ((value - 1) / 99) * 100;
        this.style.setProperty('--slider-progress', `${progress}%`);
        
        // Show warning if leverage > 1
        if (value > 1) {
            leverageWarning.classList.remove('hidden');
            leverageWarningValue.textContent = value;
        } else {
            leverageWarning.classList.add('hidden');
        }
    });
    
    // Initialize slider progress
    leverageSlider.style.setProperty('--slider-progress', '0%');
    
    // Form submission
    const form = document.getElementById('tradingForm');
    const submitSpinner = document.getElementById('submitSpinner');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        const currentSide = sideInput.value;
        submitText.textContent = 'Processing...';
        submitSpinner.classList.remove('hidden');
        
        const formData = new FormData(this);
        
        fetch('{{ route("user.liveTrading.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(response => response.json())
        .then(data => {
            const messageDiv = document.getElementById('tradeMessage');
            
            if (data.success) {
                // Show success message
                submitText.textContent = 'Success!';
                submitSpinner.classList.add('hidden');
                
                messageDiv.className = 'rounded-2xl border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-300';
                messageDiv.textContent = data.message || 'Trade placed successfully!';
                messageDiv.classList.remove('hidden');
                
                // Reset form after 2 seconds
                setTimeout(() => {
                    form.reset();
                    submitText.textContent = `Place ${currentSide.charAt(0).toUpperCase() + currentSide.slice(1)} Order`;
                    messageDiv.classList.add('hidden');
                    
                    // Reset leverage slider
                    leverageSlider.value = 1;
                    leverageValue.textContent = '1x';
                    leverageWarning.classList.add('hidden');
                    leverageSlider.style.setProperty('--slider-progress', '0%');

                    openTradeSuccessModal();
                }, 500);
            } else {
                // Show error message
                submitText.textContent = `Place ${currentSide.charAt(0).toUpperCase() + currentSide.slice(1)} Order`;
                submitSpinner.classList.add('hidden');
                
                messageDiv.className = 'rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300';
                messageDiv.textContent = data.message || 'Failed to place trade. Please try again.';
                messageDiv.classList.remove('hidden');
                
                // Hide error after 5 seconds
                setTimeout(() => {
                    messageDiv.classList.add('hidden');
                }, 5000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const messageDiv = document.getElementById('tradeMessage');
            const currentSide = sideInput.value;
            
            submitText.textContent = `Place ${currentSide.charAt(0).toUpperCase() + currentSide.slice(1)} Order`;
            submitSpinner.classList.add('hidden');
            
            messageDiv.className = 'rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300';
            messageDiv.textContent = 'An error occurred while placing the trade. Please try again.';
            messageDiv.classList.remove('hidden');
            
            setTimeout(() => {
                messageDiv.classList.add('hidden');
            }, 5000);
        });
    });
});

function initTradingViewChart(symbol, assetType) {
    if (typeof TradingView === 'undefined') {
        console.error('TradingView is not loaded');
        return;
    }
    
    let tradingViewSymbol = symbol;
    
    if (assetType === 'stock') {
        tradingViewSymbol = `NASDAQ:${symbol}`;
    } else if (assetType === 'forex') {
        tradingViewSymbol = `FX:${symbol}`;
    } else if (assetType === 'crypto') {
        tradingViewSymbol = `BINANCE:${symbol}USD`;
    }
    
    const container = document.getElementById('tradingViewChart');
    if (!container) {
        console.error('TradingView container not found');
        return;
    }
    
    container.innerHTML = '';
    
    new TradingView.widget({
        "width": "100%",
        "height": container.offsetHeight,
        "symbol": tradingViewSymbol,
        "interval": "D",
        "timezone": "Etc/UTC",
        "theme": "dark",
        "style": "1",
        "locale": "en",
        "toolbar_bg": "#030303",
        "enable_publishing": false,
        "hide_side_toolbar": false,
        "allow_symbol_change": true,
        "container_id": "tradingViewChart",
        "autosize": true,
        "studies": [
            "RSI@tv-basicstudies",
            "MACD@tv-basicstudies"
        ],
        "overrides": {
            "paneProperties.background": "#030303",
            "paneProperties.backgroundType": "solid",
            "paneProperties.vertGridProperties.color": "#0f0f0f",
            "paneProperties.horzGridProperties.color": "#0f0f0f",
            "symbolWatermarkProperties.transparency": 90,
            "scalesProperties.textColor": "#6b7280"
        }
    });
}

function initLivePrice() {
    const priceEl = document.getElementById('currentPriceValue');
    const changeEl = document.getElementById('priceChangeValue');
    if (!priceEl || !changeEl) {
        return;
    }

    const decimals = parseInt(priceEl.dataset.decimals ?? '2', 10);
    const assetType = '{{ $assetType }}';
    const symbol = '{{ is_array($asset) ? $asset["symbol"] : $asset->symbol }}';

    const formatter = new Intl.NumberFormat('en-US', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    });

    const fetchPrice = () => {
        const url = new URL('{{ route('user.liveTrading.price') }}', window.location.origin);
        url.searchParams.set('asset_type', assetType);
        url.searchParams.set('symbol', symbol);

        fetch(url.toString())
            .then(res => res.json())
            .then(data => {
                if (!data.success) return;
                const price = parseFloat(data.price ?? 0) || 0;
                const change = parseFloat(data.change_24h ?? 0) || 0;
                priceEl.textContent = `$${formatter.format(price)}`;
                changeEl.classList.remove('text-green-400', 'text-red-400');
                changeEl.classList.add(change >= 0 ? 'text-green-400' : 'text-red-400');
                changeEl.innerHTML = `${change >= 0 ? '↗' : '↘'} ${Math.abs(change).toFixed(2)}% <span class="text-gray-500">24h</span>`;
            })
            .catch(() => {});
    };

    fetchPrice();
    setInterval(fetchPrice, 60000);
}

function openTradeSuccessModal() {
    const modal = document.getElementById('tradeSuccessModal');
    if (!modal) return;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeTradeSuccessModal() {
    const modal = document.getElementById('tradeSuccessModal');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    window.location.reload();
}

document.addEventListener('click', function(event) {
    if (event.target && event.target.id === 'tradeSuccessConfirm') {
        closeTradeSuccessModal();
    }
});
</script>
@endsection
<!-- Trade Success Modal -->
<div id="tradeSuccessModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 px-4">
    <div class="w-full max-w-sm rounded-[28px] border border-[#111] bg-[#050505] p-6 text-center space-y-4">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-green-500/10 text-green-300 border border-green-500/30">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <div>
            <p class="text-lg font-semibold text-white">Order Placed</p>
            <p class="text-sm text-gray-400">Your trade has been submitted successfully.</p>
        </div>
        <button id="tradeSuccessConfirm" class="w-full rounded-2xl bg-[#00ff5f] py-3 text-black font-semibold hover:bg-[#05d454]">
            OK
        </button>
    </div>
</div>
