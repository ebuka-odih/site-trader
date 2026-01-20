<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} — Invest Smarter</title>
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Space Grotesk', system-ui, sans-serif;
            background-color: #fcfbff;
            color: #140a33;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 30px 60px rgba(20, 10, 51, 0.08);
            border-radius: 1.5rem;
        }
        .hero-gradient {
            background: radial-gradient(circle at top, rgba(111, 66, 255, 0.15), transparent 55%),
                        radial-gradient(circle at bottom, rgba(95, 54, 255, 0.2), transparent 60%);
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="relative min-h-screen hero-gradient">
        <header class="sticky top-0 z-30 border-b border-[#f0edff] bg-white/90 backdrop-blur">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                <div class="flex items-center gap-10">
                    <a href="{{ route('index') }}" class="text-2xl font-semibold tracking-tight text-[#170041]">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>
                <nav class="hidden items-center gap-6 text-sm text-[#35276e] md:flex">
                    <div class="group relative">
                        <button class="flex items-center gap-1 font-medium hover:text-[#120732] py-2">
                            What we offer
                            <svg class="h-3 w-3 text-[#5c28ff]" fill="none" viewBox="0 0 10 6">
                                <path d="M9 1L5 5 1 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <div class="invisible absolute left-0 top-full pt-2 w-48 opacity-0 transition-all duration-200 group-hover:visible group-hover:opacity-100">
                            <div class="rounded-2xl border border-[#f0edff] bg-white py-3 shadow-xl">
                                <a href="{{ route('stocks') }}" class="block px-4 py-2 text-sm text-[#35276e] hover:bg-[#f7f4ff] transition-colors">Stocks & ETFs</a>
                                <a href="{{ route('money-market-funds') }}" class="block px-4 py-2 text-sm text-[#35276e] hover:bg-[#f7f4ff] transition-colors">Money Market Funds</a>
                                <a href="{{ route('savings') }}" class="block px-4 py-2 text-sm text-[#35276e] hover:bg-[#f7f4ff] transition-colors">Savings</a>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('about') }}" class="text-[#35276e] hover:text-[#120732]">About</a>
                    <div class="group relative">
                        <button class="flex items-center gap-1 text-[#35276e] hover:text-[#120732] py-2">
                            More
                            <svg class="h-3 w-3 text-[#5c28ff]" fill="none" viewBox="0 0 10 6">
                                <path d="M9 1L5 5 1 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <div class="invisible absolute left-0 top-full pt-2 w-40 opacity-0 transition-all duration-200 group-hover:visible group-hover:opacity-100">
                            <div class="rounded-2xl border border-[#f0edff] bg-white py-3 shadow-xl">
                                <a href="{{ route('faq') }}" class="block px-4 py-2 text-sm text-[#35276e] hover:bg-[#f7f4ff] transition-colors">Support</a>
                                <a href="{{ route('contact') }}" class="block px-4 py-2 text-sm text-[#35276e] hover:bg-[#f7f4ff] transition-colors">Contact</a>
                                <a href="{{ route('products') }}" class="block px-4 py-2 text-sm text-[#35276e] hover:bg-[#f7f4ff] transition-colors">Products</a>
                            </div>
                        </div>
                    </div>
                </nav>
                <div class="hidden items-center gap-3 md:flex">
                    <a href="{{ route('login') }}" class="rounded-full px-5 py-2 text-sm font-medium text-[#35276e] hover:text-[#120732]">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="rounded-full bg-[#5c28ff] px-6 py-2 text-sm font-semibold text-white hover:bg-[#4e1fff] transition-colors shadow-lg shadow-[#5c28ff]/30">
                        Sign up
                    </a>
                </div>
                <button id="mobile-nav-toggle" class="md:hidden rounded-full border border-[#e4dcff] p-2 text-[#35276e]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
                    </svg>
                </button>
            </div>
        </header>

        <div id="mobile-nav-overlay" class="fixed inset-0 z-40 bg-black/30 opacity-0 transition pointer-events-none md:hidden"></div>
        <nav id="mobile-nav-panel" class="fixed inset-y-0 right-0 z-50 w-72 translate-x-full bg-white px-6 py-8 shadow-2xl transition md:hidden">
            <div class="flex items-center justify-between">
                <span class="text-lg font-semibold text-[#170041]">Menu</span>
                <button id="mobile-nav-close" class="rounded-full border border-[#e4dcff] p-2 text-[#35276e]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mt-8 space-y-5 text-sm text-[#35276e]">
                <div class="space-y-2">
                    <p class="text-xs uppercase tracking-[0.3em] text-[#6b628d]">Accounts</p>
                    <a href="#" class="block font-semibold">Personal</a>
                    <a href="#" class="block text-[#6b628d]">Business</a>
                </div>
                <div class="space-y-3">
                    <button class="w-full rounded-2xl border border-[#f0edff] px-4 py-2 text-left font-medium text-[#35276e]">What we offer</button>
                    <div class="space-y-2 pl-4 text-[#6b628d]">
                        <a href="{{ route('stocks') }}" class="block">Stocks & ETFs</a>
                        <a href="{{ route('money-market-funds') }}" class="block">Money Market Funds</a>
                        <a href="{{ route('savings') }}" class="block">Savings</a>
                    </div>
                </div>
                <a href="#" class="block">About</a>
                <div class="space-y-2">
                    <p class="text-xs uppercase tracking-[0.3em] text-[#6b628d]">More</p>
                    <a href="{{ route('faq') }}" class="block">Support</a>
                    <a href="{{ route('contact') }}" class="block">Contact</a>
                    <a href="{{ route('products') }}" class="block">Products</a>
                </div>
            </div>
            <div class="mt-8 space-y-3">
                <a href="{{ route('login') }}" class="block rounded-full bg-[#f7f4ff] px-5 py-2 text-center text-sm font-semibold text-[#4e3ab8]">Log in</a>
                <a href="{{ route('register') }}" class="block rounded-full bg-[#05c46b] px-5 py-2 text-center text-sm font-semibold text-black">Sign up</a>
            </div>
        </nav>

        <main class="mx-auto max-w-6xl px-6 py-12 md:py-16">
            @yield('content')
        </main>
    </div>

    <footer class="border-t border-[#f0edff] bg-white py-10">
        <div class="mx-auto flex flex-col items-center gap-6 text-center text-sm text-[#6b628d] md:flex-row md:justify-between md:text-left max-w-6xl px-6">
            <p>© {{ now()->year }} {{ config('app.name') }}. All rights reserved.</p>
            <div class="flex gap-6">
                <a href="{{ route('privacy') }}">Privacy</a>
                <a href="{{ route('terms') }}">Terms</a>
                <a href="{{ route('contact') }}">Contact</a>
            </div>
        </div>
    </footer>

    <script>
        (function(){
            const toggleBtn = document.getElementById('mobile-nav-toggle');
            const closeBtn = document.getElementById('mobile-nav-close');
            const panel = document.getElementById('mobile-nav-panel');
            const overlay = document.getElementById('mobile-nav-overlay');
            const openNav = () => {
                panel.classList.remove('translate-x-full');
                overlay.classList.remove('opacity-0','pointer-events-none');
            };
            const closeNav = () => {
                panel.classList.add('translate-x-full');
                overlay.classList.add('opacity-0','pointer-events-none');
            };
            toggleBtn?.addEventListener('click', openNav);
            closeBtn?.addEventListener('click', closeNav);
            overlay?.addEventListener('click', closeNav);
        })();
    </script>
    @stack('scripts')
</body>
</html>
