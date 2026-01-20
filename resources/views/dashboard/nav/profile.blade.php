@extends('dashboard.new-layout')

@section('content')
<div class="space-y-7 text-white">
    <div class="space-y-1">
        <p class="text-[11px] uppercase tracking-[0.3em] text-[#08f58d]">Profile</p>
        <h1 class="text-2xl font-semibold">Hi {{ $user->name }}, keep your account sharp.</h1>
        <p class="text-sm text-gray-400">Review account stats, jump into deeper settings, or sign out.</p>
    </div>

    <div class="rounded-[32px] border border-[#101010] bg-[#040404] p-5 flex flex-col sm:flex-row gap-5 items-center">
        <div class="flex items-center gap-4 w-full">
            <div class="h-16 w-16 rounded-2xl bg-[#0b0b0b] border border-[#1fff9c]/30 flex items-center justify-center text-xl font-semibold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <p class="text-lg font-semibold">{{ $user->name }}</p>
                <p class="text-sm text-gray-400">{{ $user->email }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
            @csrf
            <button type="submit" class="w-full rounded-2xl border border-[#ff4d4d]/50 px-4 py-2 text-sm font-semibold text-[#ff4d4d] hover:bg-[#ff4d4d]/10">
                Logout
            </button>
        </form>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-3xl border border-[#111] bg-[#050505] p-5 space-y-1">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Email</p>
            <p class="text-base font-semibold">{{ $user->email }}</p>
        </div>
        <div class="rounded-3xl border border-[#111] bg-[#050505] p-5 space-y-1">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Member Since</p>
            <p class="text-base font-semibold">{{ $user->created_at->format('M Y') }}</p>
        </div>
        <div class="rounded-3xl border border-[#111] bg-[#050505] p-5 space-y-1">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Status</p>
            <p class="text-sm font-semibold text-green-400 flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-green-400"></span> Active
            </p>
        </div>
    </div>

    <div class="rounded-[32px] border border-[#111] bg-[#050505] p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="space-y-1">
            <p class="text-[11px] uppercase tracking-[0.3em] text-gray-400">Full Settings</p>
            <p class="text-xl font-semibold">Update profile, security, and preferences</p>
            <p class="text-sm text-gray-400">Manage password, contact info, KYC details, and more.</p>
        </div>
        <a href="{{ route('user.profile') }}" class="rounded-full bg-gradient-to-r from-[#00ff5f] to-[#0fb863] px-6 py-3 text-black font-semibold text-sm text-center">
            Open Profile Settings
        </a>
    </div>

    @if($user->referral_code)
    <div class="rounded-[32px] border border-[#111] bg-[#050505] p-6 space-y-4">
        <div class="space-y-1">
            <p class="text-[11px] uppercase tracking-[0.3em] text-[#8f7dfd]">Referral</p>
            <p class="text-base font-semibold">Share your invite link</p>
            <p class="text-sm text-gray-400">Earn rewards when friends join {{ config('app.name') }} using your link.</p>
        </div>
        @php
            $referralLink = route('referral.link', $user->referral_code);
        @endphp
        <div class="flex flex-col gap-3 md:flex-row">
            <input id="referralLinkInput" value="{{ $referralLink }}" readonly class="flex-1 rounded-2xl border border-[#1f1f1f] bg-[#020202] px-4 py-3 text-sm text-gray-200 focus:outline-none">
            <button type="button" data-copy-target="#referralLinkInput" class="rounded-2xl border border-[#5c28ff]/40 px-4 py-3 text-sm font-semibold text-[#5c28ff] hover:bg-[#5c28ff]/10">
                Copy link
            </button>
        </div>
    </div>
    @endif

    <!-- Notifications Section -->
    <div class="rounded-[32px] border border-[#111] bg-[#050505] p-6 space-y-4">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-[11px] uppercase tracking-[0.3em] text-[#08f58d]">Notifications</p>
                <p class="text-base font-semibold">Recent Activity</p>
                <p class="text-sm text-gray-400">Stay updated with your account activities</p>
            </div>
            @if($unreadCount > 0)
            <span class="rounded-full bg-[#08f58d]/20 text-[#08f58d] px-3 py-1 text-xs font-semibold">
                {{ $unreadCount }} unread
            </span>
            @endif
        </div>

        <div class="space-y-3 max-h-[500px] overflow-y-auto">
            @forelse($notifications as $notification)
            <div class="notification-item rounded-2xl border {{ $notification->read_at ? 'border-[#1f1f1f]' : 'border-[#08f58d]/30 border-l-4 border-l-[#08f58d]' }} bg-[#020202] p-4 hover:border-[#08f58d]/50 transition-colors cursor-pointer"
                 data-id="{{ $notification->id }}"
                 data-read="{{ $notification->read_at ? 'true' : 'false' }}">
                <div class="flex items-start gap-3">
                    <!-- Notification Icon -->
                    <div class="flex-shrink-0">
                        @php
                            $iconClass = match($notification->type) {
                                'deposit', 'deposit_submitted', 'deposit_approved' => 'bg-green-600/20 text-green-400',
                                'withdrawal', 'withdrawal_submitted', 'withdrawal_approved' => 'bg-red-600/20 text-red-400',
                                'trading' => 'bg-blue-600/20 text-blue-400',
                                'copy_trade', 'copy_trade_started' => 'bg-purple-600/20 text-purple-400',
                                'bot_trade', 'bot_trade_executed', 'bot_created', 'bot_started', 'bot_resumed' => 'bg-yellow-600/20 text-yellow-400',
                                default => 'bg-gray-600/20 text-gray-400'
                            };
                        @endphp
                        <div class="w-10 h-10 rounded-xl {{ $iconClass }} flex items-center justify-center">
                            @if(in_array($notification->type, ['deposit', 'deposit_submitted', 'deposit_approved']))
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            @elseif(in_array($notification->type, ['withdrawal', 'withdrawal_submitted', 'withdrawal_approved']))
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            @elseif($notification->type === 'trading')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            @endif
                        </div>
                    </div>

                    <!-- Notification Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-white">{{ $notification->title }}</h3>
                                <p class="text-xs text-gray-400 mt-1 line-clamp-2">{{ $notification->message }}</p>
                                
                                @if($notification->data)
                                    @if(isset($notification->data['amount']))
                                        <p class="text-xs text-[#08f58d] mt-2 font-medium">
                                            Amount: {{ number_format($notification->data['amount'], 2) }} {{ $notification->data['currency'] ?? 'USD' }}
                                        </p>
                                    @endif
                                @endif
                                
                                <p class="text-xs text-gray-500 mt-2">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                            
                            @if(!$notification->read_at)
                            <button class="mark-read-btn flex-shrink-0 p-1.5 rounded-lg hover:bg-[#08f58d]/10 text-gray-400 hover:text-[#08f58d] transition-colors" 
                                    data-id="{{ $notification->id }}"
                                    title="Mark as read">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="rounded-2xl border border-[#1f1f1f] bg-[#020202] p-8 text-center">
                <svg class="w-12 h-12 text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <p class="text-sm text-gray-400">No notifications yet</p>
                <p class="text-xs text-gray-500 mt-1">You'll see your account activities here</p>
            </div>
            @endforelse
        </div>

        @if($notifications->count() > 0)
        <div class="pt-4 border-t border-[#1f1f1f]">
            <a href="{{ route('user.notifications.index') }}" class="flex items-center justify-center gap-2 rounded-2xl border border-[#08f58d]/30 px-4 py-2 text-sm font-semibold text-[#08f58d] hover:bg-[#08f58d]/10 transition-colors">
                <span>View All Notifications</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Copy referral link functionality
    document.querySelectorAll('[data-copy-target]').forEach(button => {
        button.addEventListener('click', () => {
            const target = document.querySelector(button.dataset.copyTarget);
            if (! target) return;
            target.select();
            document.execCommand('copy');
            button.textContent = 'Copied!';
            setTimeout(() => button.textContent = 'Copy link', 2000);
        });
    });

    // Mark notification as read functionality
    document.querySelectorAll('.mark-read-btn').forEach(button => {
        button.addEventListener('click', async (e) => {
            e.stopPropagation();
            const notificationId = button.dataset.id;
            const notificationItem = button.closest('.notification-item');
            
            try {
                const response = await fetch(`/user/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        // Update UI
                        notificationItem.classList.remove('border-[#08f58d]/30', 'border-l-4', 'border-l-[#08f58d]');
                        notificationItem.classList.add('border-[#1f1f1f]');
                        notificationItem.dataset.read = 'true';
                        button.remove();
                        
                        // Update unread count if it exists
                        const unreadBadge = document.querySelector('.bg-\\[\\#08f58d\\]\\/20');
                        if (unreadBadge) {
                            const currentCount = parseInt(unreadBadge.textContent.trim().split(' ')[0]);
                            if (currentCount > 1) {
                                unreadBadge.textContent = `${currentCount - 1} unread`;
                            } else {
                                unreadBadge.remove();
                            }
                        }
                    }
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        });
    });

    // Click on notification item to mark as read (if unread)
    document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', async (e) => {
            // Don't trigger if clicking the mark-read button
            if (e.target.closest('.mark-read-btn')) return;
            
            const isRead = item.dataset.read === 'true';
            if (isRead) return;

            const notificationId = item.dataset.id;
            const markReadBtn = item.querySelector('.mark-read-btn');
            
            if (markReadBtn) {
                markReadBtn.click();
            }
        });
    });
});
</script>
@endpush
