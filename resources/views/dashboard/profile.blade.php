@extends('dashboard.new-layout')

@section('content')
<div class="space-y-10 text-white">
    <div class="flex flex-col gap-1">
        <p class="text-[11px] uppercase tracking-[0.35em] text-[#08f58d]">Profile</p>
        <h1 class="text-2xl font-semibold">Account preferences</h1>
        <p class="text-sm text-gray-400">Keep your personal information, security controls, and verification data aligned with the new dashboard aesthetic.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-[320px,1fr]">
        <div class="space-y-6">
            <div class="rounded-[32px] border border-[#101010] bg-[#040404] p-6 text-center">
                <div class="relative mx-auto h-28 w-28">
                    <img src="{{ $user->avatar_url }}" alt="Profile Photo" class="h-28 w-28 rounded-full border border-[#1fff9c]/30 object-cover">
                    <button type="button" id="changeAvatarButton" class="absolute -bottom-1 -right-1 flex h-9 w-9 items-center justify-center rounded-full bg-[#00ff5f] text-xs font-semibold text-black shadow-[0_0_30px_rgba(0,255,149,0.45)]">
                        Edit
                    </button>
                </div>
                <div class="mt-4 space-y-1">
                    <h2 class="text-xl font-semibold">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-400">{{ $user->email }}</p>
                </div>
                <div class="mt-4 flex flex-wrap items-center justify-center gap-2">
                    <span class="rounded-full border border-[#1fff9c]/50 px-3 py-1 text-xs font-semibold text-[#1fff9c]">
                        {{ $user->package->name ?? 'Free' }} member
                    </span>
                    <span class="rounded-full border border-white/10 px-3 py-1 text-xs text-gray-400">
                        ID: #{{ $user->id }}
                    </span>
                </div>
                <div class="mt-6 grid gap-3 text-left text-sm text-gray-400">
                    <div class="flex items-center justify-between rounded-2xl border border-[#101010] bg-[#060606] px-4 py-3">
                        <span>Member since</span>
                        <span class="text-white font-medium">{{ $user->created_at->format('M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl border border-[#101010] bg-[#060606] px-4 py-3">
                        <span>Last login</span>
                        <span class="text-white font-medium">{{ $user->last_login_at ? $user->last_login_at->format('M d, Y') : 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl border border-[#101010] bg-[#060606] px-4 py-3">
                        <span>Status</span>
                        <span class="text-[#00ff5f] font-medium">Active</span>
                    </div>
                </div>
            </div>

            <div class="rounded-[32px] border border-[#101010] bg-[#050505] p-5 space-y-4">
                <div>
                    <p class="text-[11px] uppercase tracking-[0.4em] text-gray-500">Contact methods</p>
                    <p class="text-sm text-gray-400">These channels help us verify activity and keep you updated.</p>
                </div>
                <div class="space-y-3 text-sm text-gray-400">
                    <div class="flex items-center justify-between rounded-2xl border border-[#111] bg-[#070707] px-4 py-3">
                        <span>Phone</span>
                        <span class="text-white font-medium">{{ $user->phone ?? 'Not provided' }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl border border-[#111] bg-[#070707] px-4 py-3">
                        <span>Telegram</span>
                        <span class="text-white font-medium">{{ $user->telegram ?? 'Not connected' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-[32px] border border-[#101010] bg-[#030303] p-6">
                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-[11px] uppercase tracking-[0.35em] text-gray-500">Identity</p>
                        <h3 class="text-xl font-semibold">Personal information</h3>
                    </div>
                    <div class="rounded-full border border-white/10 px-3 py-1 text-xs text-gray-400">Directly stored on secure nodes</div>
                </div>

                @if(session()->has('success'))
                    <div class="mt-4 rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mt-4 rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-100">
                        <ul class="list-disc space-y-1 pl-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('user.updateProfile', $user->id) }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
                    @csrf
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="text-xs uppercase tracking-wide text-gray-400">Full name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-2xl border border-[#191919] bg-[#050505] px-4 py-3 text-white placeholder:text-gray-600 focus:border-[#1fff9c] focus:outline-none" placeholder="Enter your full name">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase tracking-wide text-gray-400">Email</label>
                            <input type="email" value="{{ $user->email }}" class="w-full rounded-2xl border border-[#191919] bg-[#030303] px-4 py-3 text-gray-400" readonly>
                            <p class="text-xs text-gray-500">Email changes require support</p>
                        </div>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="text-xs uppercase tracking-wide text-gray-400">Phone number</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-2xl border border-[#191919] bg-[#050505] px-4 py-3 text-white placeholder:text-gray-600 focus:border-[#1fff9c] focus:outline-none" placeholder="Enter phone number">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase tracking-wide text-gray-400">Telegram username</label>
                            <input type="text" name="telegram" value="{{ old('telegram', $user->telegram) }}" class="w-full rounded-2xl border border-[#191919] bg-[#050505] px-4 py-3 text-white placeholder:text-gray-600 focus:border-[#1fff9c] focus:outline-none" placeholder="@username">
                        </div>
                    </div>
                    <button type="submit" class="w-full rounded-full bg-[#00ff5f] px-6 py-3 text-sm font-semibold text-black shadow-[0_0_35px_rgba(0,255,149,0.35)] transition hover:bg-[#0aff80]">
                        Save changes
                    </button>
                </form>
            </div>

            <div class="rounded-[32px] border border-[#101010] bg-[#030303] p-6">
                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-[11px] uppercase tracking-[0.35em] text-gray-500">Security</p>
                        <h3 class="text-xl font-semibold">Password & protection</h3>
                    </div>
                    <div class="rounded-full border border-white/10 px-3 py-1 text-xs text-gray-400">2FA coming soon</div>
                </div>

                @if(session()->has('status'))
                    <div class="mt-4 rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('user.updatePassword') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-xs uppercase tracking-wide text-gray-400">Current password</label>
                        <input type="password" name="current_password" class="w-full rounded-2xl border border-[#191919] bg-[#050505] px-4 py-3 text-white focus:border-[#1fff9c] focus:outline-none" placeholder="Enter current password">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs uppercase tracking-wide text-gray-400">New password</label>
                        <input type="password" name="new_password" class="w-full rounded-2xl border border-[#191919] bg-[#050505] px-4 py-3 text-white focus:border-[#1fff9c] focus:outline-none" placeholder="Enter new password">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs uppercase tracking-wide text-gray-400">Confirm new password</label>
                        <input type="password" name="new_password_confirmation" class="w-full rounded-2xl border border-[#191919] bg-[#050505] px-4 py-3 text-white focus:border-[#1fff9c] focus:outline-none" placeholder="Confirm new password">
                    </div>
                    <button type="submit" class="w-full rounded-full border border-[#1fff9c]/40 px-6 py-3 text-sm font-semibold text-[#1fff9c] transition hover:border-[#1fff9c]">
                        Update password
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-[32px] border border-[#101010] bg-[#030303] p-6 lg:col-span-2">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-[11px] uppercase tracking-[0.35em] text-gray-500">KYC</p>
                    <h3 class="text-xl font-semibold">Verification overview</h3>
                </div>
                <span class="rounded-full border border-[#1fff9c]/40 px-3 py-1 text-xs font-semibold text-[#1fff9c]">Identity verified</span>
            </div>
            <p class="mt-2 text-sm text-gray-400">Your documents keep withdrawals secure. Update information whenever your residency or identity changes.</p>
            <div class="mt-6 grid gap-4 md:grid-cols-2">
                <div class="rounded-3xl border border-[#101010] bg-[#050505] p-4">
                    <p class="text-xs uppercase tracking-wide text-gray-500">Identity</p>
                    <p class="text-base font-semibold text-white">Government ID</p>
                    <p class="text-sm text-[#00ff5f]">Approved</p>
                </div>
                <div class="rounded-3xl border border-[#101010] bg-[#050505] p-4">
                    <p class="text-xs uppercase tracking-wide text-gray-500">Address</p>
                    <p class="text-base font-semibold text-white">Proof of residence</p>
                    <p class="text-sm text-[#00ff5f]">Approved</p>
                </div>
            </div>
            <a href="{{ route('user.kyc.index') }}" class="mt-6 inline-flex items-center gap-2 rounded-full border border-white/10 px-5 py-3 text-sm font-semibold text-white transition hover:border-white/40">
                View submitted documents
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="rounded-[32px] border border-[#101010] bg-[#030303] p-6">
            <p class="text-[11px] uppercase tracking-[0.35em] text-gray-500">Account actions</p>
            <h3 class="text-xl font-semibold mt-1">Move quickly</h3>
            <p class="text-sm text-gray-400">Shortcuts to the flows you use most.</p>
            <div class="mt-6 space-y-4">
                <button type="button" class="flex w-full items-center justify-between rounded-3xl border border-[#101010] bg-[#050505] px-4 py-3 text-left text-sm transition hover:border-[#1fff9c]/30">
                    <span class="text-white font-medium">Referrals</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                <a href="{{ route('user.transactions.index') }}" class="flex w-full items-center justify-between rounded-3xl border border-[#101010] bg-[#050505] px-4 py-3 text-sm transition hover:border-[#1fff9c]/30">
                    <span class="text-white font-medium">Transaction history</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <div class="rounded-[32px] border border-[#101010] bg-[#030303] p-6">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-[11px] uppercase tracking-[0.35em] text-gray-500">Notifications</p>
                <h3 class="text-xl font-semibold">Stay in the loop</h3>
            </div>
            <p class="text-xs text-gray-500">Customize how we reach you.</p>
        </div>
        <div class="mt-6 space-y-5">
            <div class="flex items-center justify-between rounded-3xl border border-[#101010] bg-[#040404] px-4 py-3">
                <div>
                    <p class="text-sm font-semibold text-white">Email notifications</p>
                    <p class="text-xs text-gray-500">Approvals, transfers, and account changes.</p>
                </div>
                <label class="relative inline-flex cursor-pointer items-center">
                    <input type="checkbox" class="peer sr-only" checked>
                    <span class="h-6 w-11 rounded-full bg-gray-600 transition peer-checked:bg-[#1fff9c] after:absolute after:m-1 after:h-4 after:w-4 after:rounded-full after:bg-white after:transition peer-checked:after:translate-x-5"></span>
                </label>
            </div>
            <div class="flex items-center justify-between rounded-3xl border border-[#101010] bg-[#040404] px-4 py-3">
                <div>
                    <p class="text-sm font-semibold text-white">Push notifications</p>
                    <p class="text-xs text-gray-500">Trades, wallets, and alerts inside the app.</p>
                </div>
                <label class="relative inline-flex cursor-pointer items-center">
                    <input type="checkbox" class="peer sr-only">
                    <span class="h-6 w-11 rounded-full bg-gray-600 transition peer-checked:bg-[#1fff9c] after:absolute after:m-1 after:h-4 after:w-4 after:rounded-full after:bg-white after:transition peer-checked:after:translate-x-5"></span>
                </label>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const trigger = document.getElementById('changeAvatarButton');
    if (!trigger) return;

    trigger.addEventListener('click', () => {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';

        input.onchange = (event) => {
            const file = event.target.files?.[0];
            if (!file) return;

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("user.updateProfile", $user->id) }}';
            form.enctype = 'multipart/form-data';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.name = 'avatar';

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;

            form.appendChild(csrfToken);
            form.appendChild(fileInput);
            document.body.appendChild(form);
            form.submit();
        };

        input.click();
    });
});
</script>
@endsection
