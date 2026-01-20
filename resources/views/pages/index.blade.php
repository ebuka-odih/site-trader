@extends('pages.layout.app')
@section('content')

  <main>
        <!-- Hero Section with Slider -->
<div class="bg-[#0D091C] py-16 md:py-24 relative overflow-hidden">
    <!-- Background elements -->
    <div class="absolute top-20 right-20 w-64 h-64 bg-[#2FE6DE]/10 rounded-full filter blur-3xl"></div>
    <div class="absolute bottom-10 left-10 w-80 h-80 bg-[#2FE6DE]/5 rounded-full filter blur-3xl"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="flex flex-col lg:flex-row items-center">
            <div class="lg:w-1/2 mb-10 lg:mb-0">
                <div class="hero-slider-content">
                    <!-- Slide 1 -->
                    <div class="hero-slide active" data-slide="1">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                            <span class="text-white">Advanced Trading </span><br>
                            <span class="text-[#2FE6DE]">Platform</span>
                        </h1>

                        <p class="text-gray-300 text-lg mb-8 max-w-xl">
                           Trade stocks, cryptocurrencies, and digital assets with professional-grade tools, AI-powered bots, and copy trading features. Join thousands of successful traders worldwide.
                        </p>
                    </div>

                    <!-- Slide 2 -->
                    <div class="hero-slide" data-slide="2">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                            <span class="text-white">AI-Powered </span><br>
                            <span class="text-[#2FE6DE]">Trading Bots</span>
                        </h1>

                        <p class="text-gray-300 text-lg mb-8 max-w-xl">
                            Automate your trading with sophisticated AI algorithms that analyze market trends, execute trades 24/7, and maximize your profits while you sleep.
                        </p>
                    </div>

                    <!-- Slide 3 -->
                    <div class="hero-slide" data-slide="3">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                            <span class="text-white">Copy Trading </span><br>
                            <span class="text-[#2FE6DE]">Success</span>
                        </h1>

                        <p class="text-gray-300 text-lg mb-8 max-w-xl">
                            Follow and automatically copy the trades of top-performing traders. Learn from the best while building your own profitable portfolio with proven strategies.
                        </p>
                    </div>
                </div>

                <!-- Slider Navigation -->
                <div class="flex space-x-2 mb-8">
                    <button class="hero-nav-dot active" data-slide="1">
                        <span class="block w-3 h-3 rounded-full bg-[#2FE6DE]"></span>
                    </button>
                    <button class="hero-nav-dot" data-slide="2">
                        <span class="block w-3 h-3 rounded-full bg-[#2FE6DE]/30"></span>
                    </button>
                    <button class="hero-nav-dot" data-slide="3">
                        <span class="block w-3 h-3 rounded-full bg-[#2FE6DE]/30"></span>
                    </button>
                </div>

                <!-- Get Started Button -->
<div class="bg-[#1A1428]/50 p-4 rounded-xl border border-[#2FE6DE]/10 mb-6 max-w-md">
    <a href="{{ route('register') }}" class="block bg-[#2FE6DE] text-black font-medium px-8 py-4 rounded-lg hover:brightness-110 transition-all text-center">
        Get Started
    </a>
