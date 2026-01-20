@extends('layouts.auth')

@section('title', 'Forgot Password')
@section('subtitle', 'Enter your email address to receive a password reset link.')

@section('content')
    <div class="space-y-5">
        @if (session('status'))
            <div class="rounded-2xl border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-300">
                {{ session('status') }}
            </div>
        @endif

        <form id="forgot-password-form" method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div class="space-y-2">
                <label for="email" class="text-xs uppercase tracking-wide text-gray-400">Email Address</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    class="w-full rounded-2xl border border-[#161616] bg-[#030303] px-4 py-3 text-sm text-white placeholder-gray-500 focus:border-[#1fff9c] focus:outline-none"
                    placeholder="you@email.com"
                    required
                    autofocus
                    autocomplete="email"
                >
                @error('email')
                    <p class="text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" id="forgot-password-btn" class="w-full rounded-2xl bg-gradient-to-r from-[#00ff5f] to-[#05c46b] py-3 text-sm font-semibold text-black transition hover:brightness-110 flex items-center justify-center gap-2">
                <span id="forgot-password-btn-text">Email Password Reset Link</span>
                <svg id="forgot-password-spinner" class="hidden h-4 w-4 animate-spin text-black" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
            </button>
        </form>
    </div>
@endsection

@section('footer')
    Remembered your password?
    <a href="{{ route('login') }}" class="text-[#00ff5f] font-semibold">Sign in</a>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('forgot-password-form');
    const btn = document.getElementById('forgot-password-btn');
    const text = document.getElementById('forgot-password-btn-text');
    const spinner = document.getElementById('forgot-password-spinner');

    form?.addEventListener('submit', () => {
        btn.disabled = true;
        spinner.classList.remove('hidden');
        text.textContent = 'Sending link...';
    });
});
</script>
@endpush
