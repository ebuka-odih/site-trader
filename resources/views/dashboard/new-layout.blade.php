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
    
    <!-- Google Fonts: Inter and JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Vite assets (uncomment when running npm run dev) -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    
    @livewireStyles
    
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
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
            
            /* Shadcn/ui style variables for React components */
            --background: 0 0% 100%;
            --foreground: 222.2 84% 4.9%;
            --card: 0 0% 100%;
            --card-foreground: 222.2 84% 4.9%;
            --primary: 142 76% 36%;
            --primary-foreground: 355.7 100% 97.3%;
            --secondary: 210 40% 96.1%;
            --secondary-foreground: 222.2 47.4% 11.2%;
            --muted: 210 40% 96.1%;
            --muted-foreground: 215.4 16.3% 46.9%;
            --border: 214.3 31.8% 91.4%;
            --ring: 142 76% 36%;
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
            
            /* Shadcn/ui style variables for dark theme */
            --background: 222.2 84% 4.9%;
            --foreground: 210 40% 98%;
            --card: 222.2 84% 4.9%;
            --card-foreground: 210 40% 98%;
            --primary: 142 76% 36%;
            --primary-foreground: 222.2 47.4% 11.2%;
            --secondary: 217.2 32.6% 17.5%;
            --secondary-foreground: 210 40% 98%;
            --muted: 217.2 32.6% 17.5%;
            --muted-foreground: 215 20.2% 65.1%;
            --border: 217.2 32.6% 17.5%;
            --ring: 142 76% 36%;
        }
        
        /* Shadcn/ui utility classes */
        .text-foreground { color: hsl(var(--foreground)); }
        .text-muted-foreground { color: hsl(var(--muted-foreground)); }
        .bg-background { background-color: hsl(var(--background)); }
        .bg-card { background-color: hsl(var(--card)); }
        .bg-primary { background-color: hsl(var(--primary)); }
        .text-primary { color: hsl(var(--primary)); }
        .border-border { border-color: hsl(var(--border)); }
        .bg-primary\/20 { background-color: hsl(var(--primary) / 0.2); }
        .border-primary\/30 { border-color: hsl(var(--primary) / 0.3); }
        .bg-primary\/10 { background-color: hsl(var(--primary) / 0.1); }
        
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
    </style>
</head>

