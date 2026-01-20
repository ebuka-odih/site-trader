@extends('dashboard.layout.app')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Notifications</h1>
            <p class="text-gray-400 mt-1">Stay updated with your account activities</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-2">
            <button id="markAllReadBtn" class="px-2 py-1 text-xs sm:px-3 sm:py-1.5 sm:text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Mark All as Read
            </button>
            <button id="clearAllBtn" class="px-2 py-1 text-xs sm:px-3 sm:py-1.5 sm:text-sm bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                Clear All
            </button>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="border-b border-gray-700">
        <nav class="-mb-px flex space-x-8">
            <button id="allTab" class="tab-button active py-2 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-400">
                All Notifications
            </button>
            <button id="unreadTab" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-400 hover:text-gray-300">
                Unread
            </button>
            <button id="depositTab" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-400 hover:text-gray-300">
                Deposits
            </button>
            <button id="withdrawalTab" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-400 hover:text-gray-300">
                Withdrawals
            </button>
            <button id="tradingTab" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-400 hover:text-gray-300">
                Trading
            </button>
            <button id="systemTab" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-400 hover:text-gray-300">
                System
            </button>
        </nav>
    </div>

    <!-- Notifications List -->
    <div class="space-y-4">
        @forelse($notifications as $notification)
        <div class="notification-item bg-gray-800 rounded-lg border border-gray-700 p-6 hover:border-gray-600 transition-colors {{ !$notification->read_at ? 'border-l-4 border-l-blue-500' : '' }}" 
             data-type="{{ $notification->type }}" 
             data-id="{{ $notification->id }}">
            
            <div class="flex flex-col space-y-4">
                <!-- Top Section: Icon, Title and Message -->
                <div class="flex items-start space-x-4">
                    <!-- Notification Icon -->
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $notification->type === 'deposit' || $notification->type === 'deposit_submitted' || $notification->type === 'deposit_approved' ? 'bg-green-600' : ($notification->type === 'withdrawal' || $notification->type === 'withdrawal_submitted' || $notification->type === 'withdrawal_approved' ? 'bg-red-600' : ($notification->type === 'trading' ? 'bg-blue-600' : ($notification->type === 'copy_trade' || $notification->type === 'copy_trade_started' ? 'bg-purple-600' : ($notification->type === 'copy_trade_stopped' ? 'bg-red-600' : ($notification->type === 'bot_trade' || $notification->type === 'bot_trade_executed' || $notification->type === 'bot_paused' ? 'bg-yellow-600' : ($notification->type === 'bot_created' || $notification->type === 'bot_started' || $notification->type === 'bot_resumed' ? 'bg-green-600' : ($notification->type === 'bot_stopped' ? 'bg-red-600' : 'bg-gray-600'))))))) }}">
                            @if($notification->type === 'deposit' || $notification->type === 'deposit_submitted' || $notification->type === 'deposit_approved')
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            @elseif($notification->type === 'withdrawal' || $notification->type === 'withdrawal_submitted' || $notification->type === 'withdrawal_approved')
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m16 0l-4-4m4 4l-4 4"></path>
                                </svg>
                            @elseif($notification->type === 'trading')
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            @elseif($notification->type === 'copy_trade' || $notification->type === 'copy_trade_started')
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            @elseif($notification->type === 'copy_trade_stopped')
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                </svg>
                            @elseif($notification->type === 'bot_trade' || $notification->type === 'bot_trade_executed')
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            @elseif($notification->type === 'bot_created')
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            @elseif($notification->type === 'bot_started')
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @elseif($notification->type === 'bot_paused')
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @elseif($notification->type === 'bot_resumed')
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @elseif($notification->type === 'bot_stopped')
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6z"></path>
                                </svg>
                            @endif
                        </div>
                    </div>

                    <!-- Notification Content -->
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-semibold text-white">{{ $notification->title }}</h3>
                        <p class="text-gray-300 mt-1">{{ $notification->message }}</p>
                        
                        @if($notification->data)
                            <div class="mt-3 p-3 bg-gray-700 rounded-lg">
                                @if(isset($notification->data['amount']))
                                    <div class="text-sm text-gray-300">
                                        <span class="font-medium">Amount:</span> {{ auth()->user()->formatAmount($notification->data['amount']) }}
                                    </div>
                                @endif
                                @if(isset($notification->data['status']))
                                    <div class="text-sm text-gray-300 mt-1">
                                        <span class="font-medium">Status:</span> 
                                        <span class="px-2 py-1 rounded text-xs {{ $notification->data['status'] === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($notification->data['status']) }}
                                        </span>
                                    </div>
                                @endif
                                @if(isset($notification->data['symbol']))
                                    <div class="text-sm text-gray-300 mt-1">
                                        <span class="font-medium">Symbol:</span> {{ $notification->data['symbol'] }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Bottom Section: Time, Status Badge, and Action Buttons -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-2 border-t border-gray-700">
                    <!-- Time and Status -->
                    <div class="flex items-center space-x-3">
                        <span class="text-sm text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                        @if(!$notification->read_at)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                New
                            </span>
                        @endif
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-2">
                        @if(!$notification->read_at)
                            <button class="mark-read-btn px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors" 
                                    data-id="{{ $notification->id }}">
                                Mark Read
                            </button>
                        @endif
                        <button class="delete-notification-btn px-3 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700 transition-colors" 
                                data-id="{{ $notification->id }}">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-400 mb-2">No notifications</h3>
            <p class="text-gray-500">You're all caught up! New notifications will appear here.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="mt-8">
            {{ $notifications->links() }}
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    const tabs = document.querySelectorAll('.tab-button');
    const notificationItems = document.querySelectorAll('.notification-item');
    
    // Get button references
    const markAllReadBtn = document.getElementById('markAllReadBtn');
    const clearAllBtn = document.getElementById('clearAllBtn');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            tabs.forEach(t => {
                t.classList.remove('active', 'border-blue-500', 'text-blue-400');
                t.classList.add('border-transparent', 'text-gray-400');
            });

            // Add active class to clicked tab
            this.classList.add('active', 'border-blue-500', 'text-blue-400');
            this.classList.remove('border-transparent', 'text-gray-400');

            // Filter notifications
            const filterType = this.id.replace('Tab', '');
            filterNotifications(filterType);
        });
    });

    function filterNotifications(type) {
        notificationItems.forEach(item => {
            if (type === 'all') {
                item.style.display = 'block';
            } else if (type === 'unread') {
                item.style.display = item.querySelector('.bg-blue-100') ? 'block' : 'none';
            } else {
                const itemType = item.getAttribute('data-type');
                item.style.display = itemType === type ? 'block' : 'none';
            }
        });
    }

    // Mark as read functionality
    document.querySelectorAll('.mark-read-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const notificationId = this.getAttribute('data-id');
            markAsRead(notificationId);
        });
    });

    // Delete notification functionality
    document.querySelectorAll('.delete-notification-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const notificationId = this.getAttribute('data-id');
            deleteNotification(notificationId);
        });
    });

    // Mark all as read
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            markAllAsRead();
        });
    }

    // Clear all notifications
    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (confirm('Are you sure you want to clear all notifications? This action cannot be undone.')) {
                clearAllNotifications();
            }
        });
    }

    function markAsRead(notificationId) {
        fetch(`/user/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to mark notification as read');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while marking notification as read');
        });
    }

    function markAllAsRead() {
        console.log('markAllAsRead function called');
        const button = document.getElementById('markAllReadBtn');
        button.disabled = true;
        button.textContent = 'Processing...';
        
        console.log('Making fetch request to mark all as read');
        fetch('/user/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            console.log('Response received:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                console.log('Successfully marked all as read, reloading page');
                location.reload();
            } else {
                console.log('Failed to mark all as read:', data);
                alert('Failed to mark all notifications as read');
                button.disabled = false;
                button.textContent = 'Mark All as Read';
            }
        })
        .catch(error => {
            console.error('Error marking all as read:', error);
            alert('An error occurred while marking notifications as read: ' + error.message);
            button.disabled = false;
            button.textContent = 'Mark All as Read';
        });
    }

    function deleteNotification(notificationId) {
        fetch(`/user/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function clearAllNotifications() {
        console.log('clearAllNotifications function called');
        const button = document.getElementById('clearAllBtn');
        button.disabled = true;
        button.textContent = 'Processing...';
        
        console.log('Making fetch request to clear all notifications');
        fetch('/user/notifications/clear-all', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            console.log('Response received:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                console.log('Successfully cleared all notifications, reloading page');
                location.reload();
            } else {
                console.log('Failed to clear notifications:', data);
                alert('Failed to clear all notifications');
                button.disabled = false;
                button.textContent = 'Clear All';
            }
        })
        .catch(error => {
            console.error('Error clearing notifications:', error);
            alert('An error occurred while clearing notifications: ' + error.message);
            button.disabled = false;
            button.textContent = 'Clear All';
        });
    }
});
</script>
@endsection
