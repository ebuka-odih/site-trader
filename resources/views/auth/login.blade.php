@extends('layouts.auth')

@section('title', 'Sign In')
@section('subtitle', 'Securely access your portfolio and trades.')

@section('content')
<form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf
    <div class="space-y-2">
        <label for="email" class="text-xs uppercase tracking-wide text-gray-400">Email</label>
        <input
            id="email"
            name="email"
            type="email"
            value="{{ old('email') }}"
            class="w-full rounded-2xl border border-[#161616] bg-[#030303] px-4 py-3 text-sm text-white placeholder-gray-500 focus:border-[#1fff9c] focus:outline-none"
            placeholder="you@email.com"
            required
            autocomplete="email"
            autofocus
        >
        @error('email') <p class="text-xs text-red-400">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-2">
        <label for="password" class="text-xs uppercase tracking-wide text-gray-400">Password</label>
        <input
            id="password"
            name="password"
            type="password"
            class="w-full rounded-2xl border border-[#161616] bg-[#030303] px-4 py-3 text-sm text-white placeholder-gray-500 focus:border-[#1fff9c] focus:outline-none"
            placeholder="••••••••"
            required
            autocomplete="current-password"
        >
        @error('password') <p class="text-xs text-red-400">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center justify-between text-xs text-gray-400">
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="remember" class="rounded border-[#1f1f1f] bg-[#050505] text-[#1fff9c] focus:ring-[#1fff9c]" {{ old('remember') ? 'checked' : '' }}>
            Remember me
        </label>
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-[#00ff5f] hover:text-white">Forgot password?</a>
        @endif
    </div>

    <button type="submit" id="login-btn" class="w-full rounded-2xl bg-[#00ff5f] py-3 text-sm font-semibold text-black transition hover:brightness-110 flex items-center justify-center gap-2">
        <span id="login-btn-text">Sign In</span>
        <svg id="login-spinner" class="hidden h-4 w-4 animate-spin text-black" viewBox="0 0 24 24" fill="none">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
    </button>
</form>
@endsection

@section('footer')
    <p>Need an account?
        <a href="{{ route('register') }}" class="text-[#00ff5f] font-semibold">Create one</a>
    </p>
    <p class="mt-2">
        <a href="{{ route('index') }}" class="text-gray-500 hover:text-white">← Back to site</a>
    </p>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const btn = document.getElementById('login-btn');
    const text = document.getElementById('login-btn-text');
    const spinner = document.getElementById('login-spinner');
    form?.addEventListener('submit', () => {
        btn.disabled = true;
        spinner.classList.remove('hidden');
        text.textContent = 'Signing in...';
    });
});
</script>
@endpush
