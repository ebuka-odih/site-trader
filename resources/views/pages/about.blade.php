@extends('pages.new-layout')

@section('content')
<section class="space-y-16">
    @include('pages.partials.hero', [
        'eyebrow' => 'Inside ' . config('app.name'),
        'title' => 'We build the operating system for modern investing.',
        'description' => 'Founded by portfolio managers and product builders, ' . config('app.name') . ' blends institutional infrastructure with a consumer-grade experience so that you can grow wealth with confidence.',
        'cta' => ['label' => 'Open an account', 'href' => route('register')],
        'secondaryCta' => ['label' => 'Read our story', 'href' => '#story']
    ])

    @php
        $pillars = [
            ['title' => 'Purpose-built platform', 'copy' => 'One login for research, execution, and cash management. Every decision is informed, every action seamless.'],
            ['title' => 'Human expertise + automation', 'copy' => 'Our in-house investment committee pairs years of market experience with automated tooling for repeatable outcomes.'],
            ['title' => 'Transparent governance', 'copy' => 'Regulated entities, audited processes, and customer-first policies underpin everything we do.'],
        ];
    @endphp

    <div class="grid gap-6 md:grid-cols-3">
        @foreach($pillars as $pillar)
            <div class="rounded-[32px] border border-[#ede8ff] bg-white p-6">
                <p class="text-xs uppercase tracking-[0.4em] text-[#8f7dfd]">Pillar</p>
                <h3 class="mt-3 text-xl font-semibold text-[#140a33]">{{ $pillar['title'] }}</h3>
                <p class="mt-2 text-sm text-[#6b628d]">{{ $pillar['copy'] }}</p>
            </div>
        @endforeach
    </div>

    <div id="story" class="rounded-[40px] border border-[#f0edff] bg-[#f9f6ff] p-10">
        <div class="grid gap-10 lg:grid-cols-2">
            <div class="space-y-4">
                <p class="text-xs uppercase tracking-[0.4em] text-[#05c46b]">Origins</p>
                <h3 class="text-3xl font-semibold text-[#140a33]">Built in Europe, designed for global investors.</h3>
                <p class="text-[#6b628d]">Our founding team spent the last decade building trading desks for large banks. We took those learnings and reimagined what the experience could look like for individuals—high-touch, fast, and uncompromising on safety.</p>
            </div>
            <div class="grid gap-4">
                <div class="rounded-2xl bg-white p-5 shadow-sm shadow-[#a48dff]/15">
                    <p class="text-sm uppercase tracking-[0.4em] text-[#8f7dfd]">2019</p>
                    <p class="mt-2 text-lg font-semibold text-[#35276e]">Product research in London & Tallinn</p>
                    <p class="text-sm text-[#6b628d]">We spoke with thousands of savers and traders to blueprint the ideal platform.</p>
                </div>
                <div class="rounded-2xl bg-white p-5 shadow-sm shadow-[#a48dff]/15">
                    <p class="text-sm uppercase tracking-[0.4em] text-[#8f7dfd]">2021</p>
                    <p class="mt-2 text-lg font-semibold text-[#35276e]">Launch of {{ config('app.name') }}</p>
                    <p class="text-sm text-[#6b628d]">Savings, stocks, and automation now live inside a single beautifully designed app.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-8 md:grid-cols-3">
        <div class="rounded-[32px] border border-[#ede8ff] bg-white p-6">
            <p class="text-xs uppercase tracking-[0.4em] text-[#8f7dfd]">Leadership</p>
            <h4 class="mt-2 font-semibold text-[#140a33]">Global operators</h4>
            <p class="text-sm text-[#6b628d]">Our leadership team spans trading desks in London, Berlin, and New York, ensuring we anticipate how markets actually move.</p>
        </div>
        <div class="rounded-[32px] border border-[#ede8ff] bg-white p-6">
            <p class="text-xs uppercase tracking-[0.4em] text-[#8f7dfd]">Community</p>
            <h4 class="mt-2 font-semibold text-[#140a33]">Built with our users</h4>
            <p class="text-sm text-[#6b628d]">Quarterly roadmap sessions and research panels keep {{ config('app.name') }} anchored in real investor needs.</p>
        </div>
        <div class="rounded-[32px] border border-[#ede8ff] bg-white p-6">
            <p class="text-xs uppercase tracking-[0.4em] text-[#8f7dfd]">Impact</p>
            <h4 class="mt-2 font-semibold text-[#140a33]">Long-term mindset</h4>
            <p class="text-sm text-[#6b628d]">We reinvest in education, transparency, and financial literacy so the next generation of investors can thrive.</p>
        </div>
    </div>
</section>
@endsection
