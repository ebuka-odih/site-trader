@php
    $eyebrow = $eyebrow ?? null;
    $title = $title ?? '';
    $description = $description ?? '';
    $cta = $cta ?? null;
    $secondaryCta = $secondaryCta ?? null;
@endphp

<div class="rounded-[40px] border border-[#f0edff] bg-white/90 px-8 py-14 text-center shadow-[0_30px_80px_rgba(20,10,51,0.08)]">
    @if($eyebrow)
        <p class="text-xs font-semibold uppercase tracking-[0.4em] text-[#8f7dfd]">{{ $eyebrow }}</p>
    @endif
    <h1 class="mt-4 text-4xl font-semibold tracking-tight text-[#140a33] md:text-5xl">
        {!! $title !!}
    </h1>
    @if($description)
        <p class="mx-auto mt-4 max-w-3xl text-base text-[#6b628d] md:text-lg">{{ $description }}</p>
    @endif
    @if($cta || $secondaryCta)
        <div class="mt-8 flex flex-col items-center justify-center gap-3 md:flex-row">
            @if($cta)
                <a href="{{ $cta['href'] ?? '#' }}" class="inline-flex items-center justify-center rounded-full bg-[#5c28ff] px-8 py-3 text-sm font-semibold text-white shadow-lg shadow-[#5c28ff]/30 hover:bg-[#4e1fff] transition">
                    {{ $cta['label'] ?? 'Get started' }}
                </a>
            @endif
            @if($secondaryCta)
                <a href="{{ $secondaryCta['href'] ?? '#' }}" class="inline-flex items-center justify-center rounded-full border border-[#ded9ff] px-8 py-3 text-sm font-semibold text-[#4a21ef] hover:bg-[#f4f0ff] transition">
                    {{ $secondaryCta['label'] ?? 'Learn more' }}
                </a>
            @endif
        </div>
    @endif
</div>
