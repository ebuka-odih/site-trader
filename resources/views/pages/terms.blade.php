@extends('pages.new-layout')

@section('content')
<section class="space-y-16">
    @include('pages.partials.hero', [
        'eyebrow' => 'Terms of service',
        'title' => 'Understand the agreement between you and ' . config('app.name') . '.',
        'description' => 'These terms describe the rights and responsibilities tied to using our investment platform. We keep them readable and update them whenever new functionality launches.',
    ])

    @php
        $clauses = [
            ['title' => 'Account eligibility', 'body' => 'You must be at least 18 years old, pass identity checks, and reside in a supported jurisdiction to open an account. Business accounts require additional documentation.'],
            ['title' => 'Use of services', 'body' => 'You agree to use the platform for lawful investment activity, keep login credentials secure, and promptly notify us of suspicious activity.'],
            ['title' => 'Fees', 'body' => 'Platform fees are published transparently within the app. We reserve the right to update pricing with 30 days notice.'],
            ['title' => 'Risk disclosures', 'body' => 'Investing involves risk, including loss of principal. Past performance does not guarantee future results. Savings yields can change based on market conditions.'],
            ['title' => 'Termination', 'body' => 'You may close your account any time. We may suspend or terminate service for misuse, regulatory requests, or security concerns.'],
        ];
    @endphp

    <div class="space-y-6">
        @foreach($clauses as $clause)
            <div class="rounded-[32px] border border-[#ede8ff] bg-white p-6">
                <p class="text-xs uppercase tracking-[0.4em] text-[#8f7dfd]">{{ $clause['title'] }}</p>
                <p class="mt-2 text-sm text-[#6b628d]">{{ $clause['body'] }}</p>
            </div>
        @endforeach
    </div>
</section>
@endsection