</div>
            </div>

            <!-- Right Side - Hero SVG Illustrations -->
            <div class="lg:w-1/2 relative">
                <div class="hero-slider-illustrations relative z-10">
                    <!-- Illustration 1 -->
                    <div class="hero-illustration active" data-slide="1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 600" class="w-full h-auto md:max-w-lg max-w-sm mx-auto">
                            <!-- Trading Dashboard Illustration -->
                            <rect x="100" y="100" width="600" height="400" rx="20" fill="#1A1428" stroke="#2FE6DE" stroke-opacity="0.3" stroke-width="2"/>

                            <!-- Chart Area -->
                            <rect x="120" y="180" width="360" height="300" rx="10" fill="#0D091C"/>
                            <polyline points="120,380 180,320 240,350 300,280 360,300 420,250 480,280" stroke="#2FE6DE" stroke-width="3" fill="none"/>
                            <circle cx="180" cy="320" r="5" fill="#2FE6DE"/>
                            <circle cx="240" cy="350" r="5" fill="#2FE6DE"/>
                            <circle cx="300" cy="280" r="5" fill="#2FE6DE"/>
                            <circle cx="360" cy="300" r="5" fill="#2FE6DE"/>
                            <circle cx="420" cy="250" r="5" fill="#2FE6DE"/>
                            <circle cx="480" cy="280" r="5" fill="#2FE6DE"/>

                            <!-- Chart Grid Lines -->
                            <line x1="120" y1="230" x2="480" y2="230" stroke="#2FE6DE" stroke-opacity="0.1" stroke-width="1"/>
                            <line x1="120" y1="280" x2="480" y2="280" stroke="#2FE6DE" stroke-opacity="0.1" stroke-width="1"/>
                            <line x1="120" y1="330" x2="480" y2="330" stroke="#2FE6DE" stroke-opacity="0.1" stroke-width="1"/>
                            <line x1="120" y1="380" x2="480" y2="380" stroke="#2FE6DE" stroke-opacity="0.1" stroke-width="1"/>
                            <line x1="180" y1="180" x2="180" y2="480" stroke="#2FE6DE" stroke-opacity="0.1" stroke-width="1"/>
                            <line x1="240" y1="180" x2="240" y2="480" stroke="#2FE6DE" stroke-opacity="0.1" stroke-width="1"/>
                            <line x1="300" y1="180" x2="300" y2="480" stroke="#2FE6DE" stroke-opacity="0.1" stroke-width="1"/>
                            <line x1="360" y1="180" x2="360" y2="480" stroke="#2FE6DE" stroke-opacity="0.1" stroke-width="1"/>
                            <line x1="420" y1="180" x2="420" y2="480" stroke="#2FE6DE" stroke-opacity="0.1" stroke-width="1"/>

                            <!-- Sidebar -->
                            <rect x="500" y="180" width="180" height="300" rx="10" fill="#0D091C"/>

                            <!-- Sidebar Items -->
                            <rect x="520" y="200" width="140" height="40" rx="5" fill="#2FE6DE" fill-opacity="0.1"/>
                            <circle cx="540" cy="220" r="10" fill="#2FE6DE" fill-opacity="0.3"/>
                            <rect x="560" y="215" width="80" height="10" rx="2" fill="white" fill-opacity="0.5"/>

                            <rect x="520" y="250" width="140" height="40" rx="5" fill="#2FE6DE" fill-opacity="0.1"/>
                            <circle cx="540" cy="270" r="10" fill="#2FE6DE" fill-opacity="0.3"/>
                            <rect x="560" y="265" width="80" height="10" rx="2" fill="white" fill-opacity="0.5"/>

                            <rect x="520" y="300" width="140" height="40" rx="5" fill="#2FE6DE" fill-opacity="0.1"/>
                            <circle cx="540" cy="320" r="10" fill="#2FE6DE" fill-opacity="0.3"/>
                            <rect x="560" y="315" width="80" height="10" rx="2" fill="white" fill-opacity="0.5"/>

                            <rect x="520" y="350" width="140" height="40" rx="5" fill="#2FE6DE" fill-opacity="0.1"/>
                            <circle cx="540" cy="370" r="10" fill="#2FE6DE" fill-opacity="0.3"/>
                            <rect x="560" y="365" width="80" height="10" rx="2" fill="white" fill-opacity="0.5"/>

                            <rect x="520" y="400" width="140" height="40" rx="5" fill="#2FE6DE" fill-opacity="0.1"/>
                            <circle cx="540" cy="420" r="10" fill="#2FE6DE" fill-opacity="0.3"/>
                            <rect x="560" y="415" width="80" height="10" rx="2" fill="white" fill-opacity="0.5"/>

                            <!-- Header -->
                            <rect x="120" y="120" width="560" height="40" rx="5" fill="#0D091C"/>
                            <circle cx="140" cy="140" r="10" fill="#2FE6DE"/>
                            <rect x="160" y="135" width="100" height="10" rx="2" fill="white" fill-opacity="0.7"/>
                            <rect x="500" y="135" width="60" height="10" rx="2" fill="#2FE6DE" fill-opacity="0.5"/>
                            <rect x="570" y="135" width="60" height="10" rx="2" fill="#2FE6DE" fill-opacity="0.5"/>

                            <!-- Floating Elements -->
                            <circle cx="650" cy="80" r="30" fill="#2FE6DE" fill-opacity="0.1"/>
                            <circle cx="700" cy="150" r="20" fill="#2FE6DE" fill-opacity="0.1"/>
                            <circle cx="80" cy="200" r="25" fill="#2FE6DE" fill-opacity="0.1"/>
                            <circle cx="50" cy="350" r="15" fill="#2FE6DE" fill-opacity="0.1"/>
                        </svg>
                    </div>

                    <!-- Illustration 2 -->
                    <div class="hero-illustration" data-slide="2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 600" class="w-full h-auto md:max-w-lg max-w-sm mx-auto">
                            <!-- AI Bot Trading Illustration -->
                            <circle cx="400" cy="300" r="180" fill="#1A1428" stroke="#2FE6DE" stroke-opacity="0.3" stroke-width="2"/>

                            <!-- Robot Head -->
                            <rect x="340" y="200" width="120" height="100" rx="20" fill="#0D091C" stroke="#2FE6DE" stroke-width="2"/>

                            <!-- Robot Eyes -->
                            <circle cx="370" cy="230" r="15" fill="#2FE6DE" fill-opacity="0.3"/>
                            <circle cx="370" cy="230" r="8" fill="#2FE6DE"/>
                            <circle cx="430" cy="230" r="15" fill="#2FE6DE" fill-opacity="0.3"/>
                            <circle cx="430" cy="230" r="8" fill="#2FE6DE"/>

                            <!-- Robot Mouth -->
                            <rect x="365" y="260" width="70" height="10" rx="5" fill="#2FE6DE" fill-opacity="0.5"/>
                            <rect x="365" y="275" width="30" height="5" rx="2" fill="#2FE6DE" fill-opacity="0.5"/>
                            <rect x="405" y="275" width="30" height="5" rx="2" fill="#2FE6DE" fill-opacity="0.5"/>

                            <!-- Robot Antennas -->
                            <line x1="360" y1="200" x2="350" y2="170" stroke="#2FE6DE" stroke-width="2"/>
                            <circle cx="350" cy="170" r="5" fill="#2FE6DE"/>
                            <line x1="440" y1="200" x2="450" y2="170" stroke="#2FE6DE" stroke-width="2"/>
                            <circle cx="450" cy="170" r="5" fill="#2FE6DE"/>

                            <!-- Robot Body -->
                            <rect x="320" y="310" width="160" height="120" rx="20" fill="#0D091C" stroke="#2FE6DE" stroke-width="2"/>

                            <!-- Robot Control Panel -->
                            <rect x="340" y="330" width="120" height="80" rx="10" fill="#1A1428"/>

                            <!-- Robot Buttons and Lights -->
                            <circle cx="360" cy="350" r="8" fill="#2FE6DE"/>
                            <circle cx="390" cy="350" r="8" fill="#2FE6DE" fill-opacity="0.6"/>
                            <circle cx="420" cy="350" r="8" fill="#2FE6DE" fill-opacity="0.3"/>

                            <rect x="350" y="370" width="100" height="10" rx="5" fill="#2FE6DE" fill-opacity="0.2"/>
                            <rect x="350" y="390" width="70" height="10" rx="5" fill="#2FE6DE" fill-opacity="0.4"/>

                            <!-- Stock Symbols Floating Around -->
                            <text x="250" y="200" font-family="Arial" font-size="20" fill="#2FE6DE">AAPL</text>
                            <text x="500" y="220" font-family="Arial" font-size="20" fill="#2FE6DE">MSFT</text>
                            <text x="300" y="400" font-family="Arial" font-size="20" fill="#2FE6DE">GOOGL</text>
                            <text x="480" y="380" font-family="Arial" font-size="20" fill="#2FE6DE">TSLA</text>
                            <text x="520" y="300" font-family="Arial" font-size="20" fill="#2FE6DE">AMZN</text>
                            <text x="220" y="300" font-family="Arial" font-size="20" fill="#2FE6DE">NVDA</text>

                            <!-- Data Streams -->
                            <path d="M250,210 C270,230 290,240 320,310" stroke="#2FE6DE" stroke-opacity="0.3" stroke-width="1" fill="none" stroke-dasharray="5,5"/>
                            <path d="M500,230 C480,250 460,270 480,310" stroke="#2FE6DE" stroke-opacity="0.3" stroke-width="1" fill="none" stroke-dasharray="5,5"/>
                            <path d="M300,390 C310,380 315,370 320,360" stroke="#2FE6DE" stroke-opacity="0.3" stroke-width="1" fill="none" stroke-dasharray="5,5"/>
                            <path d="M480,370 C470,360 465,350 460,340" stroke="#2FE6DE" stroke-opacity="0.3" stroke-width="1" fill="none" stroke-dasharray="5,5"/>
                            <path d="M510,300 C490,300 480,300 480,310" stroke="#2FE6DE" stroke-opacity="0.3" stroke-width="1" fill="none" stroke-dasharray="5,5"/>
                            <path d="M230,300 C250,300 270,300 320,310" stroke="#2FE6DE" stroke-opacity="0.3" stroke-width="1" fill="none" stroke-dasharray="5,5"/>

                            <!-- Floating Elements -->
                            <circle cx="200" cy="150" r="30" fill="#2FE6DE" fill-opacity="0.1"/>
                            <circle cx="600" cy="200" r="40" fill="#2FE6DE" fill-opacity="0.1"/>
                            <circle cx="550" cy="450" r="25" fill="#2FE6DE" fill-opacity="0.1"/>
                            <circle cx="250" cy="450" r="35" fill="#2FE6DE" fill-opacity="0.1"/>
                        </svg>
                    </div>

                    <!-- Illustration 3 -->
                    <div class="hero-illustration" data-slide="3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 600" class="w-full h-auto md:max-w-lg max-w-sm mx-auto">
                            <!-- Secure Transactions Illustration -->
                            <rect x="250" y="150" width="300" height="300" rx="20" fill="#1A1428" stroke="#2FE6DE" stroke-opacity="0.3" stroke-width="2"/>

                            <!-- Lock Body -->
                            <rect x="325" y="250" width="150" height="150" rx="10" fill="#0D091C" stroke="#2FE6DE" stroke-width="3"/>

                            <!-- Lock Shackle -->
                            <path d="M350,250 L350,200 Q400,180 450,200 L450,250" fill="none" stroke="#2FE6DE" stroke-width="8" stroke-linecap="round"/>

                            <!-- Keyhole -->
                            <circle cx="400" cy="325" r="25" fill="#1A1428" stroke="#2FE6DE" stroke-width="2"/>
                            <rect x="395" y="325" width="10" height="25" rx="5" fill="#1A1428" stroke="#2FE6DE" stroke-width="2"/>

                            <!-- Digital Elements -->
                            <circle cx="250" cy="150" r="10" fill="#2FE6DE" fill-opacity="0.5"/>
                            <line x1="250" y1="150" x2="300" y2="200" stroke="#2FE6DE" stroke-opacity="0.3" stroke-width="1" stroke-dasharray="5,5"/>

                            <circle cx="550" cy="150" r="10" fill="#2FE6DE" fill-opacity="0.5"/>
                            <line x1="550" y1="150" x2="500" y2="200" stroke="#2FE6DE" stroke-opacity="0.3" stroke-width="1" stroke-dasharray="5,5"/>

                            <circle cx="250" cy="450" r="10" fill="#2FE6DE" fill-opacity="0.5"/>
                            <line x1="250" y1="450" x2="300" y2="400" stroke="#2FE6DE" stroke-opacity="0.3" stroke-width="1" stroke-dasharray="5,5"/>

                            <circle cx="550" cy="450" r="10" fill="#2FE6DE" fill-opacity="0.5"/>
                            <line x1="550" y1="450" x2="500" y2="400" stroke="#2FE6DE" stroke-opacity="0.3" stroke-width="1" stroke-dasharray="5,5"/>

                            <!-- Binary Code Background -->
                            <text x="270" y="180" font-family="monospace" font-size="10" fill="#2FE6DE" fill-opacity="0.3">10110101</text>
                            <text x="270" y="195" font-family="monospace" font-size="10" fill="#2FE6DE" fill-opacity="0.3">01001010</text>
                            <text x="270" y="210" font-family="monospace" font-size="10" fill="#2FE6DE" fill-opacity="0.3">11010010</text>

                            <text x="450" y="180" font-family="monospace" font-size="10" fill="#2FE6DE" fill-opacity="0.3">10110101</text>
                            <text x="450" y="195" font-family="monospace" font-size="10" fill="#2FE6DE" fill-opacity="0.3">01001010</text>
                            <text x="450" y="210" font-family="monospace" font-size="10" fill="#2FE6DE" fill-opacity="0.3">11010010</text>

                            <text x="270" y="420" font-family="monospace" font-size="10" fill="#2FE6DE" fill-opacity="0.3">10110101</text>
                            <text x="270" y="435" font-family="monospace" font-size="10" fill="#2FE6DE" fill-opacity="0.3">01001010</text>
                            <text x="270" y="450" font-family="monospace" font-size="10" fill="#2FE6DE" fill-opacity="0.3">11010010</text>

                            <text x="450" y="420" font-family="monospace" font-size="10" fill="#2FE6DE" fill-opacity="0.3">10110101</text>
                            <text x="450" y="435" font-family="monospace" font-size="10" fill="#2FE6DE" fill-opacity="0.3">01001010</text>
                            <text x="450" y="450" font-family="monospace" font-size="10" fill="#2FE6DE" fill-opacity="0.3">11010010</text>

                            <!-- Shield Elements -->
                            <path d="M200,300 L200,350 Q250,400 300,350 L300,300 Q250,320 200,300 Z" fill="#0D091C" stroke="#2FE6DE" stroke-opacity="0.5" stroke-width="1"/>
                            <path d="M500,300 L500,350 Q550,400 600,350 L600,300 Q550,320 500,300 Z" fill="#0D091C" stroke="#2FE6DE" stroke-opacity="0.5" stroke-width="1"/>

                            <!-- Floating Elements -->
                            <circle cx="150" cy="200" r="30" fill="#2FE6DE" fill-opacity="0.1"/>
                            <circle cx="650" cy="200" r="40" fill="#2FE6DE" fill-opacity="0.1"/>
                            <circle cx="650" cy="400" r="25" fill="#2FE6DE" fill-opacity="0.1"/>
                            <circle cx="150" cy="400" r="35" fill="#2FE6DE" fill-opacity="0.1"/>
                        </svg>
                    </div>
                </div>
                <div class="absolute top-1/4 right-1/4 w-64 h-64 bg-[#2FE6DE]/10 rounded-full filter blur-3xl -z-10"></div>
            </div>
        </div>
    </div>
