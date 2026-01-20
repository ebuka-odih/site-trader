@extends('dashboard.layout.app')

@section('content')
<div class="space-y-8">
    <!-- Trading Balance Card -->
    <div class="max-w-md mx-auto">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-6 border border-blue-500 shadow-lg">
            <div class="text-center">
                <h3 class="text-lg font-semibold text-white mb-2">Your Trading Balance</h3>
                <div class="text-3xl font-bold text-white mb-1">${{ number_format($user->trading_balance ?? 0, 2) }}</div>
                <p class="text-blue-100 text-sm">Available for trading</p>
            </div>
        </div>
    </div>

    <!-- Page Header -->
    <div class="text-center">
        <h1 class="text-4xl font-bold text-white mb-2">Trading Plans</h1>
        <p class="text-xl text-gray-400">Choose your preferred trading plan</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Trading Plans Grid -->
    @if($tradingPlans->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
            @foreach($tradingPlans as $plan)
                
                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-blue-500 transition-all duration-300 transform hover:scale-105">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-white mb-2">{{ $plan->name }}</h3>
                        <div class="text-3xl font-bold text-blue-400 mb-1">{{ $plan->currency }} {{ number_format($plan->min_funding, 2) }}</div>
                        @if($plan->hasUnlimitedFunding())
                            <div class="text-sm text-gray-400">- Unlimited</div>
                        @elseif($plan->max_funding && $plan->max_funding > $plan->min_funding)
                            <div class="text-sm text-gray-400">- {{ $plan->currency }} {{ number_format($plan->max_funding, 2) }}</div>
                        @endif
                        @if($plan->duration)
                            <div class="text-sm text-gray-400 mt-1">{{ $plan->duration }} days</div>
                        @endif
                    </div>
                    
                    <div class="space-y-4 mb-8">
                        @if($plan->pairs)
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-300">{{ $plan->pairs }} Pairs</span>
                            </div>
                        @endif
                        
                        @if($plan->leverage)
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-300">Leverage up to 1:{{ number_format($plan->leverage) }}</span>
                            </div>
                        @endif
                        
                        @if($plan->spreads)
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-300">Spreads from {{ $plan->spreads }} pips</span>
                            </div>
                        @endif
                        
                        @if($plan->swap_fees)
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-300">Swap Fees: {{ $plan->swap_fees }}%</span>
                            </div>
                        @endif
                        
                        
                        
                        @if($plan->max_lot_size)
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-300">Max Lot Size: {{ $plan->max_lot_size }}</span>
                            </div>
                        @endif
                        
                        @if($plan->features)
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-300">{{ $plan->features }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <button 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 transform hover:scale-105"
                        onclick="subscribeToPlan({{ $plan->id }}, '{{ $plan->name }}', {{ $plan->min_funding }}, '{{ $plan->currency }}')"
                    >
                        SUBSCRIBE NOW
                    </button>
                </div>
            @endforeach
        </div>
    @else
        <!-- No Plans Available -->
        <div class="max-w-2xl mx-auto text-center">
            <div class="bg-gray-800 rounded-xl p-8 border border-gray-700">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-white mb-2">No Trading Plans Available</h3>
                <p class="text-gray-400">Currently there are no active trading plans. Please check back later or contact support for more information.</p>
            </div>
        </div>
    @endif

    <!-- Additional Information -->
    <div class="max-w-4xl mx-auto mt-12">
        <div class="bg-gray-800 rounded-xl p-8 border border-gray-700">
            <h2 class="text-2xl font-bold text-white mb-6 text-center">Trading Plan Benefits</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white">Professional Trading Tools</h3>
                            <p class="text-gray-400">Access to advanced charting, technical analysis, and real-time market data.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white">Risk Management</h3>
                            <p class="text-gray-400">Built-in stop-loss, take-profit, and position sizing tools to protect your capital.</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white">24/7 Support</h3>
                            <p class="text-gray-400">Round-the-clock customer support to help you with any trading questions.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white">Educational Resources</h3>
                            <p class="text-gray-400">Comprehensive trading guides, webinars, and market analysis to improve your skills.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Subscribe Modal -->
<div id="subscribeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-gray-800 rounded-xl p-6 w-full max-w-md border border-gray-700">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-white">Subscribe to Plan</h3>
                <button onclick="closeSubscribeModal()" class="text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="subscribeForm" method="POST" action="">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Plan Name</label>
                        <input type="text" id="planName" readonly class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white">
                    </div>
                    
                    <div id="planFundingRange" class="p-3 bg-blue-900/20 border border-blue-500/30 rounded-lg hidden">
                        <div class="text-sm text-blue-300">
                            <strong>Funding Range:</strong> <span id="fundingRangeText"></span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Amount to Pay</label>
                        <input type="number" id="amountPaid" name="amount_paid" step="0.01" required class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500" oninput="checkBalance()" onchange="checkBalance()" placeholder="Enter amount within plan limits">
                        <p class="text-xs text-gray-400 mt-1">Amount must be within the plan's funding range</p>
                    </div>
                    
                    <!-- Balance Check Section -->
                    <div id="balanceCheckSection" class="hidden">
                        <div class="p-4 bg-red-900/20 border border-red-500/30 rounded-lg">
                            <div class="text-sm text-red-400 mb-3">
                                <strong>Insufficient Trading Balance</strong><br>
                                Your current trading balance: <strong>${{ number_format($user->trading_balance ?? 0, 2) }}</strong><br>
                                Amount needed: <strong>$<span id="amountNeeded"></span></strong>
                            </div>
                            <button type="button" onclick="openFundAccountModal()" class="w-full px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-sm">
                                Fund Your Trading Account
                            </button>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Currency</label>
                        <select name="currency" id="currency" required class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="USD">USD</option>
                            <option value="EUR">EUR</option>
                            <option value="GBP">GBP</option>
                            <option value="JPY">JPY</option>
                            <option value="CAD">CAD</option>
                            <option value="AUD">AUD</option>
                            <option value="CHF">CHF</option>
                            <option value="CNY">CNY</option>
                            <option value="INR">INR</option>
                            <option value="BRL">BRL</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Notes (Optional)</label>
                        <textarea name="notes" rows="3" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="Any additional notes..."></textarea>
                    </div>
                </div>
                
                <div class="flex space-x-3 mt-6">
                    <button type="button" onclick="closeSubscribeModal()" class="flex-1 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="subscribeButton" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        Subscribe
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Fund Trading Modal -->
<div id="fundTradingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-gray-800 rounded-xl p-6 w-full max-w-md border border-gray-700">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-white">Fund Your Trading Account</h3>
                <button onclick="closeFundTradingModal()" class="text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
                            <div class="mb-4 p-4 bg-blue-900/20 border border-blue-500/30 rounded-lg">
                <div class="text-sm text-blue-300">
                    <strong>Current Trading Balance:</strong> ${{ number_format($user->trading_balance ?? 0, 2) }}<br>
                    <strong>Amount to Deposit:</strong> $<span id="fundAmountDisplay"></span>
                </div>
            </div>
            
            <form id="fundTradingForm" method="POST" action="{{ route('user.payment') }}" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Deposit Amount</label>
                        <input type="number" id="fundAmount" name="amount" step="0.01" required class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="Enter amount to deposit">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Wallet Type</label>
                        <select name="wallet_type" required class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="trading">Trading Balance</option>
                            <option value="holding">Holding Balance</option>
                            <option value="staking">Staking Balance</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Payment Method</label>
                        <select name="payment_method_id" required class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            @foreach(\App\Models\PaymentMethod::where('is_active', true)->get() as $method)
                                <option value="{{ $method->id }}">{{ $method->crypto_display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Proof of Payment</label>
                        <input type="file" name="proof" required accept="image/*,.pdf" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <p class="text-xs text-gray-400 mt-1">Upload screenshot or PDF of your payment</p>
                    </div>
                </div>
                
                <div class="flex space-x-3 mt-6">
                    <button type="button" onclick="closeFundTradingModal()" class="flex-1 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                        Submit Deposit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const userTradingBalance = {{ $user->trading_balance ?? 0 }};
    
    // Plan max funding data for validation
    window.planMaxFunding = {
        @foreach($tradingPlans as $plan)
            {{ $plan->id }}: {{ $plan->max_funding ? $plan->max_funding : 'null' }},
        @endforeach
    };
    
    function subscribeToPlan(planId, planName, planPrice, planCurrency) {
        // Set modal values
        document.getElementById('planName').value = planName;
        document.getElementById('amountPaid').value = planPrice;
        document.getElementById('currency').value = planCurrency;
        
        // Set form action
        document.getElementById('subscribeForm').action = `/user/plans/subscribe/${planId}`;
        
        // Store plan data for validation
        window.currentPlanData = {
            id: planId,
            name: planName,
            min_funding: planPrice,
            max_funding: window.planMaxFunding[planId] || null,
            currency: planCurrency
        };
        
        // Show funding range
        const fundingRangeDiv = document.getElementById('planFundingRange');
        const fundingRangeText = document.getElementById('fundingRangeText');
        if (fundingRangeDiv && fundingRangeText) {
            const maxFunding = window.planMaxFunding[planId];
            if (maxFunding && maxFunding > planPrice) {
                fundingRangeText.textContent = `${planCurrency} ${planPrice.toFixed(2)} - ${planCurrency} ${maxFunding.toFixed(2)}`;
            } else {
                fundingRangeText.textContent = `${planCurrency} ${planPrice.toFixed(2)} - Unlimited`;
            }
            fundingRangeDiv.classList.remove('hidden');
        }
        
        // Check balance immediately when modal opens
        checkBalance();
        
        // Show modal
        document.getElementById('subscribeModal').classList.remove('hidden');
    }
    
    function checkBalance() {
        const amountInput = document.getElementById('amountPaid');
        const balanceSection = document.getElementById('balanceCheckSection');
        const amountNeededSpan = document.getElementById('amountNeeded');
        
        if (!amountInput || !balanceSection) return;
        
        const enteredAmount = parseFloat(amountInput.value) || 0;
        
        // Get plan details from the modal
        const planName = document.getElementById('planName').value;
        const planId = document.getElementById('subscribeForm').action.split('/').pop();
        
        // Find the plan data (we'll need to pass this from the subscribeToPlan function)
        const currentPlan = window.currentPlanData;
        
        if (currentPlan) {
            // Check if amount is within min/max funding range
            if (enteredAmount < currentPlan.min_funding) {
                // Show min funding error
                showFundingError(`Amount must be at least ${currentPlan.currency} ${currentPlan.min_funding.toFixed(2)}`);
                return;
            }
            
            if (currentPlan.max_funding && enteredAmount > currentPlan.max_funding) {
                // Show max funding error
                showFundingError(`Amount cannot exceed ${currentPlan.currency} ${currentPlan.max_funding.toFixed(2)}`);
                return;
            }
        }
        
        // Clear any funding errors
        hideFundingError();
        
        if (enteredAmount > userTradingBalance) {
            const amountNeeded = enteredAmount - userTradingBalance;
            amountNeededSpan.textContent = amountNeeded.toFixed(2);
            balanceSection.classList.remove('hidden');
        } else {
            balanceSection.classList.add('hidden');
        }
    }
    
    function showFundingError(message) {
        let errorSection = document.getElementById('fundingErrorSection');
        if (!errorSection) {
            errorSection = document.createElement('div');
            errorSection.id = 'fundingErrorSection';
            errorSection.className = 'p-4 bg-red-900/20 border border-red-500/30 rounded-lg';
            errorSection.innerHTML = `
                <div class="text-sm text-red-400">
                    <strong>Invalid Amount:</strong> ${message}
                </div>
            `;
            
            // Insert after the amount input field
            const amountInput = document.getElementById('amountPaid');
            amountInput.parentNode.insertBefore(errorSection, amountInput.nextSibling);
        } else {
            errorSection.innerHTML = `
                <div class="text-sm text-red-400">
                    <strong>Invalid Amount:</strong> ${message}
                </div>
            `;
            errorSection.classList.remove('hidden');
        }
        
        // Disable subscribe button when there are validation errors
        const subscribeButton = document.getElementById('subscribeButton');
        if (subscribeButton) {
            subscribeButton.disabled = true;
        }
    }
    
    function hideFundingError() {
        const errorSection = document.getElementById('fundingErrorSection');
        if (errorSection) {
            errorSection.classList.add('hidden');
        }
        
        // Enable subscribe button when validation errors are cleared
        const subscribeButton = document.getElementById('subscribeButton');
        if (subscribeButton) {
            subscribeButton.disabled = false;
        }
    }
    
    function openFundAccountModal() {
        // Close subscribe modal
        closeSubscribeModal();
        
        // Open fund trading modal with the amount needed
        const amountInput = document.getElementById('amountPaid');
        const enteredAmount = parseFloat(amountInput.value) || 0;
        const amountNeeded = Math.max(0, enteredAmount - userTradingBalance);
        
        // Set fund modal values
        document.getElementById('fundAmount').value = amountNeeded.toFixed(2);
        document.getElementById('fundAmountDisplay').textContent = amountNeeded.toFixed(2);
        
        // Show fund modal
        document.getElementById('fundTradingModal').classList.remove('hidden');
    }
    
    function closeSubscribeModal() {
        document.getElementById('subscribeModal').classList.add('hidden');
        // Hide balance check section when modal closes
        const balanceSection = document.getElementById('balanceCheckSection');
        if (balanceSection) {
            balanceSection.classList.add('hidden');
        }
        // Clear plan data and hide funding errors
        window.currentPlanData = null;
        hideFundingError();
        
        // Hide funding range
        const fundingRangeDiv = document.getElementById('planFundingRange');
        if (fundingRangeDiv) {
            fundingRangeDiv.classList.add('hidden');
        }
    }
    
    function closeFundTradingModal() {
        document.getElementById('fundTradingModal').classList.add('hidden');
    }
    
    // Close modals when clicking outside
    document.getElementById('subscribeModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeSubscribeModal();
        }
    });
    
    document.getElementById('fundTradingModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeFundTradingModal();
        }
    });
</script>
@endsection
