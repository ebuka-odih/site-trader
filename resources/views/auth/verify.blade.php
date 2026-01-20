@extends('layouts.auth')

@section('title', 'Verify Email')
@section('subtitle', 'Enter the 6-digit code we sent to your inbox.')

@section('content')
    <div class="space-y-6">
        <div class="text-center space-y-3">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-[#00ff5f]/30 to-[#05c46b]/40 text-[#00ff5f]">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9 6 9-6M4.5 7h15a1.5 1.5 0 011.5 1.5v7a1.5 1.5 0 01-1.5 1.5h-15A1.5 1.5 0 013 15.5v-7A1.5 1.5 0 014.5 7z" />
                </svg>
            </div>
            <p class="text-sm text-gray-400">We've sent a verification code to</p>
            <p class="text-base font-semibold text-white">
                {{ request()->query('email') ?? 'your email' }}
            </p>
        </div>

        @if (session('success'))
            <div class="rounded-2xl border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-300">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300">
                {{ session('error') }}
            </div>
        @endif

        <form id="verify-form" method="POST" action="{{ route('verification.code.verify') }}" class="space-y-5">
            @csrf

            <div class="space-y-2">
                <label for="code" class="text-xs uppercase tracking-wide text-gray-400">Verification Code</label>
                <input
                    id="code"
                    name="code"
                    type="text"
                    value="{{ old('code') }}"
                    class="w-full rounded-2xl border border-[#161616] bg-[#030303] px-4 py-3 text-sm text-white placeholder-gray-500 focus:border-[#1fff9c] focus:outline-none @error('code') border-red-500 focus:border-red-500 @enderror"
                    placeholder="Enter 6-digit code"
                    required
                    autocomplete="one-time-code"
                    maxlength="6"
                    pattern="[0-9]{6}"
                >
                @error('code')
                    <p class="text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" id="verify-btn" class="w-full rounded-2xl bg-gradient-to-r from-[#00ff5f] to-[#05c46b] py-3 text-sm font-semibold text-black transition hover:brightness-110 flex items-center justify-center gap-2">
                <span id="verify-btn-text">Verify Email</span>
                <svg id="verify-spinner" class="hidden h-4 w-4 animate-spin text-black" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
            </button>
        </form>

        <div class="text-center text-sm text-gray-400 space-y-3">
            <p>
                Didn't receive the code?
                <button type="button" id="resend-btn" class="font-semibold text-[#00ff5f] hover:text-white">
                    Resend Code
                </button>
            </p>
            <p>
                Remember your password?
                <a href="{{ route('login') }}" class="font-semibold text-[#00ff5f] hover:text-white">Sign in</a>
            </p>
        </div>
    </div>

    <form id="resend-form" action="{{ route('verification.code.resend') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="email" value="{{ request()->query('email') }}">
    </form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('verify-form');
    const btn = document.getElementById('verify-btn');
    const text = document.getElementById('verify-btn-text');
    const spinner = document.getElementById('verify-spinner');
    const codeInput = document.getElementById('code');
    const resendBtn = document.getElementById('resend-btn');
    const resendForm = document.getElementById('resend-form');

    form?.addEventListener('submit', () => {
        btn.disabled = true;
        spinner.classList.remove('hidden');
        text.textContent = 'Verifying...';
    });

    codeInput?.addEventListener('input', (event) => {
        const digitsOnly = event.target.value.replace(/\D/g, '').slice(0, 6);
        event.target.value = digitsOnly;
    });

    resendBtn?.addEventListener('click', () => {
        btn.disabled = true;
        spinner.classList.remove('hidden');
        text.textContent = 'Sending code...';
        resendForm?.submit();
    });
});
</script>
@endpush
