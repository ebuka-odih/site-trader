@extends('dashboard.layout.app')

@section('content')
<div class="min-h-screen bg-gray-900">
    <!-- Header -->
    <div class="bg-gray-800 border-b border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <button onclick="history.back()" class="text-gray-400 hover:text-white mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <h1 class="text-xl font-semibold text-white">{{ $asset->name }} ({{ $asset->symbol }}) Chart</h1>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-400">Current Price</div>
                    <div class="text-lg font-bold text-white">${{ number_format($asset->current_price, 8) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-gray-800 rounded-lg shadow-xl border border-gray-700">
            <!-- Chart Header -->
            <div class="p-4 border-b border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-medium text-white">{{ $asset->name }} Price Chart</h2>
                        <p class="text-gray-400 text-sm">Real-time price analysis and technical indicators</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-sm text-gray-400">24h Change</div>
                            <div class="text-sm font-medium {{ $asset->price_change_24h >= 0 ? 'text-green-400' : 'text-red-400' }}">
                                {{ $asset->price_change_24h >= 0 ? '+' : '' }}{{ number_format($asset->price_change_24h, 2) }}%
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-400">Market Cap</div>
                            <div class="text-sm font-medium text-white">${{ number_format($asset->market_cap) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TradingView Chart -->
            <div class="p-4">
                <div class="bg-gray-700 rounded-lg overflow-hidden" style="height: 600px;">
                    <div id="tradingViewChart" class="w-full h-full"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const symbol = '{{ $asset->symbol }}';
    const assetName = '{{ $asset->name }}';
    const assetType = '{{ $asset->type }}';
    
    // Initialize TradingView chart
    initFullTradingViewChart(symbol, assetName, assetType);
});

function initFullTradingViewChart(symbol, assetName, assetType) {
    // Determine the correct symbol for TradingView
    let tvSymbol = getTradingViewSymbol(symbol, assetType);
    
    new TradingView.widget({
        "width": "100%",
        "height": "100%",
        "symbol": tvSymbol,
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
        ],
        "show_popup_button": true,
        "popup_width": "1000",
        "popup_height": "650"
    });
}

function getTradingViewSymbol(symbol, assetType) {
    // If it's a stock, use NASDAQ
    if (assetType === 'stock') {
        return `NASDAQ:${symbol}`;
    }
    
    // Crypto mappings
    const cryptoMappings = {
        'BTC': 'BINANCE:BTCUSD',
        'ETH': 'BINANCE:ETHUSD',
        'USDT': 'BINANCE:USDTUSD',
        'USDC': 'BINANCE:USDCUSD',
        'BNB': 'BINANCE:BNBUSD',
        'ADA': 'BINANCE:ADAUSD',
        'SOL': 'BINANCE:SOLUSD',
        'DOT': 'BINANCE:DOTUSD',
        'DOGE': 'BINANCE:DOGEUSD',
        'AVAX': 'BINANCE:AVAXUSD',
        'MATIC': 'BINANCE:MATICUSD',
        'LINK': 'BINANCE:LINKUSD',
        'UNI': 'BINANCE:UNIUSD',
        'LTC': 'BINANCE:LTCUSD',
        'BCH': 'BINANCE:BCHUSD',
        'XRP': 'BINANCE:XRPUSD',
        'ATOM': 'BINANCE:ATOMUSD',
        'FTM': 'BINANCE:FTMUSD',
        'NEAR': 'BINANCE:NEARUSD',
        'ALGO': 'BINANCE:ALGOUSD'
    };
    
    // Check if we have a specific crypto mapping
    if (cryptoMappings[symbol]) {
        return cryptoMappings[symbol];
    }
    
    // Default fallback for crypto
    return `BINANCE:${symbol}USD`;
}
</script>
@endsection
