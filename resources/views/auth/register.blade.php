@extends('layouts.auth')

@section('title', 'Create Account')
@section('subtitle', 'Open a Smart Trader account and start investing.')

@section('content')
<form method="POST" action="{{ route('register') }}" class="space-y-5">
    @csrf
    
    <!-- Honeypot fields (renamed to avoid browser autofill) -->
    <input type="text" name="hp_tag_a" style="position:absolute;left:-9999px" tabindex="-1" autocomplete="off" aria-hidden="true" data-lpignore="true">
    <input type="text" name="hp_tag_b" style="position:absolute;left:-9999px" tabindex="-1" autocomplete="off" aria-hidden="true" data-lpignore="true">
    <input type="text" name="hp_tag_c" style="position:absolute;left:-9999px" tabindex="-1" autocomplete="off" aria-hidden="true" data-lpignore="true">
    <input type="hidden" name="registration_time" value="{{ time() }}">
    <input type="hidden" name="referral_code" value="{{ session('referrer_code') }}">

    @if(session('status'))
        <p class="rounded-2xl border border-[#1fff9c]/40 bg-[#071c11] px-4 py-3 text-sm text-[#1fff9c]">
            {{ session('status') }}
        </p>
    @endif

    <div class="space-y-2">
        <label for="name" class="text-xs uppercase tracking-wide text-gray-400">Full Name</label>
        <input
            id="name"
            name="name"
            type="text"
            value="{{ old('name') }}"
            class="w-full rounded-2xl border border-[#161616] bg-[#030303] px-4 py-3 text-sm text-white placeholder-gray-500 focus:border-[#1fff9c] focus:outline-none"
            placeholder="Jane Doe"
            required
            autocomplete="name"
            autofocus
        >
        @error('name') <p class="text-xs text-red-400">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-2">
        <label for="username" class="text-xs uppercase tracking-wide text-gray-400">Username</label>
        <input
            id="username"
            name="username"
            type="text"
            value="{{ old('username') }}"
            class="w-full rounded-2xl border border-[#161616] bg-[#030303] px-4 py-3 text-sm text-white placeholder-gray-500 focus:border-[#1fff9c] focus:outline-none"
            placeholder="janedoe"
            required
            autocomplete="username"
        >
        @error('username') <p class="text-xs text-red-400">{{ $message }}</p> @enderror
    </div>

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
        >
        @error('email') <p class="text-xs text-red-400">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-2">
        <label for="phone" class="text-xs uppercase tracking-wide text-gray-400">Phone Number</label>
        <input
            id="phone"
            name="phone"
            type="tel"
            value="{{ old('phone') }}"
            class="w-full rounded-2xl border border-[#161616] bg-[#030303] px-4 py-3 text-sm text-white placeholder-gray-500 focus:border-[#1fff9c] focus:outline-none"
            placeholder="+1234567890"
            required
            autocomplete="tel"
        >
        @error('phone') <p class="text-xs text-red-400">{{ $message }}</p> @enderror

    <div class="space-y-2">
        <label for="country" class="text-xs uppercase tracking-wide text-gray-400">Country <span class="text-red-500">*</span></label>
        @php
            $countries = [
                "Afghanistan",
                "Åland Islands",
                "Albania",
                "Algeria",
                "American Samoa",
                "Andorra",
                "Angola",
                "Anguilla",
                "Antarctica",
                "Antigua and Barbuda",
                "Argentina",
                "Armenia",
                "Aruba",
                "Australia",
                "Austria",
                "Azerbaijan",
                "Bahamas",
                "Bahrain",
                "Bangladesh",
                "Barbados",
                "Belarus",
                "Belgium",
                "Belize",
                "Benin",
                "Bermuda",
                "Bhutan",
                "Bolivia",
                "Bosnia and Herzegovina",
                "Botswana",
                "Bouvet Island",
                "Brazil",
                "British Indian Ocean Territory",
                "Brunei Darussalam",
                "Bulgaria",
                "Burkina Faso",
                "Burundi",
                "Cambodia",
                "Cameroon",
                "Canada",
                "Cape Verde",
                "Cayman Islands",
                "Central African Republic",
                "Chad",
                "Chile",
                "China",
                "Christmas Island",
                "Cocos (Keeling) Islands",
                "Colombia",
                "Comoros",
                "Congo",
                "Congo, The Democratic Republic of The",
                "Cook Islands",
                "Costa Rica",
                "Cote D'ivoire",
                "Croatia",
                "Cuba",
                "Cyprus",
                "Czech Republic",
                "Denmark",
                "Djibouti",
                "Dominica",
                "Dominican Republic",
                "Ecuador",
                "Egypt",
                "El Salvador",
                "Equatorial Guinea",
                "Eritrea",
                "Estonia",
                "Ethiopia",
                "Falkland Islands (Malvinas)",
                "Faroe Islands",
                "Fiji",
                "Finland",
                "France",
                "French Guiana",
                "French Polynesia",
                "French Southern Territories",
                "Gabon",
                "Gambia",
                "Georgia",
                "Germany",
                "Ghana",
                "Gibraltar",
                "Greece",
                "Greenland",
                "Grenada",
                "Guadeloupe",
                "Guam",
                "Guatemala",
                "Guernsey",
                "Guinea",
                "Guinea-bissau",
                "Guyana",
                "Haiti",
                "Heard Island and Mcdonald Islands",
                "Holy See (Vatican City State)",
                "Honduras",
                "Hong Kong",
                "Hungary",
                "Iceland",
                "India",
                "Indonesia",
                "Iran, Islamic Republic of",
                "Iraq",
                "Ireland",
                "Isle of Man",
                "Israel",
                "Italy",
                "Jamaica",
                "Japan",
                "Jersey",
                "Jordan",
                "Kazakhstan",
                "Kenya",
                "Kiribati",
                "Korea, Democratic People's Republic of",
                "Korea, Republic of",
                "Kuwait",
                "Kyrgyzstan",
                "Lao People's Democratic Republic",
                "Latvia",
                "Lebanon",
                "Lesotho",
                "Liberia",
                "Libyan Arab Jamahiriya",
                "Liechtenstein",
                "Lithuania",
                "Luxembourg",
                "Macao",
                "Macedonia, The Former Yugoslav Republic of",
                "Madagascar",
                "Malawi",
                "Malaysia",
                "Maldives",
                "Mali",
                "Malta",
                "Marshall Islands",
                "Martinique",
                "Mauritania",
                "Mauritius",
                "Mayotte",
                "Mexico",
                "Micronesia, Federated States of",
                "Moldova, Republic of",
                "Monaco",
                "Mongolia",
                "Montenegro",
                "Montserrat",
                "Morocco",
                "Mozambique",
                "Myanmar",
                "Namibia",
                "Nauru",
                "Nepal",
                "Netherlands",
                "Netherlands Antilles",
                "New Caledonia",
                "New Zealand",
                "Nicaragua",
                "Niger",
                "Nigeria",
                "Niue",
                "Norfolk Island",
                "Northern Mariana Islands",
                "Norway",
                "Oman",
                "Pakistan",
                "Palau",
                "Palestinian Territory, Occupied",
                "Panama",
                "Papua New Guinea",
                "Paraguay",
                "Peru",
                "Philippines",
                "Pitcairn",
                "Poland",
                "Portugal",
                "Puerto Rico",
                "Qatar",
                "Reunion",
                "Romania",
                "Russian Federation",
                "Rwanda",
                "Saint Helena",
                "Saint Kitts and Nevis",
                "Saint Lucia",
                "Saint Pierre and Miquelon",
                "Saint Vincent and The Grenadines",
                "Samoa",
                "San Marino",
                "Sao Tome and Principe",
                "Saudi Arabia",
                "Senegal",
                "Serbia",
                "Seychelles",
                "Sierra Leone",
                "Singapore",
                "Slovakia",
                "Slovenia",
                "Solomon Islands",
                "Somalia",
                "South Africa",
                "South Georgia and The South Sandwich Islands",
                "Spain",
                "Sri Lanka",
                "Sudan",
                "Suriname",
                "Svalbard and Jan Mayen",
                "Swaziland",
                "Sweden",
                "Switzerland",
                "Syrian Arab Republic",
                "Taiwan",
                "Tajikistan",
                "Tanzania, United Republic of",
                "Thailand",
                "Timor-leste",
                "Togo",
                "Tokelau",
                "Tonga",
                "Trinidad and Tobago",
                "Tunisia",
                "Turkey",
                "Turkmenistan",
                "Turks and Caicos Islands",
                "Tuvalu",
                "Uganda",
                "Ukraine",
                "United Arab Emirates",
                "United Kingdom",
                "United States",
                "United States Minor Outlying Islands",
                "Uruguay",
                "Uzbekistan",
                "Vanuatu",
                "Venezuela",
                "Viet Nam",
                "Virgin Islands, British",
                "Virgin Islands, U.S.",
                "Wallis and Futuna",
                "Western Sahara",
                "Yemen",
                "Zambia",
                "Zimbabwe"
            ];
        @endphp
        <select
            id="country"
            name="country"
            class="w-full rounded-2xl border border-[#161616] bg-[#030303] px-4 py-3 text-sm text-white focus:border-[#1fff9c] focus:outline-none"
            required
        >
            <option value="" disabled {{ old('country') ? '' : 'selected' }}>Select your country</option>
            @foreach($countries as $country)
                <option value="{{ $country }}" {{ old('country') === $country ? 'selected' : '' }}>{{ $country }}</option>
            @endforeach
        </select>
        @error('country') <p class="text-xs text-red-400">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-2">
        <label for="currency" class="text-xs uppercase tracking-wide text-gray-400">Preferred Currency</label>
        <select
            id="currency"
            name="currency"
            class="w-full rounded-2xl border border-[#161616] bg-[#030303] px-4 py-3 text-sm text-white focus:border-[#1fff9c] focus:outline-none"
            required
        >
            <option value="">Select currency</option>
            <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
            <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
            <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
            <option value="JPY" {{ old('currency') == 'JPY' ? 'selected' : '' }}>JPY - Japanese Yen</option>
            <option value="CAD" {{ old('currency') == 'CAD' ? 'selected' : '' }}>CAD - Canadian Dollar</option>
            <option value="AUD" {{ old('currency') == 'AUD' ? 'selected' : '' }}>AUD - Australian Dollar</option>
            <option value="CHF" {{ old('currency') == 'CHF' ? 'selected' : '' }}>CHF - Swiss Franc</option>
            <option value="CNY" {{ old('currency') == 'CNY' ? 'selected' : '' }}>CNY - Chinese Yuan</option>
            <option value="INR" {{ old('currency') == 'INR' ? 'selected' : '' }}>INR - Indian Rupee</option>
            <option value="BRL" {{ old('currency') == 'BRL' ? 'selected' : '' }}>BRL - Brazilian Real</option>
        </select>
        @error('currency') <p class="text-xs text-red-400">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-2">
        <label for="password" class="text-xs uppercase tracking-wide text-gray-400">Password</label>
        <input
            id="password"
            name="password"
            type="password"
            class="w-full rounded-2xl border border-[#161616] bg-[#030303] px-4 py-3 text-sm text-white placeholder-gray-500 focus:border-[#1fff9c] focus:outline-none"
            placeholder="Create password"
            required
            autocomplete="new-password"
        >
        @error('password') <p class="text-xs text-red-400">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-2">
        <label for="password_confirmation" class="text-xs uppercase tracking-wide text-gray-400">Confirm Password</label>
        <input
            id="password_confirmation"
            name="password_confirmation"
            type="password"
            class="w-full rounded-2xl border border-[#161616] bg-[#030303] px-4 py-3 text-sm text-white placeholder-gray-500 focus:border-[#1fff9c] focus:outline-none"
            placeholder="Repeat password"
            required
            autocomplete="new-password"
        >
    </div>

    <button type="submit" id="register-btn" class="w-full rounded-2xl bg-gradient-to-r from-[#00ff5f] to-[#05c46b] py-3 text-sm font-semibold text-black transition hover:brightness-110 flex items-center justify-center gap-2">
        <span id="register-btn-text">Create account</span>
        <svg id="register-spinner" class="hidden h-4 w-4 animate-spin text-black" viewBox="0 0 24 24" fill="none">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
    </button>
</form>
@endsection

@section('footer')
    <p>Already a member?
        <a href="{{ route('login') }}" class="text-[#00ff5f] font-semibold">Sign in</a>
    </p>
    <p class="mt-2">
        <a href="{{ route('index') }}" class="text-gray-500 hover:text-white">← Back to site</a>
    </p>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const btn = document.getElementById('register-btn');
    const text = document.getElementById('register-btn-text');
    const spinner = document.getElementById('register-spinner');
    form?.addEventListener('submit', () => {
        btn.disabled = true;
        spinner.classList.remove('hidden');
        text.textContent = 'Creating account...';
    });
});
</script>
@endpush
