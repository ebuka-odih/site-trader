@extends('pages.new-layout')

@section('content')
<section class="space-y-16">
    @include('pages.partials.hero', [
        'eyebrow' => 'Support',
        'title' => 'Frequently asked questions',
        'description' => 'Everything you need to know about accounts, security, and pricing before you get started.',
        'cta' => ['label' => 'Contact support', 'href' => route('contact')]
    ])

    @php
        $faqs = [
            ['q' => 'Where is my money held?', 'a' => 'Client assets are custodied with regulated European banks and prime brokers. Cash in Savings is diversified across AAA-rated money market funds.'],
            ['q' => 'How fast can I withdraw?', 'a' => 'Withdrawals typically arrive same day in the EU/UK via SEPA and FPS. USD wires leave within one business day.'],
            ['q' => 'What does it cost?', 'a' => 'You pay a flat 0.25% annual platform fee which covers trading, custody, reporting, and support. No hidden spreads or tiers.'],
            ['q' => 'Is there a minimum?', 'a' => 'No minimum deposit is required. Start with spare cash, or transfer seven figures—the experience stays the same.'],
            ['q' => 'Do you offer automation?', 'a' => 'Yes, build playbooks that rebalance portfolios, auto-invest savings, or trigger alerts based on price and fundamentals.'],
        ];
    @endphp

    <div class="space-y-4">
        @foreach($faqs as $faq)
            <div class="rounded-[32px] border border-[#ede8ff] bg-white p-6">
                <p class="text-base font-semibold text-[#140a33]">{{ $faq['q'] }}</p>
                <p class="mt-2 text-sm text-[#6b628d]">{{ $faq['a'] }}</p>
            </div>
        @endforeach
    </div>
</section>
@endsection
