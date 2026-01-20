@extends('dashboard.layout.app')

@section('content')
<div class="min-h-screen bg-gray-900">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Subscribe to Signal Plan</h1>
                        <p class="text-gray-400 mt-1">Choose a signal plan that fits your trading needs</p>
                    </div>
                    <div class="flex justify-center sm:justify-end">
                        <a href="{{ route('user.signal-subscriptions.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors text-center">
                            Back to Subscriptions
                        </a>
                    </div>
                </div>
            </div>

            <!-- User Balance Info -->
            <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-700">
                    <h2 class="text-lg font-semibold text-white">Your Account Balance</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-white">{{ $user->formatAmount($user->balance) }}</div>
                            <div class="text-sm text-gray-400">Available Balance</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-400">{{ $user->formatAmount($user->trading_balance) }}</div>
                            <div class="text-sm text-gray-400">Trading Balance</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-400">{{ $user->formatAmount($user->getTotalBalanceAttribute()) }}</div>
                            <div class="text-sm text-gray-400">Total Balance</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Signal Plans Grid -->
            @if($signalPlans->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($signalPlans as $plan)
                        <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-700">
                                <h3 class="text-lg font-semibold text-white">{{ $plan->name }}</h3>
                                <p class="text-gray-400 text-sm mt-1">{{ $plan->description }}</p>
                            </div>
                            <div class="p-6">
                                <div class="mb-4">
                                    <span class="text-3xl font-bold text-white">${{ number_format($plan->price, 2) }}</span>
                                </div>
                                <div class="space-y-2 mb-6">
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">Signals:</span>
                                        <span class="text-white">{{ $plan->signal_quantity ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">Duration:</span>
                                        <span class="text-white">{{ $plan->signal_duration ?? 'N/A' }} days</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">Success Rate:</span>
                                        <span class="text-white">{{ number_format($plan->success_rate ?? 0, 1) }}%</span>
                                    </div>
                                </div>
                                @if($user->balance >= $plan->price)
                                    <button onclick="openSubscribeModal({{ $plan->id }}, '{{ $plan->name }}', {{ $plan->price }})" 
                                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-medium transition-colors">
                                        Subscribe Now
                                    </button>
                                @else
                                    <div class="space-y-2">
                                        <div class="p-3 bg-red-900 border border-red-700 rounded-lg">
                                            <span class="text-red-400 text-sm">Insufficient Balance</span>
                                        </div>
                                        <a href="{{ route('user.deposits.create') }}" 
                                           class="w-full bg-yellow-600 hover:bg-yellow-700 text-white py-3 px-4 rounded-lg font-medium transition-colors text-center block">
                                            Fund Account
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-12 text-center">
                        <h3 class="text-lg font-medium text-white mb-2">No Signal Plans Available</h3>
                        <p class="text-gray-400 mb-6">There are currently no signal plans available for subscription.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Subscribe Modal -->
<div id="subscribeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-gray-800 rounded-xl shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-700">
                <h3 class="text-lg font-semibold text-white">Subscribe to Signal Plan</h3>
            </div>
            <form id="subscribeForm" action="{{ route('user.signal-subscriptions.store') }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" id="modal_plan_id" name="plan_id">
                <input type="hidden" id="modal_amount" name="amount">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Selected Plan</label>
                    <p class="text-white font-medium" id="modal_plan_name"></p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Amount to Pay</label>
                    <p class="text-2xl font-bold text-white" id="modal_amount_display"></p>
                </div>
                
                <div class="flex space-x-3">
                    <button type="button" onclick="closeSubscribeModal()" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-lg font-medium transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition-colors">
                        Confirm Subscription
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openSubscribeModal(planId, planName, amount) {
    document.getElementById('modal_plan_id').value = planId;
    document.getElementById('modal_amount').value = amount;
    document.getElementById('modal_plan_name').textContent = planName;
    document.getElementById('modal_amount_display').textContent = '$' + amount.toFixed(2);
    document.getElementById('subscribeModal').classList.remove('hidden');
}

function closeSubscribeModal() {
    document.getElementById('subscribeModal').classList.add('hidden');
}
</script>
@endsection
