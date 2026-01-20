@extends('dashboard.new-layout')

@section('content')
<div class="space-y-7 text-white">
    <!-- Page Header -->
    <div class="space-y-1">
        <p class="text-[11px] uppercase tracking-[0.3em] text-[#08f58d]">KYC Verification</p>
        <h1 class="text-2xl font-semibold">Complete your identity verification</h1>
        <p class="text-sm text-gray-400">Unlock all platform features by verifying your identity</p>
    </div>

    <!-- Overall Status Card -->
    <div class="rounded-[32px] border border-[#101010] bg-[#040404] p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#0b0b0b] border {{ $kycStatus['overall_status'] === 'completed' ? 'border-[#1fff9c]/30' : ($kycStatus['overall_status'] === 'in_progress' ? 'border-[#facc15]/30' : 'border-gray-700/30') }}">
                @if($kycStatus['overall_status'] === 'completed')
                    <svg class="h-6 w-6 text-[#1fff9c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                @else
                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                @endif
            </div>
            <div>
                <p class="text-sm text-gray-400">Verification Status</p>
                <p class="text-lg font-semibold {{ $kycStatus['overall_status'] === 'completed' ? 'text-[#1fff9c]' : ($kycStatus['overall_status'] === 'in_progress' ? 'text-[#facc15]' : 'text-gray-400') }}">
                    {{ ucfirst(str_replace('_', ' ', $kycStatus['overall_status'])) }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @php
                $completedSections = 0;
                if ($kycStatus['personal_info']['status'] === 'completed') $completedSections++;
                if ($kycStatus['address_info']['status'] === 'completed') $completedSections++;
                if ($kycStatus['id_info']['status'] === 'completed') $completedSections++;
            @endphp
            <span class="text-sm text-gray-400">{{ $completedSections }}/3 sections completed</span>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-2xl border border-[#1fff9c]/30 bg-[#071c11] px-4 py-3 text-[#1fff9c]">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-2xl border border-[#ff4d4d]/30 bg-[#1a0a0a] px-4 py-3 text-[#ff4d4d]">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('user.kyc.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <!-- Personal Information -->
        <div class="rounded-[32px] border border-[#111] bg-[#050505] p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-white">Personal Information</h3>
                    <p class="text-xs text-gray-500 mt-1">Basic details about yourself</p>
                </div>
                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                    {{ $kycStatus['personal_info']['status'] === 'completed' ? 'bg-[#1fff9c]/20 text-[#1fff9c]' : 'bg-[#facc15]/20 text-[#facc15]' }}">
                    {{ ucfirst($kycStatus['personal_info']['status']) }}
                </span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wide">Full Name</label>
                    <input type="text" value="{{ $kycStatus['personal_info']['full_name'] }}" 
                           class="w-full px-4 py-3 bg-[#020202] border border-[#1f1f1f] rounded-2xl text-gray-300 focus:outline-none focus:border-[#1fff9c]/50" disabled>
                    <p class="text-xs text-gray-500 mt-1.5">Full name cannot be changed</p>
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wide">Email</label>
                    <input type="email" value="{{ $kycStatus['personal_info']['email'] }}" 
                           class="w-full px-4 py-3 bg-[#020202] border border-[#1f1f1f] rounded-2xl text-gray-300 focus:outline-none focus:border-[#1fff9c]/50" disabled>
                    <p class="text-xs text-gray-500 mt-1.5">Email cannot be changed</p>
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wide">Phone</label>
                    <input type="text" value="{{ $kycStatus['personal_info']['phone'] }}" 
                           class="w-full px-4 py-3 bg-[#020202] border border-[#1f1f1f] rounded-2xl text-gray-300 focus:outline-none focus:border-[#1fff9c]/50" disabled>
                    <p class="text-xs text-gray-500 mt-1.5">Phone cannot be changed</p>
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wide">Date of Birth</label>
                    <input type="date" name="date_of_birth" value="{{ $kycStatus['personal_info']['date_of_birth'] }}" 
                           class="w-full px-4 py-3 bg-[#020202] border border-[#1f1f1f] rounded-2xl text-white focus:outline-none focus:border-[#1fff9c]/50">
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wide">Nationality</label>
                    <input type="text" name="nationality" value="{{ $kycStatus['personal_info']['nationality'] }}" 
                           placeholder="Enter your nationality"
                           class="w-full px-4 py-3 bg-[#020202] border border-[#1f1f1f] rounded-2xl text-white placeholder-gray-600 focus:outline-none focus:border-[#1fff9c]/50">
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="rounded-[32px] border border-[#111] bg-[#050505] p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-white">Address Information</h3>
                    <p class="text-xs text-gray-500 mt-1">Your residential address</p>
                </div>
                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                    {{ $kycStatus['address_info']['status'] === 'completed' ? 'bg-[#1fff9c]/20 text-[#1fff9c]' : 'bg-[#facc15]/20 text-[#facc15]' }}">
                    {{ ucfirst($kycStatus['address_info']['status']) }}
                </span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wide">Street Address</label>
                    <input type="text" name="street_address" value="{{ $kycStatus['address_info']['street_address'] }}" 
                           placeholder="Enter your street address"
                           class="w-full px-4 py-3 bg-[#020202] border border-[#1f1f1f] rounded-2xl text-white placeholder-gray-600 focus:outline-none focus:border-[#1fff9c]/50">
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wide">City</label>
                    <input type="text" name="city" value="{{ $kycStatus['address_info']['city'] }}" 
                           placeholder="Enter your city"
                           class="w-full px-4 py-3 bg-[#020202] border border-[#1f1f1f] rounded-2xl text-white placeholder-gray-600 focus:outline-none focus:border-[#1fff9c]/50">
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wide">State/Province</label>
                    <input type="text" name="state" value="{{ $kycStatus['address_info']['state'] }}" 
                           placeholder="Enter your state or province"
                           class="w-full px-4 py-3 bg-[#020202] border border-[#1f1f1f] rounded-2xl text-white placeholder-gray-600 focus:outline-none focus:border-[#1fff9c]/50">
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wide">Postal Code</label>
                    <input type="text" name="postal_code" value="{{ $kycStatus['address_info']['postal_code'] }}" 
                           placeholder="Enter your postal code"
                           class="w-full px-4 py-3 bg-[#020202] border border-[#1f1f1f] rounded-2xl text-white placeholder-gray-600 focus:outline-none focus:border-[#1fff9c]/50">
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wide">Country</label>
                    <input type="text" name="country" value="{{ $kycStatus['address_info']['country'] }}" 
                           placeholder="Enter your country"
                           class="w-full px-4 py-3 bg-[#020202] border border-[#1f1f1f] rounded-2xl text-white placeholder-gray-600 focus:outline-none focus:border-[#1fff9c]/50">
                </div>
            </div>
        </div>

        <!-- ID Information -->
        <div class="rounded-[32px] border border-[#111] bg-[#050505] p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-white">Identity Documents</h3>
                    <p class="text-xs text-gray-500 mt-1">Upload your identification documents</p>
                </div>
                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                    {{ $kycStatus['id_info']['status'] === 'completed' ? 'bg-[#1fff9c]/20 text-[#1fff9c]' : 'bg-[#facc15]/20 text-[#facc15]' }}">
                    {{ ucfirst($kycStatus['id_info']['status']) }}
                </span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wide">ID Type</label>
                    <select name="id_type" class="w-full px-4 py-3 bg-[#020202] border border-[#1f1f1f] rounded-2xl text-white focus:outline-none focus:border-[#1fff9c]/50">
                        <option value="">Select ID Type</option>
                        <option value="passport" {{ $kycStatus['id_info']['id_type'] === 'passport' ? 'selected' : '' }}>Passport</option>
                        <option value="national_id" {{ $kycStatus['id_info']['id_type'] === 'national_id' ? 'selected' : '' }}>National ID</option>
                        <option value="drivers_license" {{ $kycStatus['id_info']['id_type'] === 'drivers_license' ? 'selected' : '' }}>Driver's License</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wide">ID Number</label>
                    <input type="text" name="id_number" value="{{ $kycStatus['id_info']['id_number'] }}" 
                           placeholder="Enter your ID number"
                           class="w-full px-4 py-3 bg-[#020202] border border-[#1f1f1f] rounded-2xl text-white placeholder-gray-600 focus:outline-none focus:border-[#1fff9c]/50">
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wide">ID Front Side</label>
                    <input type="file" name="id_front" accept="image/*" 
                           class="w-full px-4 py-3 bg-[#020202] border border-[#1f1f1f] rounded-2xl text-white focus:outline-none focus:border-[#1fff9c]/50 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#1fff9c] file:text-black hover:file:bg-[#1fff9c]/80 file:cursor-pointer">
                    <p class="text-xs text-gray-500 mt-1.5">Upload front side of your ID document</p>
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wide">ID Back Side</label>
                    <input type="file" name="id_back" accept="image/*" 
                           class="w-full px-4 py-3 bg-[#020202] border border-[#1f1f1f] rounded-2xl text-white focus:outline-none focus:border-[#1fff9c]/50 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#1fff9c] file:text-black hover:file:bg-[#1fff9c]/80 file:cursor-pointer">
                    <p class="text-xs text-gray-500 mt-1.5">Upload back side of your ID document</p>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wide">Selfie with ID (Optional)</label>
                    <input type="file" name="selfie" accept="image/*" 
                           class="w-full px-4 py-3 bg-[#020202] border border-[#1f1f1f] rounded-2xl text-white focus:outline-none focus:border-[#1fff9c]/50 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#1fff9c] file:text-black hover:file:bg-[#1fff9c]/80 file:cursor-pointer">
                    <p class="text-xs text-gray-500 mt-1.5">Take a selfie while holding your ID document (optional)</p>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end pb-8">
            <button type="submit" class="rounded-full bg-gradient-to-r from-[#00ff5f] to-[#0fb863] px-8 py-3 text-black font-semibold text-sm hover:opacity-90 transition-opacity">
                Submit KYC Application
            </button>
        </div>
    </form>
</div>
@endsection
