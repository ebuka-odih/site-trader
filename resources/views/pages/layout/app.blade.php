
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from www.blackrockinvestmentcorporation.com/ by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Aug 2025 18:10:48 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="csrf-token" content="QHTgDfeSDEhGixs61ktyfaAnqYfyNU0Xv8qcvRbs">
    <meta name="locale" content="en">
    <meta name="content-language" content="en">
    <title>{{ config('app.name') }} | Advanced Trading Platform</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>{{ config('app.name')[0] }}</text></svg>" type="image/svg+xml" />
    <script src="https://cdn.tailwindcss.com/"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/pako@2.1.0/dist/pako.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap');

        :root {
            --primary-bg: #0A0714;
            --secondary-bg: #0D091C;
            --card-bg: #1A1428;
            --accent-color: #2FE6DE;
            --accent-hover: rgba(47, 230, 222, 0.8);
            --accent-light: rgba(47, 230, 222, 0.1);
            --text-primary: #FFFFFF;
            --text-secondary: #A0AEC0;
            --border-color: rgba(47, 230, 222, 0.1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--primary-bg);
            color: var(--text-primary);
        }

        /* Custom Scrollbar Styling */
        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(47, 230, 222, 0.2) #1A1428;
        }

        *::-webkit-scrollbar {
            width: 8px;
        }

        *::-webkit-scrollbar-track {
            background: #1A1428;
            border-radius: 10px;
        }

        *::-webkit-scrollbar-thumb {
            background-color: rgba(47, 230, 222, 0.2);
            border-radius: 10px;
            border: 2px solid transparent;
            background-clip: content-box;
        }

        *::-webkit-scrollbar-thumb:hover {
            background-color: rgba(47, 230, 222, 0.4);
        }

        /* Animations */
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .animate-bounce-slow {
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .animate-ping-slow {
            animation: ping 2s ease-in-out infinite;
        }

        @keyframes ping {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Navbar styles */
        .navbar-item {
            position: relative;
        }

        .navbar-item::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--accent-color);
            transition: width 0.3s ease;
        }

        .navbar-item:hover::after,
        .navbar-item.active::after {
            width: 100%;
        }

        /* Card styles */
        .card {
            background-color: var(--card-bg);
            border-radius: 0.75rem;
            border: 1px solid var(--border-color);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        /* Button styles */
        .btn-primary {
            background-color: var(--accent-color);
            color: var(--primary-bg);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background-color: var(--accent-hover);
        }

        .btn-secondary {
            background-color: transparent;
            color: var(--text-primary);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid var(--border-color);
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-secondary:hover {
            border-color: var(--accent-color);
            color: var(--accent-color);
        }

        /* Mobile navigation */
        .mobile-nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: var(--secondary-bg);
            z-index: 50;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .mobile-nav.active {
            transform: translateX(0);
        }

        /* Market ticker */
        .market-ticker {
            overflow: hidden;
            white-space: nowrap;
            position: relative;
        }

        .ticker-content {
            display: inline-block;
            animation: ticker 30s linear infinite;
        }

        @keyframes ticker {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }

        /* Dropdown styles */
        .dropdown {
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: var(--secondary-bg);
            min-width: 200px;
            border-radius: 0.5rem;
            border: 1px solid var(--border-color);
            z-index: 10;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Mobile responsive */
        @media (max-width: 640px) {
            .live-notification {
                bottom: 10px;
                right: 10px;
                left: 10px;
                max-width: none;
                min-width: auto;
            }
        }
    </style>
        <!-- Livewire Styles --><style >[wire\:loading][wire\:loading], [wire\:loading\.delay][wire\:loading\.delay], [wire\:loading\.inline-block][wire\:loading\.inline-block], [wire\:loading\.inline][wire\:loading\.inline], [wire\:loading\.block][wire\:loading\.block], [wire\:loading\.flex][wire\:loading\.flex], [wire\:loading\.table][wire\:loading\.table], [wire\:loading\.grid][wire\:loading\.grid], [wire\:loading\.inline-flex][wire\:loading\.inline-flex] {display: none;}[wire\:loading\.delay\.none][wire\:loading\.delay\.none], [wire\:loading\.delay\.shortest][wire\:loading\.delay\.shortest], [wire\:loading\.delay\.shorter][wire\:loading\.delay\.shorter], [wire\:loading\.delay\.short][wire\:loading\.delay\.short], [wire\:loading\.delay\.default][wire\:loading\.delay\.default], [wire\:loading\.delay\.long][wire\:loading\.delay\.long], [wire\:loading\.delay\.longer][wire\:loading\.delay\.longer], [wire\:loading\.delay\.longest][wire\:loading\.delay\.longest] {display: none;}[wire\:offline][wire\:offline] {display: none;}[wire\:dirty]:not(textarea):not(input):not(select) {display: none;}:root {--livewire-progress-bar-color: #ffffff;}[x-cloak] {display: none !important;}</style>
</head>

<body>

    <!-- Advanced Stock Market Ticker -->
<div class="bg-gradient-to-r from-[#0A0714] via-[#0D091C] to-[#0A0714] py-3 overflow-hidden border-b border-[#2FE6DE]/20 shadow-lg">
    <div class="ticker-wrap">
        <div class="ticker" id="stock-ticker">
            <div class="ticker-item flex items-center">
                <span class="loading-ticker text-[#2FE6DE] animate-pulse">
                    <i class="fas fa-chart-line mr-2"></i>Loading market data...
                </span>
            </div>
        </div>
    </div>

    <!-- Market Status Bar -->
    <div class="flex justify-center items-center mt-1">
        <div class="flex items-center space-x-6 text-xs">
            <div class="flex items-center space-x-1">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-gray-400" id="market-status">Market Open</span>
            </div>
            <div class="text-gray-400" id="market-time"></div>
            <div class="flex items-center space-x-1">
                <i class="fas fa-arrow-up text-green-400"></i>
                <span class="text-green-400" id="gainers-count">0</span>
                <span class="text-gray-500">gainers</span>
            </div>
            <div class="flex items-center space-x-1">
                <i class="fas fa-arrow-down text-red-400"></i>
                <span class="text-red-400" id="losers-count">0</span>
                <span class="text-gray-500">losers</span>
            </div>
        </div>
    </div>
</div>

<script>
// Advanced Stock Market Ticker
class StockTicker {
    constructor() {
        this.stocks = [
            {
                symbol: 'AAPL',
                name: 'Apple Inc.',
                price: 175.43,
                change: 2.67,
                changePercent: 1.55,
                volume: 65432100,
                sector: 'Technology',
                exchange: 'NASDAQ'
            },
            {
                symbol: 'MSFT',
                name: 'Microsoft Corp',
                price: 384.30,
                change: -1.15,
                changePercent: -0.30,
                volume: 32156789,
                sector: 'Technology',
                exchange: 'NASDAQ'
            },
            {
                symbol: 'GOOGL',
                name: 'Alphabet Inc',
                price: 142.65,
                change: 1.25,
                changePercent: 0.88,
                volume: 28765432,
                sector: 'Communication',
                exchange: 'NASDAQ'
            },
            {
                symbol: 'TSLA',
                name: 'Tesla Inc',
                price: 248.48,
                change: 5.73,
                changePercent: 2.36,
                volume: 89543210,
                sector: 'Consumer Cyclical',
                exchange: 'NASDAQ'
            },
            {
                symbol: 'AMZN',
                name: 'Amazon.com',
                price: 156.77,
                change: -0.85,
                changePercent: -0.54,
                volume: 45321098,
                sector: 'Consumer Cyclical',
                exchange: 'NASDAQ'
            },
            {
                symbol: 'NVDA',
                name: 'NVIDIA Corp',
                price: 875.15,
                change: 18.90,
                changePercent: 2.21,
                volume: 76543210,
                sector: 'Technology',
                exchange: 'NASDAQ'
            },
            {
                symbol: 'META',
                name: 'Meta Platforms',
                price: 498.37,
                change: 7.23,
                changePercent: 1.47,
                volume: 23456789,
                sector: 'Communication',
                exchange: 'NASDAQ'
            },
            {
                symbol: 'BRK.B',
                name: 'Berkshire Hathaway',
                price: 432.15,
                change: 2.45,
                changePercent: 0.57,
                volume: 4567890,
                sector: 'Financial',
                exchange: 'NYSE'
            },
            {
                symbol: 'JPM',
                name: 'JPMorgan Chase',
                price: 168.45,
                change: -0.75,
                changePercent: -0.44,
                volume: 12345678,
                sector: 'Financial',
                exchange: 'NYSE'
            },
            {
                symbol: 'JNJ',
                name: 'Johnson & Johnson',
                price: 162.80,
                change: 1.10,
                changePercent: 0.68,
                volume: 8765432,
                sector: 'Healthcare',
                exchange: 'NYSE'
            },
            {
                symbol: 'V',
                name: 'Visa Inc',
                price: 267.90,
                change: 3.15,
                changePercent: 1.19,
                volume: 6543210,
                sector: 'Financial',
                exchange: 'NYSE'
            },
            {
                symbol: 'PG',
                name: 'Procter & Gamble',
                price: 155.22,
                change: -0.33,
                changePercent: -0.21,
                volume: 5432109,
                sector: 'Consumer Defensive',
                exchange: 'NYSE'
            },
            {
                symbol: 'UNH',
                name: 'UnitedHealth Group',
                price: 542.88,
                change: 4.67,
                changePercent: 0.87,
                volume: 3456789,
                sector: 'Healthcare',
                exchange: 'NYSE'
            },
            {
                symbol: 'HD',
                name: 'Home Depot',
                price: 385.44,
                change: -2.11,
                changePercent: -0.54,
                volume: 4321098,
                sector: 'Consumer Cyclical',
                exchange: 'NYSE'
            },
            {
                symbol: 'MA',
                name: 'Mastercard Inc',
                price: 456.78,
                change: 5.23,
                changePercent: 1.16,
                volume: 2345678,
                sector: 'Financial',
                exchange: 'NYSE'
            }
        ];

        this.init();
    }

    init() {
        this.updateTicker();
        this.updateMarketStatus();
        this.simulateRealTimeUpdates();

        // Update ticker every 30 seconds
        setInterval(() => {
            this.updateTicker();
        }, 30000);

        // Update market status every minute
        setInterval(() => {
            this.updateMarketStatus();
        }, 60000);
    }

    simulateRealTimeUpdates() {
        // Update prices every 3 seconds to simulate real-time trading
        setInterval(() => {
            this.stocks = this.stocks.map(stock => {
                const volatility = Math.random() * 0.02; // 0-2% volatility
                const direction = Math.random() > 0.5 ? 1 : -1;
                const priceChange = stock.price * volatility * direction * 0.1; // Smaller changes

                const newPrice = Math.max(0.01, stock.price + priceChange);
                const change = newPrice - stock.price;
                const changePercent = (change / stock.price) * 100;

                return {
                    ...stock,
                    price: newPrice,
                    change: change,
                    changePercent: changePercent,
                    volume: stock.volume + Math.floor(Math.random() * 100000)
                };
            });

            this.updateTicker();
        }, 3000);
    }

    updateTicker() {
        const tickerContainer = document.getElementById('stock-ticker');
        if (!tickerContainer) return;

        let tickerHTML = '';

        this.stocks.forEach(stock => {
            const isPositive = stock.changePercent >= 0;
            const changeClass = isPositive ? 'text-green-400' : 'text-red-400';
            const changeBgClass = isPositive ? 'bg-green-500/10' : 'bg-red-500/10';
            const changeIcon = isPositive ? 'fa-caret-up' : 'fa-caret-down';
            const borderClass = isPositive ? 'border-green-500/20' : 'border-red-500/20';

            tickerHTML += `
                <div class="ticker-item flex items-center mr-8 bg-[#1A1428]/50 rounded-lg px-3 py-2 border ${borderClass} hover:bg-[#1A1428] transition-all group">
                    <div class="flex items-center space-x-3">
                        <!-- Stock Symbol with Exchange -->
                        <div class="flex flex-col items-center">
                            <div class="font-bold text-white text-sm group-hover:text-[#2FE6DE] transition-colors">${stock.symbol}</div>
                            <div class="text-xs text-gray-500">${stock.exchange}</div>
                        </div>

                        <!-- Divider -->
                        <div class="w-px h-8 bg-[#2FE6DE]/20"></div>

                        <!-- Price -->
                        <div class="text-right">
                            <div class="font-mono font-semibold text-white text-sm">
                                ${this.formatCurrency(stock.price)}
                            </div>
                        </div>

                        <!-- Change -->
                        <div class="flex items-center space-x-1 ${changeBgClass} px-2 py-1 rounded-md">
                            <i class="fas ${changeIcon} ${changeClass} text-xs"></i>
                            <span class="${changeClass} font-mono text-xs font-medium">
                                ${this.formatChange(stock.change)}
                            </span>
                            <span class="${changeClass} font-mono text-xs">
                                (${this.formatPercentage(stock.changePercent)})
                            </span>
                        </div>

                        <!-- Volume (abbreviated) -->
                        <div class="text-xs text-gray-400 min-w-0">
                            <div class="truncate">Vol: ${this.formatVolume(stock.volume)}</div>
                        </div>

                        <!-- Sector Tag -->
                        <div class="hidden lg:block">
                            <span class="px-2 py-1 bg-[#2FE6DE]/10 text-[#2FE6DE] rounded-full text-xs font-medium">
                                ${stock.sector}
                            </span>
                        </div>
                    </div>
                </div>
            `;
        });

        tickerContainer.innerHTML = tickerHTML;
        this.updateStats();
    }

    updateStats() {
        const gainers = this.stocks.filter(stock => stock.changePercent > 0).length;
        const losers = this.stocks.filter(stock => stock.changePercent < 0).length;

        const gainersElement = document.getElementById('gainers-count');
        const losersElement = document.getElementById('losers-count');

        if (gainersElement) gainersElement.textContent = gainers;
        if (losersElement) losersElement.textContent = losers;
    }

    updateMarketStatus() {
        const now = new Date();
        const marketOpen = new Date();
        marketOpen.setHours(9, 30, 0, 0); // 9:30 AM EST
        const marketClose = new Date();
        marketClose.setHours(16, 0, 0, 0); // 4:00 PM EST

        const isWeekday = now.getDay() >= 1 && now.getDay() <= 5;
        const isMarketHours = now >= marketOpen && now <= marketClose;
        const isOpen = isWeekday && isMarketHours;

        const statusElement = document.getElementById('market-status');
        const timeElement = document.getElementById('market-time');

        if (statusElement) {
            statusElement.textContent = isOpen ? 'Market Open' : 'Market Closed';
            statusElement.className = isOpen ? 'text-green-400' : 'text-red-400';
        }

        if (timeElement) {
            const timeOptions = {
                timeZone: 'America/New_York',
                hour12: true,
                hour: 'numeric',
                minute: '2-digit',
                timeZoneName: 'short'
            };
            timeElement.textContent = now.toLocaleTimeString('en-US', timeOptions);
        }
    }

    formatCurrency(value) {
        return '$' + value.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    formatChange(value) {
        const sign = value >= 0 ? '+' : '';
        return `${sign}${value.toFixed(2)}`;
    }

    formatPercentage(value) {
        const sign = value >= 0 ? '+' : '';
        return `${sign}${value.toFixed(2)}%`;
    }

    formatVolume(value) {
        if (value >= 1e9) {
            return (value / 1e9).toFixed(1) + 'B';
        } else if (value >= 1e6) {
            return (value / 1e6).toFixed(1) + 'M';
        } else if (value >= 1e3) {
            return (value / 1e3).toFixed(1) + 'K';
        } else {
            return value.toLocaleString();
        }
    }
}

// Initialize the stock ticker when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new StockTicker();
});

// Remove the old crypto functions and replace with stock ticker
function fetchCryptoData() {
    // This function is now handled by StockTicker class
    // Keeping empty to avoid errors if called elsewhere
}

function updateTicker(data) {
    // This function is now handled by StockTicker class
    // Keeping empty to avoid errors if called elsewhere
}
</script>

<style>
/* Advanced Ticker Styles */
.ticker-wrap {
    width: 100%;
    overflow: hidden;
    height: 60px;
    padding: 0;
    box-sizing: border-box;
    position: relative;
}

.ticker {
    display: flex;
    white-space: nowrap;
    padding-right: 100%;
    animation-iteration-count: infinite;
    animation-timing-function: linear;
    animation-name: stockTicker;
    animation-duration: 45s; /* Slightly slower for better readability */
}

.ticker-item {
    display: inline-flex;
    align-items: center;
    padding: 0 0.5rem;
    flex-shrink: 0;
}

@keyframes stockTicker {
    0% {
        transform: translate3d(0, 0, 0);
    }
    100% {
        transform: translate3d(-100%, 0, 0);
    }
}

/* Hover effects for ticker items */
.ticker-item:hover {
    transform: scale(1.02);
    z-index: 10;
    position: relative;
}

/* Enhanced animations */
.ticker-item .group-hover\:text-\[\#2FE6DE\]:hover {
    text-shadow: 0 0 8px rgba(47, 230, 222, 0.5);
}

/* Loading animation */
.loading-ticker {
    background: linear-gradient(-45deg, #2FE6DE, #1A1428, #2FE6DE, #1A1428);
    background-size: 400% 400%;
    animation: loadingGradient 2s ease infinite;
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

@keyframes loadingGradient {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .ticker-item {
        padding: 0 0.25rem;
    }

    .ticker-wrap {
        height: 50px;
    }

    .ticker {
        animation-duration: 35s; /* Faster on mobile */
    }
}

/* Market status indicators */
#market-status {
    transition: color 0.3s ease;
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: .7;
    }
}

/* Smooth transitions for real-time updates */
.ticker-item * {
    transition: all 0.2s ease;
}
</style>
    <!-- Main Header -->
    <header class="bg-[#0D091C] border-b border-[#2FE6DE]/10 shadow-lg sticky top-0 z-40">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('index') }}" class="flex items-center">
                        @if(\App\Helpers\WebsiteSettingsHelper::hasTextLogo())
                            <!-- Text Logo -->
                            <div class="flex items-center mr-3" style="height: 5rem;">
                                <span class="text-white font-extrabold text-3xl tracking-wide">{{ \App\Helpers\WebsiteSettingsHelper::getTextLogo() }}</span>
                            </div>
                        @elseif(\App\Helpers\WebsiteSettingsHelper::hasImageLogo())
                            <!-- Image Logo -->
                            <img src="{{ \App\Helpers\WebsiteSettingsHelper::getLogoUrl() }}" 
                                 alt="{{ \App\Helpers\WebsiteSettingsHelper::getSiteName() }}" 
                                 class="w-auto object-contain mr-3" style="height: 5rem;">
                        @else
                            <!-- Site Name as Logo (fallback) -->
                            <span class="text-2xl font-extrabold text-[#2FE6DE] tracking-wide">{{ \App\Helpers\WebsiteSettingsHelper::getSiteName() }}</span>
                        @endif
                    </a>
                    <nav class="hidden lg:flex ml-10 space-x-6">
                        <a href="{{ route('index') }}" class="navbar-item text-white hover:text-[#2FE6DE] transition-colors py-2 active">Home</a>
                        <a href="{{ route('market') }}" class="navbar-item text-white hover:text-[#2FE6DE] transition-colors py-2 flex items-center ">
                                Markets
                            </a>
                        <div class="dropdown">
                            <a href="#" class="navbar-item text-white hover:text-[#2FE6DE] transition-colors py-2 flex items-center">
                                Trade
                                <i class="fas fa-chevron-down text-xs ml-1"></i>
                            </a>
                            <div class="dropdown-content py-2">
                                <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-[#1A1428] text-white hover:text-[#2FE6DE]">Spot</a>
                                <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-[#1A1428] text-white hover:text-[#2FE6DE]">Margin</a>
                                <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-[#1A1428] text-white hover:text-[#2FE6DE]">Bot Trading</a>
                                <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-[#1A1428] text-white hover:text-[#2FE6DE]">Copy Trading</a>
                                <a href="{{ route('ai-traders.index') }}" class="block px-4 py-2 hover:bg-[#1A1428] text-white hover:text-[#2FE6DE] flex items-center">
                                    <i class="fas fa-robot mr-2"></i>AI Traders
                                </a>
                            </div>
                        </div>
                        <a href="{{ route('about') }}" class="navbar-item text-white hover:text-[#2FE6DE] transition-colors py-2 ">About</a>
                        <a href="{{ route('contact') }}" class="navbar-item text-white hover:text-[#2FE6DE] transition-colors py-2 ">Contact</a>
                    </nav>
                </div>

                <div class="hidden lg:flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="btn-secondary">Login</a>
                    <a href="{{ route('register') }}" class="btn-primary">Sign Up</a>
                </div>

                <!-- Mobile menu button -->
                <div class="lg:hidden flex items-center">
                                        <button id="mobileMenuBtn" class="text-white focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile menu -->
    <div id="mobileMenu" class="mobile-nav lg:hidden">
        <div class="flex justify-between items-center p-4 border-b border-[#2FE6DE]/10">
            <a href="{{ route('index') }}">
                <span class="text-lg font-bold text-[#2FE6DE]">{{ config('app.name') }}</span>
            </a>
            <button id="closeMenuBtn" class="text-white focus:outline-none">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="p-4">
                            <div class="flex space-x-2 mb-6">
                    <a href="{{ route('login') }}" class="btn-secondary flex-1 text-center">Login</a>
                    <a href="{{ route('register') }}" class="btn-primary flex-1 text-center">Sign Up</a>
                </div>

            <nav class="space-y-1">
                <a href="{{ route('index') }}" class="block py-3 px-4 rounded-lg bg-[#1A1428] text-[#2FE6DE]">
                    <i class="fas fa-home mr-2"></i> Home
                </a>
                <a href="{{ route('market') }}" class="block py-3 px-4 rounded-lg text-white">
                    <i class="fas fa-chart-bar mr-2"></i> Markets
                </a>
                <a href="{{ route('ai-traders.index') }}" class="block py-3 px-4 rounded-lg text-white">
                    <i class="fas fa-robot mr-2"></i> AI Traders
                </a>
                <div class="mobile-dropdown">
                    <button class="w-full text-left py-3 px-4 rounded-lg flex items-center justify-between text-white">
                        <span><i class="fas fa-exchange-alt mr-2"></i> Trade</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div class="pl-8 pb-2 hidden">
                        <a href="{{ route('login') }}" class="block py-2 text-gray-400 hover:text-white">Spot</a>
                        <a href="{{ route('login') }}" class="block py-2 text-gray-400 hover:text-white">Margin</a>
                        <a href="{{ route('login') }}" class="block py-2 text-gray-400 hover:text-white">Bot Trading</a>
                        <a href="{{ route('login') }}" class="block py-2 text-gray-400 hover:text-white">Copy Trading</a>
                    </div>
                </div>

                <a href="{{ route('about') }}" class="block py-3 px-4 rounded-lg text-white">
                    <i class="fas fa-info-circle mr-2"></i> About
                </a>

                <a href="{{ route('contact') }}" class="block py-3 px-4 rounded-lg text-white">
                    <i class="fas fa-envelope mr-2"></i> Contact
                </a>
            </nav>

            <div class="mt-8 pt-6 border-t border-[#2FE6DE]/10">
                <div class="flex justify-center space-x-4">
                    <a href="#" class="text-gray-400 hover:text-[#2FE6DE] transition-colors">
                        <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-[#2FE6DE] transition-colors">
                        <i class="fab fa-facebook text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-[#2FE6DE] transition-colors">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-[#2FE6DE] transition-colors">
                        <i class="fab fa-telegram text-xl"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

  @yield('content')

    <footer class="bg-[#0D091C] border-t border-[#2FE6DE]/10 py-10">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
                <div class="lg:col-span-2">
                    <span class="text-2xl font-bold text-[#2FE6DE] mb-4 block">{{ config('app.name') }}</span>
                    <p class="text-gray-400 text-sm mb-4">Advanced trading platform offering stocks, crypto, AI bots, copy trading, and professional tools for traders worldwide.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-white">Products</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-[#2FE6DE] transition-colors">Spot Trading</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-[#2FE6DE] transition-colors">Margin Trading</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-[#2FE6DE] transition-colors">Bot Trading</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-[#2FE6DE] transition-colors">Copy Trading</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-white">Support</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('faq') }}" class="text-gray-400 hover:text-[#2FE6DE] transition-colors">FAQ</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-[#2FE6DE] transition-colors">Contact Us</a></li>
                        <li><a href="{{ route('terms') }}" class="text-gray-400 hover:text-[#2FE6DE] transition-colors">Terms of Service</a></li>
                        <li><a href="{{ route('privacy') }}" class="text-gray-400 hover:text-[#2FE6DE] transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-10 pt-6 border-t border-[#2FE6DE]/10 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm mb-4 md:mb-0">&copy; 2025 {{ config('app.name') }}. All rights reserved.</p>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('terms') }}" class="text-gray-400 hover:text-[#2FE6DE] text-sm transition-colors">Terms</a>
                    <a href="{{ route('privacy') }}" class="text-gray-400 hover:text-[#2FE6DE] text-sm transition-colors">Privacy</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('front/js/crypto-api.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
            // Fetch cryptocurrency data from CoinGecko API
            fetchCryptoData();

            // Refresh data every 60 seconds
            setInterval(fetchCryptoData, 60000);

            // Mobile menu toggle
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const closeMenuBtn = document.getElementById('closeMenuBtn');
            const mobileMenu = document.getElementById('mobileMenu');

            if (mobileMenuBtn && mobileMenu && closeMenuBtn) {
                mobileMenuBtn.addEventListener('click', () => {
                    mobileMenu.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });

                closeMenuBtn.addEventListener('click', () => {
                    mobileMenu.classList.remove('active');
                    document.body.style.overflow = '';
                });
            }

            // Mobile dropdowns
            const mobileDropdowns = document.querySelectorAll('.mobile-dropdown');
            mobileDropdowns.forEach(dropdown => {
                const button = dropdown.querySelector('button');
                const content = dropdown.querySelector('div');

                button.addEventListener('click', () => {
                    content.classList.toggle('hidden');
                    const icon = button.querySelector('.fas.fa-chevron-down');
                    if (icon) {
                        icon.classList.toggle('fa-chevron-down');
                        icon.classList.toggle('fa-chevron-up');
                    }
                });
            });

            // Add locale handling for JavaScript
            window.locale = 'en';
            window.translations = "investment";

        });

        // Live Activity Notification System
        function initializeLiveNotifications() {
            const activities = [
                {
                    type: 'investment',
                    name: 'Michael Johnson',
                    country: 'United States',
                    amount: 25000,
                    icon: 'fas fa-chart-line'
                },
                {
                    type: 'withdrawal',
                    name: 'Sarah Chen',
                    country: 'Singapore',
                    amount: 8500,
                    icon: 'fas fa-money-bill-wave'
                },
                {
                    type: 'deposit',
                    name: 'David Rodriguez',
                    country: 'Spain',
                    amount: 12000,
                    icon: 'fas fa-plus-circle'
                },
                {
                    type: 'investment',
                    name: 'Emma Thompson',
                    country: 'United Kingdom',
                    amount: 45000,
                    icon: 'fas fa-chart-line'
                },
                {
                    type: 'withdrawal',
                    name: 'Alex Petrov',
                    country: 'Russia',
                    amount: 18500,
                    icon: 'fas fa-money-bill-wave'
                },
                {
                    type: 'deposit',
                    name: 'Maria Silva',
                    country: 'Brazil',
                    amount: 9200,
                    icon: 'fas fa-plus-circle'
                },
                {
                    type: 'investment',
                    name: 'James Wilson',
                    country: 'Australia',
                    amount: 33000,
                    icon: 'fas fa-chart-line'
                },
                {
                    type: 'withdrawal',
                    name: 'Fatima Al-Rashid',
                    country: 'UAE',
                    amount: 22000,
                    icon: 'fas fa-money-bill-wave'
                },
                {
                    type: 'deposit',
                    name: 'Pierre Dubois',
                    country: 'France',
                    amount: 15500,
                    icon: 'fas fa-plus-circle'
                },
                {
                    type: 'investment',
                    name: 'Yuki Tanaka',
                    country: 'Japan',
                    amount: 28000,
                    icon: 'fas fa-chart-line'
                },
                {
                    type: 'withdrawal',
                    name: 'Hans Mueller',
                    country: 'Germany',
                    amount: 14200,
                    icon: 'fas fa-money-bill-wave'
                },
                {
                    type: 'deposit',
                    name: 'Priya Sharma',
                    country: 'India',
                    amount: 7800,
                    icon: 'fas fa-plus-circle'
                },
                {
                    type: 'investment',
                    name: 'Carlos Mendoza',
                    country: 'Mexico',
                    amount: 19500,
                    icon: 'fas fa-chart-line'
                },
                {
                    type: 'withdrawal',
                    name: 'Anna Kowalski',
                    country: 'Poland',
                    amount: 11000,
                    icon: 'fas fa-money-bill-wave'
                },
                {
                    type: 'deposit',
                    name: 'Ahmed Hassan',
                    country: 'Egypt',
                    amount: 6500,
                    icon: 'fas fa-plus-circle'
                },
                {
                    type: 'investment',
                    name: 'Jennifer Lee',
                    country: 'South Korea',
                    amount: 38000,
                    icon: 'fas fa-chart-line'
                },
                {
                    type: 'withdrawal',
                    name: 'Roberto Rossi',
                    country: 'Italy',
                    amount: 16800,
                    icon: 'fas fa-money-bill-wave'
                },
                {
                    type: 'deposit',
                    name: 'Olga Petersen',
                    country: 'Norway',
                    amount: 23500,
                    icon: 'fas fa-plus-circle'
                }
            ];

            function showRandomNotification() {
                const activity = activities[Math.floor(Math.random() * activities.length)];
                const now = new Date();

                // Random time between 1-30 minutes ago
                const minutesAgo = Math.floor(Math.random() * 30) + 1;
                const timeAgo = minutesAgo === 1 ? '1 minute ago' : `${minutesAgo} minutes ago`;

                let message, details;

                switch(activity.type) {
                    case 'investment':
                        message = `${activity.name} from ${activity.country} just invested`;
                        details = `Successfully invested ${activity.amount.toLocaleString()} in trading portfolio`;
                        break;
                    case 'withdrawal':
                        message = `${activity.name} from ${activity.country} just withdrew`;
                        details = `Successfully withdrew ${activity.amount.toLocaleString()} to bank account`;
                        break;
                    case 'deposit':
                        message = `${activity.name} from ${activity.country} just deposited`;
                        details = `Successfully deposited ${activity.amount.toLocaleString()} to trading account`;
                        break;
                }

                showNotification(activity.type, message, details, timeAgo, activity.icon);
            }

            // Show first notification after 5 seconds
            setTimeout(showRandomNotification, 5000);

            // Show new notification every 5 seconds
            setInterval(showRandomNotification, 5000);
        }

        function showNotification(type, message, details, time, iconClass) {
            const notification = document.getElementById('live-notification');
            const icon = document.getElementById('notification-icon');
            const messageEl = document.getElementById('notification-message');
            const detailsEl = document.getElementById('notification-details');
            const timeEl = document.getElementById('notification-time');

            if (!notification) return;

            // Set content
            messageEl.textContent = message;
            detailsEl.textContent = details;
            timeEl.textContent = time;

            // Update icon
            icon.innerHTML = `<i class="${iconClass}"></i>`;

            // Update styling based on type
            notification.className = `live-notification ${type}`;
            icon.className = `notification-icon ${type}`;

            // Show notification
            notification.style.display = 'block';

            // Auto hide after 4 seconds (to avoid overlap with next notification)
            setTimeout(() => {
                hideNotification();
            }, 4000);
        }

        function hideNotification() {
            const notification = document.getElementById('live-notification');
            if (notification) {
                notification.classList.add('notification-hide');
                setTimeout(() => {
                    notification.style.display = 'none';
                    notification.classList.remove('notification-hide');
                }, 300);
            }
        }

        function closeNotification() {
            hideNotification();
        }

        async function fetchCryptoData() {
    try {
        // Fetch data for the top cryptocurrencies
        const response = await fetch('https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&amp;order=market_cap_desc&amp;per_page=20&amp;page=1&amp;sparkline=false&amp;price_change_percentage=24h');

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();

        // Update the ticker
        updateTicker(data);

        // Update the market overview table and cards
        updateMarketTable(data);
        updateMarketCards(data);

    } catch (error) {
        console.error('Error fetching crypto data:', error);

        // Show error message in table
        const marketTable = document.querySelector('#crypto-market-table');
        if (marketTable) {
            marketTable.innerHTML = `
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-red-400">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        Unable to load market data. Please try again later.
                    </td>
                </tr>
            `;
        }

        // Show error message in cards
        const marketCards = document.querySelector('#crypto-market-cards');
        if (marketCards) {
            marketCards.innerHTML = `
                <div class="bg-[#1A1428] rounded-xl p-4 border border-[#2FE6DE]/10">
                    <div class="flex justify-center items-center py-8 text-red-400">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>Unable to load market data. Please try again later.</span>
                    </div>
                </div>
            `;
        }
    }
}

function updateTicker(data) {
    const tickerContainer = document.getElementById('crypto-ticker');
    if (!tickerContainer) return;

    let tickerHTML = '';

    data.forEach(coin => {
        const priceChangeClass = coin.price_change_percentage_24h >= 0 ? 'text-green-500' : 'text-red-500';
        const priceChangeIcon = coin.price_change_percentage_24h >= 0 ? 'fa-caret-up' : 'fa-caret-down';

        tickerHTML += `
            <div class="ticker-item flex items-center mr-8">
                <img src="${coin.image}" alt="${coin.symbol.toUpperCase()}" class="w-5 h-5 mr-2">
                <span class="font-medium mr-1">${coin.symbol.toUpperCase()}</span>
                <span>${formatCurrency(coin.current_price)}</span>
                <span class="${priceChangeClass} ml-2">
                    <i class="fas ${priceChangeIcon} mr-1"></i>${formatPercentage(coin.price_change_percentage_24h)}
                </span>
            </div>
        `;
    });

    tickerContainer.innerHTML = tickerHTML;
}
function updateMarketTable(data) {
    const tableBody = document.querySelector('#crypto-market-table');
    if (!tableBody) return;

    let tableHTML = '';

    // Generate table rows for each cryptocurrency
    data.slice(0, 3).forEach((coin, index) => {
        const priceChangeClass = coin.price_change_percentage_24h >= 0 ? 'text-green-500' : 'text-red-500';
        const priceChangeBgClass = coin.price_change_percentage_24h >= 0 ? 'bg-green-500/10' : 'bg-red-500/10';

        tableHTML += `
            <tr class="hover:bg-[#2FE6DE]/5 transition-colors">
                <td class="px-6 py-4 text-gray-400">${index + 1}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <img src="${coin.image}" alt="${coin.symbol.toUpperCase()}" class="w-8 h-8 mr-3">
                        <div>
                            <div class="font-medium">${coin.name}</div>
                            <div class="text-gray-400 text-sm">${coin.symbol.toUpperCase()}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-right font-medium">${formatCurrency(coin.current_price)}</td>
                <td class="px-6 py-4 text-right">
                    <span class="px-2 py-1 ${priceChangeBgClass} ${priceChangeClass} rounded-md">${formatPercentage(coin.price_change_percentage_24h)}</span>
                </td>
                <td class="px-6 py-4 text-right text-gray-300">${formatVolume(coin.total_volume)}</td>
                <td class="px-6 py-4 text-right text-gray-300">${formatVolume(coin.market_cap)}</td>
                <td class="px-6 py-4 text-right">
                    <a href="#" class="px-4 py-1 bg-[#2FE6DE]/10 text-[#2FE6DE] rounded-lg hover:bg-[#2FE6DE]/20 transition-colors text-sm">Trade</a>
                </td>
            </tr>
        `;
    });

    tableBody.innerHTML = tableHTML;
}

function updateMarketCards(data) {
    const cardsContainer = document.querySelector('#crypto-market-cards');
    if (!cardsContainer) return;

    let cardsHTML = '';

    // Generate card for each cryptocurrency
    data.slice(0, 3).forEach((coin, index) => {
        const priceChangeClass = coin.price_change_percentage_24h >= 0 ? 'text-green-500' : 'text-red-500';

        cardsHTML += `
            <div class="bg-[#1A1428] rounded-xl p-4 border border-[#2FE6DE]/10 hover:border-[#2FE6DE]/30 transition-all">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center">
                        <div class="w-10 h-10 mr-3 flex-shrink-0">
                            <img src="${coin.image}" alt="${coin.symbol.toUpperCase()}" class="w-full h-full">
                        </div>
                        <div>
                            <div class="font-medium">${coin.name}</div>
                            <div class="text-gray-400 text-xs">${coin.symbol.toUpperCase()}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-medium">${formatCurrency(coin.current_price)}</div>
                        <div class="${priceChangeClass} text-sm">${formatPercentage(coin.price_change_percentage_24h)}</div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 mb-3">
                    <div class="bg-[#0D091C]/50 rounded-lg p-2">
                        <div class="text-xs text-gray-400">24h Volume</div>
                        <div class="text-sm font-medium">${formatVolume(coin.total_volume)}</div>
                    </div>
                    <div class="bg-[#0D091C]/50 rounded-lg p-2">
                        <div class="text-xs text-gray-400">Market Cap</div>
                        <div class="text-sm font-medium">${formatVolume(coin.market_cap)}</div>
                    </div>
                </div>

                <a href="#" class="block w-full py-2 text-center bg-[#2FE6DE]/10 text-[#2FE6DE] rounded-lg hover:bg-[#2FE6DE]/20 transition-colors text-sm">Trade</a>
            </div>
        `;
    });

    cardsContainer.innerHTML = cardsHTML;
}

// Helper functions for formatting
function formatCurrency(value) {
    // Format based on value size
    if (value >= 1000) {
        return '$' + value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    } else if (value >= 1) {
        return '$' + value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 4 });
    } else {
        return '$' + value.toLocaleString('en-US', { minimumFractionDigits: 4, maximumFractionDigits: 6 });
    }
}

function formatPercentage(value) {
    const sign = value >= 0 ? '+' : '';
    return `${sign}${value.toFixed(2)}%`;
}

function formatVolume(value) {
    if (value >= 1e9) {
        return '$' + (value / 1e9).toFixed(1) + 'B';
    } else if (value >= 1e6) {
        return '$' + (value / 1e6).toFixed(1) + 'M';
    } else if (value >= 1e3) {
        return '$' + (value / 1e3).toFixed(1) + 'K';
    } else {
        return '$' + value.toFixed(0);
    }
}
</script>
    <!-- TradingView Widget Script -->
<script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>

<style>
/* Ticker Animation */
.ticker-wrap {
    width: 100%;
    overflow: hidden;
    height: 40px;
    padding: 0;
    box-sizing: border-box;
}

.ticker {
    display: flex;
    white-space: nowrap;
    padding-right: 100%;
    animation-iteration-count: infinite;
    animation-timing-function: linear;
    animation-name: ticker;
    animation-duration: 30s;
}

.ticker-item {
    display: inline-flex;
    align-items: center;
    padding: 0 1rem;
}

@keyframes ticker {
    0% {
        transform: translate3d(0, 0, 0);
    }
    100% {
        transform: translate3d(-100%, 0, 0);
    }
}

/* Button Styles */
.btn-primary {
    display: inline-block;
    background-color: rgba(47, 230, 222, 0.1);
    color: #2FE6DE;
    border-radius: 0.5rem;
    transition: all 0.2s;
}

.btn-primary:hover {
    background-color: rgba(47, 230, 222, 0.2);
}

.btn-secondary {
    display: inline-block;
    background-color: rgba(47, 230, 222, 0.1);
    color: #2FE6DE;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    border: 1px solid rgba(47, 230, 222, 0.3);
    transition: all 0.2s;
}

.btn-secondary:hover {
    background-color: rgba(47, 230, 222, 0.2);
    border-color: rgba(47, 230, 222, 0.5);
}

/* Animation */
@keyframes float {
    0% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
    100% {
        transform: translateY(0px);
    }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

/* Line clamp for text truncation */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
    <!-- Livewire Scripts -->
<script src="livewire/livewire5dd3.js?id=cc800bf4"   data-csrf="QHTgDfeSDEhGixs61ktyfaAnqYfyNU0Xv8qcvRbs" data-update-uri="/livewire/update" data-navigate-once="true"></script>


</body>

<!-- Mirrored from www.blackrockinvestmentcorporation.com/ by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Aug 2025 18:11:14 GMT -->
</html>
