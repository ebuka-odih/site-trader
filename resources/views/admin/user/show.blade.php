@extends('admin.layouts.app')

@section('content')
<div class="p-4 sm:p-6">
    <!-- Header -->
    <div class="mb-6">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('admin.user.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Users
            </a>
        </div>
        
        <!-- Title and Status -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">User Details</h1>
            <div class="flex items-center">
                {!! $user->status_badge !!}
            </div>
        </div>
    </div>

    <!-- User Profile Header -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-6">
            <!-- Avatar -->
            <div class="flex justify-center sm:justify-start">
                <div class="w-20 h-20 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                </div>
            </div>
            
            <!-- User Info -->
            <div class="flex-1 text-center sm:text-left">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base">{{ $user->email }}</p>
                <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 mt-2 space-y-1 sm:space-y-0">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Member since {{ $user->created_at->format('M Y') }}</span>
                    @if($user->last_login_at)
                        <span class="text-sm text-gray-500 dark:text-gray-400">Last active {{ $user->last_login_at->diffForHumans() }}</span>
                    @endif
                </div>
            </div>
            
            <!-- Balance -->
            <div class="text-center sm:text-right">
                <div class="text-2xl sm:text-3xl font-bold text-green-600 dark:text-green-400">${{ number_format($user->total_balance, 2) }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Total Balance</div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Left Column - Personal Information -->
        <div class="xl:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->username ?? 'Not set' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->email }}</p>
                        @if($user->email_verified_at)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Verified
                            </span>
                            <div class="mt-2">
                                <form method="POST" action="{{ route('admin.user.unverifyEmail', $user->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            onclick="return confirm('Are you sure you want to remove email verification for this user?')"
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Remove Verification
                                    </button>
                                </form>
                            </div>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Unverified
                            </span>
                            <div class="mt-2">
                                <form method="POST" action="{{ route('admin.user.verifyEmail', $user->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            onclick="return confirm('Are you sure you want to manually verify this user\\'s email?')"
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Verify Email
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->phone ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Country</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->country ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telegram Handle</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->telegram ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Account Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account Status</label>
                        {!! $user->status_badge !!}
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Trader Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->trader ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300' }}">
                            {{ $user->trader ? 'Active Trader' : 'Regular User' }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Trade Count</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->trade_count ?? 0 }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Member Since</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->created_at->format('F j, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- KYC Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">KYC Information</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $kycStatus['overall_status'] === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 
                           ($kycStatus['overall_status'] === 'in_progress' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300') }}">
                        {{ ucfirst(str_replace('_', ' ', $kycStatus['overall_status'])) }}
                    </span>
                </div>
                
                <!-- Personal Information -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Personal Information</h4>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                            {{ $kycStatus['personal_info']['status'] === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' }}">
                            {{ ucfirst($kycStatus['personal_info']['status']) }}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Date of Birth:</span>
                            <span class="text-gray-900 dark:text-white ml-2">{{ $kycStatus['personal_info']['date_of_birth'] ?: 'Not provided' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Nationality:</span>
                            <span class="text-gray-900 dark:text-white ml-2">{{ $kycStatus['personal_info']['nationality'] ?: 'Not provided' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Address Information</h4>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                            {{ $kycStatus['address_info']['status'] === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' }}">
                            {{ ucfirst($kycStatus['address_info']['status']) }}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                        <div class="md:col-span-2">
                            <span class="text-gray-500 dark:text-gray-400">Street Address:</span>
                            <span class="text-gray-900 dark:text-white ml-2">{{ $kycStatus['address_info']['street_address'] ?: 'Not provided' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">City:</span>
                            <span class="text-gray-900 dark:text-white ml-2">{{ $kycStatus['address_info']['city'] ?: 'Not provided' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">State:</span>
                            <span class="text-gray-900 dark:text-white ml-2">{{ $kycStatus['address_info']['state'] ?: 'Not provided' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Postal Code:</span>
                            <span class="text-gray-900 dark:text-white ml-2">{{ $kycStatus['address_info']['postal_code'] ?: 'Not provided' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Country:</span>
                            <span class="text-gray-900 dark:text-white ml-2">{{ $kycStatus['address_info']['country'] ?: 'Not provided' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Identity Documents -->
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Identity Documents</h4>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                            {{ $kycStatus['id_info']['status'] === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' }}">
                            {{ ucfirst($kycStatus['id_info']['status']) }}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">ID Type:</span>
                            <span class="text-gray-900 dark:text-white ml-2">{{ $kycStatus['id_info']['id_type'] ? ucfirst(str_replace('_', ' ', $kycStatus['id_info']['id_type'])) : 'Not provided' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">ID Number:</span>
                            <span class="text-gray-900 dark:text-white ml-2">{{ $kycStatus['id_info']['id_number'] ?: 'Not provided' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">ID Front:</span>
                            <span class="text-gray-900 dark:text-white ml-2">{{ $kycStatus['id_info']['id_front'] ? 'Uploaded' : 'Not uploaded' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">ID Back:</span>
                            <span class="text-gray-900 dark:text-white ml-2">{{ $kycStatus['id_info']['id_back'] ? 'Uploaded' : 'Not uploaded' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Selfie:</span>
                            <span class="text-gray-900 dark:text-white ml-2">{{ $kycStatus['id_info']['selfie'] ? 'Uploaded' : 'Not uploaded' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Financial Information & Actions -->
        <div class="space-y-6">
            <!-- Financial Overview -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Financial Overview</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Main Balance</span>
                        <span class="font-semibold text-gray-900 dark:text-white">${{ number_format($user->balance, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Trading Balance</span>
                        <span class="font-semibold text-gray-900 dark:text-white">${{ number_format($user->trading_balance, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Mining Balance</span>
                        <span class="font-semibold text-gray-900 dark:text-white">${{ number_format($user->mining_balance, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Referral Balance</span>
                        <span class="font-semibold text-gray-900 dark:text-white">${{ number_format($user->referral_balance, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Holding Balance</span>
                        <span class="font-semibold text-gray-900 dark:text-white">${{ number_format($user->holding_balance, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Staking Balance</span>
                        <span class="font-semibold text-gray-900 dark:text-white">${{ number_format($user->staking_balance, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Profit Balance</span>
                        <span class="font-semibold {{ $user->profit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $user->profit >= 0 ? '+' : '' }}${{ number_format($user->profit, 2) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center border-t border-gray-200 dark:border-gray-700 pt-4">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Total Balance</span>
                        <span class="text-lg font-bold text-green-600 dark:text-green-400">${{ number_format($user->total_balance, 2) }}</span>
                    </div>
                </div>
     </div>

            <!-- Balance Management -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Balance Management</h3>
                
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <h4 class="text-sm font-medium text-red-800 dark:text-red-200">Please correct the following errors:</h4>
                                <ul class="mt-2 text-sm text-red-700 dark:text-red-300 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.updateBalance', $user->id) }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <!-- Wallet Type Selection -->
                    <div>
                        <label for="wallet_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Wallet Type</label>
                        <select name="wallet_type" id="wallet_type" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            onchange="updateCurrentBalance()">
                            <option value="balance">Main Balance</option>
                            <option value="trading_balance">Trading Balance</option>
                            <option value="mining_balance">Mining Balance</option>
                            <option value="referral_balance">Referral Balance</option>
                            <option value="holding_balance">Holding Balance</option>
                            <option value="staking_balance">Staking Balance</option>
                            <option value="profit">Profit</option>
                        </select>
                    </div>

                    <!-- Current Balance Display -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Current Balance:</span>
                            <span id="current-balance" class="text-lg font-bold text-gray-900 dark:text-white">
                                ${{ number_format($user->balance, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- Amount Input -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Amount</label>
                        <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="Enter amount" required>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                        <button type="submit" name="action_type" value="add" 
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 px-3 rounded-md transition-colors flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Funds
                        </button>
                        <button type="submit" name="action_type" value="remove" 
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white text-sm font-medium py-2 px-3 rounded-md transition-colors flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m16 0l-4-4m4 4l-4 4"></path>
                            </svg>
                            Remove Funds
                        </button>
                    </div>
                </form>
            </div>

            <!-- Account Status Management -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Account Status</h3>
                <form action="{{ route('admin.updateStatus', $user->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Account Status</label>
                        <select name="status" id="status" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ $user->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>
                    <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        Update Status
                    </button>
                </form>
                
                <!-- Quick Suspend/Unsuspend Actions -->
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 space-y-2">
                    @if($user->status !== 'suspended')
                        <form action="{{ route('admin.user.suspend', $user->id) }}" method="POST" class="inline-block w-full">
                            @csrf
                            <button type="submit" 
                                    onclick="return confirm('Are you sure you want to suspend this user account?')"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                </svg>
                                Suspend Account
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.user.unsuspend', $user->id) }}" method="POST" class="inline-block w-full">
                            @csrf
                            <button type="submit" 
                                    onclick="return confirm('Are you sure you want to unsuspend this user account?')"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Unsuspend Account
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.transactions.deposits') }}?user={{ $user->id }}" 
                        class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        View Deposits
                    </a>
                    <a href="{{ route('admin.transactions.withdrawals') }}?user={{ $user->id }}" 
                        class="block w-full text-center bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        View Withdrawals
                    </a>
                    <a href="{{ route('admin.trade.history') }}?user={{ $user->id }}" 
                        class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        View Trade History
                    </a>
                    <button onclick="confirmDelete()" 
                        class="block w-full text-center bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        Delete User
                    </button>
                </div>
            </div>
        </div>
    </div>
         </div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Confirm User Deletion</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Are you sure you want to delete this user? This action cannot be undone.</p>
            <div class="flex space-x-3">
                <button onclick="closeDeleteModal()" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors">
                    Cancel
                </button>
                <form action="{{ route('admin.deleteUser', $user->id) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                        class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        Delete User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// User balance data for JavaScript
const userBalances = {
    balance: {{ $user->balance }},
    trading_balance: {{ $user->trading_balance }},
    mining_balance: {{ $user->mining_balance }},
    referral_balance: {{ $user->referral_balance }},
    holding_balance: {{ $user->holding_balance }},
    staking_balance: {{ $user->staking_balance }},
    profit: {{ $user->profit }}
};

function updateCurrentBalance() {
    const walletType = document.getElementById('wallet_type').value;
    const currentBalance = userBalances[walletType];
    const balanceElement = document.getElementById('current-balance');
    
    if (balanceElement) {
        balanceElement.textContent = '$' + currentBalance.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }
}


function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Initialize the balance display on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCurrentBalance();
});
</script>
@endsection