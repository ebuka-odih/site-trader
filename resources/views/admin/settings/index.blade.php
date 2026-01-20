@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
<div class="p-4">
    <div class="max-w-7xl mx-auto p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Settings</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage your profile and system settings</p>
        </div>

        <!-- Session Messages -->
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Settings Tabs -->
        <div class="mb-6">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-8">
                    <button onclick="showTab('profile')" id="profile-tab" class="tab-button py-2 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600 dark:text-blue-400">
                        Profile Settings
                    </button>
                    <button onclick="showTab('system')" id="system-tab" class="tab-button py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300">
                        System Settings
                    </button>
                    <button onclick="showTab('livechat')" id="livechat-tab" class="tab-button py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300">
                        Livechat Settings
                    </button>
                    <button onclick="showTab('website')" id="website-tab" class="tab-button py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300">
                        Website Settings
                    </button>
                </nav>
            </div>
        </div>

        <!-- Profile Settings Tab -->
        <div id="profile-settings" class="tab-content">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Profile Information</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Update your personal information and profile picture</p>
                </div>
                
                <form id="profileForm" method="POST" action="{{ route('admin.settings.profile.update') }}" class="p-6 space-y-6">
                    @csrf
                    <div class="flex items-center space-x-6">
                        <!-- Profile Image -->
                        <div class="flex-shrink-0">
                            <div class="relative">
                                <img id="profile-image-preview" 
                                     src="{{ $admin->profile_image ? asset('storage/' . $admin->profile_image) : asset('img/avatars/trader1.jpg') }}" 
                                     alt="Profile" 
                                     class="w-20 h-20 rounded-full object-cover border-4 border-gray-200 dark:border-gray-600">
                                <label for="profile_image" class="absolute inset-0 w-20 h-20 rounded-full bg-black bg-opacity-50 flex items-center justify-center cursor-pointer opacity-0 hover:opacity-100 transition-opacity">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </label>
                                <input type="file" id="profile_image" name="profile_image" accept="image/*" class="hidden">
                            </div>
                        </div>
                        
                        <div class="flex-1">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ $admin->name }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $admin->email }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500">Admin since {{ $admin->created_at->format('M Y') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                            <input type="text" id="name" name="name" value="{{ $admin->name }}" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ $admin->email }}" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                            <input type="text" id="phone" name="phone" value="{{ $admin->phone ?? '' }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Change Password</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Password</label>
                                <input type="password" id="current_password" name="current_password"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Password</label>
                                <input type="password" id="password" name="password"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm New Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- System Settings Tab -->
        <div id="system-settings" class="tab-content hidden">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">System Configuration</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Configure general system settings and preferences</p>
                </div>
                
                <form id="systemForm" method="POST" action="{{ route('admin.settings.system.update') }}" class="p-6 space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="site_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Site Name</label>
                            <input type="text" id="site_name" name="site_name" value="{{ $systemSettings['site_name'] }}" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                        
                        <div>
                            <label for="site_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Site Email</label>
                            <input type="email" id="site_email" name="site_email" value="{{ $systemSettings['site_email'] }}" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                        
                        <div>
                            <label for="default_currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Default Currency</label>
                            <select id="default_currency" name="default_currency" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                <option value="USD" {{ $systemSettings['default_currency'] == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                <option value="EUR" {{ $systemSettings['default_currency'] == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                <option value="GBP" {{ $systemSettings['default_currency'] == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                <option value="JPY" {{ $systemSettings['default_currency'] == 'JPY' ? 'selected' : '' }}>JPY - Japanese Yen</option>
                                <option value="CAD" {{ $systemSettings['default_currency'] == 'CAD' ? 'selected' : '' }}>CAD - Canadian Dollar</option>
                                <option value="AUD" {{ $systemSettings['default_currency'] == 'AUD' ? 'selected' : '' }}>AUD - Australian Dollar</option>
                                <option value="CHF" {{ $systemSettings['default_currency'] == 'CHF' ? 'selected' : '' }}>CHF - Swiss Franc</option>
                                <option value="CNY" {{ $systemSettings['default_currency'] == 'CNY' ? 'selected' : '' }}>CNY - Chinese Yuan</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="timezone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Timezone</label>
                            <select id="timezone" name="timezone" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                <option value="UTC" {{ $systemSettings['timezone'] == 'UTC' ? 'selected' : '' }}>UTC</option>
                                <option value="America/New_York" {{ $systemSettings['timezone'] == 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                                <option value="America/Los_Angeles" {{ $systemSettings['timezone'] == 'America/Los_Angeles' ? 'selected' : '' }}>America/Los_Angeles</option>
                                <option value="Europe/London" {{ $systemSettings['timezone'] == 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                                <option value="Europe/Paris" {{ $systemSettings['timezone'] == 'Europe/Paris' ? 'selected' : '' }}>Europe/Paris</option>
                                <option value="Asia/Tokyo" {{ $systemSettings['timezone'] == 'Asia/Tokyo' ? 'selected' : '' }}>Asia/Tokyo</option>
                                <option value="Asia/Shanghai" {{ $systemSettings['timezone'] == 'Asia/Shanghai' ? 'selected' : '' }}>Asia/Shanghai</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="max_file_upload_size" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Max File Upload Size (KB)</label>
                            <input type="number" id="max_file_upload_size" name="max_file_upload_size" value="{{ $systemSettings['max_file_upload_size'] }}" min="100" max="10240" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    <!-- Toggle Settings -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">Feature Toggles</h4>
                        
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div>
                                <h5 class="text-sm font-medium text-gray-900 dark:text-white">Maintenance Mode</h5>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Enable maintenance mode to temporarily disable the site</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1" {{ $systemSettings['maintenance_mode'] ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div>
                                <h5 class="text-sm font-medium text-gray-900 dark:text-white">User Registration</h5>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Allow new users to register on the site</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="registration_enabled" name="registration_enabled" value="1" {{ $systemSettings['registration_enabled'] ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div>
                                <h5 class="text-sm font-medium text-gray-900 dark:text-white">Email Verification Required</h5>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Require users to verify their email before accessing the site</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="email_verification_required" name="email_verification_required" value="1" {{ $systemSettings['email_verification_required'] ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                            Update System Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Livechat Settings Tab -->
        <div id="livechat-settings" class="tab-content hidden">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Livechat Configuration</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Configure your livechat widget settings and appearance</p>
                </div>
                
                <form method="POST" action="{{ route('admin.settings.livechat.update') }}" class="p-6 space-y-6">
                    @csrf
                    <!-- Provider Settings -->
                    <div class="space-y-6">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white">Provider Configuration</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="provider" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Livechat Provider</label>
                                <select id="provider" name="provider" required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                    <option value="jivochat" {{ $livechatSettings['provider'] == 'jivochat' ? 'selected' : '' }}>JivoChat</option>
                                    <option value="tawk" {{ $livechatSettings['provider'] == 'tawk' ? 'selected' : '' }}>Tawk.to</option>
                                    <option value="intercom" {{ $livechatSettings['provider'] == 'intercom' ? 'selected' : '' }}>Intercom</option>
                                    <option value="zendesk" {{ $livechatSettings['provider'] == 'zendesk' ? 'selected' : '' }}>Zendesk Chat</option>
                                    <option value="other" {{ $livechatSettings['provider'] == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="widget_script" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Livechat Script</label>
                                <textarea id="widget_script" name="widget_script" rows="6" required
                                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                          placeholder="Paste your complete livechat JavaScript code here...">{{ $livechatSettings['widget_script'] ?? '' }}</textarea>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Paste the complete livechat script (including &lt;script&gt; tags) from your provider</p>
                            </div>
                        </div>
                    </div>

                    <!-- Display Settings -->
                    <div class="space-y-6">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white">Display Settings</h4>
                        
                        <!-- Enable/Disable Toggle -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div>
                                <h5 class="text-sm font-medium text-gray-900 dark:text-white">Enable Livechat</h5>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Show livechat widget on the website</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="is_enabled" name="is_enabled" value="1" {{ $livechatSettings['is_enabled'] ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <!-- Page Visibility Settings -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div>
                                    <h5 class="text-sm font-medium text-gray-900 dark:text-white">Support Page</h5>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Show on support page</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="show_on_support_page" name="show_on_support_page" value="1" {{ $livechatSettings['show_on_support_page'] ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                            
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div>
                                    <h5 class="text-sm font-medium text-gray-900 dark:text-white">Contact Page</h5>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Show on contact page</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="show_on_contact_page" name="show_on_contact_page" value="1" {{ $livechatSettings['show_on_contact_page'] ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                            
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div>
                                    <h5 class="text-sm font-medium text-gray-900 dark:text-white">Homepage</h5>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Show on homepage</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="show_on_homepage" name="show_on_homepage" value="1" {{ $livechatSettings['show_on_homepage'] ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </div>

                        <!-- Widget Position -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="widget_position" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Widget Position</label>
                                <select id="widget_position" name="widget_position" required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                    <option value="bottom-right" {{ $livechatSettings['widget_position'] == 'bottom-right' ? 'selected' : '' }}>Bottom Right</option>
                                    <option value="bottom-left" {{ $livechatSettings['widget_position'] == 'bottom-left' ? 'selected' : '' }}>Bottom Left</option>
                                    <option value="top-right" {{ $livechatSettings['widget_position'] == 'top-right' ? 'selected' : '' }}>Top Right</option>
                                    <option value="top-left" {{ $livechatSettings['widget_position'] == 'top-left' ? 'selected' : '' }}>Top Left</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <!-- Save Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            Save Livechat Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Website Settings Tab -->
        <div id="website-settings" class="tab-content hidden">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Website Configuration</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Manage your website appearance and branding settings</p>
                </div>
                
                <form id="websiteForm" method="POST" action="{{ route('admin.settings.website.update') }}" class="p-6 space-y-6" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Site Logo Section -->
                    <div class="space-y-6">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white">Site Logo</h4>
                        
                        <!-- Current Logo Display -->
                        <div class="flex items-center space-x-6">
                            <div class="flex-shrink-0">
                                <div class="relative">
                                    <img id="logo-preview" src="{{ \App\Helpers\WebsiteSettingsHelper::getLogoUrl() }}" 
                                         alt="Site Logo" class="w-24 h-24 object-contain border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-20 rounded-lg transition-all duration-200 flex items-center justify-center">
                                        <span class="text-white text-xs opacity-0 hover:opacity-100 transition-opacity">Change</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-1">
                                <label for="site_logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upload New Logo</label>
                                <input type="file" id="site_logo" name="site_logo" accept="image/*" 
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Recommended size: 200x60px. Supported formats: PNG, JPG, SVG</p>
                            </div>
                        </div>
                    </div>

                    <!-- Site Information Section -->
                    <div class="space-y-6">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white">Site Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="website_site_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Site Name</label>
                                <input type="text" id="website_site_name" name="site_name" value="{{ \App\Helpers\WebsiteSettingsHelper::getSiteName() }}" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="site_tagline" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Site Tagline</label>
                                <input type="text" id="site_tagline" name="site_tagline" value="{{ \App\Helpers\WebsiteSettingsHelper::getSiteTagline() }}" 
                                       placeholder="Your site tagline or description"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>
                        
                        <!-- Text Logo Field -->
                        <div class="mt-6">
                            <label for="text_logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Text Logo</label>
                            <input type="text" id="text_logo" name="text_logo" value="{{ \App\Helpers\WebsiteSettingsHelper::getTextLogo() }}" 
                                   placeholder="Enter text logo (e.g., CB, CryptoBroker, etc.)"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">If set, this text will be used as logo instead of image logo. Leave empty to use image logo or site name.</p>
                        </div>
                    </div>

                    <!-- Branding Colors Section -->
                    <div class="space-y-6">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white">Branding Colors</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="primary_color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Primary Color</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" id="primary_color" name="primary_color" value="{{ \App\Helpers\WebsiteSettingsHelper::getPrimaryColor() }}" 
                                           class="w-12 h-10 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer">
                                    <input type="text" value="{{ \App\Helpers\WebsiteSettingsHelper::getPrimaryColor() }}" readonly
                                           class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                                </div>
                            </div>
                            
                            <div>
                                <label for="secondary_color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Secondary Color</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" id="secondary_color" name="secondary_color" value="{{ \App\Helpers\WebsiteSettingsHelper::getSecondaryColor() }}" 
                                           class="w-12 h-10 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer">
                                    <input type="text" value="{{ \App\Helpers\WebsiteSettingsHelper::getSecondaryColor() }}" readonly
                                           class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Save Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            Save Website Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
// Tab switching functionality - Global function (defined immediately)
function showTab(tabName) {
    try {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Remove active class from all tabs
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('border-blue-500', 'text-blue-600', 'dark:text-blue-400');
            button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
        });
        
        // Show selected tab content
        const tabContent = document.getElementById(tabName + '-settings');
        if (tabContent) {
            tabContent.classList.remove('hidden');
        }
        
        // Add active class to selected tab
        const activeTab = document.getElementById(tabName + '-tab');
        if (activeTab) {
            activeTab.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
            activeTab.classList.add('border-blue-500', 'text-blue-600', 'dark:text-blue-400');
        }
    } catch (error) {
        console.error('Error switching tab:', error);
    }
}
</script>

@push('scripts')
<script>

document.addEventListener('DOMContentLoaded', function() {
// Profile image preview
document.getElementById('profile_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profile-image-preview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// Profile form submission
document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("admin.settings.profile.update") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message
                });
            } else {
                alert(data.message);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'An error occurred while updating profile'
            });
        } else {
            alert('Error updating profile');
        }
    });
});

// System settings form submission
document.getElementById('systemForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    
    // Convert checkbox values to boolean
    data.maintenance_mode = document.getElementById('maintenance_mode').checked;
    data.registration_enabled = document.getElementById('registration_enabled').checked;
    data.email_verification_required = document.getElementById('email_verification_required').checked;
    
    fetch('{{ route("admin.settings.system.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message
                });
            } else {
                alert(data.message);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'An error occurred while updating system settings'
            });
        } else {
            alert('Error updating system settings');
        }
    });

    // Livechat form now uses traditional form submission


    // Website logo preview
    document.getElementById('site_logo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logo-preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Website color picker synchronization
    document.getElementById('primary_color').addEventListener('input', function(e) {
        const textInput = e.target.nextElementSibling;
        textInput.value = e.target.value;
    });

    document.getElementById('secondary_color').addEventListener('input', function(e) {
        const textInput = e.target.nextElementSibling;
        textInput.value = e.target.value;
    });

    // Website form submission - Let it submit normally for redirect
    // No need to prevent default or handle with AJAX since we're redirecting
});
</script>
@endpush
