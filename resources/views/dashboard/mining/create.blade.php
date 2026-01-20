@extends('dashboard.layout.app')

@section('content')
<div class="min-h-screen bg-gray-900">
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Start Mining</h1>
                        <p class="text-gray-400">Set up your mining subscription</p>
                    </div>
                    <a href="{{ route('user.mining.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Back to Mining
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
                <!-- Mining Form -->
                <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-white mb-6">Mining Setup</h2>
                        
                        <form method="POST" action="{{ route('user.mining.store') }}" class="space-y-6">
                            @csrf
                            
                            <!-- Plan Selection -->
                            <div>
                                <label for="plan_id" class="block text-sm font-medium text-gray-300 mb-2">Select Mining Plan <span class="text-red-500">*</span></label>
                                <select id="plan_id" name="plan_id" required class="w-full bg-white border border-gray-600 text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Choose a mining plan</option>
                                    @foreach($miningPlans as $plan)
                                        <option value="{{ $plan->id }}" {{ request('plan') == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->name }} - ${{ number_format($plan->price, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('plan_id')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Amount to Invest -->
                            <div>
                                <label for="amount_invested" class="block text-sm font-medium text-gray-300 mb-2">Amount to Invest <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="number" id="amount_invested" name="amount_invested" step="0.01" min="0" required 
                                           class="w-full bg-white border border-gray-600 text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                           placeholder="Enter amount to invest">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <span class="text-gray-500 text-sm" id="currency-display">{{ auth()->user()->currency ?? 'USD' }}</span>
                                    </div>
                                </div>
                                @error('amount_invested')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-300 mb-2">Notes (Optional)</label>
                                <textarea id="notes" name="notes" rows="3" 
                                          class="w-full bg-white border border-gray-600 text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                          placeholder="Add any notes about this mining subscription"></textarea>
                                @error('notes')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-medium transition-colors">
                                    Start Mining
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-400">Select a mining plan to view details</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mining Benefits -->
                    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-white mb-4">Mining Benefits</h3>
                            <div class="space-y-3">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-medium">Passive Income</h4>
                                        <p class="text-gray-400 text-sm">Earn cryptocurrency through mining operations</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-medium">No Hardware Required</h4>
                                        <p class="text-gray-400 text-sm">Cloud mining without expensive equipment</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-medium">Professional Management</h4>
                                        <p class="text-gray-400 text-sm">24/7 monitoring and maintenance included</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-medium">Flexible Terms</h4>
                                        <p class="text-gray-400 text-sm">Choose from various mining durations</p>
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
                                    Mining involves risks including market volatility, difficulty changes, and potential loss of funds. 
                                    Mining rewards are not guaranteed and may vary based on network conditions.
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
    const amountInput = document.getElementById('amount_invested');
    const currencyDisplay = document.getElementById('currency-display');
    const planDetails = document.getElementById('plan-details');
    
    const miningPlans = @json($miningPlans);
    
    function updatePlanDetails(planId) {
        const plan = miningPlans.find(p => p.id == planId);
        
        if (plan) {
            currencyDisplay.textContent = plan.currency || '{{ auth()->user()->currency ?? 'USD' }}';
            
            planDetails.innerHTML = `
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Plan Name:</span>
                        <span class="text-white font-medium">${plan.name}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Price:</span>
                        <span class="text-white">$${parseFloat(plan.price).toFixed(2)}</span>
                    </div>
                    ${plan.hashrate ? `
                    <div class="flex justify-between">
                        <span class="text-gray-400">Hashrate:</span>
                        <span class="text-blue-400 font-semibold">${plan.hashrate}</span>
                    </div>
                    ` : ''}
                    ${plan.equipment ? `
                    <div class="flex justify-between">
                        <span class="text-gray-400">Equipment:</span>
                        <span class="text-white">${plan.equipment}</span>
                    </div>
                    ` : ''}
                    ${plan.downtime ? `
                    <div class="flex justify-between">
                        <span class="text-gray-400">Uptime:</span>
                        <span class="text-white">${plan.downtime}</span>
                    </div>
                    ` : ''}
                    ${plan.electricity_costs ? `
                    <div class="flex justify-between">
                        <span class="text-gray-400">Electricity:</span>
                        <span class="text-white">${plan.electricity_costs}</span>
                    </div>
                    ` : ''}
                    ${plan.mining_duration ? `
                    <div class="flex justify-between">
                        <span class="text-gray-400">Duration:</span>
                        <span class="text-white">${plan.mining_duration} days</span>
                    </div>
                    ` : ''}
                    ${plan.min_funding ? `
                    <div class="flex justify-between">
                        <span class="text-gray-400">Min Investment:</span>
                        <span class="text-white">$${parseFloat(plan.min_funding).toFixed(2)}</span>
                    </div>
                    ` : ''}
                    ${plan.max_funding ? `
                    <div class="flex justify-between">
                        <span class="text-gray-400">Max Investment:</span>
                        <span class="text-white">$${parseFloat(plan.max_funding).toFixed(2)}</span>
                    </div>
                    ` : ''}
                </div>
            `;
        } else {
            currencyDisplay.textContent = '{{ auth()->user()->currency ?? 'USD' }}';
            planDetails.innerHTML = `
                <div class="text-center py-8">
                    <div class="text-gray-400 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <p class="text-gray-400">Select a mining plan to view details</p>
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
