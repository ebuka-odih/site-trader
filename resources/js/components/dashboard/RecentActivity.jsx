import React from 'react';
import { formatCurrency } from './utils';

const RecentActivity = ({ activity = [] }) => {
    const formatAmount = (amount) => {
        if (typeof amount === 'number') {
            return formatCurrency(Math.abs(amount));
        }
        return amount || '$0.00';
    };

    const getActivityIcon = (type) => {
        switch (type) {
            case 'deposit':
                return (
                    <div className="flex h-10 w-10 items-center justify-center rounded-full bg-[#00ff63]/20 text-[#00ff63]">
                        <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                );
            case 'withdrawal':
                return (
                    <div className="flex h-10 w-10 items-center justify-center rounded-full bg-red-500/20 text-red-400">
                        <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M20 12H4" />
                        </svg>
                    </div>
                );
            case 'trade_profit':
            case 'trade':
                return (
                    <div className="flex h-10 w-10 items-center justify-center rounded-full bg-blue-500/20 text-blue-400">
                        <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                );
            default:
                return (
                    <div className="flex h-10 w-10 items-center justify-center rounded-full bg-gray-500/20 text-gray-400">
                        <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                        </svg>
                    </div>
                );
        }
    };

    return (
        <div className="space-y-3">
            {/* Recent Activity Header */}
            <div className="flex justify-between items-center px-1">
                <p className="text-xs uppercase font-semibold text-gray-400 tracking-widest">Recent Activity</p>
            </div>

            {/* Activity Items */}
            <div className="rounded-xl border bg-[#0a0a0a] text-white shadow-sm border-[#1a1a1a]">
                <div className="p-3 space-y-4">
                    {(activity || []).slice(0, 5).map((item, index) => {
                        const amount = item.amount || 0;
                        const isPositive = amount >= 0;
                        const formattedAmount = item.formatted_amount || formatAmount(amount);

                        return (
                            <div
                                key={`activity-${index}`}
                                className={`flex items-center justify-between ${
                                    index < Math.min(activity.length - 1, 4) ? 'border-b border-[#1a1a1a] pb-4' : ''
                                }`}
                            >
                                <div className="flex items-center gap-3">
                                    {getActivityIcon(item.type)}
                                    <div>
                                        <p className="text-sm font-medium text-white">
                                            {item.title || ucfirst(item.type || 'activity')}
                                        </p>
                                        <p className="text-xs text-gray-400">
                                            {item.time_ago || 'Just now'}
                                        </p>
                                    </div>
                                </div>
                                <span
                                    className={`text-sm font-semibold ${
                                        isPositive ? 'text-[#00ff63]' : 'text-red-400'
                                    }`}
                                >
                                    {isPositive ? '+' : ''}{formattedAmount}
                                </span>
                            </div>
                        );
                    })}
                </div>

                {/* Show message if activity is empty */}
                {(!activity || activity.length === 0) && (
                    <div className="p-8 text-center">
                        <div className="flex justify-center mb-3">
                            <div className="flex h-12 w-12 items-center justify-center rounded-full bg-[#1a1a1a]">
                                <svg className="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <p className="text-sm text-gray-400">No recent activity</p>
                        <p className="text-xs text-gray-500 mt-1">Your transactions will appear here</p>
                    </div>
                )}
            </div>
        </div>
    );
};

// Helper function to capitalize first letter
const ucfirst = (str) => {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
};

export default RecentActivity;

