</div>

<!-- Why Choose Section -->
<div class="py-16 bg-gradient-to-b from-[#0D091C] to-[#0A0714]">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">Why Choose <span class="text-[#2FE6DE]">{{ config('app.name') }}</span></h2>
            <p class="text-gray-400 max-w-2xl mx-auto">Our platform offers a comprehensive suite of tools and features designed to enhance your trading experience.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-[#1A1428] rounded-xl p-6 border border-[#2FE6DE]/10 hover:border-[#2FE6DE]/30 transition-all">
                <div class="w-14 h-14 rounded-full bg-[#2FE6DE]/10 flex items-center justify-center mb-6">
                    <i class="fas fa-shield-alt text-[#2FE6DE] text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Advanced Security</h3>
                <p class="text-gray-400">Industry-leading security measures to protect your assets.</p>
            </div>

            <div class="bg-[#1A1428] rounded-xl p-6 border border-[#2FE6DE]/10 hover:border-[#2FE6DE]/30 transition-all">
                <div class="w-14 h-14 rounded-full bg-[#2FE6DE]/10 flex items-center justify-center mb-6">
                    <i class="fas fa-chart-line text-[#2FE6DE] text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Professional Tools</h3>
                <p class="text-gray-400">Advanced charting and real-time market data for informed decisions.</p>
            </div>

            <div class="bg-[#1A1428] rounded-xl p-6 border border-[#2FE6DE]/10 hover:border-[#2FE6DE]/30 transition-all">
                <div class="w-14 h-14 rounded-full bg-[#2FE6DE]/10 flex items-center justify-center mb-6">
                    <i class="fas fa-robot text-[#2FE6DE] text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">AI-Powered Trading</h3>
                <p class="text-gray-400">Utilize our AI-powered trading bots based on predefined strategies.</p>
            </div>

            <div class="bg-[#1A1428] rounded-xl p-6 border border-[#2FE6DE]/10 hover:border-[#2FE6DE]/30 transition-all">
                <div class="w-14 h-14 rounded-full bg-[#2FE6DE]/10 flex items-center justify-center mb-6">
                    <i class="fas fa-bolt text-[#2FE6DE] text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Fast Execution</h3>
                <p class="text-gray-400">High-performance matching engine for minimal latency and slippage.</p>
            </div>
        </div>
    </div>
</div>

<!-- Security & Compliance Section -->
<div class="py-16 bg-[#0A0714]">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">Security & <span class="text-[#2FE6DE]">Compliance</span></h2>
            <p class="text-gray-400 max-w-2xl mx-auto">Your security and regulatory compliance are our top priorities. We maintain the highest standards to protect your investments.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-[#1A1428] rounded-xl p-6 border border-[#2FE6DE]/10 text-center">
                <div class="w-16 h-16 rounded-full bg-[#2FE6DE]/10 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-[#2FE6DE] text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Bank-Grade Security</h3>
                <p class="text-gray-400">Multi-layer security with cold storage, 2FA, and regular security audits to protect your funds.</p>
            </div>

            <div class="bg-[#1A1428] rounded-xl p-6 border border-[#2FE6DE]/10 text-center">
                <div class="w-16 h-16 rounded-full bg-[#2FE6DE]/10 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-certificate text-[#2FE6DE] text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Regulatory Compliance</h3>
                <p class="text-gray-400">Fully compliant with international financial regulations and anti-money laundering standards.</p>
            </div>

            <div class="bg-[#1A1428] rounded-xl p-6 border border-[#2FE6DE]/10 text-center">
                <div class="w-16 h-16 rounded-full bg-[#2FE6DE]/10 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-shield text-[#2FE6DE] text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">KYC Verification</h3>
                <p class="text-gray-400">Comprehensive Know Your Customer verification process to ensure platform integrity and user safety.</p>
            </div>
        </div>
    </div>
</div>

<!-- Stats Counter Section -->
<div class="py-12 bg-[#0A0714]">
    <div class="container mx-auto px-4">
        <div class="bg-[#1A1428] rounded-xl p-8 border border-[#2FE6DE]/10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div class="counter-item">
                    <div class="text-[#2FE6DE] text-2xl md:text-4xl font-bold mb-2" data-count="50">
                        <span class="counter">50</span><span>K+</span>
                    </div>
                    <p class="text-gray-300">Active Traders</p>
                </div>

                <div class="counter-item">
                    <div class="text-[#2FE6DE] text-2xl md:text-4xl font-bold mb-2" data-count="120">
                        <span class="counter">120</span><span>+</span>
                    </div>
                    <p class="text-gray-300">Trading Pairs</p>
                </div>

                <div class="counter-item">
                    <div class="text-[#2FE6DE] text-2xl md:text-4xl font-bold mb-2" data-count="99">
                        <span class="counter">99</span><span>.9%</span>
                    </div>
                    <p class="text-gray-300">Uptime Guarantee</p>
                </div>

                <div class="counter-item">
                    <div class="text-[#2FE6DE] text-2xl md:text-4xl font-bold mb-2" data-count="2">
                        <span class="counter">2</span><span>B+</span>
                    </div>
                    <p class="text-gray-300">Trading Volume</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="relative rounded-2xl overflow-hidden mb-16 shadow-2xl shadow-[#2FE6DE]/10 border border-[#2FE6DE]/10 group">
                <div class="absolute inset-0 bg-gradient-to-r from-[#2FE6DE]/20 to-purple-600/20 blur-xl opacity-30 group-hover:opacity-40 transition-opacity"></div>
                <img src="{{ asset('front/1.jpeg') }}"
                     alt="About {{ config('app.name') }}"
                     class="w-full h-64 md:h-[500px] object-cover rounded-2xl relative z-10 transition-transform duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-[#0A0714] via-transparent to-transparent z-20 rounded-2xl"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6 md:p-10 z-30">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-[#2FE6DE]/20 flex items-center justify-center">
                            <i class="fas fa-chart-line text-[#2FE6DE]"></i>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Trading Advantages -->
    <div class="py-16 bg-gradient-to-b from-[#0D091C] to-[#0A0714]">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <div class="inline-block mb-4 px-4 py-1 bg-[#2FE6DE]/10 rounded-full border border-[#2FE6DE]/20">
                    <span class="text-[#2FE6DE] text-sm font-medium">Why Traders Choose Us</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold mb-6">Trading <span class="text-[#2FE6DE]">Advantages</span></h2>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">Discover the key benefits that make our platform the preferred choice for successful traders worldwide.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-[#1A1428] rounded-xl border border-[#2FE6DE]/10 hover:border-[#2FE6DE]/30 transition-all hover:transform hover:-translate-y-1 hover:shadow-lg hover:shadow-[#2FE6DE]/5 overflow-hidden">
                    <div class="h-48 relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1559526324-4b87b5e36e44?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
                             alt="Low Trading Fees"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#1A1428]/80 via-transparent to-transparent"></div>
                        <div class="absolute bottom-4 left-4">
                            <div class="w-12 h-12 rounded-full bg-[#2FE6DE]/20 flex items-center justify-center">
                                <i class="fas fa-percentage text-[#2FE6DE] text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-xl font-semibold mb-3">Low Trading Fees</h3>
                        <p class="text-gray-300 mb-4">Maximize your profits with our competitive fee structure. We offer some of the lowest trading fees in the industry, ensuring more of your gains stay in your pocket.
</p>
                        <ul class="text-gray-400 space-y-2">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-[#2FE6DE] mt-1 mr-2"></i>
                                <span>0.1% trading fees</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-[#2FE6DE] mt-1 mr-2"></i>
                                <span>No hidden charges</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-[#2FE6DE] mt-1 mr-2"></i>
                                <span>Volume discounts available</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="bg-[#1A1428] rounded-xl border border-[#2FE6DE]/10 hover:border-[#2FE6DE]/30 transition-all hover:transform hover:-translate-y-1 hover:shadow-lg hover:shadow-[#2FE6DE]/5 overflow-hidden">
                    <div class="h-48 relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1553877522-43269d4ea984?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
                             alt="24/7 Support"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#1A1428]/80 via-transparent to-transparent"></div>
                        <div class="absolute bottom-4 left-4">
                            <div class="w-12 h-12 rounded-full bg-[#F59E0B]/20 flex items-center justify-center">
                                <i class="fas fa-headset text-[#F59E0B] text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-xl font-semibold mb-3">24/7 Support</h3>
                        <p class="text-gray-300 mb-4">Get help whenever you need it with our round-the-clock customer support. Our expert team is always ready to assist you with any trading questions or technical issues.</p>
                        <ul class="text-gray-400 space-y-2">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-[#2FE6DE] mt-1 mr-2"></i>
                                <span>Live chat support</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-[#2FE6DE] mt-1 mr-2"></i>
                                <span>Expert trading guidance</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-[#2FE6DE] mt-1 mr-2"></i>
                                <span>Multilingual support</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="bg-[#1A1428] rounded-xl border border-[#2FE6DE]/10 hover:border-[#2FE6DE]/30 transition-all hover:transform hover:-translate-y-1 hover:shadow-lg hover:shadow-[#2FE6DE]/5 overflow-hidden">
                    <div class="h-48 relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
                             alt="Global Markets"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#1A1428]/80 via-transparent to-transparent"></div>
                        <div class="absolute bottom-4 left-4">
                            <div class="w-12 h-12 rounded-full bg-[#10B981]/20 flex items-center justify-center">
                                <i class="fas fa-globe text-[#10B981] text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-xl font-semibold mb-3">Global Markets</h3>
                        <p class="text-gray-300 mb-4">Access global financial markets from a single platform. Trade stocks, cryptocurrencies, and other assets from major exchanges worldwide with real-time data and execution.</p>
                        <ul class="text-gray-400 space-y-2">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-[#2FE6DE] mt-1 mr-2"></i>
                                <span>120+ trading pairs</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-[#2FE6DE] mt-1 mr-2"></i>
                                <span>Multiple asset classes</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-[#2FE6DE] mt-1 mr-2"></i>
                                <span>Real-time market data</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Popular Stocks Section -->
<div class="py-12 bg-gradient-to-b from-[#0A0714] to-[#0D091C]">
    <div class="container mx-auto px-4">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold mb-4">Popular <span class="text-[#2FE6DE]">Stocks</span></h2>
            <p class="text-gray-400 max-w-2xl mx-auto">Track and trade the most popular stocks with real-time data and advanced tools</p>
        </div>

        <!-- Market Status Indicator -->
        <div class="flex justify-center mb-6">
            <div class="bg-[#1A1428] rounded-lg px-4 py-2 border border-[#2FE6DE]/10">
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-sm text-gray-300" id="market-status">Market Status: <span class="text-green-400">Loading...</span></span>
                    <span class="text-xs text-gray-400" id="last-updated"></span>
                </div>
            </div>
        </div>

        <!-- Desktop Table View (hidden on mobile) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full bg-[#1A1428] rounded-xl overflow-hidden border border-[#2FE6DE]/10">
                <thead>
                    <tr class="bg-[#0D091C]">
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">#</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Company</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-gray-400">Price</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-gray-400">Change</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-gray-400">Change %</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-gray-400">Volume</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-gray-400">Market Cap</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-gray-400">Action</th>
                    </tr>
                </thead>
                <tbody id="stock-market-table" class="divide-y divide-[#2FE6DE]/5">
                    <!-- Loading state will be replaced by JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View (shown only on mobile) -->
        <div class="md:hidden">
            <div id="stock-market-cards" class="space-y-4">
                <!-- Loading state will be replaced by JavaScript -->
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
            <div class="bg-[#1A1428] rounded-lg p-4 border border-[#2FE6DE]/10">
                <div class="text-center">
                    <div class="text-green-400 text-xl font-bold" id="gainers-count">-</div>
                    <div class="text-xs text-gray-400">Gainers</div>
                </div>
            </div>
            <div class="bg-[#1A1428] rounded-lg p-4 border border-[#2FE6DE]/10">
                <div class="text-center">
                    <div class="text-red-400 text-xl font-bold" id="losers-count">-</div>
                    <div class="text-xs text-gray-400">Losers</div>
                </div>
            </div>
            <div class="bg-[#1A1428] rounded-lg p-4 border border-[#2FE6DE]/10">
                <div class="text-center">
                    <div class="text-[#2FE6DE] text-xl font-bold" id="total-volume">-</div>
                    <div class="text-xs text-gray-400">Total Volume</div>
                </div>
            </div>
            <div class="bg-[#1A1428] rounded-lg p-4 border border-[#2FE6DE]/10">
                <div class="text-center">
                    <div class="text-[#2FE6DE] text-xl font-bold" id="avg-change">-</div>
                    <div class="text-xs text-gray-400">Avg Change</div>
                </div>
            </div>
        </div>

        <div class="text-center mt-8">
                         <a href="{{ route('market') }}" class="inline-block px-6 py-3 bg-[#2FE6DE]/10 text-[#2FE6DE] rounded-lg border border-[#2FE6DE]/30 hover:bg-[#2FE6DE]/20 transition-colors">
                View All Markets <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</div>

<script>
// Advanced Stock Market Widget
class StockMarketWidget {
    constructor() {
        this.stocks = [
            {
                symbol: 'AAPL',
                name: 'Apple Inc.',
                price: 175.43,
                change: 2.67,
                changePercent: 1.55,
                volume: 65432100,
                marketCap: 2786543210000,
                sector: 'Technology'
            },
            {
                symbol: 'MSFT',
                name: 'Microsoft Corporation',
                price: 384.30,
                change: -1.15,
                changePercent: -0.30,
                volume: 32156789,
                marketCap: 2856432100000,
                sector: 'Technology'
            },
            {
                symbol: 'GOOGL',
                name: 'Alphabet Inc.',
                price: 142.65,
                change: 1.25,
                changePercent: 0.88,
                volume: 28765432,
                marketCap: 1786543210000,
                sector: 'Communication'
            },
            {
                symbol: 'TSLA',
                name: 'Tesla, Inc.',
                price: 248.48,
                change: 5.73,
                changePercent: 2.36,
                volume: 89543210,
                marketCap: 789876543210,
                sector: 'Consumer Cyclical'
            },
            {
                symbol: 'AMZN',
                name: 'Amazon.com Inc.',
                price: 156.77,
                change: -0.85,
                changePercent: -0.54,
                volume: 45321098,
                marketCap: 1623456789012,
                sector: 'Consumer Cyclical'
            },
            {
                symbol: 'NVDA',
                name: 'NVIDIA Corporation',
                price: 875.15,
                change: 18.90,
                changePercent: 2.21,
                volume: 76543210,
                marketCap: 2156789012345,
                sector: 'Technology'
            },
            {
                symbol: 'META',
                name: 'Meta Platforms Inc.',
                price: 498.37,
                change: 7.23,
                changePercent: 1.47,
                volume: 23456789,
                marketCap: 1265789012345,
                sector: 'Communication'
            },
            {
                symbol: 'BRK.B',
                name: 'Berkshire Hathaway Inc.',
                price: 432.15,
                change: 2.45,
                changePercent: 0.57,
                volume: 4567890,
                marketCap: 958765432109,
                sector: 'Financial Services'
            }
        ];

        this.apiKeys = {
            alphavantage: 'demo', // Replace with your API key
            finnhub: 'demo',      // Replace with your API key
            iex: 'demo'           // Replace with your API key
        };

        this.init();
    }

    init() {
        this.showLoadingState();
        this.simulateRealTimeUpdates();
        this.loadStockData();
    }

    showLoadingState() {
        // Show loading in table
        const tableBody = document.getElementById('stock-market-table');
        if (tableBody) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-400">
                        <div class="flex justify-center items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-[#2FE6DE]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Loading market data...</span>
                        </div>
                    </td>
                </tr>
            `;
        }

        // Show loading in cards
        const cardsContainer = document.getElementById('stock-market-cards');
        if (cardsContainer) {
            cardsContainer.innerHTML = `
                <div class="bg-[#1A1428] rounded-xl p-4 border border-[#2FE6DE]/10">
                    <div class="flex justify-center items-center py-8 text-gray-400">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-[#2FE6DE]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Loading market data...</span>
                    </div>
                </div>
            `;
        }
    }

    async loadStockData() {
        try {
            // Try to fetch real data from API
            const liveData = await this.fetchLiveData();
            if (liveData && liveData.length > 0) {
                this.stocks = liveData;
            } else {
                // Simulate realistic price movements on fallback data
                this.simulatePriceMovements();
            }

            // Display the data
            this.displayStocks();
            this.updateQuickStats();
            this.updateMarketStatus();

        } catch (error) {
            console.log('Using fallback data:', error.message);
            // Use fallback data with simulated movements
            this.simulatePriceMovements();
            this.displayStocks();
            this.updateQuickStats();
            this.updateMarketStatus();
        }
    }

    async fetchLiveData() {
        // Try different APIs in order of preference
        const apis = [
            () => this.fetchFromAlphaVantage(),
            () => this.fetchFromFinnhub(),
            () => this.fetchFromIEX()
        ];

        for (const apiCall of apis) {
            try {
                const data = await apiCall();
                if (data && data.length > 0) {
                    return data;
                }
            } catch (error) {
                console.log('API call failed, trying next...');
                continue;
            }
        }

        return null;
    }

    async fetchFromAlphaVantage() {
        // Note: This is a demo implementation
        // In production, replace with your actual API key and implement proper batch requests
        throw new Error('Alpha Vantage API not configured');
    }

    async fetchFromFinnhub() {
        // Note: This is a demo implementation
        // In production, replace with your actual API key
        throw new Error('Finnhub API not configured');
    }

    async fetchFromIEX() {
        // Note: This is a demo implementation
        // In production, replace with your actual API key
        throw new Error('IEX Cloud API not configured');
    }

    simulatePriceMovements() {
        // Simulate realistic price movements
        this.stocks = this.stocks.map(stock => {
            const volatility = Math.random() * 0.03; // 0-3% volatility
            const direction = Math.random() > 0.5 ? 1 : -1;
            const priceChange = stock.price * volatility * direction;

            return {
                ...stock,
                price: Math.max(0.01, stock.price + priceChange),
                change: priceChange,
                changePercent: (priceChange / stock.price) * 100,
                volume: stock.volume + Math.floor(Math.random() * 1000000)
            };
        });
    }

    simulateRealTimeUpdates() {
        // Update prices every 5 seconds to simulate real-time data
        setInterval(() => {
            this.simulatePriceMovements();
            this.displayStocks();
            this.updateQuickStats();
        }, 5000);
    }

    displayStocks() {
        this.displayTable();
        this.displayMobileCards();
    }

    displayTable() {
        const tableBody = document.getElementById('stock-market-table');
        if (!tableBody) return;

        let html = '';

        this.stocks.slice(0, 8).forEach((stock, index) => {
            const changeClass = stock.change >= 0 ? 'text-green-500' : 'text-red-500';
            const changeBgClass = stock.change >= 0 ? 'bg-green-500/10' : 'bg-red-500/10';
            const logoUrl = this.getStockLogo(stock.symbol);

            html += `
                <tr class="hover:bg-[#2FE6DE]/5 transition-colors animate-fadeIn">
                    <td class="px-6 py-4 text-gray-400 font-medium">${index + 1}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 mr-3 rounded-full bg-[#2FE6DE]/10 flex items-center justify-center overflow-hidden">
                                <img src="${logoUrl}" alt="${stock.symbol}" class="w-6 h-6"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-6 h-6 rounded-full bg-[#2FE6DE]/20 flex items-center justify-center" style="display:none">
                                    <span class="text-xs font-bold text-[#2FE6DE]">${stock.symbol.charAt(0)}</span>
                                </div>
                            </div>
                            <div>
                                <div class="font-medium text-white">${stock.name}</div>
                                <div class="text-gray-400 text-sm">${stock.symbol}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right font-mono font-medium text-white">
                        ${this.formatCurrency(stock.price)}
                    </td>
                    <td class="px-6 py-4 text-right font-mono ${changeClass}">
                        ${this.formatChange(stock.change)}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <span class="px-2 py-1 ${changeBgClass} ${changeClass} rounded-md font-mono text-sm">
                            ${this.formatPercentage(stock.changePercent)}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right text-gray-300 font-mono">
                        ${this.formatVolume(stock.volume)}
                    </td>
                    <td class="px-6 py-4 text-right text-gray-300 font-mono">
                        ${this.formatVolume(stock.marketCap)}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button class="px-4 py-1 bg-[#2FE6DE]/10 text-[#2FE6DE] rounded-lg hover:bg-[#2FE6DE]/20 transition-colors text-sm font-medium">
                            Trade
                        </button>
                    </td>
                </tr>
            `;
        });

        tableBody.innerHTML = html;
    }

    displayMobileCards() {
        const cardsContainer = document.getElementById('stock-market-cards');
        if (!cardsContainer) return;

        let html = '';

        this.stocks.slice(0, 8).forEach((stock, index) => {
            const changeClass = stock.change >= 0 ? 'text-green-500' : 'text-red-500';
            const logoUrl = this.getStockLogo(stock.symbol);

            html += `
                <div class="bg-[#1A1428] rounded-xl p-4 border border-[#2FE6DE]/10 hover:border-[#2FE6DE]/30 transition-all animate-fadeIn">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <div class="w-12 h-12 mr-3 flex-shrink-0 rounded-full bg-[#2FE6DE]/10 flex items-center justify-center overflow-hidden">
                                <img src="${logoUrl}" alt="${stock.symbol}" class="w-8 h-8"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-8 h-8 rounded-full bg-[#2FE6DE]/20 flex items-center justify-center" style="display:none">
                                    <span class="text-sm font-bold text-[#2FE6DE]">${stock.symbol.charAt(0)}</span>
                                </div>
                            </div>
                            <div>
                                <div class="font-medium text-white">${stock.name}</div>
                                <div class="text-gray-400 text-xs">${stock.symbol} • ${stock.sector}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-mono font-medium text-white">${this.formatCurrency(stock.price)}</div>
                            <div class="${changeClass} text-sm font-mono">${this.formatPercentage(stock.changePercent)}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div class="bg-[#0D091C]/50 rounded-lg p-2">
                            <div class="text-xs text-gray-400">Change</div>
                            <div class="text-sm font-medium font-mono ${changeClass}">${this.formatChange(stock.change)}</div>
                        </div>
                        <div class="bg-[#0D091C]/50 rounded-lg p-2">
                            <div class="text-xs text-gray-400">Volume</div>
                            <div class="text-sm font-medium font-mono text-gray-300">${this.formatVolume(stock.volume)}</div>
                        </div>
                    </div>

                    <button class="block w-full py-2 text-center bg-[#2FE6DE]/10 text-[#2FE6DE] rounded-lg hover:bg-[#2FE6DE]/20 transition-colors text-sm font-medium">
                        Trade ${stock.symbol}
                    </button>
                </div>
            `;
        });

        cardsContainer.innerHTML = html;
    }

    updateQuickStats() {
        const gainers = this.stocks.filter(stock => stock.change > 0).length;
        const losers = this.stocks.filter(stock => stock.change < 0).length;
        const totalVolume = this.stocks.reduce((sum, stock) => sum + stock.volume, 0);
        const avgChange = this.stocks.reduce((sum, stock) => sum + stock.changePercent, 0) / this.stocks.length;

        document.getElementById('gainers-count').textContent = gainers;
        document.getElementById('losers-count').textContent = losers;
        document.getElementById('total-volume').textContent = this.formatVolume(totalVolume);
        document.getElementById('avg-change').textContent = this.formatPercentage(avgChange);
    }

    updateMarketStatus() {
        const now = new Date();
        const marketOpen = new Date();
        marketOpen.setHours(9, 30, 0, 0); // 9:30 AM
        const marketClose = new Date();
        marketClose.setHours(16, 0, 0, 0); // 4:00 PM

        const isWeekday = now.getDay() >= 1 && now.getDay() <= 5;
        const isMarketHours = now >= marketOpen && now <= marketClose;
        const isOpen = isWeekday && isMarketHours;

        const statusElement = document.getElementById('market-status');
        const lastUpdatedElement = document.getElementById('last-updated');

        if (statusElement) {
            statusElement.innerHTML = isOpen ?
                'Market Status: <span class="text-green-400">Open</span>' :
                'Market Status: <span class="text-red-400">Closed</span>';
        }

        if (lastUpdatedElement) {
            lastUpdatedElement.textContent = `Updated: ${now.toLocaleTimeString()}`;
        }
    }

    getStockLogo(symbol) {
        const logoMap = {
            'AAPL': 'https://logo.clearbit.com/apple.com',
            'MSFT': 'https://logo.clearbit.com/microsoft.com',
            'GOOGL': 'https://logo.clearbit.com/google.com',
            'TSLA': 'https://logo.clearbit.com/tesla.com',
            'AMZN': 'https://logo.clearbit.com/amazon.com',
            'NVDA': 'https://logo.clearbit.com/nvidia.com',
            'META': 'https://logo.clearbit.com/meta.com',
            'BRK.B': 'https://logo.clearbit.com/berkshirehathaway.com'
        };

        return logoMap[symbol] || `https://via.placeholder.com/32/2FE6DE/000000?text=${symbol.charAt(0)}`;
    }

    formatCurrency(value) {
        return '$' + value.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    formatChange(value) {
        const sign = value >= 0 ? '+' : '';
        return `${sign}${value.toFixed(2)}`;
    }

    formatPercentage(value) {
        const sign = value >= 0 ? '+' : '';
        return `${sign}${value.toFixed(2)}%`;
    }

    formatVolume(value) {
        if (value >= 1e12) {
            return (value / 1e12).toFixed(1) + 'T';
        } else if (value >= 1e9) {
            return (value / 1e9).toFixed(1) + 'B';
        } else if (value >= 1e6) {
            return (value / 1e6).toFixed(1) + 'M';
        } else if (value >= 1e3) {
            return (value / 1e3).toFixed(1) + 'K';
        } else {
            return value.toLocaleString();
        }
    }
}

// Initialize the widget when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new StockMarketWidget();
});
</script>

<style>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeIn {
    animation: fadeIn 0.5s ease-out;
}

/* Table hover effects */
#stock-market-table tr:hover {
    background-color: rgba(47, 230, 222, 0.05);
}

/* Mobile card hover effects */
#stock-market-cards .bg-\[#1A1428\]:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(47, 230, 222, 0.1);
}

/* Loading animation */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Responsive font sizes */
@media (max-width: 640px) {
    .font-mono {
        font-size: 0.875rem;
    }
}
</style>

<!-- Investment Plans Section -->
<div class="py-16 bg-[#0A0714]">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">Trading <span class="text-[#2FE6DE]">Plans</span></h2>
            <p class="text-gray-400 max-w-2xl mx-auto">Select the trading plan that matches your experience level and investment goals. All plans include professional support and advanced trading tools.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="bg-[#1A1428] rounded-xl p-6 border border-[#2FE6DE]/10 hover:border-[#2FE6DE]/30 transition-all">

                    <div class="text-center mb-6">
                        <h3 class="text-xl font-semibold mb-2">Gold Plan</h3>
                        <div class="text-[#2FE6DE] text-4xl font-bold mb-2">$25,000<span class="text-lg text-gray-400">/7-2days</span></div>
                        <p class="text-gray-400">Beginner Trading Plan</p>
                        <p class="text-xs text-[#2FE6DE] mt-1">*Minimum $5,000</p>
                    </div>

                    <ul class="space-y-3 mb-8">

            <li class="flex items-center ">
            <i class="fas fa-check text-green-500 mr-3"></i>
            <span>Manual Trading support</span>
        </li>
            <li class="flex items-center ">
            <i class="fas fa-check text-green-500 mr-3"></i>
            <span>Live market webinars and strategy sessions</span>
        </li>
            <li class="flex items-center ">
            <i class="fas fa-check text-green-500 mr-3"></i>
            <span>Personal account manager</span>
        </li>
            <li class="flex items-center ">
            <i class="fas fa-check text-green-500 mr-3"></i>
            <span>VIP access to investment seminars and exclusive events</span>
        </li>
            <li class="flex items-center ">
            <i class="fas fa-check text-green-500 mr-3"></i>
            <span>Access to trading tools</span>
        </li>
            <li class="flex items-center ">
            <i class="fas fa-check text-green-500 mr-3"></i>
            <span>Unlimited trades</span>
        </li>
            <li class="flex items-center ">
            <i class="fas fa-check text-green-500 mr-3"></i>
            <span>Advanced market analysis</span>
        </li>
            <li class="flex items-center ">
            <i class="fas fa-check text-green-500 mr-3"></i>
            <span>24/7 dedicated support</span>
        </li>
    </ul>

                    <a href="{{ route('register') }}" class="block w-full py-3 text-center bg-[#2FE6DE]/10 text-[#2FE6DE] hover:bg-[#2FE6DE]/20 rounded-lg transition-all">
                        Get Started
                    </a>
                </div>
                            <div class="bg-[#1A1428] rounded-xl p-6 border border-[#2FE6DE]/10 hover:border-[#2FE6DE]/30 transition-all">

                    <div class="text-center mb-6">
                        <h3 class="text-xl font-semibold mb-2">VIP Plan</h3>
                        <div class="text-[#2FE6DE] text-4xl font-bold mb-2">$100,000<span class="text-lg text-gray-400">/7-4days</span></div>
                        <p class="text-gray-400">Advanced Trading Plan</p>
                        <p class="text-xs text-[#2FE6DE] mt-1">*Minimum $25,000</p>
                    </div>

                    <ul class="space-y-3 mb-8">

            <li class="flex items-center ">
            <i class="fas fa-check text-green-500 mr-3"></i>
            <span>Manual Trading support</span>
        </li>
            <li class="flex items-center ">
            <i class="fas fa-check text-green-500 mr-3"></i>
            <span>Live market webinars and strategy sessions</span>
        </li>
            <li class="flex items-center ">
            <i class="fas fa-check text-green-500 mr-3"></i>
            <span>Personal account manager</span>
        </li>
            <li class="flex items-center ">
            <i class="fas fa-check text-green-500 mr-3"></i>
            <span>VIP access to investment seminars and exclusive events</span>
        </li>
            <li class="flex items-center ">
            <i class="fas fa-check text-green-500 mr-3"></i>
            <span>Access to trading tools</span>
        </li>
            <li class="flex items-center ">
            <i class="fas fa-check text-green-500 mr-3"></i>
            <span>Unlimited trades</span>
        </li>
            <li class="flex items-center ">
            <i class="fas fa-check text-green-500 mr-3"></i>
            <span>Advanced market analysis</span>
        </li>
            <li class="flex items-center ">
            <i class="fas fa-check text-green-500 mr-3"></i>
            <span>24/7 dedicated support</span>
        </li>
    </ul>

                    <a href="{{ route('register') }}" class="block w-full py-3 text-center bg-[#2FE6DE]/10 text-[#2FE6DE] hover:bg-[#2FE6DE]/20 rounded-lg transition-all">
                        Get Started
                    </a>
                </div>
                            <div class="bg-[#1A1428] rounded-xl p-6 border-2 border-[#2FE6DE] relative transform hover:scale-105 transition-all">
                                            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-[#2FE6DE] text-black px-4 py-1 rounded-full text-sm font-medium">
                            Recommended
                        </div>

                    <div class="text-center mb-6">
                        <h3 class="text-xl font-semibold mb-2">Membership Card Plan</h3>
                        <div class="text-[#2FE6DE] text-4xl font-bold mb-2">$1,000,000<span class="text-lg text-gray-400">/7-4days</span></div>
                        <p class="text-gray-400">Professional Trading Plan</p>
                        <p class="text-xs text-[#2FE6DE] mt-1">*Minimum $100,000</p>
                    </div>

                    <ul class="space-y-3 mb-8">

            <li class="flex items-center ">
            <i class="fas fa-check text-green-500 mr-3"></i>
            <span>Manual Trading support</span>
        </li>
            <li class="flex items-center ">
            <i class="fas fa-check text-green-500 mr-3"></i>
            <span>Live market webinars and strategy sessions</span>
        </li>
            <li class="flex items-center ">
            <i class="fas fa-check text-green-500 mr-3"></i>
            <span>Personal account manager</span>
        </li>
            <li class="flex items-center ">
            <i class="fas fa-check text-green-500 mr-3"></i>
            <span>VIP access to investment seminars and exclusive events</span>
        </li>
            <li class="flex items-center ">
            <i class="fas fa-check text-green-500 mr-3"></i>
            <span>Access to trading tools</span>
        </li>
            <li class="flex items-center ">
            <i class="fas fa-check text-green-500 mr-3"></i>
            <span>Unlimited trades</span>
        </li>
            <li class="flex items-center ">
            <i class="fas fa-check text-green-500 mr-3"></i>
            <span>Advanced market analysis</span>
        </li>
            <li class="flex items-center ">
            <i class="fas fa-check text-green-500 mr-3"></i>
            <span>24/7 dedicated support</span>
        </li>
    </ul>

                    <a href="{{ route('register') }}" class="block w-full py-3 text-center bg-[#2FE6DE] text-black hover:brightness-110 rounded-lg transition-all">
                        Get Started
                    </a>
                </div>
                    </div>
    </div>
</div>

<!-- Trading Options Section -->
<div class="py-16 bg-gradient-to-b from-[#0A0714] to-[#0D091C]">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">Start Trading <span class="text-[#2FE6DE]">Now</span></h2>
            <p class="text-gray-400 max-w-2xl mx-auto">Choose how you want to trade and invest in stocks and financial markets</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
             <div class="bg-[#1A1428] rounded-xl p-6 border border-[#2FE6DE]/10 hover:border-[#2FE6DE]/30 transition-all">
                <div class="w-12 h-12 rounded-full bg-[#2FE6DE]/10 flex items-center justify-center mb-4">
                    <i class="fas fa-chart-line text-[#2FE6DE] text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Stock Trading</h3>
                <p class="text-gray-400 mb-4">Trade stocks with advanced charts and real-time market data</p>
                <a href="{{ route('login') }}" class="text-[#2FE6DE] hover:underline flex items-center text-sm">
                    Trade Now <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>

            <div class="bg-[#1A1428] rounded-xl p-6 border border-[#2FE6DE]/10 hover:border-[#2FE6DE]/30 transition-all">
                <div class="w-12 h-12 rounded-full bg-[#2FE6DE]/10 flex items-center justify-center mb-4">
                    <i class="fas fa-copy text-[#2FE6DE] text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Copy Trading</h3>
                <p class="text-gray-400 mb-4">Automatically copy the trades of successful traders</p>
                <a href="{{ route('login') }}" class="text-[#2FE6DE] hover:underline flex items-center text-sm">
                    Start Copying <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>

            <div class="bg-[#1A1428] rounded-xl p-6 border border-[#2FE6DE]/10 hover:border-[#2FE6DE]/30 transition-all">
                <div class="w-12 h-12 rounded-full bg-[#2FE6DE]/10 flex items-center justify-center mb-4">
                    <i class="fas fa-robot text-[#2FE6DE] text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Bot Trading</h3>
                <p class="text-gray-400 mb-4">Automate your trading with AI-powered bots and strategies</p>
                <a href="{{ route('login') }}" class="text-[#2FE6DE] hover:underline flex items-center text-sm">
                    Start Bot <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>


<!-- Testimonials Section -->
<div class="py-16 bg-gradient-to-b from-[#0A0714] to-[#0D091C]">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">What Our <span class="text-[#2FE6DE]">Traders Say</span></h2>
            <p class="text-gray-400 max-w-2xl mx-auto">Join thousands of successful traders who trust our platform for their trading needs.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-[#1A1428] rounded-xl p-6 border border-[#2FE6DE]/10">
                <div class="flex items-center mb-4">
                    <div class="flex text-[#2FE6DE]">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <p class="text-gray-300 mb-4">"The AI trading bots have completely transformed my trading strategy. I've seen consistent profits since I started using the platform."</p>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-[#2FE6DE]/20 flex items-center justify-center mr-3">
                        <span class="text-[#2FE6DE] font-bold">MJ</span>
                    </div>
                    <div>
                        <div class="font-medium text-white">Michael Johnson</div>
                        <div class="text-gray-400 text-sm">Professional Trader</div>
                    </div>
                </div>
            </div>

            <div class="bg-[#1A1428] rounded-xl p-6 border border-[#2FE6DE]/10">
                <div class="flex items-center mb-4">
                    <div class="flex text-[#2FE6DE]">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <p class="text-gray-300 mb-4">"Copy trading has been a game-changer for me. I can follow successful traders and learn from their strategies while earning profits."</p>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-[#2FE6DE]/20 flex items-center justify-center mr-3">
                        <span class="text-[#2FE6DE] font-bold">SC</span>
                    </div>
                    <div>
                        <div class="font-medium text-white">Sarah Chen</div>
                        <div class="text-gray-400 text-sm">Copy Trader</div>
                    </div>
                </div>
            </div>

            <div class="bg-[#1A1428] rounded-xl p-6 border border-[#2FE6DE]/10">
                <div class="flex items-center mb-4">
                    <div class="flex text-[#2FE6DE]">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <p class="text-gray-300 mb-4">"The security features and customer support are outstanding. I feel confident trading with my funds on this platform."</p>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-[#2FE6DE]/20 flex items-center justify-center mr-3">
                        <span class="text-[#2FE6DE] font-bold">DR</span>
                    </div>
                    <div>
                        <div class="font-medium text-white">David Rodriguez</div>
                        <div class="text-gray-400 text-sm">Portfolio Manager</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- CTA Section -->
<div class="py-16 bg-[#0A0714] relative overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 bg-[#2FE6DE]/5 rounded-full filter blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-[#2FE6DE]/5 rounded-full filter blur-3xl"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="bg-gradient-to-r from-[#1A1428] to-[#0D091C] rounded-2xl p-8 md:p-12 border border-[#2FE6DE]/10 shadow-xl">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">Ready to Start Your Trading Journey?</h2>
                <p class="text-gray-300 text-lg mb-10 max-w-2xl mx-auto">
                    Join thousands of traders worldwide and experience the power of our advanced trading platform.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('register') }}" class="bg-[#2FE6DE] text-black font-medium px-8 py-4 rounded-lg hover:brightness-110 transition-all text-center">
                        Create Account
                    </a>
                    <a href="{{ route('about') }}" class="bg-transparent text-white font-medium px-8 py-4 rounded-lg border border-[#2FE6DE]/30 hover:bg-[#2FE6DE] transition-colors text-center">
                        About Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Real-time Stock API Integration -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fetch stock data
    fetchStockData();

    // Initialize counters
    initCounters();

    // Refresh data every 60 seconds
    setInterval(fetchStockData, 60000);
});

async function fetchStockData() {
    try {
        // Using mock data for demonstration - replace with your preferred stock API
        const mockStockData = [
            {
                symbol: 'AAPL',
                name: 'Apple Inc.',
                price: 175.25,
                change: 2.45,
                changePercent: 1.42,
                volume: 65432100,
                marketCap: 2786543210000,
                logo: 'https://logo.clearbit.com/apple.com'
            },
            {
                symbol: 'MSFT',
                name: 'Microsoft Corporation',
                price: 384.50,
                change: -1.25,
                changePercent: -0.32,
                volume: 32156789,
                marketCap: 2856432100000,
                logo: 'https://logo.clearbit.com/microsoft.com'
            },
            {
                symbol: 'GOOGL',
                name: 'Alphabet Inc.',
                price: 142.15,
                change: 0.85,
                changePercent: 0.60,
                volume: 28765432,
                marketCap: 1786543210000,
                logo: 'https://logo.clearbit.com/google.com'
            },
            {
                symbol: 'TSLA',
                name: 'Tesla, Inc.',
                price: 248.75,
                change: 5.20,
                changePercent: 2.13,
                volume: 89543210,
                marketCap: 789876543210,
                logo: 'https://logo.clearbit.com/tesla.com'
            },
            {
                symbol: 'AMZN',
                name: 'Amazon.com Inc.',
                price: 156.80,
                change: -0.95,
                changePercent: -0.60,
                volume: 45321098,
                marketCap: 1623456789012,
                logo: 'https://logo.clearbit.com/amazon.com'
            },
            {
                symbol: 'NVDA',
                name: 'NVIDIA Corporation',
                price: 875.30,
                change: 15.75,
                changePercent: 1.83,
                volume: 76543210,
                marketCap: 2156789012345,
                logo: 'https://logo.clearbit.com/nvidia.com'
            }
        ];

        // For production, replace with actual API call:
        /*
        const response = await fetch('https://api.finnhub.io/api/v1/stock/symbol?exchange=US&token=YOUR_API_KEY');
        const data = await response.json();
        */

        // Update the market overview table and cards
        updateMarketTable(mockStockData);
        updateMarketCards(mockStockData);

    } catch (error) {
        console.error('Error fetching stock data:', error);

        // Show error message in table
        document.querySelector('#stock-market-table').innerHTML = `
            <tr>
                <td colspan="8" class="px-6 py-8 text-center text-red-400">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Unable to load market data. Please try again later.
                </td>
            </tr>
        `;

        // Show error message in cards
        document.querySelector('#stock-market-cards').innerHTML = `
            <div class="bg-[#1A1428] rounded-xl p-4 border border-[#2FE6DE]/10">
                <div class="flex justify-center items-center py-8 text-red-400">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>Unable to load market data. Please try again later.</span>
                </div>
            </div>
        `;
    }
}

function updateMarketTable(data) {
    const tableBody = document.querySelector('#stock-market-table');
    if (!tableBody) return;

    let tableHTML = '';

    // Generate table rows for each stock
    data.slice(0, 6).forEach((stock, index) => {
        const priceChangeClass = stock.change >= 0 ? 'text-green-500' : 'text-red-500';
        const priceChangeBgClass = stock.change >= 0 ? 'bg-green-500/10' : 'bg-red-500/10';

        tableHTML += `
            <tr class="hover:bg-[#2FE6DE]/5 transition-colors">
                <td class="px-6 py-4 text-gray-400">${index + 1}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 mr-3 rounded-full bg-[#2FE6DE]/10 flex items-center justify-center overflow-hidden">
                            <img src="${stock.logo}" alt="${stock.symbol}" class="w-6 h-6" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <span class="text-xs font-bold text-[#2FE6DE]" style="display:none">${stock.symbol.charAt(0)}</span>
                        </div>
                        <div>
                            <div class="font-medium">${stock.name}</div>
                            <div class="text-gray-400 text-sm">${stock.symbol}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-right font-medium">${formatCurrency(stock.price)}</td>
                <td class="px-6 py-4 text-right">
                    <span class="${priceChangeClass}">${formatChange(stock.change)}</span>
                </td>
                <td class="px-6 py-4 text-right">
                    <span class="px-2 py-1 ${priceChangeBgClass} ${priceChangeClass} rounded-md">${formatPercentage(stock.changePercent)}</span>
                </td>
                <td class="px-6 py-4 text-right text-gray-300">${formatVolume(stock.volume)}</td>
                <td class="px-6 py-4 text-right text-gray-300">${formatVolume(stock.marketCap)}</td>
                <td class="px-6 py-4 text-right">
                    <a href="#" class="px-4 py-1 bg-[#2FE6DE]/10 text-[#2FE6DE] rounded-lg hover:bg-[#2FE6DE]/20 transition-colors text-sm">Trade</a>
                </td>
            </tr>
        `;
    });

    tableBody.innerHTML = tableHTML;
}

function updateMarketCards(data) {
    const cardsContainer = document.querySelector('#stock-market-cards');
    if (!cardsContainer) return;

    let cardsHTML = '';

    // Generate card for each stock
    data.slice(0, 6).forEach((stock, index) => {
        const priceChangeClass = stock.change >= 0 ? 'text-green-500' : 'text-red-500';

        cardsHTML += `
            <div class="bg-[#1A1428] rounded-xl p-4 border border-[#2FE6DE]/10 hover:border-[#2FE6DE]/30 transition-all">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center">
                        <div class="w-10 h-10 mr-3 flex-shrink-0 rounded-full bg-[#2FE6DE]/10 flex items-center justify-center overflow-hidden">
                            <img src="${stock.logo}" alt="${stock.symbol}" class="w-6 h-6" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <span class="text-xs font-bold text-[#2FE6DE]" style="display:none">${stock.symbol.charAt(0)}</span>
                        </div>
                        <div>
                            <div class="font-medium">${stock.name}</div>
                            <div class="text-gray-400 text-xs">${stock.symbol}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-medium">${formatCurrency(stock.price)}</div>
                        <div class="${priceChangeClass} text-sm">${formatPercentage(stock.changePercent)}</div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 mb-3">
                    <div class="bg-[#0D091C]/50 rounded-lg p-2">
                        <div class="text-xs text-gray-400">Change</div>
                        <div class="text-sm font-medium ${priceChangeClass}">${formatChange(stock.change)}</div>
                    </div>
                    <div class="bg-[#0D091C]/50 rounded-lg p-2">
                        <div class="text-xs text-gray-400">Volume</div>
                        <div class="text-sm font-medium">${formatVolume(stock.volume)}</div>
                    </div>
                </div>

                <a href="#" class="block w-full py-2 text-center bg-[#2FE6DE]/10 text-[#2FE6DE] rounded-lg hover:bg-[#2FE6DE]/20 transition-colors text-sm">Trade</a>
            </div>
        `;
    });

    cardsContainer.innerHTML = cardsHTML;
}

function initCounters() {
    const counters = document.querySelectorAll('.counter');

    counters.forEach(counter => {
        const target = parseInt(counter.textContent.replace(/,/g, ''));
        const increment = target / 100;

        let current = 0;
        const updateCounter = () => {
            if (current < target) {
                current += increment;
                counter.textContent = Math.ceil(current).toLocaleString('en-US');
                setTimeout(updateCounter, 10);
            } else {
                counter.textContent = target.toLocaleString('en-US');
            }
        };

        // Start the counter animation when the element is in view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    updateCounter();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        observer.observe(counter);
    });
}

// Helper functions for formatting
function formatCurrency(value) {
    return '$' + value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function formatChange(value) {
    const sign = value >= 0 ? '+' : '';
    return `${sign}${value.toFixed(2)}`;
}

function formatPercentage(value) {
    const sign = value >= 0 ? '+' : '';
    return `${sign}${value.toFixed(2)}%`;
}

function formatVolume(value) {
    if (value >= 1e12) {
        return (value / 1e12).toFixed(1) + 'T';
    } else if (value >= 1e9) {
        return (value / 1e9).toFixed(1) + 'B';
    } else if (value >= 1e6) {
        return (value / 1e6).toFixed(1) + 'M';
    } else if (value >= 1e3) {
        return (value / 1e3).toFixed(1) + 'K';
    } else {
        return value.toFixed(0);
    }
}

// Hero Slider Functionality
function initHeroSlider() {
    const slides = document.querySelectorAll('.hero-slide');
    const illustrations = document.querySelectorAll('.hero-illustration');
    const dots = document.querySelectorAll('.hero-nav-dot');
    let currentSlide = 1;
    let slideInterval;

    // Function to change slide
    function goToSlide(slideNumber) {
        // Update content slides
        slides.forEach(slide => {
            slide.classList.remove('active');
            if (slide.dataset.slide == slideNumber) {
                slide.classList.add('active');
            }
        });

        // Update illustrations
        illustrations.forEach(illustration => {
            illustration.classList.remove('active');
            if (illustration.dataset.slide == slideNumber) {
                illustration.classList.add('active');
            }
        });

        // Update navigation dots
        dots.forEach(dot => {
            dot.classList.remove('active');
            dot.querySelector('span').classList.remove('bg-[#2FE6DE]');
            dot.querySelector('span').classList.add('bg-[#2FE6DE]/30');

            if (dot.dataset.slide == slideNumber) {
                dot.classList.add('active');
                dot.querySelector('span').classList.remove('bg-[#2FE6DE]/30');
                dot.querySelector('span').classList.add('bg-[#2FE6DE]');
            }
        });

        currentSlide = parseInt(slideNumber);
    }

    // Set up click handlers for dots
    dots.forEach(dot => {
        dot.addEventListener('click', function() {
            const slideNumber = this.dataset.slide;
            goToSlide(slideNumber);
            resetInterval();
        });
    });

    // Auto-advance slides
    function startInterval() {
        slideInterval = setInterval(() => {
            let nextSlide = currentSlide + 1;
            if (nextSlide > slides.length) {
                nextSlide = 1;
            }
            goToSlide(nextSlide);
        }, 5000);
    }

    function resetInterval() {
        clearInterval(slideInterval);
        startInterval();
    }

    // Initialize the slider
    startInterval();
}

// Initialize hero slider when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize hero slider
    initHeroSlider();
});
</script>

<style>
/* Button Styles */
.btn-primary {
    display: inline-block;
    background-color: rgba(47, 230, 222, 0.1);
    color: #2FE6DE;
    border-radius: 0.5rem;
    transition: all 0.2s;
}

.btn-primary:hover {
    background-color: rgba(47, 230, 222, 0.2);
}

.btn-secondary {
    display: inline-block;
    background-color: rgba(47, 230, 222, 0.1);
    color: #2FE6DE;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    border: 1px solid rgba(47, 230, 222, 0.3);
    transition: all 0.2s;
}

.btn-secondary:hover {
    background-color: rgba(47, 230, 222, 0.2);
    border-color: rgba(47, 230, 222, 0.5);
}

/* Animation */
@keyframes float {
    0% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
    100% {
        transform: translateY(0px);
    }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

/* Hero Slider Styles */
.hero-slide {
    display: none;
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
}

.hero-slide.active {
    display: block;
    opacity: 1;
}

.hero-illustration {
    display: none;
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
}

.hero-illustration.active {
    display: block;
    opacity: 1;
    animation: float 6s ease-in-out infinite;
}

.hero-nav-dot {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.hero-nav-dot span {
    transition: all 0.3s ease;
}

.hero-nav-dot:hover span {
    background-color: rgba(47, 230, 222, 0.8) !important;
}
</style>
    </main>

 @endsection
