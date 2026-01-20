@extends('dashboard.new-layout')

@section('content')
@php
    $activeType = $type ?? 'stock';
    $isStock = $activeType === 'stock';
    $title = $isStock ? 'Stocks' : 'Crypto';
    $priceDecimals = $isStock ? 2 : 6;
@endphp
<div class="space-y-8 text-white">
    <div class="flex flex-col gap-4">
        <div>
            <p class="text-xs uppercase tracking-wide text-[#08f58d]">Market Watch</p>
            <h1 class="text-3xl font-semibold tracking-tight">All {{ $title }}</h1>
            <p class="text-gray-400 text-sm">Live prices pulled from Finnhub/CoinMarketCap are refreshed by the background scheduler.</p>
        </div>
        <div class="flex items-center gap-3 rounded-full border border-[#1a1a1a] bg-[#050505] p-1 w-fit">
            <a href="{{ route('user.nav.assets', ['type' => 'stock']) }}" class="asset-tab rounded-full px-4 py-1 text-xs font-semibold {{ $isStock ? 'bg-[#00ff5f] text-black' : 'text-gray-400' }}">
                Stocks
            </a>
            <a href="{{ route('user.nav.assets', ['type' => 'crypto']) }}" class="asset-tab rounded-full px-4 py-1 text-xs font-semibold {{ !$isStock ? 'bg-[#00ff5f] text-black' : 'text-gray-400' }}">
                Crypto
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('user.nav.assets') }}" class="flex gap-3" id="assetSearchForm">
        <input type="hidden" name="type" value="{{ $activeType }}">
        <div class="flex-1 relative">
            <input
                type="text"
                name="search"
                value="{{ $search ?? '' }}"
                placeholder="Search by symbol or name (e.g., {{ $isStock ? 'AAPL' : 'BTC' }})"
                class="w-full rounded-2xl border border-[#191919] bg-[#030303] px-4 py-3 pl-10 text-white placeholder-gray-500 focus:border-[#1fff9c] focus:outline-none"
                id="assetSearchInput"
            >
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <button type="submit" class="rounded-2xl bg-[#00ff5f] px-6 py-3 text-black font-semibold text-sm hover:brightness-110 transition hidden md:inline-flex">
            Search
        </button>
        @if($search)
            <a href="{{ route('user.nav.assets', ['type' => $activeType]) }}" class="rounded-2xl border border-[#1f1f1f] px-6 py-3 text-gray-400 font-semibold text-sm hover:text-white hover:border-[#1fff9c]/30 transition">
                Clear
            </a>
        @endif
    </form>

    @if($search)
        <div class="flex items-center gap-2 text-sm">
            <span class="text-gray-400">Search results for:</span>
            <span class="text-white font-semibold">"{{ $search }}"</span>
            <span class="text-gray-500">({{ $assets->total() }} {{ $assets->total() === 1 ? 'result' : 'results' }})</span>
        </div>
    @endif

    <div class="rounded-[32px] border border-[#101010] bg-[#040404] overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 border-b border-[#121212] text-xs uppercase tracking-wide text-gray-500">
            <span>Market Overview</span>
            <button id="refreshAssets" class="flex items-center justify-center gap-1 rounded-full border border-[#1fff9c]/40 px-4 py-1.5 text-xs font-semibold text-[#1fff9c] hover:border-[#1fff9c]">
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span>Refresh</span>
            </button>
        </div>
        <div class="grid grid-cols-12 px-4 py-3 text-xs uppercase tracking-wide text-gray-500 border-b border-[#121212]">
            <span class="col-span-5 md:col-span-3">Symbol</span>
            <span class="col-span-3 md:col-span-3 text-right">Price</span>
            <span class="col-span-3 md:col-span-3 text-right">24h Change</span>
            <span class="hidden md:block md:col-span-2 text-right">Updated</span>
            <span class="col-span-1 text-center">Favorite</span>
        </div>
        <div class="divide-y divide-[#0d0d0d]">
            @foreach($assets as $asset)
                @php
                    $isPositive = (float) $asset->price_change_24h >= 0;
                @endphp
                <div class="grid grid-cols-12 items-center px-4 py-4 text-sm hover:bg-[#0a0a0a] transition-colors">
                    <a href="{{ route('user.liveTrading.trade', ['asset_type' => $asset->type, 'symbol' => $asset->symbol]) }}" class="col-span-11 grid grid-cols-12 items-center cursor-pointer">
                        <div class="col-span-5 md:col-span-3">
                            <p class="font-semibold">{{ $asset->symbol }}</p>
                            <p class="text-xs text-gray-500">{{ $asset->name }}</p>
                        </div>
                        <div class="col-span-3 md:col-span-3 text-right font-semibold">
                            ${{ number_format((float) $asset->current_price, $priceDecimals) }}
                        </div>
                        <div class="col-span-3 md:col-span-3 text-right">
                            <span class="inline-flex items-center justify-end gap-1 rounded-full px-3 py-1 text-xs font-semibold {{ $isPositive ? 'bg-[#0f2b14] text-[#00ff5f]' : 'bg-[#2b0f0f] text-[#ff4d4d]' }}">
                                {{ $isPositive ? '+' : '' }}{{ number_format((float) $asset->price_change_24h, 2) }}%
                            </span>
                        </div>
                        <div class="hidden md:block md:col-span-2 text-right text-xs text-gray-500">
                            {{ optional($asset->updated_at)->diffForHumans() ?? '—' }}
                        </div>
                    </a>
                    <div class="col-span-1 flex justify-center">
                        @php
                            $isFavorited = in_array($asset->id, $favoriteIds ?? []);
                        @endphp
                        <button
                            type="button"
                            class="favorite-btn p-2 rounded-full hover:bg-[#1a1a1a] transition-colors"
                            data-asset-id="{{ $asset->id }}"
                            data-favorited="{{ $isFavorited ? 'true' : 'false' }}"
                            onclick="event.preventDefault(); toggleFavorite({{ $asset->id }}, this);"
                        >
                            <svg class="w-5 h-5 {{ $isFavorited ? 'text-yellow-400 fill-yellow-400' : 'text-gray-500' }}" fill="{{ $isFavorited ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="flex items-center justify-between text-xs text-gray-500">
        <span>Showing {{ $assets->firstItem() }}-{{ $assets->lastItem() }} of {{ $assets->total() }}</span>
        <div class="flex gap-2">
            @if($assets->onFirstPage())
                <span class="rounded-full border border-[#1f1f1f] px-3 py-1 opacity-50">Prev</span>
            @else
                <a href="{{ $assets->previousPageUrl() }}" class="rounded-full border border-[#1fff9c]/30 px-3 py-1 text-[#1fff9c]">Prev</a>
            @endif
            @if($assets->hasMorePages())
                <a href="{{ $assets->nextPageUrl() }}" class="rounded-full border border-[#1fff9c]/30 px-3 py-1 text-[#1fff9c]">Next</a>
            @else
                <span class="rounded-full border border-[#1f1f1f] px-3 py-1 opacity-50">Next</span>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleFavorite(assetId, button) {
    const icon = button.querySelector('svg');
    const isFavorited = button.getAttribute('data-favorited') === 'true';
    
    // Optimistic UI update
    if (isFavorited) {
        button.setAttribute('data-favorited', 'false');
        icon.classList.remove('text-yellow-400', 'fill-yellow-400');
        icon.classList.add('text-gray-500');
        icon.setAttribute('fill', 'none');
    } else {
        button.setAttribute('data-favorited', 'true');
        icon.classList.remove('text-gray-500');
        icon.classList.add('text-yellow-400', 'fill-yellow-400');
        icon.setAttribute('fill', 'currentColor');
    }
    
    // Make API call
    fetch(`/user/favorites/${assetId}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            // Revert on error
            if (isFavorited) {
                button.setAttribute('data-favorited', 'true');
                icon.classList.remove('text-gray-500');
                icon.classList.add('text-yellow-400', 'fill-yellow-400');
                icon.setAttribute('fill', 'currentColor');
            } else {
                button.setAttribute('data-favorited', 'false');
                icon.classList.remove('text-yellow-400', 'fill-yellow-400');
                icon.classList.add('text-gray-500');
                icon.setAttribute('fill', 'none');
            }
            alert(data.message || 'Failed to update favorite.');
        }
    })
    .catch(error => {
        // Revert on error
        if (isFavorited) {
            button.setAttribute('data-favorited', 'true');
            icon.classList.remove('text-gray-500');
            icon.classList.add('text-yellow-400', 'fill-yellow-400');
            icon.setAttribute('fill', 'currentColor');
        } else {
            button.setAttribute('data-favorited', 'false');
            icon.classList.remove('text-yellow-400', 'fill-yellow-400');
            icon.classList.add('text-gray-500');
            icon.setAttribute('fill', 'none');
        }
        alert('Failed to update favorite. Please try again.');
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const refreshBtn = document.getElementById('refreshAssets');
    const searchInput = document.getElementById('assetSearchInput');
    const searchForm = document.getElementById('assetSearchForm');
    if (!refreshBtn) return;

    const label = refreshBtn.querySelector('span');
    const icon = refreshBtn.querySelector('svg');
    const setState = (loading) => {
        if (loading) {
            refreshBtn.disabled = true;
            refreshBtn.classList.add('opacity-60');
            label.textContent = 'Refreshing...';
            icon.classList.add('animate-spin');
        } else {
            refreshBtn.disabled = false;
            refreshBtn.classList.remove('opacity-60');
            label.textContent = 'Refresh';
            icon.classList.remove('animate-spin');
        }
    };

    refreshBtn.addEventListener('click', () => {
        setState(true);
        fetch('{{ route("user.liveTrading.refreshPrices") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                setState(false);
                alert(data.message || 'Failed to refresh prices.');
            }
        })
        .catch(() => {
            setState(false);
            alert('Failed to refresh prices. Please try again.');
        });
    });
    if (searchInput && searchForm) {
        let debounceTimer;
        searchInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                searchForm.submit();
            }, 400);
        });
    }
});
</script>
@endpush
