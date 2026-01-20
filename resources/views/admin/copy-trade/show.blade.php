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
  <div class="mb-6">
    <!-- Back Button -->
    <div class="mb-4">
      <a href="{{ route('admin.copied-trades.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Back to Copied Trades
      </a>
    </div>
    
    <!-- Title and Status -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Copied Trade Details</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">View and manage copied trade information</p>
      </div>
      <div class="flex items-center gap-2">
        @if($copiedTrade->status == 1)
          <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Active</span>
        @else
          <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">Inactive</span>
        @endif
      </div>
    </div>
  </div>

  <!-- Main Content Grid -->
  <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <!-- Left Column - Main Details -->
    <div class="xl:col-span-2 space-y-6">
      <!-- Trade Information -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Trade Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">User</label>
            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $copiedTrade->user->name ?? '—' }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $copiedTrade->user->email ?? '—' }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Copy Trader</label>
            <div class="mt-1 flex items-center gap-2">
              @if($copiedTrade->copy_trader)
                <img src="{{ $copiedTrade->copy_trader->avatar_url }}" alt="{{ $copiedTrade->copy_trader->name }}" class="h-8 w-8 rounded-full border border-gray-300 dark:border-gray-600 object-cover" onerror="this.src='{{ asset('img/trader.jpg') }}'">
                <p class="text-sm text-gray-900 dark:text-white">{{ $copiedTrade->copy_trader->name }}</p>
              @else
                <p class="text-sm text-gray-500 dark:text-gray-400">—</p>
              @endif
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Investment Amount</label>
            <p class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">${{ number_format($copiedTrade->amount, 2) }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current PnL</label>
            <p class="mt-1 text-sm font-semibold {{ ($copiedTrade->pnl ?? 0) >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
              ${{ number_format($copiedTrade->pnl ?? 0, 2) }}
            </p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Trade Count</label>
            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $copiedTrade->trade_count ?? 0 }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Wins / Losses</label>
            <p class="mt-1 text-sm text-gray-900 dark:text-white">
              <span class="text-green-600 dark:text-green-400">{{ $copiedTrade->win ?? 0 }}</span> / 
              <span class="text-red-600 dark:text-red-400">{{ $copiedTrade->loss ?? 0 }}</span>
            </p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Created At</label>
            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $copiedTrade->created_at->format('M d, Y g:i A') }}</p>
          </div>
          @if($copiedTrade->stopped_at)
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stopped At</label>
            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $copiedTrade->stopped_at->format('M d, Y g:i A') }}</p>
          </div>
          @endif
        </div>
      </div>

      <!-- PNL History Section -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">PNL History</h3>
          <button onclick="openPnlHistoryModal({{ $copiedTrade->id }})" class="px-3 py-1.5 text-xs rounded-md bg-purple-600 text-white hover:bg-purple-700 transition-colors">Manage PNL History</button>
        </div>
        
        @if($copiedTrade->pnl_histories && $copiedTrade->pnl_histories->count() > 0)
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-900/50">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">PNL</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($copiedTrade->pnl_histories as $pnlHistory)
                <tr>
                  <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                    {{ $pnlHistory->created_at->format('M d, Y g:i A') }}
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold {{ $pnlHistory->pnl >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                    ${{ number_format($pnlHistory->pnl, 2) }}
                  </td>
                  <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                    {{ $pnlHistory->description ?? '—' }}
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="text-center py-8">
            <p class="text-sm text-gray-500 dark:text-gray-400">No PNL history entries yet.</p>
          </div>
        @endif
      </div>
    </div>

    <!-- Right Column - Actions -->
    <div class="space-y-6">
      <!-- Actions Card -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Actions</h3>
        <div class="space-y-3">
          <button onclick="openEditModal({{ $copiedTrade->id }}, {{ $copiedTrade->trade_count ?? 0 }}, {{ $copiedTrade->win ?? 0 }}, {{ $copiedTrade->loss ?? 0 }}, {{ $copiedTrade->pnl ?? 0 }})" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
            Edit Performance Metrics
          </button>
          
          @if($copiedTrade->status == 1)
            <form method="POST" action="{{ route('admin.copied-trades.deactivate', $copiedTrade->id) }}" onsubmit="return confirm('Stop this copied trade? PnL will be transferred to user balance.');">
              @csrf
              <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                Stop Copy Trade
              </button>
            </form>
          @else
            <form method="POST" action="{{ route('admin.copied-trades.activate', $copiedTrade->id) }}" onsubmit="return confirm('Start this copied trade? Only admin can restart stopped trades.');">
              @csrf
              <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                Activate Copy Trade
              </button>
            </form>
          @endif
          
          <form method="POST" action="{{ route('admin.copied-trades.destroy', $copiedTrade->id) }}" onsubmit="return confirm('Are you sure you want to delete this copied trade? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
              Delete Copy Trade
            </button>
          </form>
        </div>
      </div>

      <!-- Performance Summary -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Performance Summary</h3>
        <div class="space-y-3">
          @php
            $totalTrades = ($copiedTrade->win ?? 0) + ($copiedTrade->loss ?? 0);
            $winRate = $totalTrades > 0 ? round((($copiedTrade->win ?? 0) / $totalTrades) * 100, 1) : 0;
            $roi = $copiedTrade->amount > 0 ? (($copiedTrade->pnl ?? 0) / $copiedTrade->amount) * 100 : 0;
          @endphp
          <div>
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Win Rate</label>
            <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $winRate }}%</p>
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">ROI</label>
            <p class="mt-1 text-lg font-semibold {{ $roi >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
              {{ number_format($roi, 2) }}%
            </p>
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Total PNL History Entries</label>
            <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $copiedTrade->pnl_histories->count() ?? 0 }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Edit PnL Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center">
  <div class="relative p-6 border w-80 shadow-lg rounded-md bg-white dark:bg-gray-800">
    <div class="mt-3">
      <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Edit Performance Metrics</h3>
      <form id="editPnlForm" method="POST">
        @csrf
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Trade Count</label>
          <input type="number" id="trade_count" name="trade_count" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Wins</label>
          <input type="number" id="win" name="win" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Losses</label>
          <input type="number" id="loss" name="loss" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
        </div>
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Profit/Loss ($)</label>
          <input type="number" id="pnl" name="pnl" step="0.01" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
        </div>
        <div class="flex justify-end space-x-3">
          <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">Cancel</button>
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- PNL History Modal -->
<div id="pnlHistoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
  <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white dark:bg-gray-800">
    <div class="mt-3">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">PNL History</h3>
        <button onclick="closePnlHistoryModal()" class="text-gray-400 hover:text-gray-600">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
      
      <!-- Add/Edit Form -->
      <form id="pnlHistoryForm" method="POST" action="" class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg" onsubmit="handlePnlHistorySubmit(event)">
        @csrf
        <input type="hidden" id="pnlHistoryTradeId" name="copied_trade_id">
        <input type="hidden" id="pnlHistoryId" name="pnl_history_id">
        <h4 id="pnlHistoryFormTitle" class="text-md font-medium text-gray-900 dark:text-white mb-4">Add PNL Entry</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">PNL Amount ($)</label>
            <input type="number" id="pnlHistoryPnl" name="pnl" step="0.01" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description (Optional)</label>
            <input type="text" id="pnlHistoryDescription" name="description" maxlength="1000" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white">
          </div>
        </div>
        <div class="mt-4 flex justify-end space-x-3">
          <button type="button" onclick="resetPnlHistoryForm()" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">Reset</button>
          <button type="button" onclick="closePnlHistoryModal()" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">Cancel</button>
          <button type="submit" id="pnlHistorySubmitBtn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add</button>
        </div>
      </form>
      
      <!-- PNL History Table -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-900/50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">PNL</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody id="pnlHistoryTableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <tr>
              <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Loading...</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
