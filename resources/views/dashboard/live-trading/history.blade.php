@extends("dashboard.new-layout")

@section("title", "Trading History")

@section("content")
<div class="min-h-screen bg-gray-900 text-white">
    <div class="bg-gray-800 border-b border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route("user.liveTrading.index") }}" class="text-gray-400 hover:text-white mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <h1 class="text-xl font-semibold">Trading History</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-6">
            <nav class="flex space-x-8 border-b border-gray-700">
                <button type="button" class="history-tab active border-b-2 border-blue-500 py-4 px-1 text-sm font-medium text-blue-400" data-tab="open">
                    Open Trades
                    <span class="ml-2 bg-blue-600 text-white text-xs px-2 py-1 rounded-full">{{ $openTrades->count() }}</span>
                </button>
                <button type="button" class="history-tab border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-400 hover:text-gray-300 hover:border-gray-300" data-tab="closed">
                    Closed Trades
                    <span class="ml-2 bg-gray-600 text-white text-xs px-2 py-1 rounded-full">{{ $closedTrades->count() }}</span>
                </button>
            </nav>
        </div>

        <div id="open-content" class="history-content active">
            @if($openTrades->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Asset</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Type</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Side</th>
                                <th class="text-right py-3 px-4 text-sm font-medium text-gray-400">Amount</th>
                                <th class="text-right py-3 px-4 text-sm font-medium text-gray-400">Leverage</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Date</th>
                                <th class="text-center py-3 px-4 text-sm font-medium text-gray-400">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($openTrades as $trade)
                            <tr class="border-b border-gray-700 hover:bg-gray-700/50 transition-colors">
                                <td class="py-4 px-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">{{ substr($trade->symbol, 0, 2) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-white font-medium">{{ $trade->symbol }}</div>
                                            <div class="text-gray-400 text-sm">{{ ucfirst($trade->asset_type) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">Market</td>
                                <td class="py-4 px-4">Buy</td>
                                <td class="text-right py-4 px-4">$100.00</td>
                                <td class="text-right py-4 px-4">1x</td>
                                <td class="py-4 px-4">Today</td>
                                <td class="text-center py-4 px-4">
                                    <button class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded">Cancel</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <h3 class="text-lg font-medium text-gray-300 mb-2">No Open Trades</h3>
                    <p class="text-gray-400">You do not have any open trades at the moment.</p>
                </div>
            @endif
        </div>

        <div id="closed-content" class="history-content hidden">
            <div class="text-center py-12">
                <h3 class="text-lg font-medium text-gray-300 mb-2">Closed Trades</h3>
                <p class="text-gray-400">Closed trades will appear here.</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const tabs = document.querySelectorAll(".history-tab");
    const contents = document.querySelectorAll(".history-content");
    
    tabs.forEach(tab => {
        tab.addEventListener("click", function() {
            const targetTab = this.getAttribute("data-tab");
            
            tabs.forEach(t => t.classList.remove("active", "border-blue-500", "text-blue-400"));
            this.classList.add("active", "border-blue-500", "text-blue-400");
            
            contents.forEach(content => content.classList.add("hidden"));
            document.getElementById(targetTab + "-content").classList.remove("hidden");
        });
    });
});
</script>

@include("components.trading-footer")

@endsection
