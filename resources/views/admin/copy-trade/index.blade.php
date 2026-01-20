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

  <div class="mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Copied History</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400">List of all user copied trades</p>
    </div>
  </div>

  <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-900/50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trader</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trade Count</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Win</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loss</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">PnL</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
          @foreach($copiedTrades as $trade)
          <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $trade->created_at->format('M d, Y') }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $trade->user->name ?? '—' }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $trade->copy_trader?->name ?? '—' }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">${{ number_format($trade->amount, 2) }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $trade->trade_count ?? 0 }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $trade->win ?? 0 }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $trade->loss ?? 0 }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
              <span class="{{ ($trade->pnl ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                ${{ number_format($trade->pnl ?? 0, 2) }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              @if($trade->status == 1)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Active</span>
              @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">Inactive</span>
              @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
              <div class="inline-flex items-center gap-2">
                <a href="{{ route('admin.copied-trades.show', $trade->id) }}" class="px-3 py-1.5 text-xs rounded-md bg-indigo-600 text-white hover:bg-indigo-700 transition-colors">View</a>
                <button onclick="openEditModal({{ $trade->id }}, {{ $trade->trade_count ?? 0 }}, {{ $trade->win ?? 0 }}, {{ $trade->loss ?? 0 }}, {{ $trade->pnl ?? 0 }})" class="px-3 py-1.5 text-xs rounded-md bg-blue-600 text-white hover:bg-blue-700 transition-colors">Edit PnL</button>
                <button onclick="openPnlHistoryModal({{ $trade->id }})" class="px-3 py-1.5 text-xs rounded-md bg-purple-600 text-white hover:bg-purple-700 transition-colors">PNL History</button>
                @if($trade->status == 1)
                  <form method="POST" action="{{ route('admin.copied-trades.deactivate', $trade->id) }}" onsubmit="return confirm('Stop this copied trade? PnL will be transferred to user balance.');" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 text-xs rounded-md bg-red-600 text-white hover:bg-red-700 transition-colors">Stop</button>
                  </form>
                @else
                  <form method="POST" action="{{ route('admin.copied-trades.activate', $trade->id) }}" onsubmit="return confirm('Start this copied trade? Only admin can restart stopped trades.');" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 text-xs rounded-md bg-green-600 text-white hover:bg-green-700 transition-colors">Start</button>
                  </form>
                @endif
                <form method="POST" action="{{ route('admin.copied-trades.destroy', $trade->id) }}" onsubmit="return confirm('Are you sure you want to delete this copied trade? This action cannot be undone.');" class="inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="px-3 py-1.5 text-xs rounded-md bg-red-600 text-white hover:bg-red-700 transition-colors">Delete</button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">{{ $copiedTrades->links() }}</div>
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
@endsection
