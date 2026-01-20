@extends('pages.new-layout')

@section('content')
<section class="space-y-16">
    @include('pages.partials.hero', [
        'eyebrow' => 'Products',
        'title' => 'One modern stack for saving, trading, and automating.',
        'description' => config('app.name') . ' bundles brokerage, high-yield savings, and automation into a single intuitive app so you no longer juggle accounts.',
        'cta' => ['label' => 'Browse assets', 'href' => route('market')],
        'secondaryCta' => ['label' => 'See pricing', 'href' => '#pricing']
    ])

    @php
        $products = [
            ['label' => 'Global stocks', 'headline' => 'US, EU & UK markets', 'copy' => 'Trade thousands of securities with fractionals, instant settlement, and smart order routing.'],
            ['label' => 'Automations', 'headline' => 'Recurring plays & alerts', 'copy' => 'Set guardrails, trigger rebalances, and mirror strategies without writing code.'],
            ['label' => 'Cash', 'headline' => 'Savings & money market funds', 'copy' => 'Earn competitive APY on idle balances with daily liquidity.'],
        ];
    @endphp

    <div class="grid gap-6 md:grid-cols-3">
        @foreach($products as $product)
            <div class="rounded-[32px] border border-[#ede8ff] bg-white p-6">
                <p class="text-xs uppercase tracking-[0.4em] text-[#8f7dfd]">{{ $product['label'] }}</p>
                <h3 class="mt-3 text-xl font-semibold text-[#140a33]">{{ $product['headline'] }}</h3>
                <p class="mt-2 text-sm text-[#6b628d]">{{ $product['copy'] }}</p>
            </div>
        @endforeach
    </div>

    <div id="pricing" class="rounded-[40px] border border-[#f0edff] bg-[#f7f4ff] p-10 space-y-8">
        <div class="text-center space-y-3">
            <p class="text-xs uppercase tracking-[0.3em] text-[#05c46b]">Simple pricing</p>
            <h3 class="text-3xl font-semibold text-[#140a33]">One flat platform fee.</h3>
            <p class="text-[#6b628d]">No tiers, no surprises. Pay 0.25% annually across invested assets and get everything {{ config('app.name') }} offers.</p>
        </div>
        <div class="grid gap-6 md:grid-cols-3 text-sm text-[#6b628d]">
            <div class="rounded-3xl bg-white p-5">
                <p class="font-semibold text-[#35276e]">Trading</p>
                <p class="mt-2">- Zero custody fees <br>- FX at interbank rates <br>- Priority execution</p>
            </div>
            <div class="rounded-3xl bg-white p-5">
                <p class="font-semibold text-[#35276e]">Savings</p>
                <p class="mt-2">- Earn daily <br>- Withdraw anytime <br>- Covered by EU protections</p>
            </div>
            <div class="rounded-3xl bg-white p-5">
                <p class="font-semibold text-[#35276e]">Automation</p>
                <p class="mt-2">- Unlimited playbooks <br>- AI allocations <br>- Smart alerts</p>
            </div>
        </div>
    </div>
</section>
@endsection
