@extends('pages.new-layout')

@section('content')
<section class="space-y-16">
    @include('pages.partials.hero', [
        'eyebrow' => 'Market coverage',
        'title' => 'Access the world\'s most liquid exchanges from one account.',
        'description' => 'Use ' . config('app.name') . ' to trade U.S., European, and Baltic markets with unified reporting, fast settlement, and rich data.',
        'cta' => ['label' => 'View supported assets', 'href' => route('stocks')],
    ])

    @php
        $markets = [
            ['region' => 'United States', 'items' => ['NYSE & Nasdaq equities', '4,000+ ETFs', 'Primary & secondary listings']],
            ['region' => 'Europe', 'items' => ['Frankfurt, Paris, Amsterdam', 'London Stock Exchange', 'MiFID II compliant routing']],
            ['region' => 'Baltics', 'items' => ['Tallinn, Riga, Vilnius', 'Government & corporate bonds', 'Fractional access']],
        ];
    @endphp

    <div class="grid gap-6 md:grid-cols-3">
        @foreach($markets as $market)
            <div class="rounded-[32px] border border-[#ede8ff] bg-white p-6 space-y-3">
                <p class="text-xs uppercase tracking-[0.4em] text-[#8f7dfd]">{{ $market['region'] }}</p>
                <ul class="space-y-2 text-sm text-[#6b628d] list-disc pl-5">
                    @foreach($market['items'] as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>

    <div class="rounded-[40px] border border-[#f0edff] bg-[#f7f4ff] p-10">
        <div class="grid gap-10 lg:grid-cols-2">
            <div class="space-y-4">
                <p class="text-xs uppercase tracking-[0.3em] text-[#05c46b]">Liquidity watch</p>
                <h3 class="text-3xl font-semibold text-[#140a33]">Smart routing minimizes slippage.</h3>
                <p class="text-[#6b628d]">We automatically match against the best venue at the time you submit an order. Multiple venues, no manual toggling.</p>
            </div>
            <div class="rounded-3xl bg-white p-6 shadow-sm shadow-[#a48dff]/20">
                <p class="text-xs uppercase tracking-[0.3em] text-[#8f7dfd]">Data partners</p>
                <p class="mt-3 text-sm text-[#6b628d]">Quotes and market depth are delivered via direct exchange connections and premium feeds so you see what the pros see.</p>
            </div>
        </div>
    </div>
</section>
@endsection
