import React, { useState, useEffect } from 'react';

const NotificationsPage = ({ notifications: initialNotifications = [], csrfToken }) => {
    const [notifications, setNotifications] = useState(initialNotifications);
    const [activeTab, setActiveTab] = useState('all');
    const [loading, setLoading] = useState(false);
    const [deletingId, setDeletingId] = useState(null);

    // Filter notifications based on active tab
    const filteredNotifications = notifications.filter(notification => {
        if (activeTab === 'all') return true;
        if (activeTab === 'unread') return !notification.read_at;
        
        const typeMap = {
            'deposits': ['deposit', 'deposit_submitted', 'deposit_approved', 'deposit_completed'],
            'withdrawals': ['withdrawal', 'withdrawal_submitted', 'withdrawal_approved', 'withdrawal_completed'],
            'trading': ['trading', 'trade', 'copy_trade', 'copy_trade_started'],
            'system': ['system', 'account_reactivated', 'account_suspended']
        };
        
        return typeMap[activeTab]?.includes(notification.type) || false;
    });

    // Get unread count
    const unreadCount = notifications.filter(n => !n.read_at).length;

    // Get notification icon based on type
    const getNotificationIcon = (type) => {
        const iconConfig = {
            'deposit': { bg: 'bg-green-500/20', text: 'text-green-400', icon: 'plus' },
            'deposit_submitted': { bg: 'bg-green-500/20', text: 'text-green-400', icon: 'plus' },
            'deposit_approved': { bg: 'bg-green-500/20', text: 'text-green-400', icon: 'check' },
            'deposit_completed': { bg: 'bg-green-500/20', text: 'text-green-400', icon: 'check' },
            'withdrawal': { bg: 'bg-red-500/20', text: 'text-red-400', icon: 'minus' },
            'withdrawal_submitted': { bg: 'bg-red-500/20', text: 'text-red-400', icon: 'minus' },
            'withdrawal_approved': { bg: 'bg-red-500/20', text: 'text-red-400', icon: 'check' },
            'withdrawal_completed': { bg: 'bg-red-500/20', text: 'text-red-400', icon: 'check' },
            'trading': { bg: 'bg-blue-500/20', text: 'text-blue-400', icon: 'trending' },
            'trade': { bg: 'bg-blue-500/20', text: 'text-blue-400', icon: 'trending' },
            'copy_trade': { bg: 'bg-purple-500/20', text: 'text-purple-400', icon: 'copy' },
            'copy_trade_started': { bg: 'bg-purple-500/20', text: 'text-purple-400', icon: 'copy' },
            'bot_trade': { bg: 'bg-yellow-500/20', text: 'text-yellow-400', icon: 'bot' },
            'bot_created': { bg: 'bg-yellow-500/20', text: 'text-yellow-400', icon: 'bot' },
            'bot_started': { bg: 'bg-yellow-500/20', text: 'text-yellow-400', icon: 'bot' },
            'system': { bg: 'bg-gray-500/20', text: 'text-gray-400', icon: 'bell' },
            'account_reactivated': { bg: 'bg-green-500/20', text: 'text-green-400', icon: 'check' },
            'account_suspended': { bg: 'bg-red-500/20', text: 'text-red-400', icon: 'x' }
        };

        const config = iconConfig[type] || { bg: 'bg-gray-500/20', text: 'text-gray-400', icon: 'bell' };

        const renderIcon = () => {
            switch (config.icon) {
                case 'plus':
                    return (
                        <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4" />
                        </svg>
                    );
                case 'minus':
                    return (
                        <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M20 12H4" />
                        </svg>
                    );
                case 'check':
                    return (
                        <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                        </svg>
                    );
                case 'trending':
                    return (
                        <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    );
                case 'copy':
                    return (
                        <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    );
                case 'bot':
                    return (
                        <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    );
                case 'x':
                    return (
                        <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    );
                default:
                    return (
                        <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    );
            }
        };

        return (
            <div className={`flex h-12 w-12 items-center justify-center rounded-xl ${config.bg} ${config.text} flex-shrink-0`}>
                {renderIcon()}
            </div>
        );
    };

    // Get status badge
    const getStatusBadge = (data) => {
        if (!data?.status) return null;
        
        const statusConfig = {
            'active': { bg: 'bg-yellow-500/20', text: 'text-yellow-400', border: 'border-yellow-500/30' },
            'suspended': { bg: 'bg-orange-500/20', text: 'text-orange-400', border: 'border-orange-500/30' },
            'completed': { bg: 'bg-green-500/20', text: 'text-green-400', border: 'border-green-500/30' },
            'pending': { bg: 'bg-yellow-500/20', text: 'text-yellow-400', border: 'border-yellow-500/30' },
            'approved': { bg: 'bg-green-500/20', text: 'text-green-400', border: 'border-green-500/30' },
            'rejected': { bg: 'bg-red-500/20', text: 'text-red-400', border: 'border-red-500/30' }
        };

        const config = statusConfig[data.status.toLowerCase()] || { bg: 'bg-gray-500/20', text: 'text-gray-400', border: 'border-gray-500/30' };

        return (
            <span className={`inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border ${config.bg} ${config.text} ${config.border}`}>
                {data.status.charAt(0).toUpperCase() + data.status.slice(1)}
            </span>
        );
    };

    // Format time ago
    const formatTimeAgo = (dateString) => {
        if (!dateString) return 'Just now';
        
        const date = new Date(dateString);
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);
        
        if (diffInSeconds < 60) return 'Just now';
        if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
        if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
        if (diffInSeconds < 604800) return `${Math.floor(diffInSeconds / 86400)}d ago`;
        if (diffInSeconds < 2592000) return `${Math.floor(diffInSeconds / 604800)}w ago`;
        if (diffInSeconds < 31536000) return `${Math.floor(diffInSeconds / 2592000)}mo ago`;
        return `${Math.floor(diffInSeconds / 31536000)}y ago`;
    };

    // Mark notification as read
    const markAsRead = async (id) => {
        try {
            const response = await fetch(`/user/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (response.ok) {
                setNotifications(notifications.map(n => 
                    n.id === id ? { ...n, read_at: new Date().toISOString() } : n
                ));
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    };

    // Delete notification
    const deleteNotification = async (id) => {
        setDeletingId(id);
        try {
            const response = await fetch(`/user/notifications/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (response.ok) {
                setNotifications(notifications.filter(n => n.id !== id));
            }
        } catch (error) {
            console.error('Error deleting notification:', error);
        } finally {
            setDeletingId(null);
        }
    };

    // Mark all as read
    const markAllAsRead = async () => {
        if (unreadCount === 0) return;
        
        setLoading(true);
        try {
            const response = await fetch('/user/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (response.ok) {
                setNotifications(notifications.map(n => ({ ...n, read_at: n.read_at || new Date().toISOString() })));
            }
        } catch (error) {
            console.error('Error marking all as read:', error);
        } finally {
            setLoading(false);
        }
    };

    // Clear all notifications
    const clearAll = async () => {
        if (notifications.length === 0) return;
        
        if (!window.confirm('Are you sure you want to clear all notifications? This action cannot be undone.')) {
            return;
        }

        setLoading(true);
        try {
            const response = await fetch('/user/notifications/clear-all', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (response.ok) {
                setNotifications([]);
            }
        } catch (error) {
            console.error('Error clearing all notifications:', error);
        } finally {
            setLoading(false);
        }
    };

    const tabs = [
        { id: 'all', label: 'All', count: notifications.length },
        { id: 'unread', label: 'Unread', count: unreadCount },
        { id: 'deposits', label: 'Deposits' },
        { id: 'withdrawals', label: 'Withdrawals' },
        { id: 'trading', label: 'Trading' },
        { id: 'system', label: 'System' }
    ];

    return (
        <div className="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
            {/* Header */}
            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 className="text-3xl font-bold text-foreground">Notifications</h1>
                    <p className="text-sm text-muted-foreground mt-1">
                        Stay updated with your account activities
                    </p>
                </div>
                <div className="flex items-center gap-3">
                    <button
                        onClick={markAllAsRead}
                        disabled={loading || unreadCount === 0}
                        className="px-4 py-2 text-sm font-medium rounded-lg border border-border bg-background text-foreground hover:bg-card transition-colors disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-background"
                    >
                        {loading ? 'Processing...' : 'Mark All Read'}
                    </button>
                    <button
                        onClick={clearAll}
                        disabled={loading || notifications.length === 0}
                        className="px-4 py-2 text-sm font-medium rounded-lg bg-red-500/20 text-red-400 border border-red-500/30 hover:bg-red-500/30 transition-colors disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-red-500/20"
                    >
                        Clear All
                    </button>
                </div>
            </div>

            {/* Filter Tabs */}
            <div className="border-b border-border">
                <nav className="-mb-px flex space-x-1 overflow-x-auto">
                    {tabs.map(tab => (
                        <button
                            key={tab.id}
                            onClick={() => setActiveTab(tab.id)}
                            className={`relative py-3 px-4 font-medium text-sm whitespace-nowrap transition-colors rounded-t-lg ${
                                activeTab === tab.id
                                    ? 'text-primary border-b-2 border-primary bg-primary/5'
                                    : 'text-muted-foreground hover:text-foreground hover:bg-card/50'
                            }`}
                        >
                            {tab.label}
                            {tab.count !== undefined && tab.count > 0 && (
                                <span className={`ml-2 px-2 py-0.5 rounded-full text-xs font-semibold ${
                                    activeTab === tab.id
                                        ? 'bg-primary/20 text-primary'
                                        : 'bg-muted text-muted-foreground'
                                }`}>
                                    {tab.count}
                                </span>
                            )}
                        </button>
                    ))}
                </nav>
            </div>

            {/* Notifications List */}
            <div className="space-y-3">
                {filteredNotifications.length > 0 ? (
                    filteredNotifications.map(notification => (
                        <div
                            key={notification.id}
                            className={`group rounded-xl border bg-card p-5 transition-all hover:border-primary/30 ${
                                !notification.read_at 
                                    ? 'border-primary/30 border-l-4 border-l-primary bg-primary/5' 
                                    : 'border-border'
                            }`}
                        >
                            <div className="flex items-start gap-4">
                                {/* Icon */}
                                {getNotificationIcon(notification.type)}

                                {/* Content */}
                                <div className="flex-1 min-w-0">
                                    <div className="flex items-start justify-between gap-4">
                                        <div className="flex-1 min-w-0">
                                            <div className="flex items-center gap-2 mb-1">
                                                <h3 className={`text-base font-semibold ${
                                                    !notification.read_at ? 'text-foreground' : 'text-foreground/90'
                                                }`}>
                                                    {notification.title}
                                                </h3>
                                                {!notification.read_at && (
                                                    <span className="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-primary/20 text-primary">
                                                        New
                                                    </span>
                                                )}
                                            </div>
                                            <p className="text-sm text-muted-foreground leading-relaxed">
                                                {notification.message}
                                            </p>
                                            
                                            {/* Additional Data */}
                                            {notification.data && Object.keys(notification.data).length > 0 && (
                                                <div className="mt-3 flex flex-wrap items-center gap-2">
                                                    {notification.data.amount && (
                                                        <div className="inline-flex items-center px-3 py-1.5 rounded-lg bg-primary/10 border border-primary/20">
                                                            <span className="text-sm font-semibold text-primary">
                                                                ${parseFloat(notification.data.amount).toLocaleString('en-US', { 
                                                                    minimumFractionDigits: 2, 
                                                                    maximumFractionDigits: 2 
                                                                })}
                                                            </span>
                                                            {notification.data.currency && notification.data.currency !== 'USD' && (
                                                                <span className="ml-1 text-xs text-muted-foreground">
                                                                    {notification.data.currency}
                                                                </span>
                                                            )}
                                                        </div>
                                                    )}
                                                    {getStatusBadge(notification.data)}
                                                </div>
                                            )}
                                        </div>
                                    </div>

                                    {/* Footer */}
                                    <div className="flex items-center justify-between mt-4 pt-4 border-t border-border">
                                        <span className="text-xs text-muted-foreground">
                                            {formatTimeAgo(notification.created_at)}
                                        </span>
                                        
                                        <div className="flex items-center gap-2">
                                            {!notification.read_at && (
                                                <button
                                                    onClick={() => markAsRead(notification.id)}
                                                    className="px-3 py-1.5 text-xs font-medium rounded-lg border border-border bg-background text-foreground hover:bg-card transition-colors"
                                                >
                                                    Mark Read
                                                </button>
                                            )}
                                            <button
                                                onClick={() => deleteNotification(notification.id)}
                                                disabled={deletingId === notification.id}
                                                className="px-3 py-1.5 text-xs font-medium rounded-lg bg-red-500/20 text-red-400 border border-red-500/30 hover:bg-red-500/30 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                            >
                                                {deletingId === notification.id ? 'Deleting...' : 'Delete'}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ))
                ) : (
                    <div className="text-center py-16">
                        <div className="flex justify-center mb-4">
                            <div className="flex h-20 w-20 items-center justify-center rounded-2xl bg-muted/50 border border-border">
                                <svg className="h-10 w-10 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                        </div>
                        <h3 className="text-lg font-semibold text-foreground mb-2">No notifications</h3>
                        <p className="text-sm text-muted-foreground">
                            {activeTab === 'all' 
                                ? "You're all caught up! New notifications will appear here."
                                : `No ${tabs.find(t => t.id === activeTab)?.label.toLowerCase()} notifications found.`
                            }
                        </p>
                    </div>
                )}
            </div>
        </div>
    );
};

export default NotificationsPage;
