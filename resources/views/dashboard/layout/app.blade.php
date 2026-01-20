
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ \App\Helpers\WebsiteSettingsHelper::getSiteName() }} - Dashboard</title>
  <link rel="icon" href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon">
  
  {{-- PWA Meta Tags --}}
  <meta name="theme-color" content="#1fff9c">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="apple-mobile-web-app-title" content="{{ \App\Helpers\WebsiteSettingsHelper::getSiteName() }}">
  <meta name="description" content="{{ \App\Helpers\WebsiteSettingsHelper::getSiteTagline() ?: 'Secure cryptocurrency trading platform' }}">
  
  {{-- Apple Touch Icons --}}
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('pwa-icons/apple-touch-icon-180x180.png') }}">
  <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('pwa-icons/apple-touch-icon-152x152.png') }}">
  <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('pwa-icons/apple-touch-icon-120x120.png') }}">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('pwa-icons/apple-touch-icon-76x76.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('pwa-icons/apple-touch-icon-180x180.png') }}">
  
  {{-- PWA Manifest --}}
  <link rel="manifest" href="{{ route('pwa.manifest') }}">
    
    <!-- Tailwind CSS CDN for immediate styling -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Pusher JS for real-time updates -->
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>

    <!-- TradingView Widget -->
    <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>

    <!-- SweetAlert2 for notifications -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Vite assets (uncomment when running npm run dev) -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    
    @livewireStyles

    <style>
        [x-cloak] { display: none !important; }
        
        /* Theme CSS Variables */
        :root {
            /* Light theme colors */
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-tertiary: #f1f5f9;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --border-hover: #cbd5e1;
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        }
        
        .dark {
            /* Dark theme colors */
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-tertiary: #334155;
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            --border-color: #475569;
            --border-hover: #64748b;
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.3), 0 1px 2px -1px rgb(0 0 0 / 0.3);
        }
        
        /* Light theme classes - More specific selectors */
        .light-theme {
            background-color: var(--bg-primary) !important;
            color: var(--text-primary) !important;
        }
        
        .light-theme,
        .light-theme * {
            background-color: inherit;
            color: inherit;
        }
        
        /* Background overrides */
        .light-theme .bg-gray-900,
        .light-theme div.bg-gray-900,
        .light-theme body.bg-gray-900 {
            background-color: var(--bg-primary) !important;
        }
        
        .light-theme .bg-gray-800,
        .light-theme div.bg-gray-800,
        .light-theme header.bg-gray-800,
        .light-theme nav.bg-gray-800 {
            background-color: var(--bg-secondary) !important;
        }
        
        .light-theme .bg-gray-700,
        .light-theme div.bg-gray-700,
        .light-theme button.bg-gray-700 {
            background-color: var(--bg-tertiary) !important;
        }
        
        .light-theme .bg-gray-600 {
            background-color: var(--border-hover) !important;
        }
        
        /* Text color overrides */
        .light-theme .text-white,
        .light-theme h1.text-white,
        .light-theme h2.text-white,
        .light-theme h3.text-white,
        .light-theme span.text-white {
            color: var(--text-primary) !important;
        }
        
        .light-theme .text-gray-300,
        .light-theme span.text-gray-300,
        .light-theme p.text-gray-300 {
            color: var(--text-secondary) !important;
        }
        
        .light-theme .text-gray-400,
        .light-theme span.text-gray-400,
        .light-theme button.text-gray-400 {
            color: var(--text-muted) !important;
        }
        
        .light-theme .text-gray-200 {
            color: var(--text-secondary) !important;
        }
        
        /* Border overrides */
        .light-theme .border-gray-700,
        .light-theme .border-r.border-gray-700,
        .light-theme .border-b.border-gray-700,
        .light-theme .border-t.border-gray-700 {
            border-color: var(--border-color) !important;
        }
        
        .light-theme .border-gray-600 {
            border-color: var(--border-color) !important;
        }
        
        /* Hover effects */
        .light-theme .hover\\:bg-gray-700:hover,
        .light-theme button:hover {
            background-color: var(--border-hover) !important;
        }
        
        .light-theme .hover\\:bg-gray-600:hover {
            background-color: var(--border-hover) !important;
        }
        
        .light-theme .hover\\:bg-gray-800:hover {
            background-color: var(--bg-tertiary) !important;
        }
        
        .light-theme .hover\\:text-white:hover,
        .light-theme button:hover {
            color: var(--text-primary) !important;
        }
        
        .light-theme .hover\\:text-gray-300:hover {
            color: var(--text-secondary) !important;
        }
        
        /* Specific component overrides */
        .light-theme #sidebar {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-color) !important;
        }
        
        .light-theme header {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-color) !important;
        }
        
        .light-theme main {
            background-color: var(--bg-primary) !important;
        }
        
        /* Dropdown menus */
        .light-theme .bg-gray-800.border.border-gray-700 {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-color) !important;
        }
        
        /* Buttons and interactive elements */
        .light-theme button {
            color: var(--text-muted) !important;
        }
        
        .light-theme button:hover {
            background-color: var(--border-hover) !important;
            color: var(--text-primary) !important;
        }
        
        /* Additional light theme overrides for better coverage */
        .light-theme .min-h-screen {
            background-color: var(--bg-primary) !important;
        }
        
        .light-theme .h-screen {
            background-color: var(--bg-primary) !important;
        }
        
        .light-theme .flex {
            color: inherit;
        }
        
        .light-theme .flex-1 {
            background-color: inherit;
        }
        
        .light-theme .overflow-hidden {
            background-color: inherit;
        }
        
        .light-theme .overflow-x-hidden {
            background-color: inherit;
        }
        
        .light-theme .overflow-y-auto {
            background-color: inherit;
        }
        
        /* Force light theme on all elements */
        .light-theme * {
            color: inherit !important;
        }
        
        .light-theme div,
        .light-theme section,
        .light-theme article,
        .light-theme aside,
        .light-theme nav,
        .light-theme header,
        .light-theme main,
        .light-theme footer {
            background-color: inherit !important;
        }
        
        /* Theme transition */
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
        
        /* Tooltip styles */
        .tooltip-container {
            position: relative;
        }
        .tooltip-container:hover .tooltip {
            opacity: 1;
            visibility: visible;
        }
        .tooltip {
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            margin-bottom: 8px;
            padding: 6px 12px;
            background-color: rgba(0, 0, 0, 0.9);
            color: white;
            font-size: 12px;
            white-space: nowrap;
            border-radius: 6px;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s, visibility 0.2s;
            pointer-events: none;
            z-index: 50;
        }
        .tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 5px solid transparent;
            border-top-color: rgba(0, 0, 0, 0.9);
        }
    </style>


