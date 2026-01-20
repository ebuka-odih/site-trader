@extends('dashboard.layout.app')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="text-center">
        <h1 class="text-4xl font-bold text-white mb-2">Signal Plans</h1>
        <p class="text-xl text-gray-400">Subscribe to professional trading signals</p>
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

    <!-- Signal Plans Grid -->
    @if($signalPlans->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
            @foreach($signalPlans as $plan)
                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-green-500 transition-all duration-300 transform hover:scale-105">
                    <!-- Plan Header -->
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-white mb-2">{{ $plan->name }}</h3>
                        <div class="text-3xl font-bold text-green-400 mb-1">${{ number_format($plan->price, 2) }}</div>
                        @if($plan->has_discount)
                            <div class="text-lg text-gray-400 line-through mb-1">${{ number_format($plan->original_price, 2) }}</div>
                        @endif
                        <div class="text-sm text-gray-400">{{ $plan->signal_quantity }} Signals</div>
                        <div class="text-sm text-gray-400">{{ $plan->signal_duration }} Days</div>
                    </div>
                    
                    <!-- Plan Details -->
                    <div class="space-y-4 mb-8">
                        <!-- Market Type -->
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-300">{{ ucfirst($plan->signal_market_type) }} Market</span>
                        </div>

                        <!-- Signal Strength -->
                        <div class="flex items-center space-x-3">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $plan->signal_strength)
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-gray-300">Signal Strength</span>
                        </div>

                        <!-- Success Rate -->
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-300">{{ number_format($plan->success_rate, 1) }}% Success Rate</span>
                        </div>

                        <!-- Daily Signals -->
                        @if($plan->daily_signals)
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-purple-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-300">{{ $plan->daily_signals }} Signals/Day</span>
                            </div>
                        @endif

                        <!-- Max Daily Signals -->
                        @if($plan->max_daily_signals)
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-indigo-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-gray-300">Max {{ $plan->max_daily_signals }}/Day</span>
                            </div>
                        @endif

                        <!-- Features -->
                        @if($plan->signal_features && is_array($plan->signal_features))
                            @foreach(array_slice($plan->signal_features, 0, 3) as $feature)
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-300">{{ $feature }}</span>
                                </div>
                            @endforeach
                            @if(count($plan->signal_features) > 3)
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-400">+{{ count($plan->signal_features) - 3 }} more features</span>
                                </div>
                            @endif
                        @endif
                    </div>
                    
                    <!-- Subscribe Button -->
                    <button onclick="openSubscribeModal({{ $plan->id }}, '{{ $plan->name }}', {{ $plan->price }})" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 transform hover:scale-105">
                        SUBSCRIBE NOW
                    </button>
                </div>
            @endforeach
        </div>
    @else
        <!-- No Plans Available -->
        <div class="text-center py-12">
            <div class="text-gray-400 mb-4">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-white mb-2">No Signal Plans Available</h3>
            <p class="text-gray-400">Check back later for new signal plans.</p>
        </div>
    @endif
</div>

<!-- Subscribe Modal -->
<div id="subscribeModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-gray-800 rounded-xl shadow-2xl w-full max-w-md">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-white">Subscribe to Signal Plan</h3>
                    <button onclick="closeSubscribeModal()" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="subscribeForm" action="{{ route('user.signal-subscriptions.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" id="modal_plan_id" name="plan_id">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Plan Name</label>
                        <div class="text-white font-semibold" id="modal_plan_name"></div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Plan Price</label>
                        <div class="text-green-400 font-semibold text-lg" id="modal_plan_price"></div>
                    </div>
                    
                    <div>
                        <label for="modal_amount" class="block text-sm font-medium text-gray-300 mb-1">Amount to Pay</label>
                        <input type="number" id="modal_amount" name="amount" step="0.01" min="0" required class="w-full bg-white border border-gray-600 text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter amount">
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeSubscribeModal()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Subscribe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openSubscribeModal(planId, planName, planPrice) {
    document.getElementById('modal_plan_id').value = planId;
    document.getElementById('modal_plan_name').textContent = planName;
    document.getElementById('modal_plan_price').textContent = '$' + planPrice.toFixed(2);
    document.getElementById('modal_amount').value = planPrice;
    document.getElementById('subscribeModal').classList.remove('hidden');
}

function closeSubscribeModal() {
    document.getElementById('subscribeModal').classList.add('hidden');
}
</script>
@endsection
