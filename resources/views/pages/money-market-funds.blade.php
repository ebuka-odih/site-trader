@extends('pages.new-layout')

@section('content')
<section class="space-y-16">
    @include('pages.partials.hero', [
        'eyebrow' => 'Money market funds',
        'title' => 'High-yield cash with institutional safeguards.',
        'description' => 'Channel idle balances into AAA-rated European money market funds and earn daily yield while keeping liquidity.',
        'cta' => ['label' => 'Move cash into MMFs', 'href' => route('register')]
    ])

    <div class="grid gap-6 md:grid-cols-3">
        @foreach([
            ['title' => 'AAA-rated funds', 'copy' => 'We screen issuers for credit quality and duration so you only access the safest instruments.'],
            ['title' => 'Daily liquidity', 'copy' => 'Submit redemption requests anytime and typically receive funds the same day.'],
            ['title' => 'Auto sweep', 'copy' => 'Enable automatic transfers so unused cash never sits idle in your trading balance.'],
        ] as $card)
            <div class="rounded-[32px] border border-[#ede8ff] bg-white p-6">
                <p class="text-xs uppercase tracking-[0.4em] text-[#8f7dfd]">{{ $card['title'] }}</p>
                <p class="mt-2 text-sm text-[#6b628d]">{{ $card['copy'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="rounded-[40px] border border-[#f0edff] bg-[#f7f4ff] p-10">
        <div class="grid gap-10 lg:grid-cols-2">
            <div class="space-y-4">
                <p class="text-xs uppercase tracking-[0.3em] text-[#05c46b]">Yield engine</p>
                <h3 class="text-3xl font-semibold text-[#140a33]">Transparent returns tied to ECB rates.</h3>
                <p class="text-[#6b628d]">Your APY moves in tandem with the European Central Bank’s deposit facility. We display current yields inside the app so you always know what you earn.</p>
            </div>
            <div class="rounded-3xl bg-white p-6 shadow-sm shadow-[#a48dff]/20 text-sm text-[#6b628d] space-y-3">
                <p class="uppercase tracking-[0.4em] text-[#8f7dfd] text-xs">Safeguards</p>
                <p>- Assets custodied at Tier-1 banks</p>
                <p>- Independent risk oversight</p>
                <p>- Diversified commercial paper exposure</p>
            </div>
        </div>
    </div>
</section>
@endsection
