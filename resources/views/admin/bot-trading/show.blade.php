@extends('admin.layouts.app')

@section('content')
@if(!$bot)
    <div class="p-4 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-2xl mx-auto text-center">
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-8">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Bot Not Found</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">The bot you're looking for doesn't exist or has been deleted.</p>
                <a href="{{ route('admin.bot-trading.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Bots
                </a>
            </div>
        </div>
    </div>
@else
<div class="p-4 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <!-- Page Header -->
    <div class="mb-6">
        <!-- Mobile Layout -->
        <div class="block sm:hidden">
            <!-- Back Button - Full Width on Mobile -->
            <div class="mb-4">
                <a href="{{ route('admin.bot-trading.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 w-full justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Bots
                </a>
            </div>
            
            <!-- Title Section - Centered on Mobile -->
            <div class="text-center mb-4">
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">Bot Details</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $bot->name ?? 'Unnamed Bot' }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $bot->user->name ?? 'Unknown User' }} - Admin Panel</p>
            </div>
            
            <!-- Action Button - Full Width on Mobile -->
            <div>
                <a href="{{ route('admin.bot-trading.edit', $bot) }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 w-full">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Bot
                </a>
            </div>
        </div>
        
        <!-- Desktop Layout -->
        <div class="hidden sm:flex sm:items-center sm:justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.bot-trading.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Bots
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Bot Details</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $bot->name ?? 'Unnamed Bot' }} - {{ $bot->user->name ?? 'Unknown User' }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.bot-trading.edit', $bot) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Bot
                </a>
            </div>
        </div>
    </div>

    <!-- Bot Status & Performance Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Status Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Status</p>
                    <div class="mt-2">
                        @if($bot->status === 'active')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                Active
                            </span>
                        @elseif($bot->status === 'paused')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                <span class="w-2 h-2 bg-yellow-400 rounded-full mr-2"></span>
                                Paused
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                <span class="w-2 h-2 bg-red-400 rounded-full mr-2"></span>
                                Stopped
                            </span>
                        @endif
                    </div>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="space-y-3">
                @if($bot->status === 'active')
                <button onclick="pauseBot({{ $bot->id }})" class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Pause Bot
                </button>
                <button onclick="stopBot({{ $bot->id }})" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Stop Bot
                </button>
                @elseif($bot->status === 'paused')
                <button onclick="resumeBot({{ $bot->id }})" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Resume Bot
                </button>
                <button onclick="stopBot({{ $bot->id }})" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Stop Bot
                </button>
                @elseif($bot->status === 'stopped')
                <button onclick="startBot({{ $bot->id }})" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Start Bot
                </button>
                @endif
            </div>
        </div>

        <!-- Total Profit Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Profit</p>
                    <p class="text-3xl font-bold {{ ($bot->total_profit ?? 0) >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        ${{ number_format($bot->total_profit ?? 0, 2) }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Invested: ${{ number_format($bot->total_invested ?? 0, 2) }}
                    </p>
                    <button onclick="openEditBotPnlModal({{ $bot->id }}, {{ $bot->total_profit ?? 0 }}, {{ $bot->total_invested ?? 0 }}, {{ $bot->successful_trades ?? 0 }}, {{ $bot->success_rate ?? 0 }})" 
                            class="mt-3 inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit PnL
                    </button>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Success Rate Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Success Rate</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($bot->success_rate ?? 0, 1) }}%
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ $bot->successful_trades ?? 0 }} successful trades
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Trades Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Trades</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $bot->total_trades ?? 0 }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Created {{ $bot->created_at ? $bot->created_at->diffForHumans() : 'Unknown' }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Bot Configuration and Market Info Row -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">
        <!-- Bot Configuration - 80% width -->
        <div class="lg:col-span-4">
            <!-- Bot Configuration -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Bot Configuration</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <h4 class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-4">Basic Settings</h4>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Name:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $bot->name ?? 'Not set' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Strategy:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $bot->strategy ?? 'Not set')) }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Trading Pair:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ ($bot->base_asset ?? 'N/A') }}/{{ ($bot->quote_asset ?? 'N/A') }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">User:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $bot->user->name ?? 'Unknown User' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Created:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $bot->created_at ? $bot->created_at->format('M d, Y H:i') : 'Unknown' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Users in Bot:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ number_format($bot->participants_count ?? 0) }}</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-4">Risk Management</h4>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Max Investment:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">${{ number_format($bot->max_investment ?? 0, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Min Trade Amount:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">${{ number_format($bot->min_trade_amount ?? 0, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Max Trade Amount:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">${{ number_format($bot->max_trade_amount ?? 0, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Daily Loss Limit:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">${{ number_format($bot->daily_loss_limit ?? 0, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Leverage:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $bot->leverage ?? 1 }}x</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-4">Trading Settings</h4>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Max Open Trades:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $bot->max_open_trades ?? 5 }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Trade Duration:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        @switch($bot->trade_duration)
                                            @case('1h')
                                                1 Hour
                                                @break
                                            @case('4h')
                                                4 Hours
                                                @break
                                            @case('24h')
                                                24 Hours
                                                @break
                                            @case('1w')
                                                1 Week
                                                @break
                                            @case('1m')
                                                1 Month
                                                @break
                                            @default
                                                {{ $bot->trade_duration ?? 'Not set' }}
                                        @endswitch
                                    </span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Target Yield:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $bot->target_yield_percentage ?? 'Not set' }}%</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Auto Close:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $bot->auto_close ? 'Yes' : 'No' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">24/7 Trading:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $bot->trading_24_7 ? 'Yes' : 'No' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Market Info - 20% width -->
        <div class="lg:col-span-1">
            <!-- Market Info -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Market Info</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Current Price:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">${{ number_format($bot->asset->current_price ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">24h Change:</span>
                        <span class="text-sm font-medium {{ ($bot->asset->price_change_24h ?? 0) >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ ($bot->asset->price_change_24h ?? 0) >= 0 ? '+' : '' }}{{ number_format($bot->asset->price_change_24h ?? 0, 2) }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Trades - Full Width -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Trades</h3>
                <button onclick="openCreateTradeModal()" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Trade
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Trade ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Profit/Loss</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($bot->trades()->latest()->take(10)->get() as $trade)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $trade->trade_id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $trade->type === 'buy' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                {{ strtoupper($trade->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ number_format($trade->base_amount, 6) }} {{ $trade->base_asset }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            ${{ number_format($trade->price, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm {{ $trade->profit_loss >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            ${{ number_format($trade->profit_loss, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                {{ ucfirst($trade->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $trade->created_at->format('M d, H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <button onclick="openEditTradePnlModal({{ $trade->id }}, {{ $trade->profit_loss ?? 0 }}, {{ $trade->profit_loss_percentage ?? 0 }})" 
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit PnL
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-8 h-8 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">No trades found</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">This bot hasn't executed any trades yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Bot PnL Modal -->
    <div id="editBotPnlModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Bot PnL</h3>
                </div>
                <form id="editBotPnlForm" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Total Profit ($)</label>
                        <input type="number" step="0.01" id="botTotalProfit" name="total_profit" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Total Invested ($)</label>
                        <input type="number" step="0.01" id="botTotalInvested" name="total_invested" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Successful Trades</label>
                        <input type="number" id="botSuccessfulTrades" name="successful_trades" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Success Rate (%)</label>
                        <input type="number" step="0.1" id="botSuccessRate" name="success_rate" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeEditBotPnlModal()" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            Update PnL
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Trade PnL Modal -->
    <div id="editTradePnlModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Trade PnL</h3>
                </div>
                <form id="editTradePnlForm" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Profit/Loss ($)</label>
                        <input type="number" step="0.01" id="tradeProfitLoss" name="profit_loss" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Profit/Loss Percentage (%)</label>
                        <input type="number" step="0.01" id="tradeProfitLossPercentage" name="profit_loss_percentage" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeEditTradePnlModal()" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            Update PnL
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Create Trade Modal -->
    <div id="createTradeModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Create New Trade</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manually create a trade for {{ $bot->name ?? 'this bot' }}</p>
                </div>
                <form id="createTradeForm" class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Trade Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Trade Type <span class="text-red-500">*</span></label>
                            <select name="type" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Select Type</option>
                                <option value="buy">Buy</option>
                                <option value="sell">Sell</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status <span class="text-red-500">*</span></label>
                            <select name="status" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Select Status</option>
                                <option value="pending">Pending</option>
                                <option value="executed">Executed</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>

                        <!-- Base Asset -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Base Asset <span class="text-red-500">*</span></label>
                            <input type="text" name="base_asset" value="{{ $bot->base_asset }}" required 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="e.g., BTC">
                        </div>

                        <!-- Quote Asset -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Quote Asset <span class="text-red-500">*</span></label>
                            <input type="text" name="quote_asset" value="{{ $bot->quote_asset }}" required 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="e.g., USDT">
                        </div>

                        <!-- Base Amount -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Base Amount <span class="text-red-500">*</span></label>
                            <input type="number" name="base_amount" step="0.00000001" required 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="e.g., 0.001">
                            <p class="text-xs text-blue-500 dark:text-blue-400 mt-1">The quantity of the base asset (e.g., BTC, ETH, EUR/USD) being traded</p>
                        </div>

                        <!-- Price -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Price ({{ $bot->quote_asset ?? auth()->user()->currency ?? 'USD' }}) <span class="text-red-500">*</span></label>
                            <input type="number" name="price" step="0.01" required 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="e.g., 45000.00">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">The price per unit of the base asset in the quote currency</p>
                        </div>

                        <!-- Quote Amount -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Quote Amount ({{ $bot->quote_asset ?? auth()->user()->currency ?? 'USD' }}) <span class="text-red-500">*</span></label>
                            <input type="number" name="quote_amount" step="0.01" required 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="e.g., 45.00">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">The total value of the trade in the quote currency (Base Amount × Price)</p>
                        </div>

                        <!-- Execution Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Execution Type</label>
                            <select name="execution_type" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="market">Market</option>
                                <option value="limit">Limit</option>
                            </select>
                        </div>
                    </div>

                    <!-- Profit/Loss Section -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Profit/Loss (Optional)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Profit/Loss ({{ $bot->quote_asset ?? auth()->user()->currency ?? 'USD' }})</label>
                                <input type="number" name="profit_loss" step="0.01" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="e.g., 5.50">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Profit/Loss Percentage (%)</label>
                                <input type="number" name="profit_loss_percentage" step="0.01" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="e.g., 12.25">
                            </div>
                        </div>
                    </div>


                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" onclick="closeCreateTradeModal()" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors duration-200">
                            Create Trade
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function pauseBot(botId) {
    if (confirm('Are you sure you want to pause this bot? It can be resumed later.')) {
        fetch(`/admin/bot-trading/${botId}/pause`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Bot paused successfully!');
                location.reload();
            } else {
                alert('Failed to pause bot: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while pausing the bot');
        });
    }
}

function resumeBot(botId) {
    if (confirm('Are you sure you want to resume this bot?')) {
        fetch(`/admin/bot-trading/${botId}/resume`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Bot resumed successfully!');
                location.reload();
            } else {
                alert('Failed to resume bot: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while resuming the bot');
        });
    }
}

function stopBot(botId) {
    if (confirm('Are you sure you want to PERMANENTLY STOP this bot? This action cannot be undone and all profits will be transferred to your trading balance.')) {
        fetch(`/admin/bot-trading/${botId}/stop`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Bot stopped permanently! ' + (data.profit_transferred ? 'Profits have been transferred to your trading balance.' : ''));
                location.reload();
            } else {
                alert('Failed to stop bot: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while stopping the bot');
        });
    }
}

function startBot(botId) {
    if (confirm('Are you sure you want to start this bot? It will begin trading immediately.')) {
        fetch(`/admin/bot-trading/${botId}/start`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Bot started successfully!');
                location.reload();
            } else {
                alert('Failed to start bot: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while starting the bot');
        });
    }
}

function executeBot(botId) {
    if (confirm('Are you sure you want to execute this bot manually?')) {
        fetch(`/admin/bot-trading/${botId}/execute`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Bot executed successfully!');
                location.reload();
            } else {
                alert('Failed to execute bot: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while executing the bot: ' + error.message);
        });
    }
}

// Bot PnL Editing Functions
let currentBotId = null;

function openEditBotPnlModal(botId, totalProfit, totalInvested, successfulTrades, successRate) {
    currentBotId = botId;
    document.getElementById('botTotalProfit').value = totalProfit;
    document.getElementById('botTotalInvested').value = totalInvested;
    document.getElementById('botSuccessfulTrades').value = successfulTrades;
    document.getElementById('botSuccessRate').value = successRate;
    document.getElementById('editBotPnlModal').classList.remove('hidden');
}

function closeEditBotPnlModal() {
    document.getElementById('editBotPnlModal').classList.add('hidden');
    currentBotId = null;
}

// Trade PnL Editing Functions
let currentTradeId = null;

function openEditTradePnlModal(tradeId, profitLoss, profitLossPercentage) {
    currentTradeId = tradeId;
    document.getElementById('tradeProfitLoss').value = profitLoss;
    document.getElementById('tradeProfitLossPercentage').value = profitLossPercentage;
    document.getElementById('editTradePnlModal').classList.remove('hidden');
}

function closeEditTradePnlModal() {
    document.getElementById('editTradePnlModal').classList.add('hidden');
    currentTradeId = null;
}

// Form submission handlers
document.getElementById('editBotPnlForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        total_profit: parseFloat(formData.get('total_profit')),
        total_invested: parseFloat(formData.get('total_invested')),
        successful_trades: parseInt(formData.get('successful_trades')),
        success_rate: parseFloat(formData.get('success_rate')),
    };

    fetch(`/admin/bot-trading/${currentBotId}/edit-pnl`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Bot PnL updated successfully!');
            location.reload();
        } else {
            alert('Failed to update bot PnL: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating bot PnL');
    });
});

document.getElementById('editTradePnlForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        profit_loss: parseFloat(formData.get('profit_loss')),
        profit_loss_percentage: parseFloat(formData.get('profit_loss_percentage')),
    };

    fetch(`/admin/bot-trading/trade/${currentTradeId}/edit-pnl`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Trade PnL updated successfully!');
            location.reload();
        } else {
            alert('Failed to update trade PnL: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating trade PnL');
    });
});

// Close modals when clicking outside
document.getElementById('editBotPnlModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditBotPnlModal();
    }
});

document.getElementById('editTradePnlModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditTradePnlModal();
    }
});

// Create Trade Modal Functions
function openCreateTradeModal() {
    document.getElementById('createTradeModal').classList.remove('hidden');
    // Reset form
    document.getElementById('createTradeForm').reset();
    // Pre-fill bot assets
    document.querySelector('input[name="base_asset"]').value = '{{ $bot->base_asset }}';
    document.querySelector('input[name="quote_asset"]').value = '{{ $bot->quote_asset }}';
}

function closeCreateTradeModal() {
    document.getElementById('createTradeModal').classList.add('hidden');
}

// Create Trade Form Submission
document.getElementById('createTradeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        type: formData.get('type'),
        status: formData.get('status'),
        base_asset: formData.get('base_asset'),
        quote_asset: formData.get('quote_asset'),
        base_amount: parseFloat(formData.get('base_amount')),
        price: parseFloat(formData.get('price')),
        quote_amount: parseFloat(formData.get('quote_amount')),
        execution_type: formData.get('execution_type'),
        profit_loss: formData.get('profit_loss') ? parseFloat(formData.get('profit_loss')) : null,
        profit_loss_percentage: formData.get('profit_loss_percentage') ? parseFloat(formData.get('profit_loss_percentage')) : null,
    };

    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalContent = submitBtn.innerHTML;
    submitBtn.innerHTML = `
        <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        Creating...
    `;
    submitBtn.disabled = true;

    fetch(`/admin/bot-trading/{{ $bot->id }}/create-trade`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Trade created successfully!');
            closeCreateTradeModal();
            location.reload();
        } else {
            alert('Failed to create trade: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while creating the trade');
    })
    .finally(() => {
        // Reset button state
        submitBtn.innerHTML = originalContent;
        submitBtn.disabled = false;
    });
});

// Close create trade modal when clicking outside
document.getElementById('createTradeModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCreateTradeModal();
    }
});

// Refresh trades function
function refreshTrades() {
    // Show loading state
    const refreshBtn = document.querySelector('button[onclick="refreshTrades()"]');
    const originalContent = refreshBtn.innerHTML;
    refreshBtn.innerHTML = `
        <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        Refreshing...
    `;
    refreshBtn.disabled = true;

    // Reload the page to get fresh data
    setTimeout(() => {
        window.location.reload();
    }, 500);
}
</script>
@endif
@endsection
