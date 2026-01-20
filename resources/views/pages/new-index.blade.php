@extends('pages.new-layout')

@section('content')
<section class="space-y-20">
    <div class="text-center space-y-6">
        <div class="space-y-6">
            <h1 class="text-5xl font-normal leading-tight text-[#140a33] md:text-6xl xl:text-7xl tracking-tight">
                Invest smarter. Go further.
            </h1>
            <p class="text-lg text-[#6b628d] md:text-xl max-w-3xl mx-auto font-normal">
                The multi-award winning investment platform that pays up to 1.9% APY interest. Enhanced with {{ config('app.name') }} AI.
            </p>
        </div>
        <div class="flex justify-center">
            <a href="{{ route('register') }}" class="rounded-full bg-[#5c28ff] px-10 py-3.5 text-base font-semibold text-white shadow-xl shadow-[#5c28ff]/30 hover:bg-[#4e1fff] transition-colors">
                Get started
            </a>
        </div>
    </div>

    <div class="relative mx-auto w-full max-w-6xl overflow-hidden rounded-[32px] lg:rounded-[48px] border border-white/5 bg-[#0e022a] px-4 py-10 shadow-[0_40px_120px_rgba(8,0,40,0.6)]">
        <div class="pointer-events-none absolute inset-0 opacity-80" style="background: radial-gradient(circle at 20% 20%, rgba(147, 109, 255, 0.4), transparent 45%), radial-gradient(circle at 80% 50%, rgba(5, 196, 107, 0.15), transparent 55%), linear-gradient(120deg, rgba(28, 11, 61, 0.9), rgba(8, 4, 32, 0.95));"></div>
        <div class="relative flex flex-col items-center gap-10 md:flex-row md:items-center md:justify-between">
            <div class="text-center space-y-4 text-white md:text-left">
                <p class="text-xs uppercase tracking-[0.4em] text-[#8f7dfd]">In-App Experience</p>
                <h2 class="text-4xl font-semibold leading-tight">All your investing tools in one intuitive screen.</h2>
                <p class="max-w-md text-sm text-white/70">Track performance, rebalance with a tap, and move cash instantly between accounts. {{ config('app.name') }}’s mobile app mirrors the same seamless workflow as the desktop dashboard.</p>
            </div>
            <div class="relative flex h-[520px] w-full items-center justify-center md:h-[640px]">
                <div class="absolute inset-0 rounded-full blur-[120px] bg-gradient-to-b from-[#6f4bff] via-transparent to-transparent opacity-80"></div>
                <div class="relative z-10 flex items-center justify-center">
                    <div class="relative h-[520px] w-[280px] md:h-[640px] md:w-[360px] rounded-[62px] border-[14px] border-black bg-black shadow-[0_30px_80px_rgba(0,0,0,0.8)] overflow-hidden">
                        <div class="absolute inset-0 rounded-[40px] bg-gradient-to-b from-white via-white to-[#f4f0ff] px-6 py-6 text-[#140a33] flex flex-col gap-6">
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>9:41</span>
                                <div class="flex items-center gap-1">
                                    <span class="h-2 w-2 rounded-full bg-gray-400"></span>
                                    <span class="h-2 w-4 rounded-full bg-gray-400"></span>
                                    <span class="h-2 w-2 rounded-full bg-gray-400"></span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-full bg-gradient-to-br from-[#9f7bff] to-[#5c28ff]"></div>
                                <div>
                                    <p class="text-xs text-[#6b628d]">Portfolio</p>
                                    <p class="text-2xl font-semibold text-[#4e34ff]">€100,270.82</p>
                                    <p class="text-xs text-[#1ea672] flex items-center gap-1">▲ 7.97% <span class="text-gray-500">(€7,401.67) · All time</span></p>
                                </div>
                            </div>
                            <div class="rounded-[26px] bg-[#f5f3ff] p-4">
                                <div class="h-36 w-full rounded-2xl bg-gradient-to-b from-[#f9f7ff] to-[#ede8ff] relative overflow-hidden">
                                    <svg class="absolute inset-4 h-[85%] w-[90%]" viewBox="0 0 300 120" fill="none" stroke="#7d5dfb" stroke-width="3">
                                        <path d="M0 80 C40 100, 60 20, 100 30 C140 40, 160 100, 200 70 C230 50, 260 90, 300 30" stroke-linecap="round" />
                                    </svg>
                                </div>
                                <div class="mt-4 flex items-center justify-between text-xs text-[#6b628d]">
                                    <div class="flex gap-2">
                                        <span class="rounded-full bg-white px-3 py-1 font-semibold text-[#4e34ff]">1D</span>
                                        <span class="px-2 py-1">1W</span>
                                        <span class="px-2 py-1">1M</span>
                                        <span class="px-2 py-1">YTD</span>
                                        <span class="px-2 py-1">1Y</span>
                                        <span class="px-2 py-1">MAX</span>
                                    </div>
                                    <span class="text-[#4e34ff] text-sm font-semibold">€100.27k</span>
                                </div>
                            </div>
                            <div class="space-y-3 text-sm">
                                <div class="flex items-center justify-between rounded-2xl border border-[#eee7ff] bg-white px-4 py-3">
                                    <div>
                                        <p class="text-xs text-[#6b628d]">Cash</p>
                                        <p class="text-lg font-semibold">€5,613.31</p>
                                    </div>
                                    <button class="rounded-full bg-[#4a21ef] px-4 py-1.5 text-xs font-semibold text-white">Deposit</button>
                                </div>
                                <div class="flex items-center justify-between rounded-2xl border border-[#eee7ff] bg-white px-4 py-3">
                                    <div>
                                        <p class="text-xs text-[#6b628d]">Vaults</p>
                                        <p class="text-lg font-semibold">€24,138.11</p>
                                    </div>
                                    <button class="rounded-full border border-[#4a21ef]/30 px-4 py-1.5 text-xs font-semibold text-[#4a21ef]">Add money</button>
                                </div>
                            </div>
                        </div>
                        <div class="absolute left-1/2 top-4 h-5 w-20 -translate-x-1/2 rounded-full bg-black/60"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-8 text-center text-[#140a33]">
        <p class="text-xs uppercase tracking-[0.4em] text-[#8f7dfd]">{{ config('app.name') }} Savings</p>
        <div class="space-y-3">
            <h2 class="text-4xl font-semibold">Earn high interest</h2>
            <p class="text-[#6b628d]">Put your money to work in our easy access, high yield Savings.</p>
        </div>
        <div class="text-[100px] leading-none font-semibold text-[#5c28ff]">
            1.91<span class="text-5xl align-top">%</span> <span class="text-5xl font-medium">APY</span>
        </div>
        <div class="grid gap-6 md:grid-cols-3 text-left">
            <div class="rounded-3xl border border-[#f0edff] bg-white p-6 space-y-2">
                <p class="text-sm uppercase tracking-[0.3em] text-[#5c28ff]">Follows the</p>
                <h3 class="text-lg font-semibold">European Central Bank</h3>
                <p class="text-sm text-[#6b628d]">Benchmarked against the ECB’s variable overnight interest rate, automatically calculated daily.</p>
            </div>
            <div class="rounded-3xl border border-[#f0edff] bg-white p-6 space-y-2">
                <p class="text-sm uppercase tracking-[0.3em] text-[#5c28ff]">Daily liquidity</p>
                <h3 class="text-lg font-semibold">Withdraw whenever you need</h3>
                <p class="text-sm text-[#6b628d]">Move funds instantly back into your trading balance with no lockups or penalties.</p>
            </div>
            <div class="rounded-3xl border border-[#f0edff] bg-white p-6 space-y-2">
                <p class="text-sm uppercase tracking-[0.3em] text-[#5c28ff]">No limits</p>
                <h3 class="text-lg font-semibold">No minimums</h3>
                <p class="text-sm text-[#6b628d]">Start from spare change or add millions—there’s no cap on how much you can keep in Savings.</p>
            </div>
        </div>
        <div class="flex justify-center pt-4">
            <a href="#" class="rounded-full bg-[#5c28ff] px-8 py-3 text-sm font-semibold text-white shadow-lg shadow-[#5c28ff]/40 transition hover:brightness-110">
                More about Savings
            </a>
        </div>
    </div>

    <div class="space-y-10">
        <div class="text-center space-y-3 text-[#140a33]">
            <p class="text-xs uppercase tracking-[0.4em] text-[#8f7dfd]">Now available as fractionals</p>
            <h2 class="text-4xl font-semibold">A universe of assets, made simple</h2>
            <p class="max-w-3xl mx-auto text-[#6b628d]">From stocks to funds, bonds and more, explore a vast universe of investment options. Buy whole shares or just the amount you want—fractionals make it easy.</p>
        </div>

        <div class="flex flex-wrap justify-center gap-3 text-sm font-semibold">
            @foreach (['US stocks','EU stocks','UK stocks','ETFs','Money market funds','Baltic stocks','Baltic bonds'] as $chip)
                <span class="rounded-full border border-[#ece6ff] bg-[#f4f0ff] px-4 py-1 text-[#4a21ef]">{{ $chip }}</span>
            @endforeach
        </div>

        <div class="grid gap-8 lg:grid-cols-[420px_minmax(0,1fr)] items-center">
            <div class="rounded-[40px] bg-gradient-to-br from-[#5330ff] via-[#4617c3] to-[#27105c] p-6 shadow-[0_30px_80px_rgba(52,25,104,0.4)]">
                <div class="space-y-3">
                    @foreach ([
                        ['name'=>'NVIDIA','symbol'=>'$NVDA','sector'=>'Semiconductors'],
                        ['name'=>'Apple','symbol'=>'$AAPL','sector'=>'Digital Hardware'],
                        ['name'=>'Microsoft','symbol'=>'$MSFT','sector'=>'Software & Cloud'],
                        ['name'=>'Alphabet Class A','symbol'=>'$GOOGL','sector'=>'Cloud Services'],
                        ['name'=>'Amazon','symbol'=>'$AMZN','sector'=>'Consumer Essentials']
                    ] as $stock)
                    <div class="rounded-2xl bg-white/95 px-4 py-3 text-sm flex justify-between items-center shadow-lg shadow-black/10">
                        <div>
                            <p class="font-semibold text-[#140a33]">{{ $stock['name'] }}</p>
                            <p class="text-xs text-[#6b628d]">{{ $stock['symbol'] }} · {{ $stock['sector'] }}</p>
                        </div>
                        <span class="text-xs font-semibold text-[#4a21ef]">Details</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-6 text-[#140a33]">
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-[#8f7dfd]">The world’s largest stocks</p>
                    <h3 class="mt-2 text-3xl font-semibold">4,000+ US stocks across the NYSE and Nasdaq.</h3>
                    <p class="mt-3 text-[#6b628d]">Access blue chips, growth stories, and niche sectors without hopping between platforms.</p>
                </div>
                <a href="#" class="inline-flex items-center gap-2 rounded-full bg-[#f4f0ff] px-6 py-3 text-sm font-semibold text-[#4a21ef] hover:bg-[#e6dcff] transition">
                    Explore US stocks
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>

    <div class="space-y-8">
        <div class="text-center">
            <p class="text-xs uppercase tracking-[0.3em] text-[#6b628d]">Our trusted partners</p>
        </div>
        <div class="flex flex-wrap items-center justify-center gap-8 text-[#c9c3e8]">
            <span class="text-xl font-semibold tracking-wider">ABN·AMRO</span>
            <span class="text-xl font-semibold tracking-wider">NatWest</span>
            <span class="text-xl font-semibold tracking-wider">LHV</span>
            <span class="text-xl font-semibold tracking-wider">Drive</span>
            <span class="text-xl font-semibold tracking-wider">Bolt</span>
            <span class="text-xl font-semibold tracking-wider">Wise</span>
            <span class="text-xl font-semibold tracking-wider">Robinhood</span>
        </div>
    </div>

    <div class="rounded-[40px] bg-white p-10 text-center shadow-lg shadow-[#a48dff]/20">
        <p class="text-xs font-semibold uppercase tracking-[0.4em] text-[#7c50ff]">{{ config('app.name') }} Savings</p>
        <h2 class="mt-3 text-3xl font-semibold text-[#140a33]">Earn high interest</h2>
        <p class="mt-2 text-lg text-[#6b628d]">Put your money to work in our easy access, high yield savings.</p>
        <div class="mt-8 text-6xl font-semibold text-[#5c28ff]">1.91% <span class="text-2xl align-top text-[#6b628d]">APY</span></div>
        <div class="mt-8 flex flex-col gap-6 text-sm text-[#6b628d] md:flex-row md:justify-center">
            <div>
                <p class="font-semibold text-[#35276e]">Follows the</p>
                <p>EIOPA methodology for protection</p>
            </div>
            <div>
                <p class="font-semibold text-[#35276e]">Managed by</p>
                <p>Institutional-grade custodians</p>
            </div>
            <div>
                <p class="font-semibold text-[#35276e]">No minimums</p>
                <p>Start investing with spare change</p>
            </div>
        </div>
    </div>

    <div class="rounded-[40px] bg-[#f7f4ff] p-10">
        <div class="grid gap-10 lg:grid-cols-3">
            <div class="space-y-4">
                <div class="rounded-full bg-white px-4 py-1 text-xs font-semibold text-[#5c28ff] w-max">What we offer</div>
                <h3 class="text-3xl font-semibold text-[#140a33]">Everything in one investing app.</h3>
                <p class="text-[#6b628d]">Trade thousands of U.S. and European stocks, earn on idle cash, and automate your strategy with {{ config('app.name') }} AI insights.</p>
            </div>
            <div class="space-y-4">
                <h4 class="text-lg font-semibold text-[#35276e]">Zero hidden fees</h4>
                <p class="text-sm text-[#6b628d]">Invest globally without FX markups, custody fees, or premium tiers. You always get the same transparent pricing.</p>
                <div class="rounded-2xl border border-dashed border-[#ded9ff] p-4 text-center text-xs text-[#8a7fc1]">Fee comparison chart placeholder</div>
            </div>
            <div class="space-y-4">
                <h4 class="text-lg font-semibold text-[#35276e]">Earn while you wait</h4>
                <p class="text-sm text-[#6b628d]">Idle cash automatically flows into money market funds so your dry powder works as hard as your portfolio.</p>
                <div class="rounded-2xl border border-dashed border-[#ded9ff] p-4 text-center text-xs text-[#8a7fc1]">Automation flow placeholder</div>
            </div>
        </div>
    </div>

    <div class="space-y-10">
        <div class="flex flex-col gap-4 text-center">
                <p class="text-xs uppercase tracking-[0.4em] text-[#5c28ff]">How it works</p>
            <h3 class="text-3xl font-semibold text-[#140a33]">Built for serious compounding.</h3>
            <p class="mx-auto max-w-3xl text-[#6b628d]">Track performance across multiple currencies, schedule deposits, and rely on our regulated infrastructure.</p>
        </div>
        <div class="grid gap-6 md:grid-cols-3">
            <div class="rounded-3xl border border-[#f0edff] p-6">
                <div class="text-sm font-semibold uppercase tracking-[0.4em] text-[#6b628d]">01</div>
                <h4 class="mt-4 text-xl font-semibold text-[#35276e]">Onboard in minutes</h4>
                <p class="mt-2 text-sm text-[#6b628d]">Verify your identity digitally and fund your account in EUR, GBP, or USD without leaving the app.</p>
            </div>
            <div class="rounded-3xl border border-[#f0edff] p-6">
                <div class="text-sm font-semibold uppercase tracking-[0.4em] text-[#6b628d]">02</div>
                <h4 class="mt-4 text-xl font-semibold text-[#35276e]">Invest with insights</h4>
                <p class="mt-2 text-sm text-[#6b628d]">Screen 5,000+ securities, follow curated lists, and leverage {{ config('app.name') }} AI signals to act confidently.</p>
            </div>
            <div class="rounded-3xl border border-[#f0edff] p-6">
                <div class="text-sm font-semibold uppercase tracking-[0.4em] text-[#6b628d]">03</div>
                <h4 class="mt-4 text-xl font-semibold text-[#35276e]">Grow automatically</h4>
                <p class="mt-2 text-sm text-[#6b628d]">Schedule deposits, auto-invest into slices, and keep idle balances in high-yield vehicles for steady growth.</p>
            </div>
        </div>
    </div>

        <div class="rounded-[32px] bg-[#050505] px-8 py-12 text-center text-white">
            <p class="text-sm uppercase tracking-[0.4em] text-[#7c50ff]">Ready to move your money?</p>
            <h3 class="mt-3 text-3xl font-semibold">Join investors who already trust {{ config('app.name') }}.</h3>
            <p class="mt-4 text-white/70">Open an account in minutes and put your capital to work with regulated protection and modern tooling.</p>
            <div class="mt-8 flex flex-col items-center justify-center gap-3 md:flex-row">
                <a href="{{ route('register') }}" class="rounded-full bg-[#5c28ff] px-8 py-3 text-sm font-semibold text-white shadow-lg shadow-[#5c28ff]/40 hover:bg-[#4e1fff] transition">Create free account</a>
                <a href="{{ route('products') }}" class="rounded-full border border-white/30 px-8 py-3 text-sm font-semibold text-white/90">Explore product</a>
            </div>
        </div>
</section>
@endsection
