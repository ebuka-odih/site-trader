@extends('pages.new-layout')

@section('content')
<section class="space-y-16">
    @include('pages.partials.hero', [
        'eyebrow' => 'Stocks & ETFs',
        'title' => 'Build a world-class equity portfolio in minutes.',
        'description' => 'Invest in U.S., European, and UK companies with fractionals, smart order routing, and real-time performance tracking.',
        'cta' => ['label' => 'Start trading', 'href' => route('register')]
    ])

    <div class="grid gap-6 md:grid-cols-2">
        <div class="rounded-[32px] border border-[#ede8ff] bg-white p-6 space-y-3">
            <p class="text-xs uppercase tracking-[0.4em] text-[#8f7dfd]">Coverage</p>
            <h3 class="text-xl font-semibold text-[#140a33]">4,000+ tickers</h3>
            <p class="text-sm text-[#6b628d]">Access NYSE, Nasdaq, and major European exchanges with deep liquidity.</p>
        </div>
        <div class="rounded-[32px] border border-[#ede8ff] bg-white p-6 space-y-3">
            <p class="text-xs uppercase tracking-[0.4em] text-[#8f7dfd]">Fractionals</p>
            <h3 class="text-xl font-semibold text-[#140a33]">Own slices of any stock</h3>
            <p class="text-sm text-[#6b628d]">Invest from €1 without waiting to accumulate full share prices.</p>
        </div>
    </div>

    <div class="rounded-[40px] border border-[#f0edff] bg-[#f7f4ff] p-10 space-y-8">
        <div class="text-center space-y-3">
            <p class="text-xs uppercase tracking-[0.3em] text-[#05c46b]">Tools</p>
            <h3 class="text-3xl font-semibold text-[#140a33]">Research, decide, execute.</h3>
            <p class="text-[#6b628d]">Screen by sector, fundamentals, ESG scores, or analyst sentiment. Set smart alerts so you never miss a move.</p>
        </div>
        <div class="grid gap-6 md:grid-cols-3 text-sm text-[#6b628d]">
            <div class="rounded-3xl bg-white p-5">
                <p class="font-semibold text-[#35276e]">Discovery lists</p>
                <p class="mt-2">Curated themes and trending tickers updated daily.</p>
            </div>
            <div class="rounded-3xl bg-white p-5">
                <p class="font-semibold text-[#35276e]">Notebook</p>
                <p class="mt-2">Capture notes, price targets, and research inside the app.</p>
            </div>
            <div class="rounded-3xl bg-white p-5">
                <p class="font-semibold text-[#35276e]">Protection</p>
                <p class="mt-2">Set stop limits and auto-rebalance against your allocation plan.</p>
            </div>
        </div>
    </div>
</section>
@endsection