function openEditModal(id, tradeCount, win, loss, pnl) {
  document.getElementById('editPnlForm').action = '/admin/copied-trades/' + id + '/edit-pnl';
  document.getElementById('trade_count').value = tradeCount;
  document.getElementById('win').value = win;
  document.getElementById('loss').value = loss;
  document.getElementById('pnl').value = pnl;
  document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
  document.getElementById('editModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('editModal').addEventListener('click', function(e) {
  if (e.target === this) {
    closeEditModal();
  }
});

// PNL History Modal Functions
function openPnlHistoryModal(tradeId) {
  document.getElementById('pnlHistoryModal').classList.remove('hidden');
  document.getElementById('pnlHistoryTradeId').value = tradeId;
  const form = document.getElementById('pnlHistoryForm');
  form.action = `/admin/copied-trades/${tradeId}/pnl-history`;
  form.method = 'POST';
  loadPnlHistory(tradeId);
}

function closePnlHistoryModal() {
  document.getElementById('pnlHistoryModal').classList.add('hidden');
  const form = document.getElementById('pnlHistoryForm');
  form.reset();
  const tradeId = document.getElementById('pnlHistoryTradeId').value;
  if (tradeId) {
    form.action = `/admin/copied-trades/${tradeId}/pnl-history`;
  }
  form.method = 'POST';
  document.getElementById('pnlHistoryId').value = '';
  document.getElementById('pnlHistoryFormTitle').textContent = 'Add PNL Entry';
  document.getElementById('pnlHistorySubmitBtn').textContent = 'Add';
  
  // Remove any _method input
  const existingMethod = form.querySelector('input[name="_method"]');
  if (existingMethod) {
    existingMethod.remove();
  }
}

