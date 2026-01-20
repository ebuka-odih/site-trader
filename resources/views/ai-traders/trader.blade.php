@extends('pages.layout.app')

@section('title', $trader->name . ' - AI Stock Trader Details')

@section('content')
<!-- Trader Header -->
<section class="bg-gradient-to-r from-gray-800 to-gray-900 text-white py-16">
    <div class="w-full px-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center mb-4">
                <a href="{{ route('ai-traders.plan', $trader->aiTraderPlan) }}" class="text-gray-300 hover:text-white mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <span class="text-gray-300">{{ $trader->aiTraderPlan ? $trader->aiTraderPlan->name : 'No Plan' }}</span>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div>
                    <h1 class="text-4xl font-bold mb-4">{{ $trader->name }}</h1>
                    <p class="text-xl text-gray-300 mb-6">Advanced AI-powered stock trading with {{ $trader->ai_model }}</p>
                    
                    <div class="flex flex-wrap gap-3 mb-6">
                        <span class="bg-purple-600 text-white px-3 py-1 rounded-full text-sm">{{ $trader->ai_model }}</span>
                        <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm">{{ ucfirst($trader->trading_strategy) }}</span>
                        <span class="bg-green-600 text-white px-3 py-1 rounded-full text-sm">{{ ucfirst($trader->ai_confidence) }} Confidence</span>
                        <span class="bg-orange-600 text-white px-3 py-1 rounded-full text-sm">{{ ucfirst($trader->ai_learning_mode) }} Learning</span>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-center">
                        <div class="text-3xl font-bold {{ $trader->current_performance >= 0 ? 'text-green-400' : 'text-red-400' }}">
                            {{ $trader->formatted_performance }}
                        </div>
                        <div class="text-gray-300 text-sm">Current Performance</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-center">
                        <div class="text-3xl font-bold text-blue-400">{{ $trader->formatted_win_rate }}</div>
                        <div class="text-gray-300 text-sm">Win Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Performance Chart -->
<section class="py-16 bg-white">
    <div class="w-full px-4">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Performance Overview</h2>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <canvas id="performanceChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</section>

<!-- Trader Details -->
<section class="py-16 bg-gray-50">
    <div class="w-full px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Trading Configuration</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-3">AI Settings</h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">AI Model:</span>
                                        <span class="font-medium">{{ $trader->ai_model }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Confidence Level:</span>
                                        <span class="font-medium">{{ ucfirst($trader->ai_confidence) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Learning Mode:</span>
                                        <span class="font-medium">{{ ucfirst($trader->ai_learning_mode) }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-3">Trading Strategy</h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Strategy:</span>
                                        <span class="font-medium">{{ ucfirst($trader->trading_strategy) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Risk Tolerance:</span>
                                        <span class="font-medium">{{ $trader->risk_tolerance }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Max Positions:</span>
                                        <span class="font-medium">{{ $trader->max_positions }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <h4 class="font-semibold text-gray-900 mb-3">Stocks Trading</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($trader->stocks_to_trade as $stock)
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">{{ $stock }}</span>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <h4 class="font-semibold text-gray-900 mb-3">Risk Management</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                                    <div class="text-2xl font-bold text-red-600">{{ $trader->stop_loss_percentage }}%</div>
                                    <div class="text-sm text-red-700">Stop Loss</div>
                                </div>
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                                    <div class="text-2xl font-bold text-green-600">{{ $trader->take_profit_percentage }}%</div>
                                    <div class="text-sm text-green-700">Take Profit</div>
                                </div>
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                                    <div class="text-2xl font-bold text-blue-600">{{ $trader->position_size_percentage }}%</div>
                                    <div class="text-sm text-blue-700">Position Size</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Stats & Actions -->
                <div>
                    <!-- Trading Statistics -->
                    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Trading Statistics</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Total Trades:</span>
                                <span class="font-semibold">{{ $trader->total_trades }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Winning Trades:</span>
                                <span class="font-semibold text-green-600">{{ $trader->winning_trades }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Losing Trades:</span>
                                <span class="font-semibold text-red-600">{{ $trader->total_trades - $trader->winning_trades }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Win Rate:</span>
                                <span class="font-semibold">{{ $trader->formatted_win_rate }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Current Performance:</span>
                                <span class="font-semibold {{ $trader->current_performance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $trader->formatted_performance }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Plan Info -->
                    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Plan Information</h3>
                        <div class="space-y-3">
                            <div>
                                <div class="text-sm text-gray-500">Plan Name</div>
                                <div class="font-semibold">{{ $trader->aiTraderPlan ? $trader->aiTraderPlan->name : 'No Plan' }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Monthly Cost</div>
                                <div class="font-semibold text-2xl text-blue-600">{{ $trader->aiTraderPlan ? $trader->aiTraderPlan->formatted_price : 'N/A' }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Minimum Investment</div>
                                <div class="font-semibold">${{ $trader->aiTraderPlan ? number_format($trader->aiTraderPlan->investment_amount, 0) : 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Subscribe Button -->
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-6 text-white text-center">
                        <h3 class="text-xl font-bold mb-2">Ready to Subscribe?</h3>
                        <p class="text-blue-100 mb-4">Start using this AI trader to optimize your stock portfolio.</p>
                        <button class="w-full bg-white text-blue-600 py-3 px-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors duration-300">
                            Subscribe to {{ $trader->aiTraderPlan ? $trader->aiTraderPlan->name : 'Plan' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Similar Traders -->
@if($similarTraders->count() > 0)
<section class="py-16 bg-white">
    <div class="w-full px-4">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Similar AI Traders</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($similarTraders as $similarTrader)
                <div class="bg-gray-50 rounded-xl p-6 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-bold text-gray-900">{{ $similarTrader->name }}</h3>
                        <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                            {{ $similarTrader->formatted_performance }}
                        </span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600 mb-3">
                        <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs mr-2">{{ $similarTrader->ai_model }}</span>
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">{{ ucfirst($similarTrader->trading_strategy) }}</span>
                    </div>
                    <div class="text-sm text-gray-600 mb-4">
                        Win Rate: <span class="font-semibold">{{ $similarTrader->formatted_win_rate }}</span>
                    </div>
                    <a href="{{ route('ai-traders.trader', $similarTrader) }}" 
                       class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                        View Details →
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Performance Chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('performanceChart').getContext('2d');
    
    // Fetch performance data
    fetch('{{ route("ai-traders.performance", $trader) }}')
        .then(response => response.json())
        .then(data => {
            new Chart(ctx, {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: '8-Week Performance History'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        }
                    },
                    elements: {
                        point: {
                            radius: 4,
                            hoverRadius: 6
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error loading performance data:', error);
            // Fallback chart with sample data
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6', 'Week 7', 'Week 8'],
                    datasets: [{
                        label: 'Performance (%)',
                        data: [2.1, 4.3, 6.8, 8.2, 11.5, 14.7, 18.3, {{ $trader->current_performance }}],
                        borderColor: '{{ $trader->current_performance >= 0 ? "#10B981" : "#EF4444" }}',
                        backgroundColor: '{{ $trader->current_performance >= 0 ? "rgba(16, 185, 129, 0.1)" : "rgba(239, 68, 68, 0.1)" }}',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: '8-Week Performance History'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        }
                    },
                    elements: {
                        point: {
                            radius: 4,
                            hoverRadius: 6
                        }
                    }
                }
            });
        });
});
</script>
@endpush