</head>

<body class="bg-gray-900 text-white min-h-screen dark">
    <div class="h-screen bg-gray-900">
        <!-- Sidebar Backdrop -->
        <div id="sidebarBackdrop" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>
        
        <!-- Sidebar -->
        <div id="sidebar" class="fixed top-0 left-0 w-64 h-full bg-gray-800 border-r border-gray-700 flex flex-col transform transition-transform duration-300 ease-in-out z-50">
            <!-- User Profile Section -->
            <div class="p-3 border-b border-gray-700">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-white font-semibold">Menu</h3>
                    <!-- Close button -->
                    <button id="sidebarClose" class="p-1 text-gray-400 hover:text-white hover:bg-gray-700 rounded transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold text-lg">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold">{{ auth()->user()->name }}</h3>
                        <p class="text-gray-400 text-sm">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>



            <!-- Navigation Menu -->
            <nav class="flex-1 p-3 space-y-3 overflow-y-auto">
                <!-- Main Section -->
                <div>
                    <div class="px-3 py-1.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Main
                    </div>
                    <div class="space-y-0.5">
                        <a href="{{ route('user.dashboard') }}" class="flex items-center space-x-3 px-3 py-1.5 rounded-lg {{ request()->routeIs('user.dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </div>
                </div>

                <!-- Finance & Wallet Section -->
                <div>
                    <div class="px-3 py-1.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Finance & Wallet
                    </div>
                    <div class="space-y-0.5">
                        <a href="{{ route('user.deposit') }}" class="flex items-center space-x-3 px-3 py-1.5 rounded-lg {{ request()->routeIs('user.deposit') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="w-5 h-5" fill="currentColor">
                                <path d="M17.12 9.88a2.997 2.997 0 1 0-4.24 4.24a2.997 2.997 0 1 0 4.24-4.24M7 6v12h16V6zm14 8c-.53 0-1.04.21-1.41.59c-.38.37-.59.88-.59 1.41h-8c0-.53-.21-1.04-.59-1.41c-.37-.38-.88-.59-1.41-.59v-4c.53 0 1.04-.21 1.41-.59c.38-.37.59-.88.59-1.41h8c0 .53.21 1.04.59 1.41c.37.38.88.59 1.41.59zM5 8H3c-.55 0-1-.45-1-1s.45-1 1-1h2zm0 5H2c-.55 0-1-.45-1-1s.45-1 1-1h3zm0 5H1c-.552 0-1-.45-1-1s.448-1 1-1h4z"/>
                            </svg>
                            <span>Deposit</span>
                        </a>
                        <a href="{{ route('user.withdrawal') }}" class="flex items-center space-x-3 px-3 py-1.5 rounded-lg {{ request()->routeIs('user.withdrawal') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5">
                                <g>
                                    <path stroke-linecap="round" d="M12 10.999c-1.105 0-2 .67-2 1.499c0 .827.895 1.498 2 1.498s2 .671 2 1.499c0 .827-.895 1.498-2 1.498M12 11c.87 0 1.612.417 1.886.999m-1.886-1V10m0 6.993c-.87 0-1.612-.417-1.886-.999m1.886 1L12.003 18"/>
                                    <path stroke-linecap="round" d="M21 11a1.5 1.5 0 0 0 .414-.305C22 10.089 22 9.11 22 7.152s0-2.936-.586-3.544S19.886 3 18 3H6c-1.886 0-2.828 0-3.414.608S2 5.195 2 7.152s0 2.936.586 3.543q.18.188.414.305"/>
                                    <circle cx="12" cy="14" r="7"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 7h14"/>
                                </g>
                            </svg>
                            <span>Withdraw</span>
                        </a>
                    </div>
                </div>

                <!-- Investments Section -->
                <div>
                    <div class="px-3 py-1.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Investments
                    </div>
                    <div class="space-y-0.5">
                        <!-- Plans Dropdown -->
                        <div class="relative">
                            <button id="plansDropdown" class="flex items-center justify-between w-full px-3 py-1.5 rounded-lg text-gray-300 hover:bg-gray-700 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Available Plans</span>
                                </div>
                                <svg class="w-4 h-4 transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="plansDropdownMenu" class="absolute left-0 right-0 mt-1 bg-gray-800 border border-gray-700 rounded-lg shadow-lg opacity-0 invisible transition-all duration-200 transform -translate-y-2 z-50">
                                <div class="py-1">
                                    <a href="{{ route('user.plan.trading') }}" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                                        </svg>
                                        <span>Trading Plans</span>
                                    </a>
                                    <a href="{{ route('user.plan.signal') }}" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Signal Plans</span>
                                    </a>
                                    <a href="{{ route('user.mining.index') }}" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Mining Plans</span>
                                    </a>
                                    <a href="{{ route('user.staking.index') }}" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Staking Plans</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('user.plans.index') }}" class="flex items-center space-x-3 px-3 py-1.5 rounded-lg {{ request()->routeIs('user.plans.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" clip-rule="evenodd"></path>
                            </svg>
                            <span>My Plans</span>
                        </a>
                        <a href="{{ route('user.portfolio.index') }}" class="flex items-center space-x-3 px-3 py-1.5 rounded-lg {{ request()->routeIs('user.portfolio.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"></path>
                            </svg>
                            <span>Portfolio</span>
                        </a>
                    </div>
                </div>

                <!-- Trading Section -->
                <div>
                    <div class="px-3 py-1.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Trading
                    </div>
                    <div class="space-y-0.5">
                        <a href="{{ route("user.overview.index") }}" class="flex items-center space-x-3 px-3 py-1.5 rounded-lg {{ request()->routeIs("user.overview.*") ? "bg-blue-600 text-white" : "text-gray-300 hover:bg-gray-700" }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                            </svg>
                            <span>Overview</span>
                        </a>
                        <a href="{{ route('user.liveTrading.index') }}" class="flex items-center space-x-3 px-3 py-1.5 rounded-lg {{ request()->routeIs('user.trade.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                            </svg>
                            <span>Live Trading</span>
                        </a>
                        <a href="{{ route('user.copyTrading.index') }}" class="flex items-center space-x-3 px-3 py-1.5 rounded-lg {{ request()->routeIs('user.copyTrading.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="w-5 h-5" fill="currentColor">
                                <path d="M11.5 4a3.5 3.5 0 1 0 0 7a3.5 3.5 0 0 0 0-7M6 7.5a5.5 5.5 0 1 1 11 0a5.5 5.5 0 0 1-11 0m8.382 6h7.236l2.081 4.162L18 23.995l-5.7-6.333zm1.236 2l-.919 1.838L18 21.005l3.3-3.667l-.918-1.838zM8 16a4 4 0 0 0-4 4h7.05v2H2v-2a6 6 0 0 1 6-6h3v2z"/>
                            </svg>
                            <span>Copy Trading</span>
                        </a>
                        <a href="{{ route('user.botTrading.index') }}" class="flex items-center space-x-3 px-3 py-1.5 rounded-lg {{ request()->routeIs('user.botTrading.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="w-5 h-5" fill="currentColor">
                                <path d="M21.928 11.607c-.202-.488-.635-.605-.928-.633V8c0-1.103-.897-2-2-2h-6V4.61c.305-.274.5-.668.5-1.11a1.5 1.5 0 0 0-3 0c0 .442.195.836.5 1.11V6H5c-1.103 0-2 .897-2 2v2.997l-.082.006A1 1 0 0 0 1.99 12v2a1 1 0 0 0 1 1H3v5c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2v-5a1 1 0 0 0 1-1v-1.938a1 1 0 0 0-.072-.455M5 20V8h14l.001 3.996L19 12v2l.001.005l.001 5.995z"/>
                                <ellipse cx="8.5" cy="12" fill="currentColor" rx="1.5" ry="2"/>
                                <ellipse cx="15.5" cy="12" fill="currentColor" rx="1.5" ry="2"/>
                                <path fill="currentColor" d="M8 16h8v2H8z"/>
                            </svg>
                            <span>Bot Trading</span>
                        </a>
                        <a href="{{ route('user.aiTraders.index') }}" class="flex items-center space-x-3 px-3 py-1.5 rounded-lg {{ request()->routeIs('user.aiTraders.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.94-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                            </svg>
                            <span>AI Traders</span>
                        </a>
                        
                        <!-- <a href="{{ route('user.liveTrading.index') }}" class="flex items-center space-x-3 px-3 py-1.5 rounded-lg {{ request()->routeIs('user.liveTrading.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Live Trading</span>
                        </a> -->
                    </div>
                </div>

                <!-- Support Section -->
                <div>
                    <div class="px-3 py-1.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Support
                    </div>
                    <div class="space-y-0.5">
                        <a href="{{ route('user.support.index') }}" class="flex items-center space-x-3 px-3 py-1.5 rounded-lg {{ request()->routeIs('user.support.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-2 0c0 .993-.241 1.929-.668 2.754l-1.524-1.525a3.997 3.997 0 00.078-2.183l1.562-1.562C15.759 8.071 16 9.007 16 10zm-5.165 3.913l1.58 1.58A5.98 5.98 0 0110 16a5.976 5.976 0 01-2.516-.552l1.562-1.562a4.006 4.006 0 001.789.027zm-4.677-2.796a3.998 3.998 0 00-.027-1.789l-1.562 1.562C4.241 8.071 4 9.007 4 10c0 .993.241 1.929.668 2.754l1.524-1.525zm11.33-4.753l-1.562 1.562a4.006 4.006 0 00-1.789-.027l-1.58-1.58a5.98 5.98 0 012.516-.552 5.976 5.976 0 012.415.597zM4.668 7.246l1.525 1.525a3.997 3.997 0 00-.078 2.183l-1.562 1.562C4.241 11.929 4 10.993 4 10c0-.993.241-1.929.668-2.754z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Support Center</span>
                        </a>
                    </div>
                </div>
            </nav>
            <!-- Bottom padding to ensure last items are visible -->
            <div class="pb-8"></div>
        </div>

        <!-- Main Content -->
        <div class="w-full h-full flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-gray-800 border-b border-gray-700 py-2">
                <div class="flex items-center">
                    <!-- Left side - Brand and menu -->
                    <div class="flex items-center space-x-4 px-4 sm:px-6">
                        <!-- Menu Toggle Button -->
                        <button id="sidebarToggle" class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <div class="flex items-center relative">
                            <!-- Modern Christmas Tree Icon with Tooltip -->
                            <div class="tooltip-container absolute -top-8 left-1/2 transform -translate-x-1/2 z-10 cursor-pointer">
                                <svg width="48" height="48" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <!-- Star on top -->
                                    <path d="M16 2 L17.5 8 L24 8 L19 12 L20.5 18 L16 14 L11.5 18 L13 12 L8 8 L14.5 8 Z" fill="#FFD700" stroke="#FFA500" stroke-width="0.5"/>
                                    <!-- Top triangle -->
                                    <path d="M16 10 L12 18 L20 18 Z" fill="#10B981" stroke="#059669" stroke-width="0.5"/>
                                    <!-- Middle triangle -->
                                    <path d="M16 15 L10 24 L22 24 Z" fill="#10B981" stroke="#059669" stroke-width="0.5"/>
                                    <!-- Trunk -->
                                    <rect x="14" y="24" width="4" height="4" fill="#92400E" rx="1"/>
                                </svg>
                                <span class="tooltip">Merry Christmas</span>
                            </div>
                            @if(\App\Helpers\WebsiteSettingsHelper::hasTextLogo())
                                <!-- Text Logo -->
                                <div class="h-16 flex items-center pt-2">
                                    <span class="text-white font-extrabold text-2xl tracking-wide">{{ \App\Helpers\WebsiteSettingsHelper::getTextLogo() }}</span>
                                </div>
                            @elseif(\App\Helpers\WebsiteSettingsHelper::hasImageLogo())
                                <!-- Image Logo -->
                                <img src="{{ \App\Helpers\WebsiteSettingsHelper::getLogoUrl() }}" 
                                     alt="{{ \App\Helpers\WebsiteSettingsHelper::getSiteName() }}" 
                                     class="h-16 w-auto object-contain pt-2">
                            @else
                                <!-- Site Name as Logo (fallback) -->
                                <div class="h-16 flex items-center pt-2">
                                    <span class="text-white font-extrabold text-2xl tracking-wide">{{ \App\Helpers\WebsiteSettingsHelper::getSiteName() }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Right side - Notification and user profile -->
                    <div class="flex items-center space-x-1 sm:space-x-2 ml-auto -mr-2 sm:-mr-4">
                        

                        <!-- Theme Toggle -->
                        <button id="themeToggle" class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors">
                            <!-- Sun icon (visible in dark mode) -->
                            <svg id="sunIcon" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                            </svg>
                            <!-- Moon icon (visible in light mode) -->
                            <svg id="moonIcon" class="w-6 h-6 hidden" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                            </svg>
                        </button>

                        <!-- Notifications -->
                        <div class="relative" id="notificationDropdown">
                            <button id="notificationButton" class="relative p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                                </svg>
                                <span id="notificationBadge" class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400 hidden"></span>
                                <span id="notificationCount" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
                            </button>
                            
                            <!-- Notification Dropdown -->
                            <div id="notificationDropdownMenu" class="absolute right-0 mt-2 w-80 bg-gray-800 border border-gray-700 rounded-lg shadow-lg z-50 opacity-0 invisible transform scale-95 transition-all duration-200">
                                <div class="p-4 border-b border-gray-700">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg font-semibold text-white">Notifications</h3>
                                        <a href="{{ route('user.notifications.index') }}" class="text-sm text-blue-400 hover:text-blue-300">View All</a>
                                    </div>
                                </div>
                                
                                <div id="notificationList" class="max-h-96 overflow-y-auto">
                                    <!-- Notifications will be loaded here -->
                                    <div class="p-4 text-center text-gray-400">
                                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500 mx-auto"></div>
                                        <p class="mt-2">Loading notifications...</p>
                                    </div>
                                </div>
                                
                                <div class="p-3 border-t border-gray-700">
                                    <div class="flex justify-between">
                                        <button id="markAllReadBtn" class="text-sm text-blue-400 hover:text-blue-300">Mark all as read</button>
                                        <button id="clearAllBtn" class="text-sm text-red-400 hover:text-red-300">Clear all</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- User Dropdown -->
                        <div class="relative group" id="userDropdown">
                            <button class="flex items-center space-x-1 sm:space-x-2 p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors">
                                <div class="w-7 h-7 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <svg class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div class="absolute right-0 mt-2 w-48 bg-gray-800 border border-gray-700 rounded-lg shadow-lg z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transform scale-95 group-hover:scale-100 transition-all duration-200">
                                <div class="py-1">
                                    <a href="{{ route('user.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                                        <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                        Profile
                                    </a>
                                    <a href="{{ route('user.support.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                                        <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-2 0c0 .993-.241 1.929-.668 2.754l-1.524-1.525a3.997 3.997 0 00.078-2.183l1.562-1.562C15.759 8.071 16 9.007 16 10zm-5.165 3.913l1.58 1.58A5.98 5.98 0 0110 16a5.976 5.976 0 01-2.516-.552l1.562-1.562a4.006 4.006 0 001.789.027zm-4.677-2.796a3.998 3.998 0 00-.027-1.789l-1.562 1.562C4.241 8.071 4 9.007 4 10c0 .993.241 1.929.668 2.754l1.524-1.525zm11.33-4.753l-1.562 1.562a4.006 4.006 0 00-1.789-.027l-1.58-1.58a5.98 5.98 0 012.516-.552 5.976 5.976 0 012.415.597zM4.668 7.246l1.525 1.525a3.997 3.997 0 00-.078 2.183l-1.562 1.562C4.241 11.929 4 10.993 4 10c0-.993.241-1.929.668-2.754z" clip-rule="evenodd"></path>
                                        </svg>
                                        Support
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                                            <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
      </div>
  </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-900">
                <div class="container mx-auto px-6 py-8">
                    @if(session('success'))
                        <div id="alert-success" class="mb-3 rounded-md border border-green-500 bg-green-600 text-white px-4 py-3">
                            <div class="font-medium">{{ session('success') }}</div>
                        </div>
                        <script>console.log('[Flash] success:', @json(session('success')));</script>
                    @endif

                    @if(session('error'))
                        <div id="alert-error" class="mb-3 rounded-md border border-red-500 bg-red-600 text-white px-4 py-3">
                            <div class="font-medium">{{ session('error') }}</div>
                        </div>
                        <script>console.log('[Flash] error:', @json(session('error')));</script>
                    @endif

                    @if(session('warning'))
                        <div id="alert-warning" class="mb-3 rounded-md border border-yellow-500 bg-yellow-600 text-white px-4 py-3">
                            <div class="font-medium">{{ session('warning') }}</div>
                        </div>
                        <script>console.log('[Flash] warning:', @json(session('warning')));</script>
                    @endif

                    @if ($errors->any())
                        <div id="alert-validation" class="mb-3 rounded-md border border-yellow-500 bg-yellow-600 text-white px-4 py-3">
                            <div class="font-semibold mb-1">Please fix the following:</div>
                            <ul class="list-disc list-inside space-y-0.5">
                                @foreach ($errors->all() as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <script>console.log('[Flash] validation errors:', @json($errors->all()));</script>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>


@livewireScripts
    
    <!-- User Dropdown Script -->
    
    <!-- Theme Toggle Script -->
    <script>
        // Theme management
        class ThemeManager {
            constructor() {
                this.themeToggle = document.getElementById('themeToggle');
                this.sunIcon = document.getElementById('sunIcon');
                this.moonIcon = document.getElementById('moonIcon');
                this.body = document.body;
                
                this.init();
            }
            
            init() {
                // Get saved theme or default to dark
                const savedTheme = localStorage.getItem('theme') || 'dark';
                this.setTheme(savedTheme);
                
                // Add click event listener
                this.themeToggle.addEventListener('click', () => {
                    this.toggleTheme();
                });
            }
            
            setTheme(theme) {
                if (theme === 'light') {
                    this.body.classList.remove('dark');
                    this.body.classList.add('light-theme');
                    this.sunIcon.classList.add('hidden');
                    this.moonIcon.classList.remove('hidden');
                    
                    // Force update the document element
                    document.documentElement.classList.remove('dark');
                    document.documentElement.classList.add('light-theme');
                } else {
                    this.body.classList.remove('light-theme');
                    this.body.classList.add('dark');
                    this.sunIcon.classList.remove('hidden');
                    this.moonIcon.classList.add('hidden');
                    
                    // Force update the document element
                    document.documentElement.classList.remove('light-theme');
                    document.documentElement.classList.add('dark');
                }
                
                // Save theme preference
                localStorage.setItem('theme', theme);
                
                // Debug log
                console.log('Theme set to:', theme);
                console.log('Body classes:', this.body.className);
            }
            
            toggleTheme() {
                const currentTheme = localStorage.getItem('theme') || 'dark';
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                this.setTheme(newTheme);
            }
        }
        
        // Initialize theme manager when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            new ThemeManager();
        });
    </script>
    
    @stack('scripts')

<script>
        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarClose = document.getElementById('sidebarClose');
            const sidebarBackdrop = document.getElementById('sidebarBackdrop');
            // Always start with sidebar hidden on page load
            sidebar.classList.add('-translate-x-full');
            sidebarBackdrop.classList.add('hidden');
            
            // Clear any existing localStorage to ensure clean state
            localStorage.removeItem('sidebarOpen');
            
            // Toggle sidebar
            sidebarToggle.addEventListener('click', function() {
                const isCurrentlyOpen = !sidebar.classList.contains('-translate-x-full');
                
                if (isCurrentlyOpen) {
                    // Close sidebar
                    sidebar.classList.add('-translate-x-full');
                    sidebarBackdrop.classList.add('hidden');
                    localStorage.setItem('sidebarOpen', 'false');
                } else {
                    // Open sidebar
                    sidebar.classList.remove('-translate-x-full');
                    sidebarBackdrop.classList.remove('hidden');
                    localStorage.setItem('sidebarOpen', 'true');
                }
            });
            
            // Close sidebar when clicking backdrop
            sidebarBackdrop.addEventListener('click', function() {
                sidebar.classList.add('-translate-x-full');
                sidebarBackdrop.classList.add('hidden');
                localStorage.setItem('sidebarOpen', 'false');
            });
            
            // Close sidebar when clicking close button
            sidebarClose.addEventListener('click', function() {
                sidebar.classList.add('-translate-x-full');
                sidebarBackdrop.classList.add('hidden');
                localStorage.setItem('sidebarOpen', 'false');
            });
            
            // Close sidebar when clicking outside
            document.addEventListener('click', function(event) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnToggle = sidebarToggle.contains(event.target);
                const isClickOnBackdrop = sidebarBackdrop.contains(event.target);
                
                if (!isClickInsideSidebar && !isClickOnToggle && !isClickOnBackdrop && !sidebar.classList.contains('-translate-x-full')) {
                    // Close sidebar on any device when clicking outside
                    sidebar.classList.add('-translate-x-full');
                    sidebarBackdrop.classList.add('hidden');
                    localStorage.setItem('sidebarOpen', 'false');
                }
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                // Always show backdrop when sidebar is open (both mobile and desktop)
                if (!sidebar.classList.contains('-translate-x-full')) {
                    sidebarBackdrop.classList.remove('hidden');
                }
            });

            // Plans dropdown functionality
            const plansDropdown = document.getElementById('plansDropdown');
            const plansDropdownMenu = document.getElementById('plansDropdownMenu');
            const plansDropdownArrow = plansDropdown.querySelector('svg:last-child');

            if (plansDropdown && plansDropdownMenu) {
                plansDropdown.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const isOpen = plansDropdownMenu.classList.contains('opacity-100');
                    
                    if (isOpen) {
                        // Close dropdown
                        plansDropdownMenu.classList.remove('opacity-100', 'visible', 'translate-y-0');
                        plansDropdownMenu.classList.add('opacity-0', 'invisible', '-translate-y-2');
                        plansDropdownArrow.style.transform = 'rotate(0deg)';
                    } else {
                        // Open dropdown
                        plansDropdownMenu.classList.remove('opacity-0', 'invisible', '-translate-y-2');
                        plansDropdownMenu.classList.add('opacity-100', 'visible', 'translate-y-0');
                        plansDropdownArrow.style.transform = 'rotate(180deg)';
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!plansDropdown.contains(e.target) && !plansDropdownMenu.contains(e.target)) {
                        plansDropdownMenu.classList.remove('opacity-100', 'visible', 'translate-y-0');
                        plansDropdownMenu.classList.add('opacity-0', 'invisible', '-translate-y-2');
                        plansDropdownArrow.style.transform = 'rotate(0deg)';
                    }
                });

                // Close dropdown when sidebar closes
                sidebarToggle.addEventListener('click', function() {
                    plansDropdownMenu.classList.remove('opacity-100', 'visible', 'translate-y-0');
                    plansDropdownMenu.classList.add('opacity-0', 'invisible', '-translate-y-2');
                    plansDropdownArrow.style.transform = 'rotate(0deg)';
                });
            }

            // User dropdown functionality
            const userDropdownButton = document.getElementById('userDropdownButton');
            const userDropdownMenu = document.getElementById('userDropdownMenu');
            const userDropdownArrow = document.getElementById('userDropdownArrow');


            if (userDropdownButton && userDropdownMenu && userDropdownArrow) {
                userDropdownButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const isOpen = userDropdownMenu.classList.contains('opacity-100');
                    
                    if (isOpen) {
                        // Close dropdown
                        userDropdownMenu.classList.remove('opacity-100', 'visible', 'scale-100');
                        userDropdownMenu.classList.add('opacity-0', 'invisible', 'scale-95');
                        userDropdownArrow.style.transform = 'rotate(0deg)';
                    } else {
                        // Open dropdown
                        userDropdownMenu.classList.remove('opacity-0', 'invisible', 'scale-95');
                        userDropdownMenu.classList.add('opacity-100', 'visible', 'scale-100');
                        userDropdownArrow.style.transform = 'rotate(180deg)';
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!userDropdownButton.contains(e.target) && !userDropdownMenu.contains(e.target)) {
                        userDropdownMenu.classList.remove('opacity-100', 'visible', 'scale-100');
                        userDropdownMenu.classList.add('opacity-0', 'invisible', 'scale-95');
                        userDropdownArrow.style.transform = 'rotate(0deg)';
                    }
                });

                // Close dropdown when pressing Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        userDropdownMenu.classList.remove('opacity-100', 'visible', 'scale-100');
                        userDropdownMenu.classList.add('opacity-0', 'invisible', 'scale-95');
                        userDropdownArrow.style.transform = 'rotate(0deg)';
                    }
                });
            }
        });

        // Notification dropdown functionality
        const notificationDropdown = document.getElementById('notificationDropdown');
        const notificationButton = document.getElementById('notificationButton');
        const notificationDropdownMenu = document.getElementById('notificationDropdownMenu');
        const notificationList = document.getElementById('notificationList');
        const notificationBadge = document.getElementById('notificationBadge');
        const notificationCount = document.getElementById('notificationCount');



        if (notificationDropdown && notificationButton && notificationDropdownMenu) {
            let isOpen = false;

            // Toggle notification dropdown
            notificationButton.addEventListener('click', function(e) {
                e.stopPropagation();
                isOpen = !isOpen;
                
                if (isOpen) {
                    notificationDropdownMenu.classList.remove('opacity-0', 'invisible', 'scale-95');
                    notificationDropdownMenu.classList.add('opacity-100', 'visible', 'scale-100');
                    loadNotifications();
                } else {
                    notificationDropdownMenu.classList.remove('opacity-100', 'visible', 'scale-100');
                    notificationDropdownMenu.classList.add('opacity-0', 'invisible', 'scale-95');
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!notificationDropdown.contains(e.target)) {
                    isOpen = false;
                    notificationDropdownMenu.classList.remove('opacity-100', 'visible', 'scale-100');
                    notificationDropdownMenu.classList.add('opacity-0', 'invisible', 'scale-95');
                }
            });

            // Load notifications
            function loadNotifications() {
                fetch('/user/notifications/recent')
                    .then(response => response.json())
                    .then(data => {
                        if (data.notifications && data.notifications.length > 0) {
                            notificationList.innerHTML = data.notifications.map(notification => `
                                <div class="p-3 border-b border-gray-700 hover:bg-gray-700 transition-colors">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center ${getNotificationIconClass(notification.type)}">
                                                ${getNotificationIcon(notification.type)}
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-white truncate">${notification.title}</p>
                                            <p class="text-xs text-gray-400 mt-1">${notification.message}</p>
                                            <p class="text-xs text-gray-500 mt-1">${formatTime(notification.created_at)}</p>
                                        </div>
                                        ${!notification.read_at ? '<div class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 mt-2"></div>' : ''}
                                    </div>
                                </div>
                            `).join('');
                        } else {
                            notificationList.innerHTML = `
                                <div class="p-4 text-center text-gray-400">
                                    <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6z"></path>
                                    </svg>
                                    <p>No notifications</p>
                                </div>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Error loading notifications:', error);
                        notificationList.innerHTML = `
                            <div class="p-4 text-center text-red-400">
                                <p>Error loading notifications</p>
                            </div>
                        `;
                    });
            }

            // Load unread count
            function loadUnreadCount() {
                fetch('/user/notifications/unread-count')
                    .then(response => response.json())
                    .then(data => {
                        if (data.count > 0) {
                            notificationCount.textContent = data.count > 99 ? '99+' : data.count;
                            notificationCount.classList.remove('hidden');
                            notificationBadge.classList.remove('hidden');
                        } else {
                            notificationCount.classList.add('hidden');
                            notificationBadge.classList.add('hidden');
                        }
                    })
                    .catch(error => console.error('Error loading unread count:', error));
            }

            // Mark all as read
            document.getElementById('markAllReadBtn')?.addEventListener('click', function() {
                fetch('/user/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadNotifications();
                        loadUnreadCount();
                    }
                })
                .catch(error => console.error('Error:', error));
            });

            // Clear all notifications
            document.getElementById('clearAllBtn')?.addEventListener('click', function() {
                if (confirm('Are you sure you want to clear all notifications?')) {
                    fetch('/user/notifications/clear-all', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            loadNotifications();
                            loadUnreadCount();
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });

            // Helper functions
            function getNotificationIconClass(type) {
                const classes = {
                    'deposit': 'bg-green-600',
                    'deposit_submitted': 'bg-green-600',
                    'deposit_approved': 'bg-green-600',
                    'withdrawal': 'bg-red-600',
                    'withdrawal_submitted': 'bg-red-600',
                    'withdrawal_approved': 'bg-red-600',
                    'trading': 'bg-blue-600',
                    'copy_trade': 'bg-purple-600',
                    'copy_trade_started': 'bg-purple-600',
                    'copy_trade_stopped': 'bg-red-600',
                    'bot_trade': 'bg-yellow-600',
                    'bot_trade_executed': 'bg-yellow-600',
                    'bot_created': 'bg-green-600',
                    'bot_started': 'bg-green-600',
                    'bot_paused': 'bg-yellow-600',
                    'bot_resumed': 'bg-green-600',
                    'bot_stopped': 'bg-red-600',
                    'system': 'bg-gray-600',
                    'security': 'bg-orange-600'
                };
                return classes[type] || 'bg-gray-600';
            }

            function getNotificationIcon(type) {
                const icons = {
                    'deposit': '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>',
                    'deposit_submitted': '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>',
                    'deposit_approved': '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                    'withdrawal': '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m16 0l-4-4m4 4l-4 4"></path></svg>',
                    'withdrawal_submitted': '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m16 0l-4-4m4 4l-4 4"></path></svg>',
                    'withdrawal_approved': '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                    'trading': '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>',
                    'copy_trade': '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>',
                    'copy_trade_started': '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>',
                    'copy_trade_stopped': '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path></svg>',
                    'bot_trade': '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>',
                    'bot_trade_executed': '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>',
                    'bot_created': '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>',
                    'bot_started': '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                    'bot_paused': '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                    'bot_resumed': '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                    'bot_stopped': '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>',
                    'system': '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>',
                    'security': '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>'
                };
                return icons[type] || '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6z"></path></svg>';
            }

            function formatTime(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const diffInSeconds = Math.floor((now - date) / 1000);
                
                if (diffInSeconds < 60) return 'Just now';
                if (diffInSeconds < 3600) return Math.floor(diffInSeconds / 60) + 'm ago';
                if (diffInSeconds < 86400) return Math.floor(diffInSeconds / 3600) + 'h ago';
                return Math.floor(diffInSeconds / 86400) + 'd ago';
            }

            // Load initial data
            loadUnreadCount();
            
            // Refresh unread count every 30 seconds
            setInterval(loadUnreadCount, 30000);
        }

@yield('scripts')
</script>


<script src="{{ asset('front/livewire/livewire5dd3.js') }}"   data-csrf="QHTgDfeSDEhGixs61ktyfaAnqYfyNU0Xv8qcvRbs" data-update-uri="/livewire/update" data-navigate-once="true"></script>

{{-- PWA Service Worker Registration --}}
<script src="{{ asset('js/pwa-sw-register.js') }}"></script>

{{-- PWA Install Prompt --}}
<script src="{{ asset('js/pwa-install-prompt.js') }}"></script>

{{-- Snow Effect (for non-React pages) --}}
<script src="{{ asset('js/snow-effect.js') }}"></script>

</body>
</html>
