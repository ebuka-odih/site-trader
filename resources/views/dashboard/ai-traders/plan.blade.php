@extends('dashboard.layout.app')

@section('title', $plan->name . ' - AI Stock Traders')

@section('content')
<!-- Plan Header -->
<section class="bg-gradient-to-br from-[#0A0714] via-[#0D091C] to-[#1A1428] text-white py-16 relative overflow-hidden">
    <!-- Background elements -->
    <div class="absolute top-20 right-20 w-64 h-64 bg-[#2FE6DE]/10 rounded-full filter blur-3xl"></div>
    <div class="absolute bottom-10 left-10 w-80 h-80 bg-[#2FE6DE]/5 rounded-full filter blur-3xl"></div>
    
    <div class="w-full px-4 relative z-10">
        <div class="max-w-6xl mx-auto">
            <div class="flex items-center mb-4">
                <a href="{{ route('user.aiTraders.index') }}" class="text-gray-300 hover:text-[#2FE6DE] mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <span class="text-gray-300">Back to AI Traders</span>
            </div>
            
            <h1 class="text-4xl font-bold mb-4">{{ $plan->name }}</h1>
            <p class="text-xl text-gray-300 mb-6">{{ $plan->description }}</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-[#2FE6DE]/10 backdrop-blur-sm rounded-lg p-4 border border-[#2FE6DE]/20">
                    <div class="text-2xl font-bold text-[#2FE6DE]">{{ $plan->formatted_price }}</div>
                    <div class="text-gray-300 text-sm">Monthly Subscription</div>
                </div>
                <div class="bg-[#2FE6DE]/10 backdrop-blur-sm rounded-lg p-4 border border-[#2FE6DE]/20">
                    <div class="text-2xl font-bold text-[#2FE6DE]">{{ $plan->number_of_traders }}</div>
                    <div class="text-gray-300 text-sm">AI Traders Available</div>
                </div>
                <div class="bg-[#2FE6DE]/10 backdrop-blur-sm rounded-lg p-4 border border-[#2FE6DE]/20">
                    <div class="text-2xl font-bold text-[#2FE6DE]">${{ number_format($plan->investment_amount, 0) }}</div>
                    <div class="text-gray-300 text-sm">Minimum Investment</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Subscription Section -->
<section class="py-8 bg-[#1A1428] border-b border-[#2FE6DE]/20">
    <div class="w-full px-4">
        <div class="max-w-6xl mx-auto">
            @if(auth()->user()->hasActiveAiTraderSubscription($plan->id))
                <div class="bg-gradient-to-r from-green-600/20 to-green-500/20 border border-green-500/30 rounded-xl p-6 text-center">
                    <div class="flex items-center justify-center mb-4">
                        <i class="fas fa-check-circle text-green-500 text-3xl mr-3"></i>
                        <h3 class="text-2xl font-bold text-green-400">Plan Subscribed</h3>
                    </div>
                    <p class="text-gray-300 mb-4">You have an active subscription to the {{ $plan->name }} plan. You can now activate AI Traders from this plan.</p>
                    <div class="text-sm text-gray-400">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Expires: {{ auth()->user()->activeAiTraderSubscriptions()->where('ai_trader_plan_id', $plan->id)->first()->expires_at->format('M d, Y') }}
                    </div>
                </div>
            @else
                <div class="bg-gradient-to-r from-[#2FE6DE]/10 to-[#2FE6DE]/5 border border-[#2FE6DE]/30 rounded-xl p-6 text-center">
                    <div class="flex items-center justify-center mb-4">
                        <i class="fas fa-robot text-[#2FE6DE] text-3xl mr-3"></i>
                        <h3 class="text-2xl font-bold text-white">Subscribe to {{ $plan->name }}</h3>
                    </div>
                    <p class="text-gray-300 mb-6">Subscribe to this plan to unlock access to all AI Traders and start automated trading.</p>
                    <button onclick="subscribeToPlan({{ $plan->id }})" 
                            class="bg-gradient-to-r from-[#2FE6DE] to-[#2FE6DE]/80 text-[#0A0714] py-3 px-8 rounded-lg font-semibold hover:from-[#2FE6DE]/90 hover:to-[#2FE6DE]/70 transition-all duration-300 text-lg">
                        <i class="fas fa-credit-card mr-2"></i>Subscribe for {{ $plan->formatted_price }}/month
                    </button>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- AI Traders Grid -->
<section class="py-16 bg-[#0A0714]">
    <div class="w-full px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-white mb-4">Available AI Traders</h2>
            <p class="text-lg text-gray-300">Choose from our carefully selected AI traders, each with unique strategies and performance records.</p>
        </div>

        @if($traders->count() > 0)
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($traders as $trader)
            <div class="bg-[#1A1428] rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group border border-[#2FE6DE]/20 hover:border-[#2FE6DE]/40">
                <!-- Trader Header -->
                <div class="bg-gradient-to-r from-[#2FE6DE] to-[#2FE6DE]/80 text-[#0A0714] p-6 relative">
                    @if(auth()->user()->hasActivatedAiTrader($trader->id))
                        <div class="absolute top-3 right-3 bg-green-500 text-white text-xs px-2 py-1 rounded-full font-semibold">
                            <i class="fas fa-check-circle mr-1"></i>Active
                        </div>
                    @endif
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xl font-bold">{{ $trader->name }}</h3>
                        <span class="bg-[#0A0714] text-[#2FE6DE] text-xs px-2 py-1 rounded-full border border-[#2FE6DE]/30">
                            {{ ucfirst($trader->trading_strategy) }}
                        </span>
                    </div>
                    <div class="flex items-center text-sm text-[#0A0714]/80">
                        <span class="bg-[#0A0714]/20 text-[#0A0714] px-2 py-1 rounded text-xs mr-2 border border-[#0A0714]/30">{{ $trader->ai_model }}</span>
                        <span class="bg-[#0A0714]/20 text-[#0A0714] px-2 py-1 rounded text-xs border border-[#0A0714]/30">{{ $trader->ai_confidence }}</span>
                    </div>
                </div>

                <!-- Performance Metrics -->
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold {{ $trader->current_performance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $trader->formatted_performance }}
                            </div>
                            <div class="text-sm text-gray-500">Performance</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $trader->formatted_win_rate }}</div>
                            <div class="text-sm text-gray-500">Win Rate</div>
                        </div>
                    </div>

                    <!-- Trading Stats -->
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">Total Trades:</span>
                            <span class="font-medium text-white">{{ $trader->total_trades }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">Winning Trades:</span>
                            <span class="font-medium text-[#2FE6DE]">{{ $trader->winning_trades }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">Max Positions:</span>
                            <span class="font-medium text-white">{{ $trader->max_positions }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">Position Size:</span>
                            <span class="font-medium text-white">{{ $trader->position_size_percentage }}%</span>
                        </div>
                    </div>

                    <!-- Stocks Trading -->
                    <div class="mb-4">
                        <div class="text-sm text-gray-400 mb-2">Stocks Trading:</div>
                        <div class="flex flex-wrap gap-1">
                            @foreach($trader->stocks_to_trade as $stock)
                            <span class="bg-[#2FE6DE]/20 text-[#2FE6DE] text-xs px-2 py-1 rounded border border-[#2FE6DE]/30">{{ $stock }}</span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Risk Management -->
                    <div class="mb-6">
                        <div class="text-sm text-gray-400 mb-2">Risk Management:</div>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="bg-red-500/20 text-red-400 px-2 py-1 rounded text-center border border-red-500/30">
                                Stop Loss: {{ $trader->stop_loss_percentage }}%
                            </div>
                            <div class="bg-green-500/20 text-green-400 px-2 py-1 rounded text-center border border-green-500/30">
                                Take Profit: {{ $trader->take_profit_percentage }}%
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        @if(auth()->user()->hasActivatedAiTrader($trader->id))
                            <div class="flex-1 bg-gradient-to-r from-green-600/20 to-green-500/20 text-green-400 py-3 px-4 rounded-lg text-center border border-green-500/30">
                                <i class="fas fa-check-circle mr-2"></i>Activated
                            </div>
                        @elseif(auth()->user()->hasActiveAiTraderSubscription($plan->id))
                            <button onclick="showActivationModal({{ $trader->id }}, '{{ $trader->name }}', {{ $trader->aiTraderPlan ? $trader->aiTraderPlan->investment_amount : 0 }})" 
                                    class="flex-1 bg-gradient-to-r from-green-600 to-green-500 text-white py-3 px-4 rounded-lg font-semibold hover:from-green-700 hover:to-green-600 transition-all duration-300 text-center">
                                <i class="fas fa-play mr-2"></i>Activate
                            </button>
                        @else
                            <div class="flex-1 bg-gray-600/50 text-gray-400 py-3 px-4 rounded-lg text-center border border-gray-500/30">
                                <i class="fas fa-lock mr-2"></i>Subscribe to Activate
                            </div>
                        @endif
                        <a href="{{ route('user.aiTraders.trader', $trader) }}" 
                           class="bg-gradient-to-r from-[#2FE6DE] to-[#2FE6DE]/80 text-[#0A0714] py-3 px-4 rounded-lg font-semibold hover:from-[#2FE6DE]/90 hover:to-[#2FE6DE]/70 transition-all duration-300 text-center flex items-center justify-center">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
            </div>
        </div>
        @else
        <div class="text-center py-12">
            <div class="text-6xl mb-4">🤖</div>
            <h3 class="text-xl font-semibold text-white mb-2">No AI Traders Available</h3>
            <p class="text-gray-300">This plan doesn't have any active AI traders at the moment.</p>
        </div>
        @endif
    </div>
</section>

<!-- Plan Features -->
@if($plan->features)
<section class="py-16 bg-[#0D091C]">
    <div class="w-full px-4">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-white text-center mb-12">Plan Features</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($plan->features as $feature)
                <div class="flex items-start">
                    <div class="bg-[#2FE6DE]/20 w-8 h-8 rounded-full flex items-center justify-center mr-4 flex-shrink-0 border border-[#2FE6DE]/30">
                        <svg class="w-5 h-5 text-[#2FE6DE]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-white mb-1">{{ $feature }}</h3>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-[#2FE6DE] to-[#2FE6DE]/80 text-[#0A0714] relative overflow-hidden">
    <!-- Background elements -->
    <div class="absolute top-10 right-10 w-32 h-32 bg-[#0A0714]/10 rounded-full filter blur-2xl"></div>
    <div class="absolute bottom-10 left-10 w-40 h-40 bg-[#0A0714]/5 rounded-full filter blur-2xl"></div>
    
    <div class="w-full px-4 text-center relative z-10">
        <h2 class="text-3xl font-bold mb-4">Ready to Start with {{ $plan->name }}?</h2>
        <p class="text-xl mb-8 text-[#0A0714]/80">Join thousands of investors using AI to optimize their stock portfolios.</p>
        <a href="#traders" class="bg-[#0A0714] text-[#2FE6DE] px-8 py-3 rounded-lg font-semibold hover:bg-[#0A0714]/90 transition-colors duration-300 border border-[#0A0714] hover:border-[#2FE6DE]/50">
            Choose Your AI Trader
        </a>
    </div>
</section>

<!-- Activation Modal -->
<div id="activationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-[#1A1428] rounded-xl shadow-2xl max-w-md w-full border border-[#2FE6DE]/20">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-white">Activate AI Trader</h3>
                <button onclick="closeActivationModal()" class="text-gray-400 hover:text-white transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="mb-6">
                <div class="bg-[#2FE6DE]/10 border border-[#2FE6DE]/20 rounded-lg p-4 mb-4">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-robot text-[#2FE6DE] mr-3"></i>
                        <span class="font-semibold text-white" id="traderName">Trader Name</span>
                    </div>
                    <div class="text-sm text-gray-300">
                        <div class="flex justify-between">
                            <span>Minimum Investment:</span>
                            <span class="font-semibold text-[#2FE6DE]" id="minInvestment">$0</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Your Trading Balance:</span>
                            <span class="font-semibold text-green-400" id="userBalance">$0</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label for="investmentAmount" class="block text-sm font-medium text-gray-300 mb-2">
                        Investment Amount
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">$</span>
                        <input type="number" 
                               id="investmentAmount" 
                               class="w-full pl-8 pr-4 py-3 bg-[#0A0714] border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:border-[#2FE6DE] focus:ring-1 focus:ring-[#2FE6DE] transition-colors"
                               placeholder="Enter amount"
                               min="0"
                               step="0.01">
                    </div>
                    <div class="mt-2 text-sm text-gray-400" id="amountError"></div>
                </div>
            </div>
            
            <div class="flex gap-3">
                <button onclick="closeActivationModal()" 
                        class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-3 px-4 rounded-lg font-semibold transition-colors">
                    Cancel
                </button>
                <button onclick="confirmActivation()" 
                        id="confirmActivationBtn"
                        class="flex-1 bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white py-3 px-4 rounded-lg font-semibold transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-play mr-2"></i>Activate Trader
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Get user balance on page load
document.addEventListener('DOMContentLoaded', function() {
    // Get user balance from a data attribute or make an API call
    // For now, we'll use a placeholder - in real implementation, get from user data
    currentUserBalance = {{ auth()->user()->trading_balance ?? 0 }};
    
    // Add input validation for investment amount
    const investmentInput = document.getElementById('investmentAmount');
    if (investmentInput) {
        investmentInput.addEventListener('input', function() {
            const amount = parseFloat(this.value) || 0;
            validateInvestmentAmount(amount);
        });
    }
});

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Global variables for activation modal
let currentTraderId = null;
let currentMinInvestment = 0;
let currentUserBalance = 0;

// Show activation modal
function showActivationModal(traderId, traderName, minInvestment) {
    currentTraderId = traderId;
    currentMinInvestment = minInvestment;
    
    // Update modal content
    document.getElementById('traderName').textContent = traderName;
    document.getElementById('minInvestment').textContent = '$' + minInvestment.toLocaleString();
    document.getElementById('userBalance').textContent = '$' + currentUserBalance.toLocaleString();
    document.getElementById('investmentAmount').value = minInvestment;
    document.getElementById('amountError').textContent = '';
    
    // Show modal
    document.getElementById('activationModal').classList.remove('hidden');
    
    // Focus on input
    setTimeout(() => {
        document.getElementById('investmentAmount').focus();
    }, 100);
}

// Close activation modal
function closeActivationModal() {
    document.getElementById('activationModal').classList.add('hidden');
    currentTraderId = null;
    currentMinInvestment = 0;
}

// Validate investment amount
function validateInvestmentAmount(amount) {
    const errorElement = document.getElementById('amountError');
    const confirmBtn = document.getElementById('confirmActivationBtn');
    
    if (amount < currentMinInvestment) {
        errorElement.textContent = `Minimum investment required: $${currentMinInvestment.toLocaleString()}`;
        errorElement.className = 'mt-2 text-sm text-red-400';
        confirmBtn.disabled = true;
        return false;
    } else if (amount > currentUserBalance) {
        errorElement.textContent = `Insufficient balance. Available: $${currentUserBalance.toLocaleString()}`;
        errorElement.className = 'mt-2 text-sm text-red-400';
        confirmBtn.disabled = true;
        return false;
    } else {
        errorElement.textContent = '';
        errorElement.className = 'mt-2 text-sm text-gray-400';
        confirmBtn.disabled = false;
        return true;
    }
}

// Confirm activation
function confirmActivation() {
    const amount = parseFloat(document.getElementById('investmentAmount').value);
    
    if (!validateInvestmentAmount(amount)) {
        return;
    }
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Confirm Activation',
            text: `Are you sure you want to activate this AI Trader with $${amount.toLocaleString()}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2FE6DE',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Activate!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                activateTrader(currentTraderId, amount);
            }
        });
    } else {
        if (confirm(`Are you sure you want to activate this AI Trader with $${amount.toLocaleString()}?`)) {
            activateTrader(currentTraderId, amount);
        }
    }
}

// Activate AI Trader function
function activateTrader(traderId, investmentAmount) {
    // Show loading state
    const confirmBtn = document.getElementById('confirmActivationBtn');
    const originalText = confirmBtn.innerHTML;
    confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Activating...';
    confirmBtn.disabled = true;
    
    // Make API call to activate trader
    fetch(`/user/ai-traders/trader/${traderId}/activate`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            investment_amount: investmentAmount
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'AI Trader Activated!',
                    text: data.message || 'Your AI Trader has been successfully activated and is now trading.',
                    confirmButtonColor: '#2FE6DE'
                });
            } else {
                alert('AI Trader Activated! ' + (data.message || 'Your AI Trader has been successfully activated and is now trading.'));
            }
            
            // Close modal and reload page
            closeActivationModal();
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Failed to activate AI Trader');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Activation Failed',
                text: error.message || 'There was an error activating the AI Trader. Please try again.',
                confirmButtonColor: '#2FE6DE'
            });
        } else {
            alert('Activation Failed: ' + (error.message || 'There was an error activating the AI Trader. Please try again.'));
        }
        
        // Restore original button state
        confirmBtn.innerHTML = originalText;
        confirmBtn.disabled = false;
    });
}

// Subscribe to AI Trader Plan function
function subscribeToPlan(planId) {
    if (confirm('Are you sure you want to subscribe to this AI Trader Plan? This will enable you to activate AI Traders from this plan.')) {
        // Show loading state
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Subscribing...';
        button.disabled = true;
        
        // Make API call to subscribe to plan
        fetch(`/user/ai-traders/plan/${planId}/subscribe`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Subscription Successful!',
                        text: data.message || 'You have successfully subscribed to this AI Trader Plan.',
                        confirmButtonColor: '#2FE6DE'
                    });
                } else {
                    alert('Subscription Successful! ' + (data.message || 'You have successfully subscribed to this AI Trader Plan.'));
                }
                
                // Reload the page to show activate buttons
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                throw new Error(data.message || 'Failed to subscribe to AI Trader Plan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Subscription Failed',
                    text: error.message || 'There was an error subscribing to the AI Trader Plan. Please try again.',
                    confirmButtonColor: '#2FE6DE'
                });
            } else {
                alert('Subscription Failed: ' + (error.message || 'There was an error subscribing to the AI Trader Plan. Please try again.'));
            }
            
            // Restore original button state
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
}
</script>
@endpush