function loadPnlHistory(tradeId) {
  fetch(`/admin/copied-trades/${tradeId}/pnl-history`, {
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json'
    }
  })
    .then(response => response.json())
    .then(data => {
      const tbody = document.getElementById('pnlHistoryTableBody');
      tbody.innerHTML = '';
      
      if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No PNL history entries yet.</td></tr>';
        return;
      }
      
      data.forEach(entry => {
        const row = document.createElement('tr');
        row.className = 'bg-white dark:bg-gray-800';
        row.innerHTML = `
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">${new Date(entry.created_at).toLocaleDateString()}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm ${entry.pnl >= 0 ? 'text-green-600' : 'text-red-600'}">$${parseFloat(entry.pnl).toFixed(2)}</td>
          <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">${entry.description || '—'}</td>
          <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
            <button onclick="editPnlHistoryEntry(${entry.id}, ${entry.pnl}, '${(entry.description || '').replace(/'/g, "\\'")}')" class="px-2 py-1 text-xs rounded-md bg-blue-600 text-white hover:bg-blue-700 transition-colors mr-2">Edit</button>
            <form method="POST" action="/admin/copied-trades/pnl-history/${entry.id}" onsubmit="return confirm('Are you sure you want to delete this PNL entry?');" class="inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="px-2 py-1 text-xs rounded-md bg-red-600 text-white hover:bg-red-700 transition-colors">Delete</button>
            </form>
          </td>
        `;
        tbody.appendChild(row);
      });
    })
    .catch(error => {
      console.error('Error loading PNL history:', error);
      document.getElementById('pnlHistoryTableBody').innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center text-sm text-red-500">Error loading PNL history.</td></tr>';
    });
}

function resetPnlHistoryForm() {
  const form = document.getElementById('pnlHistoryForm');
  const tradeId = document.getElementById('pnlHistoryTradeId').value;
  form.reset();
  document.getElementById('pnlHistoryTradeId').value = tradeId;
  document.getElementById('pnlHistoryId').value = '';
  document.getElementById('pnlHistoryFormTitle').textContent = 'Add PNL Entry';
  document.getElementById('pnlHistorySubmitBtn').textContent = 'Add';
  form.action = `/admin/copied-trades/${tradeId}/pnl-history`;
  form.method = 'POST';
  
  // Remove any _method input
  const existingMethod = form.querySelector('input[name="_method"]');
  if (existingMethod) {
    existingMethod.remove();
  }
}

function editPnlHistoryEntry(id, pnl, description) {
  document.getElementById('pnlHistoryId').value = id;
  document.getElementById('pnlHistoryPnl').value = pnl;
  document.getElementById('pnlHistoryDescription').value = description;
  document.getElementById('pnlHistoryFormTitle').textContent = 'Edit PNL Entry';
  document.getElementById('pnlHistorySubmitBtn').textContent = 'Update';
  
  // Update form action and method
  const form = document.getElementById('pnlHistoryForm');
  form.action = `/admin/copied-trades/pnl-history/${id}`;
  
  // Remove existing _method input if any
  const existingMethod = form.querySelector('input[name="_method"]');
  if (existingMethod) {
    existingMethod.remove();
  }
  
  // Add PUT method
  const methodInput = document.createElement('input');
  methodInput.type = 'hidden';
  methodInput.name = '_method';
  methodInput.value = 'PUT';
  form.appendChild(methodInput);
}

function handlePnlHistorySubmit(event) {
  event.preventDefault();
  const form = event.target;
  const formData = new FormData(form);
  const tradeId = document.getElementById('pnlHistoryTradeId').value;
  const pnlHistoryId = document.getElementById('pnlHistoryId').value;
  
  let url, method;
  if (pnlHistoryId) {
    url = `/admin/copied-trades/pnl-history/${pnlHistoryId}`;
    method = 'POST';
    formData.append('_method', 'PUT');
  } else {
    url = `/admin/copied-trades/${tradeId}/pnl-history`;
    method = 'POST';
  }
  
  fetch(url, {
    method: method,
    body: formData,
    headers: {
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
    .then(response => {
      if (response.ok) {
        return response.text();
      }
      throw new Error('Network response was not ok');
    })
    .then(data => {
      // Reload the page to show success message and updated data
      window.location.reload();
    })
    .catch(error => {
      console.error('Error:', error);
      alert('An error occurred. Please try again.');
    });
}

// Close PNL History modal when clicking outside
document.getElementById('pnlHistoryModal').addEventListener('click', function(e) {
  if (e.target === this) {
    closePnlHistoryModal();
  }
});
</script>
@endsection

