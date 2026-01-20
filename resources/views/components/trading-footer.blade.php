<!-- Trading Footer Menu -->
<div class="fixed bottom-0 left-0 right-0 bg-gray-900 border-t border-gray-700 z-50">
    <div class="flex justify-around items-center py-2">
        <!-- Asset Tab -->
        <a href="{{ route('user.liveTrading.index') }}" 
           class="flex flex-col items-center px-3 py-1.5 rounded-lg transition-colors {{ request()->routeIs('user.liveTrading.index') ? 'text-blue-400 bg-blue-900/20' : 'text-gray-400 hover:text-gray-300' }}">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
            </svg>
            <span class="text-xs font-medium">Assets</span>
        </a>

        <!-- Trade Tab -->
        @php
            $onTradeRoute = request()->routeIs('user.liveTrading.trade', 'user.advancedTrading.trade');
        @endphp
        <a href="{{ $onTradeRoute ? '#' : route('user.liveTrading.index') }}" 
           class="flex flex-col items-center px-3 py-1.5 rounded-lg transition-colors {{ $onTradeRoute ? 'text-blue-400 bg-blue-900/20' : 'text-gray-400 hover:text-gray-300' }}">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <span class="text-xs font-medium">Trade</span>
        </a>

        <!-- History Tab -->
        <a href="{{ route('user.liveTrading.history') }}" 
           class="flex flex-col items-center px-3 py-1.5 rounded-lg transition-colors {{ request()->routeIs('user.liveTrading.history') ? 'text-blue-400 bg-blue-900/20' : 'text-gray-400 hover:text-gray-300' }}">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-xs font-medium">History</span>
        </a>
    </div>
</div>

<!-- Spacer to prevent content from being hidden behind fixed footer -->
<div class="h-16"></div>
