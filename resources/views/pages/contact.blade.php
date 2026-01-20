@extends('pages.new-layout')

@php
    $appHost = parse_url(config('app.url'), PHP_URL_HOST) ?? request()->getHost();
    $supportEmail = 'support@elitealgox.com';
@endphp

@section('content')
<section class="space-y-16">
    @include('pages.partials.hero', [
        'eyebrow' => 'Contact',
        'title' => 'Talk directly with the ' . config('app.name') . ' team.',
        'description' => 'Our support specialists, product strategists, and compliance partners are spread across time zones so you always get a fast, informed response.',
        'cta' => ['label' => 'Open support ticket', 'href' => route('faq')],
    ])

    @php
        $channels = [
            ['title' => 'Client support', 'copy' => 'Priority assistance for account, funding, and security questions.', 'detail' => $supportEmail],
            ['title' => 'Help center', 'copy' => 'Browse guides and get quick answers any time.', 'detail' => 'help.elitealgox.com'],
        ];
    @endphp

    <div id="contact-options" class="grid gap-6 md:grid-cols-2">
        @foreach($channels as $channel)
            <div class="rounded-[32px] border border-[#ede8ff] bg-white p-6">
                <p class="text-xs uppercase tracking-[0.4em] text-[#8f7dfd]">{{ $channel['title'] }}</p>
                <p class="mt-3 text-sm text-[#6b628d]">{{ $channel['copy'] }}</p>
                <p class="mt-4 font-semibold text-[#35276e]">{{ $channel['detail'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="rounded-[40px] border border-[#f0edff] bg-[#f7f4ff] p-10">
        <div class="grid gap-10 lg:grid-cols-2">
            <div>
                <p class="text-xs uppercase tracking-[0.4em] text-[#05c46b]">Response times</p>
                <h3 class="mt-3 text-3xl font-semibold text-[#140a33]">Real humans, fast resolutions.</h3>
                <p class="mt-4 text-[#6b628d]">Most emails are answered in under six business hours. Live chat inside the app is staffed every weekday, and urgent security items are monitored 24/7.</p>
            </div>
            <div class="rounded-3xl bg-white p-6 shadow-sm shadow-[#a48dff]/20">
                <p class="text-xs font-semibold uppercase tracking-[0.4em] text-[#8f7dfd]">Visit our offices</p>
                <div class="mt-4 space-y-4 text-sm text-[#6b628d]">
                    <div>
                        <p class="font-semibold text-[#35276e]">London</p>
                        <p>130 Bishopsgate, EC2M 3TP</p>
                    </div>
                    <div>
                        <p class="font-semibold text-[#35276e]">Tallinn</p>
                        <p>Tornimäe 7, 10145</p>
                    </div>
                    <div>
                        <p class="font-semibold text-[#35276e]">Remote</p>
                        <p>Fully distributed client success pods.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
