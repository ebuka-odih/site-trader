<div class="max-h-96 overflow-y-auto space-y-6">
    <!-- Deposit Information -->
    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Deposit Information</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
                <p class="text-lg font-semibold text-green-600 dark:text-green-400">${{ number_format($deposit->amount, 2) }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Wallet Type</label>
                <p class="text-sm text-gray-900 dark:text-white">
                    @if($deposit->wallet_type == 'balance')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300">
                            Main Balance
                        </span>
                    @elseif($deposit->wallet_type == 'trading')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                            Trading Balance
                        </span>
                    @elseif($deposit->wallet_type == 'holding')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                            Holding Balance
                        </span>
                    @elseif($deposit->wallet_type == 'staking')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300">
                            Staking Balance
                        </span>
                    @else
                        <span class="text-gray-400">N/A</span>
                    @endif
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Method</label>
                <p class="text-sm text-gray-900 dark:text-white">{{ optional($deposit->payment_method)->crypto_display_name ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <p class="text-sm">
                    @if($deposit->status == 0)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                            Pending
                        </span>
                    @elseif($deposit->status == 1)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                            Approved
                        </span>
                    @elseif($deposit->status == 2)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                            Declined
                        </span>
                    @elseif($deposit->status == \App\Models\Deposit::STATUS_IN_REVIEW)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                            In Review
                        </span>
                    @endif
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                <p class="text-sm text-gray-900 dark:text-white">{{ $deposit->created_at ? $deposit->created_at->format('M d, Y H:i') : 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- User Information -->
    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">User Information</h4>
        <div class="flex items-center space-x-4">
            <img class="w-12 h-12 rounded-full" src="{{ $deposit->user->avatar_url }}" alt="{{ $deposit->user->name }}">
            <div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $deposit->user->name }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $deposit->user->phone ?? 'No phone' }}</p>
            </div>
        </div>
    </div>

    <!-- Payment Proof -->
    @if($deposit->proof)
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Proof</h4>
            <div class="flex justify-center">
                @php
                    $fileExtension = pathinfo($deposit->proof, PATHINFO_EXTENSION);
                    $isImage = in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif', 'svg']);
                @endphp
                
                @if($isImage)
                    <img src="{{ asset('storage/' . $deposit->proof) }}" 
                         alt="Payment Proof" 
                         class="max-w-full h-auto max-h-96 rounded-lg shadow-lg">
                @else
                    <div class="text-center p-8">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-600 dark:text-gray-400">PDF Document</p>
                        <a href="{{ asset('storage/' . $deposit->proof) }}" 
                           target="_blank" 
                           class="inline-flex items-center px-4 py-2 mt-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            View Document
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    @if($deposit->status == \App\Models\Deposit::STATUS_PENDING || $deposit->status == \App\Models\Deposit::STATUS_IN_REVIEW)
        <div class="flex space-x-3 pt-4 border-t border-gray-200 dark:border-gray-600">
            <form method="POST" action="{{ route('admin.deposit.approve', $deposit->id) }}" class="flex-1">
                @csrf
                <button type="submit" 
                        class="w-full inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300"
                        onclick="return confirm('{{ $deposit->status == \App\Models\Deposit::STATUS_IN_REVIEW ? 'Approve this deposit again? This will re-credit the user.' : 'Are you sure you want to approve this deposit? This will credit the user\'s account.' }}')">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ $deposit->status == \App\Models\Deposit::STATUS_IN_REVIEW ? 'Re-Approve Deposit' : 'Approve Deposit' }}
                </button>
            </form>
            <form method="POST" action="{{ route('admin.deposit.decline', $deposit->id) }}" class="flex-1">
                @csrf
                <button type="submit" 
                        class="w-full inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300"
                        onclick="return confirm('Are you sure you want to decline this deposit? This action cannot be undone.')">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Decline Deposit
                </button>
            </form>
        </div>
    @elseif($deposit->status == \App\Models\Deposit::STATUS_APPROVED)
        <div class="pt-4 border-t border-gray-200 dark:border-gray-600">
            <form method="POST" action="{{ route('admin.deposit.review', $deposit->id) }}">
                @csrf
                <button type="submit"
                        class="w-full inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300"
                        onclick="return confirm('Move this deposit to review? Previously added funds will be removed until it is approved again.')">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    Move to Review
                </button>
            </form>
        </div>
    @endif
</div>
