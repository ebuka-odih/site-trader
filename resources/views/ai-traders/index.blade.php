@extends('pages.layout.app')

@section('title', 'AI Stock Traders - Advanced AI-Powered Trading')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-[#0A0714] via-[#0D091C] to-[#1A1428] text-white py-20 relative overflow-hidden">
    <!-- Background elements -->
    <div class="absolute top-20 right-20 w-64 h-64 bg-[#2FE6DE]/10 rounded-full filter blur-3xl"></div>
    <div class="absolute bottom-10 left-10 w-80 h-80 bg-[#2FE6DE]/5 rounded-full filter blur-3xl"></div>
    
    <div class="w-full px-4 relative z-10">
        <div class="max-w-6xl mx-auto text-center">
            <h1 class="text-5xl font-bold mb-6">
                <span class="text-white">🤖 AI Stock</span><br>
                <span class="text-[#2FE6DE]">Traders</span>
            </h1>
            <p class="text-xl mb-8 text-gray-300">
                Advanced AI-powered stock trading with cutting-edge machine learning models. 
                Let artificial intelligence optimize your stock portfolio with precision and speed.
            </p>
            <div class="flex flex-wrap justify-center gap-4 text-sm">
                <div class="bg-[#2FE6DE]/10 backdrop-blur-sm rounded-lg px-4 py-2 border border-[#2FE6DE]/20">
                    <span class="font-semibold text-[#2FE6DE]">GPT-4o</span> <span class="text-gray-300">Powered</span>
                </div>
                <div class="bg-[#2FE6DE]/10 backdrop-blur-sm rounded-lg px-4 py-2 border border-[#2FE6DE]/20">
                    <span class="font-semibold text-[#2FE6DE]">Real-time</span> <span class="text-gray-300">Analysis</span>
                </div>
                <div class="bg-[#2FE6DE]/10 backdrop-blur-sm rounded-lg px-4 py-2 border border-[#2FE6DE]/20">
                    <span class="font-semibold text-[#2FE6DE]">24/7</span> <span class="text-gray-300">Trading</span>
                </div>
                <div class="bg-[#2FE6DE]/10 backdrop-blur-sm rounded-lg px-4 py-2 border border-[#2FE6DE]/20">
                    <span class="font-semibold text-[#2FE6DE]">Risk</span> <span class="text-gray-300">Managed</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- AI Trader Plans Section -->
<section class="py-16 bg-[#0A0714]">
    <div class="w-full px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-white mb-4">Choose Your AI Trading Plan</h2>
            <p class="text-lg text-gray-300 max-w-2xl mx-auto">
                Select from our range of AI-powered trading plans, each designed for different investment levels and trading strategies.
            </p>
        </div>

        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($plans as $plan)
            <div class="bg-[#1A1428] rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-[#2FE6DE]/20 hover:border-[#2FE6DE]/40">
                <!-- Plan Header -->
                <div class="bg-gradient-to-r from-[#2FE6DE] to-[#2FE6DE]/80 text-[#0A0714] p-6">
                    <h3 class="text-xl font-bold mb-2">{{ $plan->name }}</h3>
                    <div class="text-3xl font-bold mb-2">{{ $plan->formatted_price }}</div>
                    <p class="text-[#0A0714]/80 text-sm">{{ $plan->number_of_traders }} AI Trader{{ $plan->number_of_traders > 1 ? 's' : '' }}</p>
                </div>

                <!-- Plan Details -->
                <div class="p-6">
                    <p class="text-gray-300 mb-4">{{ $plan->description }}</p>
                    
                    <!-- Investment Amount -->
                    <div class="mb-4">
                        <div class="text-sm text-gray-400 mb-1">Minimum Investment</div>
                        <div class="text-lg font-semibold text-white">${{ number_format($plan->investment_amount, 0) }}</div>
                    </div>

                    <!-- Stocks Trading -->
                    <div class="mb-4">
                        <div class="text-sm text-gray-400 mb-2">Market Covered</div>
                        <div class="flex flex-wrap gap-1">
                            @foreach($plan->stocks_trading as $stock)
                            <span class="bg-[#2FE6DE]/20 text-[#2FE6DE] text-xs px-2 py-1 rounded border border-[#2FE6DE]/30">{{ $stock }}</span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Features -->
                    @if($plan->features)
                    <div class="mb-6">
                        <div class="text-sm text-gray-400 mb-2">Features</div>
                        <ul class="space-y-1">
                            @foreach($plan->features as $feature)
                            <li class="text-sm text-gray-300 flex items-center">
                                <svg class="w-4 h-4 text-[#2FE6DE] mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $feature }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Action Button -->
                    <a href="{{ route('ai-traders.plan', $plan) }}" 
                       class="w-full bg-gradient-to-r from-[#2FE6DE] to-[#2FE6DE]/80 text-[#0A0714] py-3 px-4 rounded-lg font-semibold hover:from-[#2FE6DE]/90 hover:to-[#2FE6DE]/70 transition-all duration-300 text-center block">
                        View AI Traders
                    </a>
                </div>
            </div>
            @endforeach
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-16 bg-[#0D091C]">
    <div class="w-full px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-white mb-4">How AI Trading Works</h2>
            <p class="text-lg text-gray-300 max-w-2xl mx-auto">
                Our AI traders use advanced machine learning to analyze market data and execute trades automatically.
            </p>
        </div>

        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="bg-[#2FE6DE]/20 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 border border-[#2FE6DE]/30">
                    <svg class="w-8 h-8 text-[#2FE6DE]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2 text-white">1. AI Analysis</h3>
                <p class="text-gray-300">Advanced AI models analyze market data, news sentiment, and technical indicators in real-time.</p>
            </div>

            <div class="text-center">
                <div class="bg-[#2FE6DE]/20 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 border border-[#2FE6DE]/30">
                    <svg class="w-8 h-8 text-[#2FE6DE]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2 text-white">2. Smart Execution</h3>
                <p class="text-gray-300">AI traders execute trades automatically based on predefined strategies and risk parameters.</p>
            </div>

            <div class="text-center">
                <div class="bg-[#2FE6DE]/20 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 border border-[#2FE6DE]/30">
                    <svg class="w-8 h-8 text-[#2FE6DE]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2 text-white">3. Monitor & Optimize</h3>
                <p class="text-gray-300">Track performance, adjust strategies, and let AI continuously optimize your trading approach.</p>
            </div>
        </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-[#0A0714]">
    <div class="w-full px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-white mb-4">Why Choose Our AI Traders?</h2>
        </div>

        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-[#1A1428] p-6 rounded-lg shadow-sm border border-[#2FE6DE]/20 hover:border-[#2FE6DE]/40 transition-all duration-300">
                <div class="text-2xl mb-3">🧠</div>
                <h3 class="font-semibold mb-2 text-white">Advanced AI Models</h3>
                <p class="text-sm text-gray-300">GPT-4o, Claude 3.5 Sonnet, and other cutting-edge AI models power our trading decisions.</p>
            </div>

            <div class="bg-[#1A1428] p-6 rounded-lg shadow-sm border border-[#2FE6DE]/20 hover:border-[#2FE6DE]/40 transition-all duration-300">
                <div class="text-2xl mb-3">📊</div>
                <h3 class="font-semibold mb-2 text-white">Real-time Analysis</h3>
                <p class="text-sm text-gray-300">Continuous market monitoring and analysis for optimal trading opportunities.</p>
            </div>

            <div class="bg-[#1A1428] p-6 rounded-lg shadow-sm border border-[#2FE6DE]/20 hover:border-[#2FE6DE]/40 transition-all duration-300">
                <div class="text-2xl mb-3">🛡️</div>
                <h3 class="font-semibold mb-2 text-white">Risk Management</h3>
                <p class="text-sm text-gray-300">Built-in stop-loss, take-profit, and position sizing to protect your investments.</p>
            </div>

            <div class="bg-[#1A1428] p-6 rounded-lg shadow-sm border border-[#2FE6DE]/20 hover:border-[#2FE6DE]/40 transition-all duration-300">
                <div class="text-2xl mb-3">⚡</div>
                <h3 class="font-semibold mb-2 text-white">24/7 Trading</h3>
                <p class="text-sm text-gray-300">AI traders work around the clock, never missing profitable opportunities.</p>
            </div>
        </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-[#2FE6DE] to-[#2FE6DE]/80 text-[#0A0714] relative overflow-hidden">
    <!-- Background elements -->
    <div class="absolute top-10 right-10 w-32 h-32 bg-[#0A0714]/10 rounded-full filter blur-2xl"></div>
    <div class="absolute bottom-10 left-10 w-40 h-40 bg-[#0A0714]/5 rounded-full filter blur-2xl"></div>
    
    <div class="w-full px-4 text-center relative z-10">
        <h2 class="text-3xl font-bold mb-4">Ready to Start AI Trading?</h2>
        <p class="text-xl mb-8 text-[#0A0714]/80">Join thousands of investors using AI to optimize their stock portfolios.</p>
        <a href="#plans" class="bg-[#0A0714] text-[#2FE6DE] px-8 py-3 rounded-lg font-semibold hover:bg-[#0A0714]/90 transition-colors duration-300 border border-[#0A0714] hover:border-[#2FE6DE]/50">
            Get Started Now
        </a>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>
@endpush
