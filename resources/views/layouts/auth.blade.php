<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ \App\Helpers\WebsiteSettingsHelper::getSiteName() }} — @yield('title', 'Account')</title>
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
    
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
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
<body class="min-h-screen bg-black text-white flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md space-y-8">
        <div class="text-center space-y-3">
            <div class="tooltip-container inline-flex h-16 w-16 items-center justify-center rounded-2xl border border-[#1fff9c]/40 bg-[#041207] text-[#1fff9c] cursor-pointer">
                <!-- Modern Christmas Tree Icon -->
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
            <div>
                <p class="text-xs tracking-[0.3em] text-[#08f58d] uppercase">Smart Trader</p>
                <h1 class="text-2xl font-semibold">{{ config('app.name') }}</h1>
            </div>
            @if(View::hasSection('subtitle'))
                <p class="text-sm text-gray-400">@yield('subtitle')</p>
            @endif
        </div>

        <div class="rounded-[28px] border border-[#111] bg-[#050505] p-6 shadow-[0_30px_120px_rgba(0,0,0,0.45)]">
            @yield('content')
        </div>

        @hasSection('footer')
            <div class="text-center text-sm text-gray-500">
                @yield('footer')
            </div>
        @endif
    </div>

    @stack('scripts')
    
    {{-- PWA Service Worker Registration --}}
    <script src="{{ asset('js/pwa-sw-register.js') }}"></script>
    
    {{-- PWA Install Prompt --}}
    <script src="{{ asset('js/pwa-install-prompt.js') }}"></script>
    
    {{-- Snow Effect --}}
    <script src="{{ asset('js/snow-effect.js') }}"></script>
</body>
</html>
