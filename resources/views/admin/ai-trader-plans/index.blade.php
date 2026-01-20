@extends('admin.layouts.app')

@section('title', 'AI Trader Plans')

@section('content')
<div class="p-4">
    <div class="max-w-7xl mx-auto p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">AI Trader Plans</h1>
                <p class="text-gray-600 dark:text-gray-400">Manage AI trading plans and configurations</p>
            </div>
            <button onclick="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Plan
            </button>
        </div>

        <!-- Plans Table -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 table-auto">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Name</th>
                            <th scope="col" class="px-6 py-3">Price</th>
                            <th scope="col" class="px-6 py-3">Traders</th>
                            <th scope="col" class="px-6 py-3">Investment</th>
                            <th scope="col" class="px-6 py-3">Stocks</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plans as $plan)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4">
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $plan->name }}</div>
                                    @if($plan->description)
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($plan->description, 50) }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $plan->formatted_price }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                    {{ $plan->number_of_traders }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                ${{ number_format($plan->investment_amount, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">{{ $plan->stocks_trading_list }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <button onclick="toggleStatus({{ $plan->id }})" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $plan->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                    {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <button onclick="viewPlan({{ $plan->id }})" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                    <button onclick="editPlan({{ $plan->id }})" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button onclick="deletePlan({{ $plan->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No AI Trader Plans found. <button onclick="openCreateModal()" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Create your first plan</button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($plans->hasPages())
            <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $plans->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Create/Edit Plan Modal -->
<div id="planModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800 max-w-2xl">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modalTitle" class="text-lg font-medium text-gray-900 dark:text-white">Create AI Trader Plan</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="planForm" class="space-y-4">
                @csrf
                <input type="hidden" id="planId" name="id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Plan Name</label>
                        <input type="text" id="name" name="name" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Price ($)</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                    <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"></textarea>
                </div>
                
                <div>
                    <label for="number_of_traders" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Number of Traders</label>
                    <input type="number" id="number_of_traders" name="number_of_traders" min="1" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                </div>
                
                <div>
                    <label for="investment_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Investment Amount ($)</label>
                    <input type="number" id="investment_amount" name="investment_amount" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                </div>
                
                <div>
                    <label for="stocks_trading" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stocks to Trade (comma-separated)</label>
                    <input type="text" id="stocks_trading" name="stocks_trading" placeholder="AAPL, GOOGL, MSFT, TSLA" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Enter stock symbols separated by commas</p>
                </div>
                
                
                <div class="flex items-center justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                        <span id="submitText">Create Plan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Plan Modal -->
<div id="viewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800 max-w-2xl">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Plan Details</h3>
                <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div id="planDetails" class="space-y-4">
                <!-- Plan details will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let isEditMode = false;

function openCreateModal() {
    isEditMode = false;
    document.getElementById('modalTitle').textContent = 'Create AI Trader Plan';
    document.getElementById('submitText').textContent = 'Create Plan';
    document.getElementById('planForm').reset();
    document.getElementById('planId').value = '';
    document.getElementById('planModal').classList.remove('hidden');
}

function editPlan(id) {
    isEditMode = true;
    document.getElementById('modalTitle').textContent = 'Edit AI Trader Plan';
    document.getElementById('submitText').textContent = 'Update Plan';
    
    // Fetch plan data and populate form
    fetch(`/admin/ai-trader-plans/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const plan = data.plan;
                document.getElementById('planId').value = plan.id;
                document.getElementById('name').value = plan.name;
                document.getElementById('description').value = plan.description || '';
                document.getElementById('price').value = plan.price;
                document.getElementById('number_of_traders').value = plan.number_of_traders;
                document.getElementById('investment_amount').value = plan.investment_amount;
                document.getElementById('stocks_trading').value = plan.stocks_trading ? plan.stocks_trading.join(', ') : '';
                
                document.getElementById('planModal').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading plan data');
        });
}

function viewPlan(id) {
    fetch(`/admin/ai-trader-plans/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const plan = data.plan;
                const detailsHtml = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                            <p class="text-gray-900 dark:text-white">${plan.name}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
                            <p class="text-gray-900 dark:text-white">$${plan.price}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Number of Traders</label>
                            <p class="text-gray-900 dark:text-white">${plan.number_of_traders}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Investment Amount</label>
                            <p class="text-gray-900 dark:text-white">$${plan.investment_amount}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stocks Trading</label>
                            <p class="text-gray-900 dark:text-white">${plan.stocks_trading ? plan.stocks_trading.join(', ') : 'None'}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <p class="text-gray-900 dark:text-white">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${plan.is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'}">
                                    ${plan.is_active ? 'Active' : 'Inactive'}
                                </span>
                            </p>
                        </div>
                    </div>
                    ${plan.description ? `
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <p class="text-gray-900 dark:text-white">${plan.description}</p>
                        </div>
                    ` : ''}
                `;
                
                document.getElementById('planDetails').innerHTML = detailsHtml;
                document.getElementById('viewModal').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading plan data');
        });
}

function closeModal() {
    document.getElementById('planModal').classList.add('hidden');
}

function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
}

function toggleStatus(id) {
    if (confirm('Are you sure you want to toggle the status of this plan?')) {
        fetch(`/admin/ai-trader-plans/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error updating plan status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating plan status');
        });
    }
}

function deletePlan(id) {
    if (confirm('Are you sure you want to delete this plan? This action cannot be undone.')) {
        fetch(`/admin/ai-trader-plans/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error deleting plan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting plan');
        });
    }
}

// Form submission
document.getElementById('planForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    
    // Convert stocks_trading string to array
    if (data.stocks_trading) {
        data.stocks_trading = data.stocks_trading.split(',').map(stock => stock.trim()).filter(stock => stock);
    }
    
    // Convert features to array if needed
    if (data.features) {
        data.features = data.features.split(',').map(feature => feature.trim()).filter(feature => feature);
    }
    
    const url = isEditMode ? `/admin/ai-trader-plans/${data.id}` : '/admin/ai-trader-plans';
    const method = isEditMode ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal();
            location.reload();
        } else {
            alert(data.message || 'Error saving plan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving plan');
    });
});
</script>
@endpush
