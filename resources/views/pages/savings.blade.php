@extends('pages.new-layout')

@section('content')
<section class="space-y-16">
    @include('pages.partials.hero', [
        'eyebrow' => 'Savings',
        'title' => 'Earn daily interest with flexible access.',
        'description' => 'Our Savings product mirrors the European Central Bank’s overnight rate so your cash keeps pace with policy shifts.',
        'cta' => ['label' => 'Start earning', 'href' => route('register')]
    ])

    <div class="grid gap-6 md:grid-cols-3">
        @foreach([
            ['title' => '1.91% APY (current)', 'copy' => 'Calculated and credited every day. Rates update automatically.'],
            ['title' => 'Instant transfers', 'copy' => 'Move money between Savings and Trading with one tap—no waiting period.'],
            ['title' => 'No limits', 'copy' => 'Keep emergency cash or multimillion balances, the yield applies equally.'],
        ] as $card)
            <div class="rounded-[32px] border border-[#ede8ff] bg-white p-6">
                <p class="text-xs uppercase tracking-[0.4em] text-[#8f7dfd]">{{ $card['title'] }}</p>
                <p class="mt-2 text-sm text-[#6b628d]">{{ $card['copy'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="rounded-[40px] border border-[#f0edff] bg-[#f7f4ff] p-10 space-y-8">
        <div class="text-center space-y-3">
            <p class="text-xs uppercase tracking-[0.3em] text-[#05c46b]">How it works</p>
            <h3 class="text-3xl font-semibold text-[#140a33]">Savings runs in the background.</h3>
            <p class="text-[#6b628d]">Schedule recurring top-ups, set alerts when APY changes, and see projected earnings over any timeframe.</p>
        </div>
        <div class="grid gap-6 md:grid-cols-2 text-sm text-[#6b628d]">
            <div class="rounded-3xl bg-white p-5">
                <p class="font-semibold text-[#35276e]">Automation</p>
                <p class="mt-2">Allocate a percentage of every deposit into Savings, then auto-invest when markets drop.</p>
            </div>
            <div class="rounded-3xl bg-white p-5">
                <p class="font-semibold text-[#35276e]">Protection</p>
                <p class="mt-2">Underlying funds invest in short-term European government and corporate paper with strict risk controls.</p>
            </div>
        </div>
    </div>
</section>
@endsection
