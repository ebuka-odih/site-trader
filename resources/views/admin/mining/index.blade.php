@extends('admin.layouts.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Success/Error Messages -->
    @if(session()->has('success'))
        <div class="mb-6 bg-green-900 border border-green-700 text-green-100 px-4 py-3 rounded-lg">
            {{ session()->get('success') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="mb-6 bg-red-900 border border-red-700 text-red-100 px-4 py-3 rounded-lg">
            {{ session()->get('error') }}
        </div>
    @endif

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Mining Management</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage user mining activities and statistics</p>
        </div>
        <div>
            <a href="{{ route('admin.mining.statistics') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span>Statistics</span>
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Mining</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['total_mining']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Mining</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['active_mining']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Invested</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">${{ number_format($stats['total_invested'], 2) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Mined</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['total_mined'], 8) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mining Activities Table -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Mining Activities</h3>
        </div>
        
        @if($miningActivities->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount Invested</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Mined</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Current Value</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Start Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($miningActivities as $mining)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $mining->user->name ?? 'Unknown' }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $mining->user->email ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">{{ $mining->plan->name ?? 'Unknown Plan' }}</div>
                            @if($mining->plan->hashrate)
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $mining->plan->hashrate }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">${{ number_format($mining->amount_invested, 2) }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $mining->currency }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ number_format($mining->total_mined, 8) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm {{ $mining->current_value >= $mining->amount_invested ? 'text-green-600' : 'text-red-600' }}">
                                ${{ number_format($mining->current_value, 2) }}
                            </div>
                            @php
                                $pnl = $mining->current_value - $mining->amount_invested;
                                $pnlPercent = $mining->amount_invested > 0 ? ($pnl / $mining->amount_invested) * 100 : 0;
                            @endphp
                            <div class="text-sm {{ $pnl >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $pnl >= 0 ? '+' : '' }}${{ number_format($pnl, 2) }} ({{ $pnl >= 0 ? '+' : '' }}{{ number_format($pnlPercent, 2) }}%)
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($mining->status === 'active')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            @elseif($mining->status === 'completed')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Completed</span>
                            @elseif($mining->status === 'cancelled')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Cancelled</span>
                            @elseif($mining->status === 'suspended')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Suspended</span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($mining->status) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $mining->start_date ? $mining->start_date->format('M d, Y') : 'N/A' }}
                            @if($mining->last_mining_date)
                            <div class="text-xs">Last: {{ $mining->last_mining_date->format('M d') }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-1">
                                <button onclick="openEditModal({{ $mining->id }}, {{ $mining->total_mined ?? 0 }}, {{ $mining->current_value ?? 0 }}, '{{ $mining->status }}', '{{ $mining->notes ?? '' }}')" 
                                        class="px-2 py-1 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded transition-colors">
                                    Edit
                                </button>
                                
                                @if($mining->status === 'active')
                                    <form method="POST" action="{{ route('admin.mining.suspend', $mining->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="px-2 py-1 text-xs bg-yellow-600 hover:bg-yellow-700 text-white rounded transition-colors" 
                                                onclick="return confirm('Suspend this mining activity?')">
                                            Suspend
                                        </button>
                                    </form>
                                @elseif($mining->status === 'suspended')
                                    <form method="POST" action="{{ route('admin.mining.resume', $mining->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="px-2 py-1 text-xs bg-green-600 hover:bg-green-700 text-white rounded transition-colors" 
                                                onclick="return confirm('Resume this mining activity?')">
                                            Resume
                                        </button>
                                    </form>
                                @endif
                                
                                @if($mining->status === 'active')
                                    <form method="POST" action="{{ route('admin.mining.cancel', $mining->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="px-2 py-1 text-xs bg-orange-600 hover:bg-orange-700 text-white rounded transition-colors" 
                                                onclick="return confirm('Cancel this mining activity? Current value will be transferred to user balance.')">
                                            Cancel
                                        </button>
                                    </form>
                                @endif
                                
                                <form method="POST" action="{{ route('admin.mining.destroy', $mining->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-2 py-1 text-xs bg-red-600 hover:bg-red-700 text-white rounded transition-colors" 
                                            onclick="return confirm('Delete this mining activity? This action cannot be undone.')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $miningActivities->links() }}
        </div>
        @else
        <div class="px-6 py-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No Mining Activities</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No users have started mining activities yet.</p>
        </div>
        @endif
    </div>
</div>

<!-- Edit Mining Modal -->
<div id="editMiningModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Edit Mining Stats</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Form -->
            <form id="editMiningForm" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <!-- Total Mined -->
                    <div>
                        <label for="total_mined" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Mined</label>
                        <input type="number" 
                               id="total_mined" 
                               name="total_mined" 
                               step="0.00000001" 
                               min="0" 
                               required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Current Value -->
                    <div>
                        <label for="current_value" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Value ({{ auth()->user()->currency ?? 'USD' }})</label>
                        <input type="number" 
                               id="current_value" 
                               name="current_value" 
                               step="0.01" 
                               min="0" 
                               required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select id="status" 
                                name="status" 
                                required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="active">Active</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                        <textarea id="notes" 
                                  name="notes" 
                                  rows="3" 
                                  maxlength="1000"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"></textarea>
                    </div>

                    <!-- PnL Display -->
                    <div id="pnlDisplay" class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                        <div class="text-sm text-gray-600 dark:text-gray-400">PnL Calculation</div>
                        <div id="pnlValue" class="text-sm font-medium">$0.00 (0.00%)</div>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" 
                            onclick="closeEditModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 rounded-md">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                        Update Stats
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let currentMiningId = null;
    let currentAmountInvested = 0;

    function openEditModal(id, totalMined, currentValue, status, notes) {
        currentMiningId = id;
        
        // Set form values
        document.getElementById('total_mined').value = totalMined;
        document.getElementById('current_value').value = currentValue;
        document.getElementById('status').value = status;
        document.getElementById('notes').value = notes;
        
        // Set form action
        document.getElementById('editMiningForm').action = `/admin/mining/${id}`;
        
        // Get amount invested from the table row (we'll need to pass this)
        // For now, we'll calculate it from the current row data
        const row = event.target.closest('tr');
        const amountText = row.querySelector('td:nth-child(3)').textContent;
        currentAmountInvested = parseFloat(amountText.replace(/[$,]/g, ''));
        
        // Update PnL display
        updatePnLDisplay();
        
        // Show modal
        document.getElementById('editMiningModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editMiningModal').classList.add('hidden');
        currentMiningId = null;
    }

    function updatePnLDisplay() {
        const currentValue = parseFloat(document.getElementById('current_value').value) || 0;
        const pnl = currentValue - currentAmountInvested;
        const pnlPercent = currentAmountInvested > 0 ? (pnl / currentAmountInvested) * 100 : 0;
        
        const pnlElement = document.getElementById('pnlValue');
        pnlElement.textContent = `${pnl >= 0 ? '+' : ''}$${pnl.toFixed(2)} (${pnl >= 0 ? '+' : ''}${pnlPercent.toFixed(2)}%)`;
        pnlElement.className = `text-sm font-medium ${pnl >= 0 ? 'text-green-600' : 'text-red-600'}`;
    }

    // Add event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Update PnL when current value changes
        document.getElementById('current_value').addEventListener('input', updatePnLDisplay);
        
        // Close modal when clicking outside
        document.getElementById('editMiningModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    });
</script>
@endpush