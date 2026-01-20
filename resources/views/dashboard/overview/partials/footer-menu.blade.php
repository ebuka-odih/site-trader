<!-- Footer Menu -->
<div class="fixed bottom-0 left-0 right-0 bg-gray-800 border-t border-gray-700 z-50">
    <div class="flex justify-around">
        <a href="{{ route('user.overview.index') }}" class="flex flex-col items-center py-3 px-4 {{ request()->routeIs('user.overview.index') ? 'text-blue-400' : 'text-gray-400' }} hover:text-white">
            <svg class="w-5 h-5 mb-1" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"></path>
            </svg>
            <span class="text-xs">Overview</span>
        </a>
        <a href="{{ route('user.liveTrading.index') }}" class="flex flex-col items-center py-3 px-4 {{ request()->routeIs('user.liveTrading.index') ? 'text-blue-400' : 'text-gray-400' }} hover:text-white">
            <svg class="w-5 h-5 mb-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-xs">Live Trade</span>
        </a>
        <a href="{{ route('user.copyTrading.index') }}" class="flex flex-col items-center py-3 px-4 {{ request()->routeIs('user.copyTrading.index') ? 'text-blue-400' : 'text-gray-400' }} hover:text-white">
            <svg class="w-5 h-5 mb-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
            </svg>
            <span class="text-xs">Copy Trade</span>
        </a>
        <a href="{{ route('user.botTrading.index') }}" class="flex flex-col items-center py-3 px-4 {{ request()->routeIs('user.botTrading.index') ? 'text-blue-400' : 'text-gray-400' }} hover:text-white">
            <svg class="w-5 h-5 mb-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-xs">Bot Trade</span>
        </a>


    </div>
</div>

<!-- Add bottom padding for footer -->
<div class="pb-20"></div>
