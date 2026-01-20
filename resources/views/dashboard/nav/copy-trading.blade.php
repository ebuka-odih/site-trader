@php
    use Illuminate\Support\Str;
@endphp

@extends('dashboard.new-layout')

@section('content')
<div class="space-y-8 text-white">
    <div class="space-y-2 text-center md:text-left">
        <p class="text-xs uppercase tracking-[0.4em] text-[#08f58d]">Social Trading</p>
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="text-3xl font-semibold tracking-tight">Copy Trading</h1>
                <p class="text-gray-400 text-sm">Mirror proven traders, manage your allocations, and monitor history from one clean view.</p>
            </div>
            <div class="rounded-2xl border border-[#1fff9c]/30 bg-[#030303] px-4 py-2 text-xs text-gray-400">
                Balance available for copy trading: <span class="text-[#1fff9c]">{{ $user->formatAmount($user->trading_balance ?? 0) }}</span>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-[32px] border border-[#121212] bg-[#050505] p-6 space-y-4">
            <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Overview</p>
            <div class="space-y-1">
                <p class="text-sm text-gray-400">Active copy trades</p>
                <p class="text-3xl font-semibold">{{ $copiedTrades->where('status', 1)->count() }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-sm text-gray-400">Total allocated capital</p>
                <p class="text-3xl font-semibold">
                    {{ $user->formatAmount($copiedTrades->where('status', 1)->sum('amount')) }}
                </p>
            </div>
            <p class="text-xs text-gray-500">Copy trading runs in the background and mirrors the selected trader's moves proportionally.</p>
        </div>

        <div class="rounded-[32px] border border-[#121212] bg-[#050505] p-6 space-y-4">
            <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Allocation</p>
            <div class="space-y-3">
                @forelse($copiedTrades->take(3) as $trade)
                    <div class="flex items-center justify-between rounded-2xl border border-[#1a1a1a] px-4 py-3">
                        <div>
                            <p class="text-sm font-semibold">{{ $trade->copy_trader?->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-500">{{ $trade->status === 1 ? 'Active' : 'Inactive' }}</p>
                        </div>
                        <p class="text-sm font-semibold">{{ $user->formatAmount($trade->amount) }}</p>
                    </div>
                @empty
                    <p class="text-xs text-gray-500">No active allocations yet.</p>
                @endforelse
            </div>
            <a href="#history" class="text-xs text-[#1fff9c]">View full history</a>
        </div>

        <div class="rounded-[32px] border border-[#121212] bg-[#050505] p-6 space-y-4">
            <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Quick actions</p>
            <div class="space-y-3">
                <a href="#traders" class="block rounded-2xl border border-[#1fff9c]/30 bg-[#071c11] px-4 py-3 text-sm text-[#1fff9c] hover:border-[#1fff9c]">Explore traders</a>
                <a href="#history" class="block rounded-2xl border border-[#2d2d2d] px-4 py-3 text-sm text-gray-400 hover:text-white">Manage existing copies</a>
            </div>
        </div>
    </div>

    <div id="traders" class="space-y-4">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Marketplace</p>
                <h2 class="text-2xl font-semibold">Available Traders</h2>
            </div>
            <div class="w-full md:w-80">
                <div class="relative">
                    <input type="text" id="traderSearch" placeholder="Search traders by name..." class="w-full rounded-2xl border border-[#191919] bg-[#030303] pl-10 pr-4 py-3 text-sm text-white placeholder-gray-500 focus:border-[#1fff9c] focus:outline-none">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach($traders as $trader)
                <div class="rounded-[28px] border border-[#111111] bg-[#050505] p-5 flex flex-col gap-4">
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <img src="{{ $trader->avatar_url }}" alt="{{ $trader->name }}" class="h-12 w-12 rounded-full border border-[#1a1a1a] object-cover" onerror="this.src='{{ asset('img/trader.jpg') }}'">
                            <span class="absolute -bottom-1 -right-1 h-4 w-4 rounded-full border-2 border-[#050505] {{ ($trader->status ?? true) ? 'bg-[#1fff9c]' : 'bg-red-500' }}"></span>
                        </div>
                        <div>
                            <p class="text-base font-semibold">{{ $trader->name }}</p>
                            <p class="text-xs text-gray-500">{{ $trader->region ?? 'Global' }}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center justify-between gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Win rate</p>
                            <p class="text-[#1fff9c] font-semibold">{{ number_format($trader->win_rate, 1) }}%</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Profit share</p>
                            <p class="font-semibold">{{ number_format($trader->profit_share, 1) }}%</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Min amount</p>
                            <p class="font-semibold">${{ number_format($trader->amount, 0) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Copying users</p>
                            <p class="font-semibold">{{ number_format($trader->copiers_count ?? 0) }}</p>
                        </div>
                    </div>
                        <div class="rounded-2xl border border-[#1a1a1a] bg-[#030303] px-4 py-3 text-xs text-gray-400">
                        <p>{{ Str::limit($trader->description ?? 'Multi-strategy portfolio manager focused on consistency.', 120) }}</p>
                    </div>
                    @if(in_array($trader->id, $stoppedCopyTrades))
                        <button disabled class="rounded-2xl bg-[#101010] px-4 py-3 text-sm text-gray-500 cursor-not-allowed">Copy stopped</button>
                    @else
                        <form action="{{ route('user.copyTrading.store') }}" method="POST" class="copy-trade-form" data-trader="{{ $trader->id }}">
                            @csrf
                            <input type="hidden" name="trader_id" value="{{ $trader->id }}">
                            <input type="hidden" name="amount" value="{{ $trader->amount }}">
                            <button type="submit" class="copy-trade-btn flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-[#00ff5f] to-[#05c46b] px-4 py-3 text-sm font-semibold text-black transition hover:brightness-110">
                                <svg class="copy-trade-icon h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span class="copy-trade-text">Copy Trader</span>
                                <svg class="copy-trade-spinner hidden h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.96 7.96 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="copy-trade-loading hidden">Processing...</span>
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
            <div id="noResultsMessage" class="hidden col-span-full rounded-2xl border border-[#121212] bg-[#050505] py-10 text-center text-sm text-gray-500">No traders match your search.</div>
        </div>
    </div>

    <div id="history" class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.4em] text-gray-400">History</p>
                <h2 class="text-2xl font-semibold">Copied Trades</h2>
            </div>
            <span class="rounded-2xl border border-[#1a1a1a] px-4 py-1 text-xs text-gray-500">{{ $copiedTrades->count() }} total</span>
        </div>
        <div class="rounded-[32px] border border-[#121212] bg-[#050505] overflow-hidden">
            <div class="grid grid-cols-12 px-4 py-3 text-xs uppercase tracking-wide text-gray-500 border-b border-[#191919]">
                <span class="col-span-4">Trader</span>
                <span class="col-span-2 text-right">Amount</span>
                <span class="col-span-2 text-right">PnL</span>
                <span class="col-span-2 text-right">Status</span>
                <span class="col-span-2 text-right">Actions</span>
            </div>
            <div class="divide-y divide-[#0f0f0f]">
                @forelse($copiedTrades as $trade)
                    <div class="grid grid-cols-12 items-center px-4 py-4 text-sm">
                        <div class="col-span-4 flex items-center gap-3">
                            <img src="{{ $trade->copy_trader?->avatar_url ?? asset('img/trader.jpg') }}" class="h-10 w-10 rounded-full border border-[#1a1a1a] object-cover" onerror="this.src='{{ asset('img/trader.jpg') }}'" alt="">
                            <div>
                                <p class="font-semibold">{{ $trade->copy_trader?->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-gray-500">Started {{ $trade->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <p class="col-span-2 text-right font-semibold">{{ $user->formatAmount($trade->amount) }}</p>
                        <p class="col-span-2 text-right font-semibold {{ ($trade->pnl ?? 0) >= 0 ? 'text-[#1fff9c]' : 'text-red-400' }}">{{ $user->formatAmount($trade->pnl ?? 0) }}</p>
                        <div class="col-span-2 text-right">
                            @if($trade->status == 1)
                                <span class="rounded-full bg-[#071c11] px-3 py-1 text-xs text-[#1fff9c]">Active</span>
                            @elseif($trade->stopped_at)
                                <span class="rounded-full bg-red-500/10 px-3 py-1 text-xs text-red-400">Stopped</span>
                            @else
                                <span class="rounded-full bg-yellow-500/10 px-3 py-1 text-xs text-yellow-400">Pending</span>
                            @endif
                        </div>
                        <div class="col-span-2 text-right">
                            <a href="{{ route('user.copyTrading.detail', $trade->id) }}" class="text-xs text-[#1fff9c] hover:text-white">Details</a>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-10 text-center text-sm text-gray-500">No copy trading history yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('.copy-trade-form');
    const searchInput = document.getElementById('traderSearch');
    const traderCards = document.querySelectorAll('[data-trader-card]');
    const noResults = document.getElementById('noResultsMessage');

    const toggleBtnState = (form, loading) => {
        const btn = form.querySelector('.copy-trade-btn');
        const icon = form.querySelector('.copy-trade-icon');
        const text = form.querySelector('.copy-trade-text');
        const spinner = form.querySelector('.copy-trade-spinner');
        const loadingText = form.querySelector('.copy-trade-loading');
        btn.disabled = loading;
        btn.classList.toggle('opacity-70', loading);
        btn.classList.toggle('cursor-not-allowed', loading);
        icon.classList.toggle('hidden', loading);
        text.classList.toggle('hidden', loading);
        spinner.classList.toggle('hidden', !loading);
        loadingText.classList.toggle('hidden', !loading);
    };

    forms.forEach((form) => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            toggleBtnState(form, true);

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                toggleBtnState(form, false);
                if (data.success) {
                    Swal.fire('Success', data.message, 'success').then(() => window.location.reload());
                } else if (data.warning) {
                    Swal.fire('Notice', data.message, 'warning');
                } else {
                    Swal.fire('Error', data.message || 'Unable to process request.', 'error');
                }
            })
            .catch(() => {
                toggleBtnState(form, false);
                Swal.fire('Error', 'Unable to process request. Please try again.', 'error');
            });
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const term = this.value.toLowerCase().trim();
            let visible = 0;
            traderCards.forEach(card => {
                const name = card.querySelector('.text-base.font-semibold')?.textContent.toLowerCase() || '';
                const show = !term || name.includes(term);
                card.classList.toggle('hidden', !show);
                if (show) visible++;
            });
            if (noResults) {
                noResults.classList.toggle('hidden', visible !== 0);
            }
        });
    }
});
</script>
@endpush
