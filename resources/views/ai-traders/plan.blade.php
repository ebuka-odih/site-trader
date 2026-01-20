@extends('pages.layout.app')

@section('title', $plan->name . ' - AI Stock Traders')

@section('content')
<!-- Plan Header -->
<section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
    <div class="w-full px-4">
        <div class="max-w-6xl mx-auto">
            <div class="flex items-center mb-4">
                <a href="{{ route('ai-traders.index') }}" class="text-blue-200 hover:text-white mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <span class="text-blue-200">Back to AI Traders</span>
            </div>
            
            <h1 class="text-4xl font-bold mb-4">{{ $plan->name }}</h1>
            <p class="text-xl text-blue-100 mb-6">{{ $plan->description }}</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="text-2xl font-bold">{{ $plan->formatted_price }}</div>
                    <div class="text-blue-200 text-sm">Monthly Subscription</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="text-2xl font-bold">{{ $plan->number_of_traders }}</div>
                    <div class="text-blue-200 text-sm">AI Traders Available</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="text-2xl font-bold">${{ number_format($plan->investment_amount, 0) }}</div>
                    <div class="text-blue-200 text-sm">Minimum Investment</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- AI Traders Grid -->
<section class="py-16 bg-gray-50">
    <div class="w-full px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Available AI Traders</h2>
            <p class="text-lg text-gray-600">Choose from our carefully selected AI traders, each with unique strategies and performance records.</p>
        </div>

        @if($traders->count() > 0)
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($traders as $trader)
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
                <!-- Trader Header -->
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 text-white p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xl font-bold">{{ $trader->name }}</h3>
                        <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                            {{ ucfirst($trader->trading_strategy) }}
                        </span>
                    </div>
                    <div class="flex items-center text-sm text-gray-300">
                        <span class="bg-purple-600 text-white px-2 py-1 rounded text-xs mr-2">{{ $trader->ai_model }}</span>
                        <span class="bg-blue-600 text-white px-2 py-1 rounded text-xs">{{ $trader->ai_confidence }}</span>
                    </div>
                </div>

                <!-- Performance Metrics -->
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold {{ $trader->current_performance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $trader->formatted_performance }}
                            </div>
                            <div class="text-sm text-gray-500">Performance</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $trader->formatted_win_rate }}</div>
                            <div class="text-sm text-gray-500">Win Rate</div>
                        </div>
                    </div>

                    <!-- Trading Stats -->
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Total Trades:</span>
                            <span class="font-medium">{{ $trader->total_trades }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Winning Trades:</span>
                            <span class="font-medium text-green-600">{{ $trader->winning_trades }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Max Positions:</span>
                            <span class="font-medium">{{ $trader->max_positions }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Position Size:</span>
                            <span class="font-medium">{{ $trader->position_size_percentage }}%</span>
                        </div>
                    </div>

                    <!-- Stocks Trading -->
                    <div class="mb-4">
                        <div class="text-sm text-gray-500 mb-2">Stocks Trading:</div>
                        <div class="flex flex-wrap gap-1">
                            @foreach($trader->stocks_to_trade as $stock)
                            <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">{{ $stock }}</span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Risk Management -->
                    <div class="mb-6">
                        <div class="text-sm text-gray-500 mb-2">Risk Management:</div>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="bg-red-50 text-red-700 px-2 py-1 rounded text-center">
                                Stop Loss: {{ $trader->stop_loss_percentage }}%
                            </div>
                            <div class="bg-green-50 text-green-700 px-2 py-1 rounded text-center">
                                Take Profit: {{ $trader->take_profit_percentage }}%
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <a href="{{ route('ai-traders.trader', $trader) }}" 
                       class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 text-center block group-hover:shadow-lg">
                        View Details & Subscribe
                    </a>
                </div>
            </div>
            @endforeach
            </div>
        </div>
        @else
        <div class="text-center py-12">
            <div class="text-6xl mb-4">🤖</div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No AI Traders Available</h3>
            <p class="text-gray-600">This plan doesn't have any active AI traders at the moment.</p>
        </div>
        @endif
    </div>
</section>

<!-- Plan Features -->
@if($plan->features)
<section class="py-16 bg-white">
    <div class="w-full px-4">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Plan Features</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($plan->features as $feature)
                <div class="flex items-start">
                    <div class="bg-green-100 w-8 h-8 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">{{ $feature }}</h3>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
    <div class="w-full px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Start with {{ $plan->name }}?</h2>
        <p class="text-xl mb-8 text-blue-100">Join thousands of investors using AI to optimize their stock portfolios.</p>
        <a href="#traders" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors duration-300">
            Choose Your AI Trader
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
