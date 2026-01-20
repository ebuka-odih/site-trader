@extends('dashboard.layout.app')

@section('content')
<div class="min-h-screen bg-gray-900">
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Start Staking</h1>
                        <p class="text-gray-400">Set up your staking subscription</p>
                    </div>
                    <a href="{{ route('user.staking.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Back to Staking
                    </a>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-900/20 border border-green-500/30 rounded-lg">
                    <div class="text-green-400">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-900/20 border border-red-500/30 rounded-lg">
                    <div class="text-red-400">{{ session('error') }}</div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Staking Form -->
                <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-white mb-6">Staking Setup</h2>
                        
                        <form method="POST" action="{{ route('user.staking.store') }}" class="space-y-6">
                            @csrf
                            
                            <!-- Plan Selection -->
                            <div>
                                <label for="plan_id" class="block text-sm font-medium text-gray-300 mb-2">Select Staking Plan <span class="text-red-500">*</span></label>
                                <select id="plan_id" name="plan_id" required class="w-full bg-white border border-gray-600 text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Choose a staking plan</option>
                                    @foreach($stakingPlans as $plan)
                                        <option value="{{ $plan->id }}" {{ request('plan') == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->name }} - {{ $plan->staking_currency }} ({{ number_format($plan->apy_rate, 2) }}% APY)
                                        </option>
                                    @endforeach
                                </select>
                                @error('plan_id')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Amount to Stake -->
                            <div>
                                <label for="amount_staked" class="block text-sm font-medium text-gray-300 mb-2">Amount to Stake <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="number" id="amount_staked" name="amount_staked" step="0.00000001" min="0" required 
                                           class="w-full bg-white border border-gray-600 text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                           placeholder="Enter amount to stake">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <span class="text-gray-500 text-sm" id="currency-display">Select plan first</span>
                                    </div>
                                </div>
                                @error('amount_staked')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-300 mb-2">Notes (Optional)</label>
                                <textarea id="notes" name="notes" rows="3" 
                                          class="w-full bg-white border border-gray-600 text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                          placeholder="Add any notes about this staking subscription"></textarea>
                                @error('notes')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg font-medium transition-colors">
                                    Start Staking
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Plan Details -->
                <div class="space-y-6">
                    <!-- Selected Plan Info -->
                    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-white mb-4">Plan Details</h3>
                            <div id="plan-details" class="space-y-4">
                                <div class="text-center py-8">
                                    <div class="text-gray-400 mb-4">
                                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-400">Select a staking plan to view details</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Staking Benefits -->
                    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-white mb-4">Staking Benefits</h3>
                            <div class="space-y-3">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-medium">Passive Income</h4>
                                        <p class="text-gray-400 text-sm">Earn rewards just by holding your crypto</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-medium">Compound Growth</h4>
                                        <p class="text-gray-400 text-sm">Reinvest rewards for exponential growth</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-medium">Network Security</h4>
                                        <p class="text-gray-400 text-sm">Help secure blockchain networks</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-medium">Flexible Terms</h4>
                                        <p class="text-gray-400 text-sm">Choose from various lock periods and APY rates</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Risk Warning -->
                    <div class="bg-yellow-900/20 border border-yellow-500/30 rounded-lg p-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-yellow-400 font-medium">Risk Warning</h4>
                                <p class="text-yellow-300 text-sm mt-1">
                                    Staking involves risks including potential loss of funds. APY rates are not guaranteed and may vary. 
                                    Always do your own research before staking.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const planSelect = document.getElementById('plan_id');
    const amountInput = document.getElementById('amount_staked');
    const currencyDisplay = document.getElementById('currency-display');
    const planDetails = document.getElementById('plan-details');
    
    const stakingPlans = @json($stakingPlans);
    
    function updatePlanDetails(planId) {
        const plan = stakingPlans.find(p => p.id == planId);
        
        if (plan) {
            currencyDisplay.textContent = plan.staking_currency;
            
            planDetails.innerHTML = `
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Plan Name:</span>
                        <span class="text-white font-medium">${plan.name}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Currency:</span>
                        <span class="text-white">${plan.staking_currency}</span>
                    </div>
                    ${plan.apy_rate ? `
                    <div class="flex justify-between">
                        <span class="text-gray-400">APY Rate:</span>
                        <span class="text-green-400 font-semibold">${parseFloat(plan.apy_rate).toFixed(2)}%</span>
                    </div>
                    ` : ''}
                    ${plan.minimum_amount ? `
                    <div class="flex justify-between">
                        <span class="text-gray-400">Min Amount:</span>
                        <span class="text-white">${parseFloat(plan.minimum_amount).toFixed(8)} ${plan.staking_currency}</span>
                    </div>
                    ` : ''}
                    ${plan.lock_period ? `
                    <div class="flex justify-between">
                        <span class="text-gray-400">Lock Period:</span>
                        <span class="text-white">${plan.lock_period} days</span>
                    </div>
                    ` : ''}
                    ${plan.reward_frequency ? `
                    <div class="flex justify-between">
                        <span class="text-gray-400">Rewards:</span>
                        <span class="text-white">${plan.reward_frequency}</span>
                    </div>
                    ` : ''}
                    ${plan.staking_duration ? `
                    <div class="flex justify-between">
                        <span class="text-gray-400">Duration:</span>
                        <span class="text-white">${plan.staking_duration} days</span>
                    </div>
                    ` : ''}
                </div>
            `;
        } else {
            currencyDisplay.textContent = 'Select plan first';
            planDetails.innerHTML = `
                <div class="text-center py-8">
                    <div class="text-gray-400 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-400">Select a staking plan to view details</p>
                </div>
            `;
        }
    }
    
    planSelect.addEventListener('change', function() {
        updatePlanDetails(this.value);
    });
    
    // Initialize with selected plan if any
    if (planSelect.value) {
        updatePlanDetails(planSelect.value);
    }
});
</script>
@endsection

