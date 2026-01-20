<!-- Footer Menu -->
<div class="fixed bottom-0 left-0 right-0 bg-gray-800 border-t border-gray-700 z-50">
    <div class="flex justify-around">
        <a href="{{ route('user.portfolio.trade') }}" class="flex flex-col items-center py-3 px-4 {{ request()->routeIs('user.portfolio.trade') ? 'text-blue-400' : 'text-gray-400' }} hover:text-white">
            <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-xs">Plans</span>
        </a>
        <a href="{{ route('user.portfolio.staking') }}" class="flex flex-col items-center py-3 px-4 {{ request()->routeIs('user.portfolio.staking') ? 'text-blue-400' : 'text-gray-400' }} hover:text-white">
            <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-xs">Staking</span>
        </a>
        <a href="{{ route('user.portfolio.mining') }}" class="flex flex-col items-center py-3 px-4 {{ request()->routeIs('user.portfolio.mining') ? 'text-blue-400' : 'text-gray-400' }} hover:text-white">
            <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-xs">Mining</span>
        </a>
        <a href="{{ route('user.portfolio.holding') }}" class="flex flex-col items-center py-3 px-4 {{ request()->routeIs('user.portfolio.holding') ? 'text-blue-400' : 'text-gray-400' }} hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="w-6 h-6 mb-1" fill="currentColor">
                <path d="M12.753 4a.752.752 0 0 0-1.505 0v.464h-.051c-1.05 0-1.66.795-1.781 1.56c-.121.755.2 1.7 1.165 2.048l2.362.851c.082.03.113.07.129.1c.02.04.033.1.02.17a.27.27 0 0 1-.076.152c-.025.023-.069.051-.162.051h-1.657a.324.324 0 0 1-.32-.328a.752.752 0 0 0-1.504 0c0 1.006.81 1.833 1.824 1.833h.076v.599a.752.752 0 0 0 1.505 0v-.599h.076c1.964 0 2.426-2.735.599-3.394l-2.361-.85a.26.26 0 0 1-.157-.133a.44.44 0 0 1-.034-.263a.4.4 0 0 1 .107-.225c.036-.036.089-.067.189-.067h1.607c.17 0 .319.14.319.328a.752.752 0 0 0 1.505 0c0-1.006-.81-1.833-1.824-1.833h-.051zm-3.32 8.998c-.863 0-1.522.206-2.063.544c-.5.312-.865.72-1.157 1.046l-.031.035c-.316.352-.559.614-.873.806c-.292.179-.685.319-1.309.319a.75.75 0 0 0-.752.752V20c0 .416.337.752.752.752h9.502c1.744 0 3.426-.94 4.603-1.784a16 16 0 0 0 1.983-1.695l.004-.004c.364-.3.59-.688.647-1.117c.058-.436-.07-.85-.3-1.17a1.7 1.7 0 0 0-2.335-.42q-.296.21-.579.416c-.571.414-1.122.814-1.713 1.149c-.77.437-1.523.712-2.31.712H10.5c-.2 0-.283-.062-.312-.092a.2.2 0 0 1-.06-.151a.2.2 0 0 1 .06-.152c.03-.03.112-.092.312-.092h2.002c.533 0 1.006-.167 1.354-.489a1.616 1.616 0 0 0 0-2.376c-.348-.322-.821-.49-1.354-.49z"/>
            </svg>
            <span class="text-xs">Holding</span>
        </a>
        <a href="{{ route('user.portfolio.signal') }}" class="flex flex-col items-center py-3 px-4 {{ request()->routeIs('user.portfolio.signal') ? 'text-blue-400' : 'text-gray-400' }} hover:text-white">
            <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-xs">Signal</span>
        </a>
    </div>
</div>

<!-- Add bottom padding for footer -->
<div class="pb-20"></div>