<body class="bg-black text-white min-h-screen dark">
    <div class="h-screen bg-black flex flex-col">
        <!-- Page Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-black pb-32">
            <div class="mx-auto max-w-5xl px-2 sm:px-4 py-6 sm:py-10">
                @yield('content')
            </div>
        </main>
        
        <!-- Bottom Menu -->
        <div class="fixed bottom-0 left-0 right-0 z-50 bg-black/85 backdrop-blur border-t border-[#161616]">
            <nav class="mx-auto flex max-w-md justify-between gap-1 px-6 py-2 text-[11px] font-medium text-gray-500">
                <a href="{{ route('user.dashboard') }}" class="group flex flex-1 flex-col items-center gap-0.5 rounded-2xl px-2 py-1 {{ request()->routeIs('user.dashboard') ? 'text-white' : 'hover:text-white' }}">
                    <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-[#0c0c0c] text-gray-300 text-xs transition-colors group-hover:bg-[#1f1f1f] {{ request()->routeIs('user.dashboard') ? 'border border-[#1fff9c] text-[#1fff9c]' : '' }}">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" />
                        </svg>
                    </div>
                    <span>Home</span>
                </a>
                <a href="{{ route('user.nav.trade') }}" class="group flex flex-1 flex-col items-center gap-0.5 rounded-2xl px-2 py-1 {{ request()->routeIs('user.nav.trade') ? 'text-white' : 'hover:text-white' }}">
                    <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-[#0c0c0c] text-gray-300 text-xs transition-colors group-hover:bg-[#1f1f1f] {{ request()->routeIs('user.nav.trade') ? 'border border-[#00ff5f] text-[#00ff5f]' : '' }}">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 17l6-6 4 4 6-6" />
                        </svg>
                    </div>
                    <span>Trade</span>
                </a>
                <a href="{{ route('user.copyTrading.index') }}" class="group flex flex-1 flex-col items-center gap-0.5 rounded-2xl px-2 py-1 {{ request()->routeIs('user.copyTrading.*') ? 'text-white' : 'hover:text-white' }}">
                    <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-[#0c0c0c] text-gray-300 text-xs transition-colors group-hover:bg-[#1f1f1f] {{ request()->routeIs('user.copyTrading.*') ? 'border border-[#a855f7] text-[#a855f7]' : '' }}">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5h13M8 12h13M8 19h13M3 5h.01M3 12h.01M3 19h.01" />
                        </svg>
                    </div>
                    <span>Copy</span>
                </a>
                {{-- Bot trading entry hidden for now but kept for future enablement --}}
                {{-- <a href="{{ route('user.nav.bot-trading') }}" class="group flex flex-1 flex-col items-center gap-0.5 rounded-2xl px-2 py-1 {{ request()->routeIs('user.nav.bot-trading') ? 'text-white' : 'hover:text-white' }}">
                    <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-[#0c0c0c] text-gray-300 text-xs transition-colors group-hover:bg-[#1f1f1f] {{ request()->routeIs('user.nav.bot-trading') ? 'border border-[#14b8a6] text-[#14b8a6]' : '' }}">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a7 7 0 00-7 7v4H4a2 2 0 000 4h1a7 7 0 0014 0h1a2 2 0 000-4h-1V9a7 7 0 00-7-7zm0 0v4m-6 7h12" />
                        </svg>
                    </div>
                    <span>Bot</span>
                </a> --}}
                <a href="{{ route('user.nav.wallet') }}" class="group flex flex-1 flex-col items-center gap-0.5 rounded-2xl px-2 py-1 {{ request()->routeIs('user.nav.wallet') ? 'text-white' : 'hover:text-white' }}">
                    <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-[#0c0c0c] text-gray-300 text-xs transition-colors group-hover:bg-[#1f1f1f] {{ request()->routeIs('user.nav.wallet') ? 'border border-[#facc15] text-[#facc15]' : '' }}">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V9a2 2 0 012-2zm0 0V5a2 2 0 012-2h3" />
                        </svg>
                    </div>
                    <span>Wallet</span>
                </a>
                <a href="{{ route('user.nav.profile') }}" class="group flex flex-1 flex-col items-center gap-0.5 rounded-2xl px-2 py-1 {{ request()->routeIs('user.nav.profile') ? 'text-white' : 'hover:text-white' }}">
                    <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-[#0c0c0c] text-gray-300 text-xs transition-colors group-hover:bg-[#1f1f1f] {{ request()->routeIs('user.nav.profile') ? 'border border-[#60a5fa] text-[#60a5fa]' : '' }}">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-6 8c0-2.21 3.58-4 6-4s6 1.79 6 4" />
                        </svg>
                    </div>
                    <span>Profile</span>
                </a>
                <!-- Menu Icon with Popup -->
                <div class="relative flex flex-1 flex-col items-center gap-0.5 rounded-2xl px-2 py-1">
                    <button id="menu-toggle" class="group flex flex-col items-center gap-0.5 w-full hover:text-white transition-colors">
                        <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-[#0c0c0c] text-gray-300 text-xs transition-colors group-hover:bg-[#1f1f1f]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </div>
                        <span class="text-[11px] font-medium text-gray-500 group-hover:text-white">More</span>
                    </button>
                    <!-- Popup Menu Card -->
                    <div id="menu-popup" class="hidden absolute bottom-full right-0 mb-2 w-64 bg-[#0c0c0c] border-2 border-[#1fff9c] rounded-2xl shadow-2xl overflow-hidden z-[100]">
                        <div class="p-3 space-y-4">
                            <!-- PORTFOLIO Section -->
                            <div class="space-y-2">
                                <h3 class="text-[10px] font-bold uppercase tracking-wider text-[#1fff9c] border-b border-[#1fff9c]/30 pb-1.5">PORTFOLIO</h3>
                                <div class="space-y-1">
                                    <a href="{{ route('user.portfolio.holding') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs text-white hover:bg-[#1a1a1a] transition-colors group">
                                        <svg class="h-4 w-4 text-[#1fff9c] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        <span>Holdings</span>
                                    </a>
                                    <a href="{{ route('user.liveTrading.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs text-white hover:bg-[#1a1a1a] transition-colors group">
                                        <svg class="h-4 w-4 text-[#1fff9c] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <span>Positions</span>
                                    </a>
                                    <a href="{{ route('user.portfolio.trade') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs text-white hover:bg-[#1a1a1a] transition-colors group">
                                        <svg class="h-4 w-4 text-[#1fff9c] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span>Orders</span>
                                    </a>
                                    <a href="{{ route('user.nav.assets') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs text-white hover:bg-[#1a1a1a] transition-colors group">
                                        <svg class="h-4 w-4 text-[#1fff9c] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        <span>Markets</span>
                                    </a>
                                </div>
                            </div>

                            <!-- ACTIVITY Section -->
                            <div class="space-y-2">
                                <h3 class="text-[10px] font-bold uppercase tracking-wider text-[#1fff9c] border-b border-[#1fff9c]/30 pb-1.5">ACTIVITY</h3>
                                <div class="space-y-1">
                                    <a href="{{ route('user.nav.wallet') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs text-white hover:bg-[#1a1a1a] transition-colors group">
                                        <svg class="h-4 w-4 text-[#1fff9c] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Transactions</span>
                                    </a>
                                    <a href="{{ route('user.nav.profile') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs text-white hover:bg-[#1a1a1a] transition-colors group">
                                        <svg class="h-4 w-4 text-[#1fff9c] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                        <span>Notifications</span>
                                    </a>
                                </div>
                            </div>

                            <!-- TOOLS Section -->
                            <div class="space-y-2">
                                <h3 class="text-[10px] font-bold uppercase tracking-wider text-[#1fff9c] border-b border-[#1fff9c]/30 pb-1.5">TOOLS</h3>
                                <div class="space-y-1">
                                    <a href="{{ route('user.support.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs text-white hover:bg-[#1a1a1a] transition-colors group">
                                        <svg class="h-4 w-4 text-[#1fff9c] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Support</span>
                            </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    @livewireScripts
    
    @stack('scripts')
    
    <script>
    @yield('scripts')
    </script>
    
    <!-- Menu Popup Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const menuPopup = document.getElementById('menu-popup');
            
            if (menuToggle && menuPopup) {
                // Toggle popup on click
                menuToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    menuPopup.classList.toggle('hidden');
                });
                
                // Close popup when clicking outside
                document.addEventListener('click', function(e) {
                    if (!menuToggle.contains(e.target) && !menuPopup.contains(e.target)) {
                        menuPopup.classList.add('hidden');
                    }
                });
                
                // Close popup when clicking on menu items
                const menuItems = menuPopup.querySelectorAll('a');
                menuItems.forEach(item => {
                    item.addEventListener('click', function() {
                        menuPopup.classList.add('hidden');
                    });
                });
            }
        });
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
