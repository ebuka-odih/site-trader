@extends('admin.layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Notification Management</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Send notifications to users and manage notification history.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" id="total-users">{{ $users->count() }}</p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Users</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400" id="active-users">{{ $users->where('status', 'active')->count() }}</p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Notifications</p>
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400" id="total-notifications">0</p>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM4 15h6v-2H4v2zM4 11h6V9H4v2zM4 7h6V5H4v2zM4 3h6V1H4v2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <!-- Tab Headers -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button id="send-tab" class="tab-button active py-4 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600 dark:text-blue-400">
                    Send Notification
                </button>
                <button id="history-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300">
                    Notification History
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Send Notification Tab -->
            <div id="send-tab-content" class="tab-content">
                <form id="notification-form" action="{{ route('admin.notifications.send') }}" method="POST">
                    @csrf
                    
                    <!-- Recipient Type Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Recipient Type</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="recipient_type" value="selected" class="form-radio text-blue-600" checked>
                                <span class="ml-2 text-gray-700 dark:text-gray-300">Selected Users</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="recipient_type" value="all" class="form-radio text-blue-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">All Users</span>
                            </label>
                        </div>
                    </div>

            <!-- User Selection (shown when "Selected Users" is chosen) -->
            <div id="user-selection" class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Select Users</label>
                <select id="user-select" multiple class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" size="8">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Hold Ctrl (or Cmd on Mac) to select multiple users</p>
            </div>

                    <!-- Notification Type -->
                    <div class="mb-6">
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notification Type</label>
                        <select name="type" id="type" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <option value="">Select notification type</option>
                            <option value="system">System Notification</option>
                            <option value="announcement">Announcement</option>
                            <option value="maintenance">Maintenance Alert</option>
                            <option value="security">Security Alert</option>
                            <option value="update">Platform Update</option>
                        </select>
                    </div>

                    <!-- Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title</label>
                        <input type="text" name="title" id="title" required maxlength="255" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                               placeholder="Enter notification title...">
                    </div>

                    <!-- Message -->
                    <div class="mb-6">
                        <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Message</label>
                        <textarea name="message" id="message" required maxlength="1000" rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                  placeholder="Enter your message..."></textarea>
                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            <span id="char-count">0</span>/1000 characters
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4">
                        <button type="button" id="preview-btn" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-2 focus:ring-blue-500">
                            Preview
                        </button>
                        <button type="submit" id="send-btn" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="send-btn-text">Send Notification</span>
                            <svg id="send-btn-spinner" class="hidden animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Notification History Tab -->
            <div id="history-tab-content" class="tab-content hidden">
                <!-- Filters -->
                <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type</label>
                            <select id="filter-type" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-600 dark:text-white">
                                <option value="">All Types</option>
                                <option value="system">System</option>
                                <option value="announcement">Announcement</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="security">Security</option>
                                <option value="update">Update</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date From</label>
                            <input type="date" id="filter-date-from" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-600 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date To</label>
                            <input type="date" id="filter-date-to" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-600 dark:text-white">
                        </div>
                        <div class="flex items-end">
                            <button id="filter-btn" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                                Filter
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Notifications Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Message</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="notifications-table-body" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <!-- Table content will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div id="pagination-container" class="mt-6 flex items-center justify-between">
                    <!-- Pagination will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div id="preview-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Notification Preview</h3>
                    <button id="close-preview" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <div class="flex items-center mb-2">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                        <span id="preview-type" class="text-sm font-medium text-gray-600 dark:text-gray-400"></span>
                    </div>
                    <h4 id="preview-title" class="font-semibold text-gray-900 dark:text-white mb-2"></h4>
                    <p id="preview-message" class="text-gray-700 dark:text-gray-300 text-sm"></p>
                </div>
                <div class="mt-4">
                    <p id="preview-recipients" class="text-sm text-gray-600 dark:text-gray-400"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="edit-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Edit Notification</h3>
                    <button id="close-edit" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="edit-form">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type</label>
                            <select id="edit-type" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                <option value="system">System Notification</option>
                                <option value="announcement">Announcement</option>
                                <option value="maintenance">Maintenance Alert</option>
                                <option value="security">Security Alert</option>
                                <option value="update">Platform Update</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title</label>
                            <input type="text" id="edit-title" required maxlength="255" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Message</label>
                            <textarea id="edit-message" required maxlength="1000" rows="4" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-4">
                        <button type="button" id="cancel-edit" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                            Update Notification
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const users = @json($users);
    let currentNotificationId = null;
    let currentPage = 1;

    // Tab functionality
    const sendTab = document.getElementById('send-tab');
    const historyTab = document.getElementById('history-tab');
    const sendTabContent = document.getElementById('send-tab-content');
    const historyTabContent = document.getElementById('history-tab-content');

    sendTab.addEventListener('click', function() {
        switchTab('send');
    });

    historyTab.addEventListener('click', function() {
        switchTab('history');
        loadNotifications();
    });

    function switchTab(tab) {
        // Update tab buttons
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active', 'border-blue-500', 'text-blue-600', 'dark:text-blue-400');
            btn.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
        });

        // Hide all tab content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        if (tab === 'send') {
            sendTab.classList.add('active', 'border-blue-500', 'text-blue-600', 'dark:text-blue-400');
            sendTab.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            sendTabContent.classList.remove('hidden');
        } else {
            historyTab.classList.add('active', 'border-blue-500', 'text-blue-600', 'dark:text-blue-400');
            historyTab.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            historyTabContent.classList.remove('hidden');
        }
    }

    // Load notifications history
    function loadNotifications(page = 1) {
        const type = document.getElementById('filter-type').value;
        const dateFrom = document.getElementById('filter-date-from').value;
        const dateTo = document.getElementById('filter-date-to').value;

        const params = new URLSearchParams({
            page: page,
            ...(type && { type: type }),
            ...(dateFrom && { date_from: dateFrom }),
            ...(dateTo && { date_to: dateTo })
        });

        fetch(`{{ route('admin.notifications.history') }}?${params}`)
            .then(response => response.json())
            .then(data => {
                renderNotificationsTable(data.data);
                renderPagination(data);
                document.getElementById('total-notifications').textContent = data.total;
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
            });
    }

    // Render notifications table
    function renderNotificationsTable(notifications) {
        const tbody = document.getElementById('notifications-table-body');
        tbody.innerHTML = '';

        if (notifications.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                        No notifications found
                    </td>
                </tr>
            `;
            return;
        }

        notifications.forEach(notification => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">${notification.user.name}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">${notification.user.email}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getTypeBadgeClass(notification.type)}">
                        ${notification.type}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                    ${notification.title}
                </td>
                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                    ${notification.message}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${notification.read_at ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'}">
                        ${notification.read_at ? 'Read' : 'Unread'}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                    ${new Date(notification.created_at).toLocaleDateString()}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <button onclick="viewNotification(${notification.id})" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">View</button>
                    <button onclick="editNotification(${notification.id})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">Edit</button>
                    <button onclick="deleteNotification(${notification.id})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    // Get type badge class
    function getTypeBadgeClass(type) {
        const classes = {
            'system': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'announcement': 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
            'maintenance': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'security': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            'update': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
        };
        return classes[type] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
    }

    // Render pagination
    function renderPagination(data) {
        const container = document.getElementById('pagination-container');
        if (data.last_page <= 1) {
            container.innerHTML = '';
            return;
        }

        let pagination = '<div class="flex items-center justify-between"><div class="flex-1 flex justify-between sm:hidden">';
        
        if (data.prev_page_url) {
            pagination += `<a href="#" onclick="loadNotifications(${data.current_page - 1})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">Previous</a>`;
        }
        
        if (data.next_page_url) {
            pagination += `<a href="#" onclick="loadNotifications(${data.current_page + 1})" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">Next</a>`;
        }
        
        pagination += '</div><div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">';
        pagination += `<div><p class="text-sm text-gray-700 dark:text-gray-300">Showing <span class="font-medium">${data.from}</span> to <span class="font-medium">${data.to}</span> of <span class="font-medium">${data.total}</span> results</p></div>`;
        pagination += '<div><nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">';
        
        // Previous button
        if (data.prev_page_url) {
            pagination += `<a href="#" onclick="loadNotifications(${data.current_page - 1})" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">Previous</a>`;
        }
        
        // Page numbers
        for (let i = 1; i <= data.last_page; i++) {
            if (i === data.current_page) {
                pagination += `<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-blue-50 text-sm font-medium text-blue-600 dark:bg-blue-900 dark:text-blue-200">${i}</span>`;
            } else {
                pagination += `<a href="#" onclick="loadNotifications(${i})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">${i}</a>`;
            }
        }
        
        // Next button
        if (data.next_page_url) {
            pagination += `<a href="#" onclick="loadNotifications(${data.current_page + 1})" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">Next</a>`;
        }
        
        pagination += '</nav></div></div></div>';
        container.innerHTML = pagination;
    }

    // Filter functionality
    document.getElementById('filter-btn').addEventListener('click', function() {
        loadNotifications(1);
    });

    // Global functions for notification actions
    window.viewNotification = function(id) {
        fetch(`{{ route('admin.notifications.show', '') }}/${id}`)
            .then(response => response.json())
            .then(data => {
                alert(`Notification Details:\n\nUser: ${data.user_name} (${data.user_email})\nType: ${data.type}\nTitle: ${data.title}\nMessage: ${data.message}\nCreated: ${data.created_at}\nRead: ${data.read_at || 'Not read'}`);
            })
            .catch(error => {
                console.error('Error loading notification:', error);
                alert('Error loading notification details');
            });
    };

    window.editNotification = function(id) {
        currentNotificationId = id;
        fetch(`{{ route('admin.notifications.show', '') }}/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit-type').value = data.type;
                document.getElementById('edit-title').value = data.title;
                document.getElementById('edit-message').value = data.message;
                document.getElementById('edit-modal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error loading notification:', error);
                alert('Error loading notification details');
            });
    };

    window.deleteNotification = function(id) {
        if (confirm('Are you sure you want to delete this notification?')) {
            fetch(`{{ route('admin.notifications.destroy', '') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Notification deleted successfully');
                    loadNotifications(currentPage);
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error deleting notification:', error);
                alert('Error deleting notification');
            });
        }
    };

    // Edit form submission
    document.getElementById('edit-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            type: document.getElementById('edit-type').value,
            title: document.getElementById('edit-title').value,
            message: document.getElementById('edit-message').value
        };

        fetch(`{{ route('admin.notifications.update', '') }}/${currentNotificationId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Notification updated successfully');
                document.getElementById('edit-modal').classList.add('hidden');
                loadNotifications(currentPage);
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error updating notification:', error);
            alert('Error updating notification');
        });
    });

    // Close edit modal
    document.getElementById('close-edit').addEventListener('click', function() {
        document.getElementById('edit-modal').classList.add('hidden');
    });

    document.getElementById('cancel-edit').addEventListener('click', function() {
        document.getElementById('edit-modal').classList.add('hidden');
    });

    // Close edit modal when clicking outside
    document.getElementById('edit-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });

    // Original send notification functionality (keeping existing code)
    const recipientTypeRadios = document.querySelectorAll('input[name="recipient_type"]');
    const userSelection = document.getElementById('user-selection');
    const userSelect = document.getElementById('user-select');
    const messageTextarea = document.getElementById('message');
    const charCount = document.getElementById('char-count');
    const previewBtn = document.getElementById('preview-btn');
    const previewModal = document.getElementById('preview-modal');
    const closePreview = document.getElementById('close-preview');
    const sendBtn = document.getElementById('send-btn');
    const sendBtnText = document.getElementById('send-btn-text');
    const sendBtnSpinner = document.getElementById('send-btn-spinner');
    const form = document.getElementById('notification-form');

    let selectedUserIds = [];

    // Toggle user selection visibility
    recipientTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'selected') {
                userSelection.style.display = 'block';
            } else {
                userSelection.style.display = 'none';
                // Clear selections when switching to "All Users"
                userSelect.selectedIndex = -1;
                selectedUserIds = [];
            }
        });
    });

    // Character count for message
    messageTextarea.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });

    // Handle user selection from dropdown
    userSelect.addEventListener('change', function() {
        selectedUserIds = Array.from(this.selectedOptions).map(option => parseInt(option.value));
        console.log('Selected user IDs:', selectedUserIds);
    });

    // Preview functionality
    previewBtn.addEventListener('click', function() {
        const type = document.getElementById('type').value;
        const title = document.getElementById('title').value;
        const message = document.getElementById('message').value;
        const recipientType = document.querySelector('input[name="recipient_type"]:checked').value;

        if (!type || !title || !message) {
            alert('Please fill in all required fields');
            return;
        }

        if (recipientType === 'selected' && selectedUserIds.length === 0) {
            alert('Please select at least one user');
            return;
        }

        // Update preview content
        document.getElementById('preview-type').textContent = type.charAt(0).toUpperCase() + type.slice(1);
        document.getElementById('preview-title').textContent = title;
        document.getElementById('preview-message').textContent = message;
        
        const recipientCount = recipientType === 'all' ? users.length : selectedUserIds.length;
        document.getElementById('preview-recipients').textContent = `Will be sent to ${recipientCount} user(s)`;

        previewModal.classList.remove('hidden');
    });

    // Close preview modal
    closePreview.addEventListener('click', function() {
        previewModal.classList.add('hidden');
    });

    // Close preview modal when clicking outside
    previewModal.addEventListener('click', function(e) {
        if (e.target === previewModal) {
            previewModal.classList.add('hidden');
        }
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const recipientType = document.querySelector('input[name="recipient_type"]:checked').value;
        
        if (recipientType === 'selected' && selectedUserIds.length === 0) {
            alert('Please select at least one user');
            return;
        }

        // Add selected user IDs to form data
        selectedUserIds.forEach(userId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'user_ids[]';
            input.value = userId;
            form.appendChild(input);
        });

        // Show loading state
        sendBtn.disabled = true;
        sendBtnText.textContent = 'Sending...';
        sendBtnSpinner.classList.remove('hidden');

        // Submit form
        this.submit();
    });
});
</script>
@endpush
@endsection