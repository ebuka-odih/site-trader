@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
            <div>
                <h1 class="text-3xl font-bold text-white">Plan Management</h1>
                <p class="text-gray-400 mt-1">Manage all trading, signal, staking, and mining plans</p>
            </div>
            
            <!-- Create Plan Button -->
            <div class="flex space-x-3">
                <button onclick="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors flex items-center space-x-2 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Create Plan</span>
                </button>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div id="success-message" class="bg-green-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
                <button onclick="hideMessage('success-message')" class="ml-4 text-green-200 hover:text-white">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div id="error-message" class="bg-red-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('error') }}
                </div>
                <button onclick="hideMessage('error-message')" class="ml-4 text-red-200 hover:text-white">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div id="validation-errors" class="bg-red-600 text-white px-4 py-3 rounded-lg shadow-lg">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-semibold">Please fix the following errors:</span>
                </div>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Debug Panel (only show in development) -->
        @if(config('app.debug') && session('debug_info'))
            <div class="bg-yellow-600 text-white px-4 py-3 rounded-lg shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-semibold">Debug Information:</span>
                    </div>
                    <button onclick="hideMessage('debug-info')" class="ml-4 text-yellow-200 hover:text-white">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <div class="mt-2 text-sm">
                    <pre class="whitespace-pre-wrap">{{ session('debug_info') }}</pre>
                </div>
            </div>
        @endif

        <!-- Tabs -->
        <div class="bg-gray-800 rounded-xl p-2 shadow-lg">
            <div class="flex flex-wrap space-x-1">
                <button onclick="switchTab('trading')" id="tab-trading" class="tab-button flex-1 min-w-0 py-3 px-4 rounded-lg font-medium transition-all duration-200 text-sm sm:text-base">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                        </svg>
                        <span>Trading Plans</span>
                    </div>
                </button>
                <button onclick="switchTab('signal')" id="tab-signal" class="tab-button flex-1 min-w-0 py-3 px-4 rounded-lg font-medium transition-all duration-200 text-sm sm:text-base">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Signal Plans</span>
                    </div>
                </button>
                <button onclick="switchTab('staking')" id="tab-staking" class="tab-button flex-1 min-w-0 py-3 px-4 rounded-lg font-medium transition-all duration-200 text-sm sm:text-base">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Staking Plans</span>
                    </div>
                </button>
                <button onclick="switchTab('mining')" id="tab-mining" class="tab-button flex-1 min-w-0 py-3 px-4 rounded-lg font-medium transition-all duration-200 text-sm sm:text-base">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Mining Plans</span>
                    </div>
                </button>
            </div>
        </div>

    <!-- Tab Content -->
    <div class="space-y-6">
        <!-- Trading Plans Tab -->
        <div id="content-trading" class="tab-content">
            <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg">
                <div class="px-6 py-4 border-b border-gray-700">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-semibold text-white">Trading Plans</h2>
                            <p class="text-gray-400 text-sm">Manage trading plan configurations</p>
                        </div>
                        <button onclick="openCreateModal('trading')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2 shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>Create Trading Plan</span>
                        </button>
                    </div>
                </div>
                
                @if($tradingPlans->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-700">
                                <tr>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Name</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Funding Range</th>
                                    <th class="hidden lg:table-cell px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Leverage</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach($tradingPlans as $plan)
                                    <tr class="hover:bg-gray-700 transition-colors duration-150">
                                        <td class="px-4 sm:px-6 py-4">
                                            <div class="text-sm font-medium text-white">{{ $plan->name }}</div>
                                            <div class="text-sm text-gray-400 truncate max-w-xs">{{ $plan->description }}</div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4">
                                            <div class="text-lg font-bold text-blue-400">{{ $plan->currency }} {{ number_format($plan->min_funding, 2) }}</div>
                                            <div class="text-xs text-gray-400">
                                                @if($plan->hasUnlimitedFunding())
                                                    - Unlimited
                                                @else
                                                    - {{ $plan->currency }} {{ number_format($plan->max_funding, 2) }}
                                                @endif
                                            </div>
                                        </td>
                                        <td class="hidden lg:table-cell px-4 sm:px-6 py-4 text-sm text-gray-300">
                                            {{ $plan->leverage ? number_format($plan->leverage, 2) : 'N/A' }}
                                        </td>
                                        <td class="px-4 sm:px-6 py-4">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <!-- View/Edit Button -->
                                                <button type="button" onclick="openEditModal({{ $plan->id }})" class="inline-flex items-center px-3 py-1.5 rounded-md bg-blue-600 hover:bg-blue-700 text-white">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    View
                                                </button>

                                                <!-- Activate/Deactivate -->
                                                <form action="{{ route('admin.plans.toggle-status', $plan) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-md {{ $plan->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white">
                                                        {{ $plan->is_active ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>

                                                <!-- Delete -->
                                                <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this plan?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-md bg-red-600 hover:bg-red-700 text-white">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <div class="text-gray-400 mb-6">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-white mb-2">No Trading Plans</h3>
                        <p class="text-gray-400 mb-6">Get started by creating your first trading plan.</p>
                        <button onclick="openCreateModal('trading')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors shadow-lg">
                            Create Trading Plan
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Signal Plans Tab -->
        <div id="content-signal" class="tab-content hidden">
            <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg">
                <div class="px-6 py-4 border-b border-gray-700">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-semibold text-white">Signal Plans</h2>
                            <p class="text-gray-400 text-sm">Manage signal plan configurations</p>
                        </div>
                        <button onclick="openCreateModal('signal')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2 shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>Create Signal Plan</span>
                        </button>
                    </div>
                </div>
                
                @if($signalPlans->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-700">
                                <tr>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Name</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Price</th>
                                    <th class="hidden">Signals</th>
                                    <th class="hidden">Duration</th>
                                    <th class="hidden">Market Type</th>
                                    <th class="hidden">Signal Strength</th>
                                    <th class="hidden">Daily Signals</th>
                                    <th class="hidden">Success Rate</th>
                                    <th class="hidden">Max Daily Signals</th>
                                    <th class="hidden">Features</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @php
                                    // Build a lightweight map for client-side edit prefill
                                    $__plan_map = [];
                                @endphp
                                @foreach($signalPlans as $plan)
                                    @php
                                        $__plan_map[$plan->id] = [
                                            'id' => $plan->id,
                                            'type' => $plan->type,
                                            'name' => $plan->name,
                                            'description' => $plan->description,
                                            'currency' => $plan->currency,
                                            'price' => $plan->price,
                                            'sort_order' => $plan->sort_order,
                                            'signal_quantity' => $plan->signal_quantity,
                                            'signal_duration' => $plan->signal_duration,
                                            'signal_strength' => $plan->signal_strength,
                                            'success_rate' => $plan->success_rate,
                                            'daily_signals' => $plan->daily_signals,
                                            'max_daily_signals' => $plan->max_daily_signals,
                                            'signal_market_type' => $plan->signal_market_type,
                                            'signal_features' => $plan->signal_features,
                                            'update_url' => route('admin.plans.update', $plan),
                                        ];
                                    @endphp
                                    <tr class="hover:bg-gray-700 transition-colors duration-150">
                                        <td class="px-4 sm:px-6 py-4">
                                            <div class="text-sm font-medium text-white">{{ $plan->name }}</div>
                                            <div class="text-sm text-gray-400 truncate max-w-xs">{{ $plan->description }}</div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-300">
                                            <div class="text-sm font-semibold text-white">{{ $plan->formatted_price }}</div>
                                        </td>
                                        <td class="hidden sm:table-cell px-4 sm:px-6 py-4 text-sm text-gray-300">
                                            @if($plan->signal_quantity)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ $plan->signal_quantity }}
                                                </span>
                                            @else
                                                <span class="text-gray-500">N/A</span>
                                            @endif
                                        </td>
                                        <td class="hidden lg:table-cell px-4 sm:px-6 py-4 text-sm text-gray-300">
                                            @if($plan->signal_duration)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $plan->signal_duration }} days
                                                </span>
                                            @else
                                                <span class="text-gray-500">N/A</span>
                                            @endif
                                        </td>
                                        <td class="hidden xl:table-cell px-4 sm:px-6 py-4 text-sm text-gray-300">
                                            @if($plan->signal_market_type)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ ucfirst($plan->signal_market_type) }}
                                                </span>
                                            @else
                                                <span class="text-gray-500">N/A</span>
                                            @endif
                                        </td>
                                        <td class="hidden lg:table-cell px-4 sm:px-6 py-4 text-sm text-gray-300">
                                            @if($plan->signal_strength)
                                                <div class="flex items-center">
                                                    <span class="text-yellow-400 mr-1">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $plan->signal_strength)
                                                                ★
                                                            @else
                                                                ☆
                                                            @endif
                                                        @endfor
                                                    </span>
                                                    <span class="text-xs text-gray-400">({{ $plan->signal_strength }}/5)</span>
                                                </div>
                                            @else
                                                <span class="text-gray-500">N/A</span>
                                            @endif
                                        </td>
                                        <td class="hidden xl:table-cell px-4 sm:px-6 py-4 text-sm text-gray-300">
                                            @if($plan->daily_signals)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ $plan->daily_signals }}/day
                                                </span>
                                            @else
                                                <span class="text-gray-500">N/A</span>
                                            @endif
                                        </td>
                                        <td class="hidden xl:table-cell px-4 sm:px-6 py-4 text-sm text-gray-300">
                                            @if($plan->success_rate)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                                    {{ number_format($plan->success_rate, 1) }}%
                                                </span>
                                            @else
                                                <span class="text-gray-500">N/A</span>
                                            @endif
                                        </td>
                                        <td class="hidden xl:table-cell px-4 sm:px-6 py-4 text-sm text-gray-300">
                                            @if($plan->max_daily_signals)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                                    {{ $plan->max_daily_signals }}
                                                </span>
                                            @else
                                                <span class="text-gray-500">N/A</span>
                                            @endif
                                        </td>
                                        <td class="hidden xl:table-cell px-4 sm:px-6 py-4 text-sm text-gray-300">
                                            @if($plan->signal_features && is_array($plan->signal_features) && count($plan->signal_features) > 0)
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach(array_slice($plan->signal_features, 0, 3) as $feature)
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                            {{ $feature }}
                                                        </span>
                                                    @endforeach
                                                    @if(count($plan->signal_features) > 3)
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                            +{{ count($plan->signal_features) - 3 }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-gray-500">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-4 sm:px-6 py-4">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <!-- View/Edit Button -->
                                                <button type="button" onclick="openEditModal({{ $plan->id }})" class="inline-flex items-center px-3 py-1.5 rounded-md bg-blue-600 hover:bg-blue-700 text-white">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    View
                                                </button>

                                                <!-- Activate/Deactivate -->
                                                <form action="{{ route('admin.plans.toggle-status', $plan) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-md {{ $plan->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white">
                                                        {{ $plan->is_active ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>

                                                <!-- Delete -->
                                                <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this plan?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-md bg-red-600 hover:bg-red-700 text-white">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                <script>
                                    window.__PLAN_MAP = @json($__plan_map);
                                </script>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <div class="text-gray-400 mb-6">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-white mb-2">No Signal Plans</h3>
                        <p class="text-gray-400 mb-6">Get started by creating your first signal plan.</p>
                        <button onclick="openCreateModal('signal')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors shadow-lg">
                            Create Signal Plan
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Staking Plans Tab -->
        <div id="content-staking" class="tab-content hidden">
            <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg">
                <div class="px-6 py-4 border-b border-gray-700">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-semibold text-white">Staking Plans</h2>
                            <p class="text-gray-400 text-sm">Manage staking plan configurations</p>
                        </div>
                        <button onclick="openCreateModal('staking')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2 shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>Create Staking Plan</span>
                        </button>
                    </div>
                </div>
                
                @if($stakingPlans->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">APY Rate</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Min Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach($stakingPlans as $plan)
                                    <tr class="hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-white">{{ $plan->name }}</div>
                                            <div class="text-sm text-gray-400">{{ $plan->description }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-white">{{ $plan->formatted_price }}</div>
                                            @if($plan->has_discount)
                                                <div class="text-xs text-gray-400 line-through">{{ $plan->formatted_original_price }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                            {{ $plan->apy_rate ? "{$plan->apy_rate}%" : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                            {{ $plan->minimum_amount ? "$" . number_format($plan->minimum_amount, 2) : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.plans.edit', $plan) }}" class="text-blue-400 hover:text-blue-300">Edit</a>
                                                <form action="{{ route('admin.plans.toggle-status', $plan) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-yellow-400 hover:text-yellow-300">
                                                        {{ $plan->is_active ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this plan?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-400 hover:text-red-300">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-6 py-8 text-center">
                        <div class="text-gray-400 mb-4">
                            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-white mb-2">No Staking Plans</h3>
                        <p class="text-gray-400 mb-4">Get started by creating your first staking plan.</p>
                        <button onclick="openCreateModal('staking')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            Create Staking Plan
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Mining Plans Tab -->
        <div id="content-mining" class="tab-content hidden">
            <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg">
                <div class="px-6 py-4 border-b border-gray-700">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-semibold text-white">Mining Plans</h2>
                            <p class="text-gray-400 text-sm">Manage mining plan configurations</p>
                        </div>
                        <button onclick="openCreateModal('mining')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2 shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>Create Mining Plan</span>
                        </button>
                    </div>
                </div>
                
                @if($miningPlans->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Hashrate</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Equipment</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach($miningPlans as $plan)
                                    <tr class="hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-white">{{ $plan->name }}</div>
                                            <div class="text-sm text-gray-400">{{ $plan->description }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-white">{{ $plan->formatted_price }}</div>
                                            @if($plan->has_discount)
                                                <div class="text-xs text-gray-400 line-through">{{ $plan->formatted_original_price }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                            {{ $plan->hashrate ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                            {{ $plan->equipment ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.plans.edit', $plan) }}" class="text-blue-400 hover:text-blue-300">Edit</a>
                                                <form action="{{ route('admin.plans.toggle-status', $plan) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-yellow-400 hover:text-yellow-300">
                                                        {{ $plan->is_active ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this plan?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-400 hover:text-red-300">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-6 py-8 text-center">
                        <div class="text-gray-400 mb-4">
                            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-white mb-2">No Mining Plans</h3>
                        <p class="text-gray-400 mb-4">Get started by creating your first mining plan.</p>
                        <button onclick="openCreateModal('mining')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            Create Mining Plan
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Create Plan Modal -->
<div id="createPlanModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex-shrink-0 flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold">Create New Plan</h3>
                    </div>
                </div>
                <button onclick="closeCreateModal()" class="p-2 hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="flex-1 overflow-y-auto">
                <form id="createPlanForm" action="{{ route('admin.plans.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    
                    <!-- Plan Type Selection -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Plan Type
                        </h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="type" value="trading" class="sr-only" onchange="showPlanFields('trading')">
                                <div class="p-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg hover:border-blue-500 transition-colors plan-type-card">
                                    <div class="text-center">
                                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mx-auto mb-2">
                                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                                            </svg>
                                        </div>
                                        <h5 class="text-sm font-medium text-gray-900 dark:text-white">Trading</h5>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Forex & Crypto</p>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="relative cursor-pointer">
                                <input type="radio" name="type" value="signal" class="sr-only" onchange="showPlanFields('signal')">
                                <div class="p-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg hover:border-purple-500 transition-colors plan-type-card">
                                    <div class="text-center">
                                        <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mx-auto mb-2">
                                            <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <h5 class="text-sm font-medium text-gray-900 dark:text-white">Signal</h5>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Trading Signals</p>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="relative cursor-pointer">
                                <input type="radio" name="type" value="staking" class="sr-only" onchange="showPlanFields('staking')">
                                <div class="p-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg hover:border-green-500 transition-colors plan-type-card">
                                    <div class="text-center">
                                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mx-auto mb-2">
                                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <h5 class="text-sm font-medium text-gray-900 dark:text-white">Staking</h5>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Crypto Staking</p>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="relative cursor-pointer">
                                <input type="radio" name="type" value="mining" class="sr-only" onchange="showPlanFields('mining')">
                                <div class="p-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg hover:border-orange-500 transition-colors plan-type-card">
                                    <div class="text-center">
                                        <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center mx-auto mb-2">
                                            <svg class="w-4 h-4 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <h5 class="text-sm font-medium text-gray-900 dark:text-white">Mining</h5>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Crypto Mining</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Basic Information -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            Basic Information
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Plan Name <span class="text-red-500">*</span></label>
                                <input type="text" id="name" name="name" required class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="Enter plan name">
                            </div>
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                                <textarea id="description" name="description" rows="3" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none" placeholder="Enter plan description"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Trading Plan Fields -->
                    <div id="trading-fields" class="plan-fields hidden">
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                                </svg>
                                Trading Plan Settings
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="pairs" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Trading Pairs</label>
                                    <input type="text" id="pairs" name="pairs" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="e.g., 50+ Trading Pairs">
                                </div>
                                <div>
                                    <label for="leverage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Leverage</label>
                                    <input type="text" id="leverage" name="leverage" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="e.g., 1:100">
                                </div>
                                <div>
                                    <label for="spreads" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Spreads</label>
                                    <input type="text" id="spreads" name="spreads" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="e.g., From 1.5 pips">
                                </div>
                                <div>
                                    <label for="swap_fees" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Swap Fees</label>
                                    <input type="text" id="swap_fees" name="swap_fees" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="e.g., 2.5%">
                                </div>
                                <div>
                                    <label for="minimum_deposit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Minimum Deposit</label>
                                    <input type="number" id="minimum_deposit" name="minimum_deposit" step="0.01" min="0" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="0.00">
                                </div>
                                <div>
                                    <label for="max_lot_size" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Max Lot Size</label>
                                    <input type="text" id="max_lot_size" name="max_lot_size" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="e.g., 10 lots">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Signal Plan Fields -->
                    <div id="signal-fields" class="plan-fields hidden">
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Signal Plan Settings
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="signal_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Signal Amount <span class="text-red-500">*</span></label>
                                    <input type="number" id="signal_amount" name="signal_amount" step="0.01" min="0" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="0.00">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Amount users pay for signal entry</p>
                                </div>
                                <div>
                                    <label for="signal_currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Currency</label>
                                    <select id="signal_currency" name="signal_currency" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                        <option value="USD">USD</option>
                                        <option value="EUR">EUR</option>
                                        <option value="GBP">GBP</option>
                                        <option value="JPY">JPY</option>
                                        <option value="CAD">CAD</option>
                                        <option value="AUD">AUD</option>
                                        <option value="CHF">CHF</option>
                                        <option value="CNY">CNY</option>
                                        <option value="SGD">SGD</option>
                                        <option value="NZD">NZD</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Staking Plan Fields -->
                    <div id="staking-fields" class="plan-fields hidden">
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                </svg>
                                Staking Plan Settings
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="staking_currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Staking Currency <span class="text-red-500">*</span></label>
                                    <select id="staking_currency" name="staking_currency" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                        <option value="">Select Currency</option>
                                        <option value="BTC">Bitcoin (BTC)</option>
                                        <option value="ETH">Ethereum (ETH)</option>
                                        <option value="USDT">Tether (USDT)</option>
                                        <option value="USDC">USD Coin (USDC)</option>
                                        <option value="BNB">Binance Coin (BNB)</option>
                                        <option value="ADA">Cardano (ADA)</option>
                                        <option value="SOL">Solana (SOL)</option>
                                        <option value="DOT">Polkadot (DOT)</option>
                                        <option value="MATIC">Polygon (MATIC)</option>
                                        <option value="AVAX">Avalanche (AVAX)</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="apy_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">APY Rate (%)</label>
                                    <input type="number" id="apy_rate" name="apy_rate" step="0.01" min="0" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="e.g., 12.5">
                                </div>
                                <div>
                                    <label for="minimum_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Minimum Amount</label>
                                    <input type="number" id="minimum_amount" name="minimum_amount" step="0.01" min="0" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="e.g., 100.00">
                                </div>
                                <div>
                                    <label for="reward_frequency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reward Frequency</label>
                                    <input type="text" id="reward_frequency" name="reward_frequency" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="e.g., Daily">
                                </div>
                                <div>
                                    <label for="lock_period" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Lock Period (Days)</label>
                                    <input type="number" id="lock_period" name="lock_period" min="0" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="e.g., 30">
                                </div>
                                <div>
                                    <label for="staking_duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Staking Duration (Days)</label>
                                    <input type="number" id="staking_duration" name="staking_duration" min="0" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="e.g., 365">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mining Plan Fields -->
                    <div id="mining-fields" class="plan-fields hidden">
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                                </svg>
                                Mining Plan Settings
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="hashrate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hashrate</label>
                                    <input type="text" id="hashrate" name="hashrate" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="e.g., 1000 TH/s">
                                </div>
                                <div>
                                    <label for="equipment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Equipment</label>
                                    <input type="text" id="equipment" name="equipment" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="e.g., 10 Antminer S19">
                                </div>
                                <div>
                                    <label for="downtime" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Uptime</label>
                                    <input type="text" id="downtime" name="downtime" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="e.g., 99.9% Uptime">
                                </div>
                                <div>
                                    <label for="electricity_costs" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Electricity Costs</label>
                                    <input type="text" id="electricity_costs" name="electricity_costs" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="e.g., Included">
                                </div>
                                <div>
                                    <label for="mining_duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Duration (Days)</label>
                                    <input type="number" id="mining_duration" name="mining_duration" min="0" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="e.g., 30">
                                </div>
                                <div>
                                    <label for="mining_currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mining Currency</label>
                                    <select id="mining_currency" name="mining_currency" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                        <option value="">Select Currency</option>
                                        <option value="BTC">Bitcoin (BTC)</option>
                                        <option value="ETH">Ethereum (ETH)</option>
                                        <option value="LTC">Litecoin (LTC)</option>
                                        <option value="BCH">Bitcoin Cash (BCH)</option>
                                        <option value="DASH">Dash (DASH)</option>
                                        <option value="ZEC">Zcash (ZEC)</option>
                                        <option value="XMR">Monero (XMR)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Funding -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                            </svg>
                            Pricing & Funding
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Price <span class="text-red-500">*</span></label>
                                <input type="number" id="price" name="price" step="0.01" min="0" required class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="0.00">
                            </div>
                            <div>
                                <label for="currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Currency</label>
                                <select id="currency" name="currency" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                    <option value="USD">USD</option>
                                    <option value="EUR">EUR</option>
                                    <option value="GBP">GBP</option>
                                    <option value="JPY">JPY</option>
                                    <option value="CAD">CAD</option>
                                    <option value="AUD">AUD</option>
                                    <option value="CHF">CHF</option>
                                    <option value="CNY">CNY</option>
                                    <option value="SGD">SGD</option>
                                    <option value="NZD">NZD</option>
                                </select>
                            </div>
                            <div>
                                <label for="original_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Original Price <span class="text-gray-500 text-xs">(Optional)</span></label>
                                <input type="number" id="original_price" name="original_price" step="0.01" min="0" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="0.00">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="min_funding" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Min Funding <span class="text-red-500">*</span></label>
                                <input type="number" id="min_funding" name="min_funding" step="0.01" min="0" required class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="0.00">
                            </div>
                            <div>
                                <label for="max_funding" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Max Funding <span class="text-gray-500 text-xs">(Optional)</span></label>
                                <input type="number" id="max_funding" name="max_funding" step="0.01" min="0" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="0.00 for unlimited">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Leave empty or set to 0 for unlimited funding</p>
                            </div>
                        </div>
                    </div>

                    <!-- Plan Status -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Plan Status
                        </h4>
                        <div class="flex items-center">
                            <input type="checkbox" id="is_active" name="is_active" value="1" checked class="w-5 h-5 text-blue-600 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 focus:ring-2 transition-all duration-200">
                            <label for="is_active" class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Active Plan</label>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Uncheck to create an inactive plan that won't be visible to users</p>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="flex-shrink-0 flex items-center justify-end space-x-4 p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <button type="button" onclick="closeCreateModal()" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg font-medium transition-all duration-200">
                    Cancel
                </button>
                <button type="submit" form="createPlanForm" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-lg font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                    Create Plan
                </button>
            </div>
        </div>
    </div>
</div>


<script>
// Modal functionality
function openCreateModal(planType = null) {
    document.getElementById('createPlanModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // If a specific plan type is provided, select it
    if (planType) {
        const typeRadio = document.querySelector(`input[name="type"][value="${planType}"]`);
        if (typeRadio) {
            typeRadio.checked = true;
            showPlanFields(planType);
        }
    }
}

function closeCreateModal() {
    document.getElementById('createPlanModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    // Reset form
    document.getElementById('createPlanForm').reset();
    // Hide all plan fields
    document.querySelectorAll('.plan-fields').forEach(field => {
        field.classList.add('hidden');
    });
    // Reset plan type selection
    document.querySelectorAll('input[name="type"]').forEach(radio => {
        radio.checked = false;
    });
}

// Plan type selection
function showPlanFields(planType) {
    // Hide all plan fields
    document.querySelectorAll('.plan-fields').forEach(field => {
        field.classList.add('hidden');
    });
    
    // Show selected plan fields
    const selectedFields = document.getElementById(planType + '-fields');
    if (selectedFields) {
        selectedFields.classList.remove('hidden');
    }
    
    // Update plan type card styling
    document.querySelectorAll('.plan-type-card').forEach(card => {
        card.classList.remove('border-blue-500', 'border-purple-500', 'border-green-500', 'border-orange-500');
        card.classList.add('border-gray-200', 'dark:border-gray-600');
    });
    
    // Add active styling to selected card
    const selectedCard = document.querySelector(`input[value="${planType}"]`).closest('.plan-type-card');
    if (selectedCard) {
        selectedCard.classList.remove('border-gray-200', 'dark:border-gray-600');
        const colors = {
            'trading': 'border-blue-500',
            'signal': 'border-purple-500', 
            'staking': 'border-green-500',
            'mining': 'border-orange-500'
        };
        selectedCard.classList.add(colors[planType]);
    }
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCreateModal();
    }
});

// Close modal on backdrop click
document.getElementById('createPlanModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCreateModal();
    }
});

// Tab functionality
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('bg-blue-600', 'text-white', 'shadow-lg');
        button.classList.add('text-gray-400', 'hover:text-white', 'hover:bg-gray-700');
    });
    
    // Show selected tab content
    document.getElementById(`content-${tabName}`).classList.remove('hidden');
    
    // Add active class to selected tab button
    const activeButton = document.getElementById(`tab-${tabName}`);
    activeButton.classList.remove('text-gray-400', 'hover:text-white', 'hover:bg-gray-700');
    activeButton.classList.add('bg-blue-600', 'text-white', 'shadow-lg');
    
    // Update URL without page reload
    const url = new URL(window.location);
    url.searchParams.set('tab', tabName);
    window.history.pushState({}, '', url);
}

// Initialize with active tab
document.addEventListener('DOMContentLoaded', function() {
    const activeTab = '{{ $activeTab }}';
    
    // Set initial state for all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.add('text-gray-400', 'hover:text-white', 'hover:bg-gray-700');
    });
    
    // Switch to active tab
    switchTab(activeTab);
    
    // Add hover effects for better UX
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('mouseenter', function() {
            if (!this.classList.contains('bg-blue-600')) {
                this.classList.add('bg-gray-700');
            }
        });
        
        button.addEventListener('mouseleave', function() {
            if (!this.classList.contains('bg-blue-600')) {
                this.classList.remove('bg-gray-700');
            }
        });
    });
});







// Add responsive table functionality
document.addEventListener('DOMContentLoaded', function() {
    // Add responsive behavior for tables
    const tables = document.querySelectorAll('table');
    tables.forEach(table => {
        const wrapper = document.createElement('div');
        wrapper.className = 'overflow-x-auto';
        table.parentNode.insertBefore(wrapper, table);
        wrapper.appendChild(table);
    });
});

// Form validation function
function validateForm() {
    const form = document.getElementById('createPlanForm');
    const formData = new FormData(form);
    
    // Basic validation
    const planType = formData.get('type');
    const planName = formData.get('name');
    const price = formData.get('price');
    const minFunding = formData.get('min_funding');
    
    if (!planType) {
        alert('Please select a plan type');
        return false;
    }
    
    if (!planName || planName.trim() === '') {
        alert('Please enter a plan name');
        return false;
    }
    
    if (!price || price <= 0) {
        alert('Please enter a valid price');
        return false;
    }
    
    if (!minFunding || minFunding <= 0) {
        alert('Please enter a valid minimum funding amount');
        return false;
    }
    
    // Plan-specific validation
    if (planType === 'signal') {
        const signalAmount = formData.get('signal_amount');
        if (!signalAmount || signalAmount <= 0) {
            alert('Please enter a valid signal amount');
            return false;
        }
    }
    
    return true;
}

// Message handling functions
function hideMessage(messageId) {
    const message = document.getElementById(messageId);
    if (message) {
        message.style.display = 'none';
    }
}

// Debug logging function
function debugLog(message, data = null) {
    if (data) {
        console.log(`[Plan Debug] ${message}:`, data);
    } else {
        console.log(`[Plan Debug] ${message}`);
    }
}

function showMessage(type, message) {
    // Remove existing messages
    const existingMessages = document.querySelectorAll('#success-message, #error-message, #validation-errors');
    existingMessages.forEach(msg => msg.remove());
    
    // Create new message element
    const messageDiv = document.createElement('div');
    messageDiv.id = type === 'success' ? 'success-message' : 'error-message';
    messageDiv.className = type === 'success' 
        ? 'bg-green-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-center justify-between'
        : 'bg-red-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-center justify-between';
    
    const icon = type === 'success' 
        ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>'
        : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>';
    
    const closeColor = type === 'success' ? 'text-green-200' : 'text-red-200';
    
    messageDiv.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                ${icon}
            </svg>
            ${message}
        </div>
        <button onclick="hideMessage('${messageDiv.id}')" class="ml-4 ${closeColor} hover:text-white">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    `;
    
    // Insert message after the page header
    const header = document.querySelector('.flex.flex-col.sm\\:flex-row.justify-between');
    if (header) {
        header.parentNode.insertBefore(messageDiv, header.nextSibling);
    }
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        if (messageDiv.parentNode) {
            messageDiv.remove();
        }
    }, 5000);
}

// Add form submission handler
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createPlanForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Creating...';
            submitBtn.disabled = true;
            
            // Validate form
            if (!validateForm()) {
                e.preventDefault();
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
                return false;
            }
            
            // If validation passes, let the form submit naturally
            // The server will handle the response and show appropriate messages
        });
    }
    
    // Auto-hide messages after 5 seconds
    setTimeout(() => {
        const messages = document.querySelectorAll('#success-message, #error-message, #validation-errors');
        messages.forEach(msg => {
            if (msg.parentNode) {
                msg.remove();
            }
        });
    }, 5000);
});
</script>
@endsection
