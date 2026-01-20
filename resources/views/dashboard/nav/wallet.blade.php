@extends('dashboard.new-layout')

@section('content')
<div class="space-y-8 text-white">
    <div>
        <p class="text-xs uppercase tracking-wide text-[#08f58d]">Wallet Center</p>
        <h1 class="text-3xl font-semibold tracking-tight">Manage your funds</h1>
        <p class="text-gray-400">Deposit, withdraw, and monitor balances from one clean view.</p>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-3xl border border-[#121212] bg-[#050505] p-5">
            <p class="text-xs text-gray-500">Wallet Balance</p>
            <p class="text-3xl font-semibold">${{ number_format($user->balance ?? 0, 2) }}</p>
            <p class="text-xs text-green-400 mt-1">Available instantly</p>
        </div>
        <div class="rounded-3xl border border-[#121212] bg-[#050505] p-5">
            <p class="text-xs text-gray-500">Investing</p>
            <p class="text-3xl font-semibold">${{ number_format($totalInvested ?? 0, 2) }}</p>
            <p class="text-xs text-gray-400 mt-1">Total amount invested</p>
        </div>
        <div class="rounded-3xl border border-[#121212] bg-[#050505] p-5">
            <p class="text-xs text-gray-500">Profit</p>
            <p class="text-3xl font-semibold">${{ number_format($user->profit, 2) }}</p>
            <p class="text-xs text-green-400 mt-1">Lifetime performance</p>
        </div>
    </div>

    @if(auth()->user()->isSuspended())
        <div class="rounded-[28px] bg-gradient-to-r from-red-900/20 to-red-800/20 border border-red-500/40 p-6 mb-4">
            <div class="flex items-center space-x-3">
                <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-red-400">Account Suspended</p>
                    <p class="text-xs text-gray-400 mt-1">Your account has been suspended. Please contact support for assistance.</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid gap-4 md:grid-cols-2">
        @if(auth()->user()->isSuspended())
            <div class="rounded-[28px] bg-gradient-to-r from-gray-800/50 to-gray-800/50 border border-gray-700/40 p-6 flex items-center justify-between opacity-50 cursor-not-allowed">
                <div>
                    <p class="text-sm uppercase text-gray-500">Deposit</p>
                    <p class="text-2xl font-semibold text-gray-500">Add funds quickly</p>
                    <p class="text-gray-500 text-sm">Account suspended</p>
                </div>
                <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </div>
            <div class="rounded-[28px] bg-gradient-to-r from-gray-800/50 to-gray-800/50 border border-gray-700/40 p-6 flex items-center justify-between opacity-50 cursor-not-allowed">
                <div>
                    <p class="text-sm uppercase text-gray-500">Withdraw</p>
                    <p class="text-2xl font-semibold text-gray-500">Cash out securely</p>
                    <p class="text-gray-500 text-sm">Account suspended</p>
                </div>
                <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m8 8l-8-8 8-8" />
                </svg>
            </div>
        @else
            <a href="{{ route('user.deposit') }}" class="rounded-[28px] bg-gradient-to-r from-[#0c3619] to-[#05230f] border border-[#00ff5f]/40 p-6 flex items-center justify-between">
                <div>
                    <p class="text-sm uppercase text-[#00ff5f]">Deposit</p>
                    <p class="text-2xl font-semibold text-white">Add funds quickly</p>
                    <p class="text-gray-400 text-sm">Supports crypto and fiat methods.</p>
                </div>
                <svg class="h-5 w-5 text-[#00ff5f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </a>
            <a href="{{ route('user.withdrawal') }}" class="rounded-[28px] bg-gradient-to-r from-[#361607] to-[#230c05] border border-[#f97316]/40 p-6 flex items-center justify-between">
                <div>
                    <p class="text-sm uppercase text-[#f97316]">Withdraw</p>
                    <p class="text-2xl font-semibold text-white">Cash out securely</p>
                    <p class="text-gray-400 text-sm">Track processing status in real time.</p>
                </div>
                <svg class="h-5 w-5 text-[#f97316]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m8 8l-8-8 8-8" />
                </svg>
            </a>
        @endif
    </div>

    <div class="rounded-[28px] border border-[#121212] bg-[#050505] p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm uppercase text-gray-400">Recent Movement</h2>
            <a href="{{ route('user.deposit') }}" class="text-xs text-gray-400 hover:text-white">All transactions</a>
        </div>
        <div class="space-y-3">
            @forelse($recentMovements as $movement)
                <div class="flex items-center justify-between py-2 border-b border-[#0f0f0f] last:border-b-0">
                    <div>
                        <p class="text-sm font-semibold text-white">{{ $movement['label'] }}</p>
                        <p class="text-xs text-gray-500">{{ optional($movement['timestamp'])->diffForHumans() ?? '—' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold {{ $movement['amount'] >= 0 ? 'text-green-400' : 'text-red-400' }}">
                            {{ $movement['amount'] >= 0 ? '+' : '' }}${{ number_format(abs($movement['amount']), 2) }}
                        </p>
                        <p class="text-xs text-gray-500">{{ $movement['subtext'] }}</p>
                    </div>
                </div>
            @empty
                <p class="text-xs text-gray-500">No recent wallet activity.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
