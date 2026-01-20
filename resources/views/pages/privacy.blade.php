@extends('pages.new-layout')

@php
    $appHost = parse_url(config('app.url'), PHP_URL_HOST) ?? request()->getHost();
@endphp

@section('content')
<section class="space-y-16">
    @include('pages.partials.hero', [
        'eyebrow' => 'Privacy notice',
        'title' => 'Your data, always under your control.',
        'description' => config('app.name') . ' only collects the information required to operate regulated investment services. We never sell customer data or monetize it via ads.',
    ])

    @php
        $sections = [
            ['title' => 'Information we collect', 'content' => 'Identity verification data (name, address, national ID), transaction records, device info, and cookies are collected to meet regulatory requirements and deliver secure experiences.'],
            ['title' => 'How we use data', 'content' => 'We use your data to provide platform services, comply with anti-money laundering rules, detect fraud, improve our products, and communicate account updates.'],
            ['title' => 'Sharing & retention', 'content' => 'Data is shared only with trusted partners such as custodians, auditors, and regulators when required. We retain data for the legally mandated period and then delete or anonymize it.'],
            ['title' => 'Your rights', 'content' => 'You can request copies of your data, ask for corrections, restrict processing, or close your account at any time. Email privacy@' . $appHost . ' for help.'],
        ];
    @endphp

    <div class="space-y-6">
        @foreach($sections as $section)
            <div class="rounded-[32px] border border-[#ede8ff] bg-white p-6 space-y-3">
                <p class="text-xs uppercase tracking-[0.4em] text-[#8f7dfd]">{{ $section['title'] }}</p>
                <p class="text-sm text-[#6b628d]">{{ $section['content'] }}</p>
            </div>
        @endforeach
    </div>
</section>
@endsection
