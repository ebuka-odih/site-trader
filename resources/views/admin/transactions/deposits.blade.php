@extends('admin.layouts.app')
@section('content')

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

 <div class="px-4 pt-5">
    <!-- Navigation Tabs -->
<ul class="flex text-sm font-medium text-center text-gray-500 rounded-lg shadow sm:flex dark:divide-gray-700 dark:text-gray-400">
    <li class="w-full focus-within:z-10">
            <a href="{{ route('admin.transactions.deposits') }}" class="inline-block w-full p-4 text-gray-900 bg-gray-100 border-r border-gray-200 dark:border-gray-700 rounded-s-lg focus:ring-4 focus:ring-blue-300 active focus:outline-none dark:bg-gray-700 dark:text-white" aria-current="page">
                Deposits
            </a>
    </li>
    <li class="w-full focus-within:z-10">
            <a href="{{ route('admin.transactions.withdrawals') }}" class="inline-block w-full p-4 bg-white border-r border-gray-200 dark:border-gray-700 hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700">
                Withdrawal
            </a>
    </li>
</ul>

    <!-- Main Content -->
    <div class="p-4 mt-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
        <!-- Card Header -->
      <div class="items-center justify-between lg:flex">
        <div class="mb-4 lg:mb-0">
          <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Deposit Transactions</h3>
                <p class="text-gray-600 dark:text-gray-400">Manage and review all deposit requests</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    Total: {{ $deposits->count() }} deposits
                </span>
            </div>
        </div>

      <!-- Table -->
      <div class="flex flex-col mt-6">
        <div class="overflow-x-auto rounded-lg">
          <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow sm:rounded-lg">
              <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                <thead class="bg-gray-50 dark:bg-gray-700">
                  <tr>
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white hidden sm:table-cell">
                                        ID
                    </th>
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      User
                    </th>
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      Amount
                    </th>
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white hidden md:table-cell">
                                        Wallet
                                    </th>
                                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white hidden lg:table-cell">
                                        Payment Method
                                    </th>
                                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white hidden sm:table-cell">
                                        Date
                    </th>
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      Status
                    </th>
                      <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                                        Actions
                    </th>
                  </tr>
                </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                @forelse($deposits as $deposit)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="p-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white hidden sm:table-cell">
                                            #{{ substr($deposit->id, 0, 6) }}
                    </td>
                                        <td class="p-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <img class="w-10 h-10 rounded-full" src="{{ $deposit->user->avatar_url }}" alt="{{ $deposit->user->name }}">
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $deposit->user->name }}
                                                        <span class="sm:hidden text-xs text-gray-500 ml-2">#{{ substr($deposit->id, 0, 6) }}</span>
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $deposit->user->email }}
                                                    </div>
                                                </div>
                        </div>
                    </td>
                                        <td class="p-4 text-sm font-semibold text-gray-900 whitespace-nowrap dark:text-white">
                                            ${{ number_format($deposit->amount, 2) }}
                                        </td>
                                        <td class="p-4 whitespace-nowrap hidden md:table-cell">
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
                    </td>
                                        <td class="p-4 text-sm text-gray-900 whitespace-nowrap dark:text-white hidden lg:table-cell">
                                            {{ optional($deposit->payment_method)->crypto_display_name ?? 'N/A' }}
                    </td>
                                        <td class="p-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400 hidden sm:table-cell">
                                            {{ $deposit->created_at ? $deposit->created_at->format('M d, Y H:i') : 'N/A' }}
                    </td>
                    <td class="p-4 whitespace-nowrap">
                        @php
                            $statusClasses = [
                                \App\Models\Deposit::STATUS_PENDING => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                \App\Models\Deposit::STATUS_APPROVED => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                \App\Models\Deposit::STATUS_DECLINED => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                \App\Models\Deposit::STATUS_IN_REVIEW => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                            ];
                            $statusClass = $statusClasses[$deposit->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                            {{ $deposit->status_label }}
                        </span>
                    </td>
                                        <td class="p-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <!-- View Details Button -->
                                                <button onclick="viewDepositDetails('{{ $deposit->id }}')" 
                                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    View
                         </button>

                                                @if(in_array($deposit->status, [\App\Models\Deposit::STATUS_PENDING, \App\Models\Deposit::STATUS_IN_REVIEW]))
                                                    <!-- Approve Button -->
                                                    <button onclick="approveDeposit('{{ $deposit->id }}')" 
                                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        Approve
                         </button>

                                                    <!-- Decline Button -->
                                                    <button onclick="declineDeposit('{{ $deposit->id }}')" 
                                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        Decline
                                                    </button>
                                                @endif

                                                @if($deposit->status === \App\Models\Deposit::STATUS_APPROVED)
                                                    <button onclick="reviewDeposit('{{ $deposit->id }}')"
                                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                                        </svg>
                                                        Mark In Review
                                                    </button>
                                                @endif

                                                <!-- Delete Button -->
                                                <button onclick="deleteDeposit('{{ $deposit->id }}')" 
                                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-gray-600 rounded-lg hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="p-8 text-center text-gray-500 dark:text-gray-400">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <p class="text-lg font-medium">No deposits found</p>
                                                <p class="text-sm">There are no deposit transactions to display</p>
                                            </div>
                        </td>
                  </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

<!-- Deposit Details Modal -->
<div id="depositDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full border border-gray-200 dark:border-gray-700">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Deposit Details</h3>
                <button onclick="closeDepositModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
            
            <!-- Modal Body -->
            <div id="depositModalContent" class="p-6">
                <!-- Content will be loaded dynamically -->
                                </div>
                                </div>
                            </div>
                        </div>

<!-- Confirmation Modal -->
<div id="confirmationModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div id="confirmationIcon" class="w-12 h-12 rounded-full flex items-center justify-center mr-4">
                        <!-- Icon will be set dynamically -->
                    </div>
                    <h3 id="confirmationTitle" class="text-lg font-semibold text-gray-900 dark:text-white"></h3>
                </div>
                <p id="confirmationMessage" class="text-gray-600 dark:text-gray-400 mb-6"></p>
                <div class="flex justify-end space-x-3">
                    <button onclick="closeConfirmationModal()" class="px-4 py-2 text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <button id="confirmActionBtn" class="px-4 py-2 text-white rounded-lg">
                        Confirm
                    </button>
            </div>
          </div>
      </div>
    </div>
</div>

<script>
    // View deposit details
    function viewDepositDetails(depositId) {
        // Show loading state
        document.getElementById('depositModalContent').innerHTML = `
            <div class="flex items-center justify-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="ml-2 text-gray-600 dark:text-gray-400">Loading...</span>
            </div>
        `;
        
        document.getElementById('depositDetailsModal').classList.remove('hidden');
        
        // Fetch deposit details
        fetch(`/admin/deposit/${encodeURIComponent(depositId)}/details`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        })
            .then(async response => {
                if (!response.ok) {
                    const errorText = await response.text();
                    throw new Error(errorText || 'Unable to fetch deposit details.');
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.html) {
                    document.getElementById('depositModalContent').innerHTML = data.html;
                } else {
                    const message = data.message ?? 'Failed to load deposit details';
                    document.getElementById('depositModalContent').innerHTML = `
                        <div class="text-center py-8">
                            <p class="text-red-600 dark:text-red-400">${message}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Deposit details fetch failed', error);
                document.getElementById('depositModalContent').innerHTML = `
                    <div class="text-center py-8">
                        <p class="text-red-600 dark:text-red-400">Error loading deposit details. Please try again.</p>
                    </div>
                `;
            });
    }

    // Close deposit modal
    function closeDepositModal() {
        document.getElementById('depositDetailsModal').classList.add('hidden');
    }

    // Approve deposit
    function approveDeposit(depositId) {
        showConfirmationModal(
            'Approve Deposit',
            'Are you sure you want to approve this deposit? This will credit (or re-credit) the user\'s account.',
            'bg-green-100 text-green-600',
            'Approve',
            'bg-green-600 hover:bg-green-700',
            () => {
                // Create a form to submit POST request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/deposit/${depositId}/approve`;
                
                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        );
    }

    // Move deposit to review
    function reviewDeposit(depositId) {
        showConfirmationModal(
            'Move Deposit to Review',
            'This will remove the previously credited funds until the deposit is approved again. Continue?',
            'bg-blue-100 text-blue-600',
            'Move to Review',
            'bg-blue-600 hover:bg-blue-700',
            () => {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/deposit/${depositId}/review`;

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);

                document.body.appendChild(form);
                form.submit();
            }
        );
    }

    // Decline deposit
    function declineDeposit(depositId) {
        showConfirmationModal(
            'Decline Deposit',
            'Are you sure you want to decline this deposit? This action cannot be undone.',
            'bg-red-100 text-red-600',
            'Decline',
            'bg-red-600 hover:bg-red-700',
            () => {
                // Create a form to submit POST request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/deposit/${depositId}/decline`;
                
                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        );
    }

    // Delete deposit
    function deleteDeposit(depositId) {
        showConfirmationModal(
            'Delete Deposit',
            'Are you sure you want to delete this deposit? This action cannot be undone.',
            'bg-red-100 text-red-600',
            'Delete',
            'bg-red-600 hover:bg-red-700',
            () => {
                // Create form to submit delete request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/deposit/${depositId}/delete`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                document.body.appendChild(form);
                form.submit();
            }
        );
    }

    // Show confirmation modal
    function showConfirmationModal(title, message, iconClass, buttonText, buttonClass, onConfirm) {
        document.getElementById('confirmationTitle').textContent = title;
        document.getElementById('confirmationMessage').textContent = message;
        document.getElementById('confirmationIcon').className = `w-12 h-12 rounded-full flex items-center justify-center mr-4 ${iconClass}`;
        
        const confirmBtn = document.getElementById('confirmActionBtn');
        confirmBtn.textContent = buttonText;
        confirmBtn.className = `px-4 py-2 text-white rounded-lg ${buttonClass}`;
        
        confirmBtn.onclick = () => {
            closeConfirmationModal();
            onConfirm();
        };
        
        document.getElementById('confirmationModal').classList.remove('hidden');
    }

    // Close confirmation modal
    function closeConfirmationModal() {
        document.getElementById('confirmationModal').classList.add('hidden');
    }

    // Close modals when clicking outside
    document.addEventListener('click', function(event) {
        const depositModal = document.getElementById('depositDetailsModal');
        const confirmationModal = document.getElementById('confirmationModal');
        
        if (event.target === depositModal) {
            closeDepositModal();
        }
        
        if (event.target === confirmationModal) {
            closeConfirmationModal();
        }
    });
</script>

@endsection
