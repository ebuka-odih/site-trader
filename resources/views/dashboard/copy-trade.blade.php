@extends('dashboard.layout.app')

@section('content')
<div class="space-y-6">


    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Copy Trading</h1>
        </div>
    </div>


    <!-- Copy Trading Modal -->
    <div id="copyTradingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-gray-800 rounded-lg border border-gray-700 max-w-md w-full mx-4">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-700">
                <h3 id="modalTitle" class="text-lg font-semibold text-white">Copy Trading</h3>
                <button id="closeModal" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <div id="modalContent" class="flex items-start space-x-3">
                    <!-- Icon will be inserted here -->
                    <div id="modalIcon" class="flex-shrink-0">
                        <!-- Icon will be dynamically inserted -->
                    </div>
                    <div class="flex-1">
                        <p id="modalMessage" class="text-gray-300"></p>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="flex justify-end space-x-3 p-6 border-t border-gray-700">
                <button id="modalCloseBtn" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="border-b border-gray-700">
        <nav class="-mb-px flex space-x-8">
            <button id="tradersTab" class="tab-button active py-2 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-400">
                Available Traders
            </button>
            <button id="historyTab" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-400 hover:text-gray-300">
                My History
            </button>
        </nav>
    </div>

    <!-- Available Traders Section -->
    <div id="tradersSection" class="tab-content">
    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-white">Available Traders</h2>
            <div class="text-sm text-gray-400">{{ count($traders) }} traders available</div>
            </div>

        <!-- Search Box -->
        <div class="mb-6">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" 
                       id="traderSearch" 
                       placeholder="Search traders by name..." 
                       class="block w-full pl-10 pr-3 py-2 border border-gray-600 rounded-lg bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($traders as $trader)
            <div class="bg-gray-700 rounded-lg border border-gray-600 hover:border-blue-500 transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/20">
                <!-- Trader Header -->
                <div class="p-6 border-b border-gray-600">
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <img src="{{ $trader->avatar_url }}" alt="{{ $trader->name }}" 
                                 class="w-16 h-16 rounded-full object-cover border-2 border-gray-600"
                                 onerror="this.src='{{ asset('img/trader.jpg') }}'">
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-gray-700"></div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-white">{{ $trader->name }}</h3>
                            <p class="text-sm text-gray-400">Professional Trader</p>
                        </div>
                    </div>
                </div>

                <!-- Trader Stats -->
                <div class="p-6 space-y-4">
                    <!-- Win Rate -->
                    @php
                        $barPercent = min(100, max(0, (int)($trader->win_rate ?? 0)));
                    @endphp
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-400">Win Rate</span>
                        <div class="flex items-center space-x-2">
                            <div class="w-24 md:w-28 bg-gray-600 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-green-500 h-1.5" style="width: {{ $barPercent }}%"></div>
                            </div>
                            <span class="text-sm font-semibold text-white">{{ $trader->win_rate }}%</span>
                        </div>
                    </div>

                    <!-- Performance Stats -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-3 bg-gray-600 rounded-lg">
                            <div class="text-lg font-bold text-green-400">{{ $trader->win }}</div>
                            <div class="text-xs text-gray-400">Wins</div>
                        </div>
                        <div class="text-center p-3 bg-gray-600 rounded-lg">
                            <div class="text-lg font-bold text-red-400">{{ $trader->loss }}</div>
                            <div class="text-xs text-gray-400">Losses</div>
                        </div>
                    </div>

                    <!-- Profit Share -->
                    <div class="flex items-center justify-between p-3 bg-blue-900/20 border border-blue-700/30 rounded-lg">
                        <span class="text-sm text-gray-400">Profit Share</span>
                        <span class="text-sm font-semibold text-blue-400">{{ number_format($trader->profit_share, 1) }}%</span>
                    </div>

                    <!-- Minimum Amount -->
                    <div class="text-center p-3 bg-gray-600 rounded-lg">
                        <div class="text-sm text-gray-400">Minimum Investment</div>
                        <div class="text-lg font-bold text-white">${{ number_format($trader->amount, 2) }}</div>
                    </div>

                    <!-- Copy Trade Button -->
                    @if(in_array($trader->id, $stoppedCopyTrades))
                        <!-- Stopped - Cannot Copy -->
                        <div class="pt-2">
                            <button disabled 
                                    class="w-full bg-gray-600 text-gray-400 font-semibold py-3 px-4 rounded-lg cursor-not-allowed flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                </svg>
                                <span>Stopped</span>
                            </button>
                            <p class="text-xs text-gray-500 mt-2 text-center">Cannot copy this trader</p>
                        </div>
                    @else
                        <!-- Normal Copy Trade Button -->
                        <form action="{{ route('user.copyTrading.store') }}" method="POST" class="pt-2 copy-trade-form" data-trader="{{ $trader->id }}">
                            @csrf
                            <input type="hidden" name="trader_id" value="{{ $trader->id }}">
                            <input type="hidden" name="amount" value="{{ $trader->amount }}">
                            
                            <button type="submit" 
                                    class="copy-trade-btn w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2">
                                <!-- Default state -->
                                <svg class="copy-trade-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <span class="copy-trade-text">Copy Trade</span>
                                
                                <!-- Loading state (hidden by default) -->
                                <svg class="copy-trade-spinner hidden w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="copy-trade-loading hidden">Processing...</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        </div>
    </div>

    <!-- My Copied Trades Section -->
    <div id="historySection" class="tab-content hidden">
    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-white">My Copied Trades</h2>
            <div class="text-sm text-gray-400">{{ count($copiedTrades) }} active trades</div>
        </div>

        @if(count($copiedTrades) > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-400">Trader</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-400">Amount</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-400">Trade Count</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-400">Win/Loss</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-400">PnL</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-400">Date Started</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-400">Status</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-400">Stopped At</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($copiedTrades as $trade)
                    <tr class="border-b border-gray-700 hover:bg-gray-700/50 transition-colors">
                        <td class="py-4 px-4">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $trade->copy_trader?->avatar_url ?? asset('img/trader.jpg') }}" alt="" 
                                     class="w-8 h-8 rounded-full object-cover"
                                     onerror="this.src='{{ asset('img/trader.jpg') }}'">
                                <span class="text-white">{{ $trade->copy_trader?->name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-white">${{ number_format($trade->amount, 2) }}</td>
                        <td class="py-4 px-4 text-white">{{ $trade->trade_count ?? 0 }}</td>
                        <td class="py-4 px-4 text-white">
                            <span class="text-green-400">{{ $trade->win ?? 0 }}</span> / 
                            <span class="text-red-400">{{ $trade->loss ?? 0 }}</span>
                        </td>
                        <td class="py-4 px-4 text-white">
                            <span class="{{ ($trade->pnl ?? 0) >= 0 ? 'text-green-400' : 'text-red-400' }}">
                                ${{ number_format($trade->pnl ?? 0, 2) }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-gray-400">{{ date('M d, Y', strtotime($trade->created_at)) }}</td>
                        <td class="py-4 px-4">
                            @if($trade->status == 0 && $trade->stopped_at)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900 text-red-200">
                                    Stopped
                                </span>
                            @elseif($trade->status == 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-900 text-yellow-200">
                                    Pending
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-200">
                                    Active
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-gray-400">
                            @if($trade->stopped_at)
                                {{ date('M d, Y H:i', strtotime($trade->stopped_at)) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            <a href="{{ route('user.copyTrading.detail', $trade->id) }}" 
                               class="text-blue-400 hover:text-blue-300 text-sm font-medium">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-300 mb-2">No Copied Trades Yet</h3>
            <p class="text-gray-500">Start copying trades from our professional traders above</p>
        </div>
        @endif
    </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // Tab functionality
        const tradersTab = document.getElementById('tradersTab');
        const historyTab = document.getElementById('historyTab');
        const tradersSection = document.getElementById('tradersSection');
        const historySection = document.getElementById('historySection');

        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'border-blue-500', 'text-blue-400');
                button.classList.add('border-transparent', 'text-gray-400');
            });

            // Show selected tab content
            if (tabName === 'traders') {
                tradersSection.classList.remove('hidden');
                tradersTab.classList.add('active', 'border-blue-500', 'text-blue-400');
                tradersTab.classList.remove('border-transparent', 'text-gray-400');
            } else if (tabName === 'history') {
                historySection.classList.remove('hidden');
                historyTab.classList.add('active', 'border-blue-500', 'text-blue-400');
                historyTab.classList.remove('border-transparent', 'text-gray-400');
            }
        }

        // Add click event listeners to tabs
        tradersTab.addEventListener('click', () => {
            showTab('traders');
            // Update URL without page reload
            const url = new URL(window.location);
            url.searchParams.set('tab', 'traders');
            window.history.pushState({}, '', url);
        });
        historyTab.addEventListener('click', () => {
            showTab('history');
            // Update URL without page reload
            const url = new URL(window.location);
            url.searchParams.set('tab', 'history');
            window.history.pushState({}, '', url);
        });

        // Check URL parameters for initial tab
        const urlParams = new URLSearchParams(window.location.search);
        const initialTab = urlParams.get('tab');
        if (initialTab === 'history') {
            showTab('history');
        } else {
            showTab('traders'); // Default to traders tab
        }


        // Modal functionality
        const modal = document.getElementById('copyTradingModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalMessage = document.getElementById('modalMessage');
        const modalIcon = document.getElementById('modalIcon');
        const closeModal = document.getElementById('closeModal');
        const modalCloseBtn = document.getElementById('modalCloseBtn');

        function showModal(type, message) {
            modalTitle.textContent = 'Copy Trading';
            modalMessage.textContent = message;
            
            // Clear previous icon
            modalIcon.innerHTML = '';
            
            if (type === 'success') {
                modalTitle.textContent = 'Success!';
                modalIcon.innerHTML = `
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                `;
            } else if (type === 'warning') {
                modalTitle.textContent = 'Already Copying';
                modalIcon.innerHTML = `
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                `;
            } else if (type === 'error') {
                modalTitle.textContent = 'Error';
                modalIcon.innerHTML = `
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                `;
            }
            
            modal.classList.remove('hidden');
        }

        function hideModal() {
            modal.classList.add('hidden');
        }

        // Close modal events
        closeModal.addEventListener('click', hideModal);
        modalCloseBtn.addEventListener('click', hideModal);
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                hideModal();
            }
        });

        // Escape key to close modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                hideModal();
            }
        });

        // Function to reset copy trade button state
        function resetCopyTradeButton(form) {
            const submitBtn = form.querySelector('.copy-trade-btn');
            const icon = form.querySelector('.copy-trade-icon');
            const text = form.querySelector('.copy-trade-text');
            const spinner = form.querySelector('.copy-trade-spinner');
            const loadingText = form.querySelector('.copy-trade-loading');
            
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
            icon.classList.remove('hidden');
            text.classList.remove('hidden');
            spinner.classList.add('hidden');
            loadingText.classList.add('hidden');
        }

        // Copy trade form submission with loading state
        const copyTradeForms = document.querySelectorAll('.copy-trade-form');
        
        copyTradeForms.forEach(function(form, index){
            
            form.addEventListener('submit', function(e){
                e.preventDefault(); // Prevent default form submission
                
                const traderId = form.getAttribute('data-trader');
                const amount = form.querySelector('input[name="amount"]').value;
                const submitBtn = form.querySelector('.copy-trade-btn');
                const icon = form.querySelector('.copy-trade-icon');
                const text = form.querySelector('.copy-trade-text');
                const spinner = form.querySelector('.copy-trade-spinner');
                const loadingText = form.querySelector('.copy-trade-loading');
                
                
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                icon.classList.add('hidden');
                text.classList.add('hidden');
                spinner.classList.remove('hidden');
                loadingText.classList.remove('hidden');
                
                // Prepare form data
                const formData = new FormData(form);
                
                // Submit via AJAX
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Reset button state
                    resetCopyTradeButton(form);
                    
                    // Show appropriate modal
                    if (data.success) {
                        showModal('success', data.message);
                        // Refresh the page after 2 seconds to update the UI
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else if (data.warning) {
                        showModal('warning', data.message);
                    } else if (data.error) {
                        showModal('error', data.message);
                    } else {
                        showModal('error', 'An unexpected error occurred. Please try again.');
                    }
                })
                .catch(error => {
                    resetCopyTradeButton(form);
                    showModal('error', 'An error occurred while processing your request. Please try again.');
                });
            });
        });

        // Trader Search Functionality
        const searchInput = document.getElementById('traderSearch');
        const traderCards = document.querySelectorAll('.bg-gray-700.rounded-lg.border.border-gray-600');
        const traderCountElement = document.querySelector('.text-sm.text-gray-400');

        if (searchInput && traderCards.length > 0) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                let visibleCount = 0;
                
                traderCards.forEach(function(card) {
                    const traderNameElement = card.querySelector('h3');
                    if (traderNameElement) {
                        const traderName = traderNameElement.textContent.toLowerCase();
                        
                        if (searchTerm === '' || traderName.includes(searchTerm)) {
                            card.style.display = 'block';
                            visibleCount++;
                        } else {
                            card.style.display = 'none';
                        }
                    }
                });

                // Update trader count
                if (traderCountElement) {
                    traderCountElement.textContent = `${visibleCount} traders available`;
                }

                // Show "no results" message if no traders match
                const noResultsMessage = document.getElementById('noResultsMessage');
                if (visibleCount === 0 && searchTerm !== '') {
                    if (!noResultsMessage) {
                        const gridContainer = traderCards[0].parentElement;
                        const messageDiv = document.createElement('div');
                        messageDiv.id = 'noResultsMessage';
                        messageDiv.className = 'col-span-full text-center py-12';
                        messageDiv.innerHTML = `
                            <div class="w-16 h-16 bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-300 mb-2">No traders found</h3>
                            <p class="text-gray-500">Try adjusting your search terms</p>
                        `;
                        gridContainer.appendChild(messageDiv);
                    }
                } else if (noResultsMessage) {
                    noResultsMessage.remove();
                }
            });

            // Clear search functionality
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    this.value = '';
                    this.dispatchEvent(new Event('input'));
                }
            });
        }
    });
</script>
@endpush

<!-- Include Overview Footer -->
@include('dashboard.overview.partials.footer-menu')

@endsection
