@extends('dashboard.new-layout')

@section('content')
@php
    $accountTabs = $accountTabs ?? [];
    $timeRanges = ['1D', '1W', '1M', '3M', '1Y', 'All'];
    $watchlist = ($stockAssets ?? collect())->take(12);
    $accountTabsCollection = collect($accountTabs);
    $investingTab = $accountTabsCollection->firstWhere('id', 'investing') ?? $accountTabsCollection->first();
    $pnlTab = $accountTabsCollection->firstWhere('id', 'pnl');
    $walletTab = $accountTabsCollection->firstWhere('id', 'wallet');
@endphp

    <div class="space-y-8 text-white">
        <div>
            <p class="text-sm font-semibold text-[#08f58d]">Smart Trader</p>
            <h1 class="text-2xl font-semibold tracking-tight">Welcome back, {{ auth()->user()->name }}!</h1>
        </div>

        <div id="accountTabs" class="flex gap-3 overflow-x-auto pb-2">
            @foreach ($accountTabs as $index => $tab)
                <button
                    data-account="{{ $tab['id'] }}"
                    class="min-w-[200px] shrink-0 rounded-2xl border px-4 py-3 text-left transition-colors {{ $index === 0 ? 'border-[#1fff9c] bg-[#071c11]' : 'border-[#242424] bg-[#050505]' }}"
                >
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs text-gray-400">{{ $tab['label'] }}</p>
                            <p class="text-2xl font-semibold text-white tab-balance" data-balance="{{ $tab['balance'] }}">
                                {{ $index === 0 ? $tab['balance'] : '•••••' }}
                            </p>
                            <p class="text-xs {{ $tab['isPositive'] ? 'text-green-400' : 'text-red-400' }}">{{ $tab['change'] }}</p>
                        </div>
                        <div data-icon-ring class="flex h-8 w-8 items-center justify-center rounded-full border {{ $index === 0 ? 'border-[#1fff9c] text-[#1fff9c]' : 'border-[#2c2c2c] text-gray-400' }}">
                            <span data-icon-active class="{{ $index === 0 ? '' : 'hidden' }}">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            <span data-icon-inactive class="{{ $index === 0 ? 'hidden' : '' }}">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </button>
            @endforeach
        </div>

        <div class="rounded-[32px] bg-black text-white shadow-[0_0_60px_rgba(0,0,0,0.45)]">
            <div class="flex flex-col gap-1 px-6 pb-2 pt-6">
                <p class="text-sm uppercase text-gray-400">Balance</p>
                <p id="activeBalance" class="text-4xl font-semibold">{{ data_get($investingTab, 'balance', '$0.00') }}</p>
                <p id="activeChange" class="text-sm {{ data_get($investingTab, 'isPositive', true) ? 'text-green-400' : 'text-red-400' }}">
                    {{ data_get($investingTab, 'change', 'No data yet') }}
                </p>
            </div>

            <div class="relative h-72 w-full bg-black">
                <canvas id="portfolioChart" class="h-full w-full"></canvas>
            </div>

            <div id="timeRanges" class="flex flex-wrap gap-2 px-6 pb-4">
                @foreach ($timeRanges as $index => $range)
                    <button
                        data-range="{{ $range }}"
                        class="rounded-full px-4 py-1 text-xs font-semibold {{ $range === '1M' ? 'bg-[#00ff5f] text-black' : 'bg-[#0f0f0f] text-gray-300' }}"
                    >
                        {{ $range }}
                    </button>
                @endforeach
            </div>
            <div class="mx-6 mb-6 rounded-2xl border border-[#111111] bg-[#030303] px-5 py-4 flex items-center justify-between text-sm">
                <p class="text-gray-400">Buying Power</p>
                <p class="text-white font-semibold">{{ $user->formatAmount($user->trading_balance ?? 0) }}</p>
            </div>

            <div class="px-6 pb-6 pt-8 mt-4">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-sm uppercase tracking-wide text-gray-400">Watchlist</h3>
                    <a href="{{ url('user/assets-directory?type=stock') }}" class="text-xs text-[#a1a1a1] hover:text-white">View all stocks</a>
                </div>
                <div class="space-y-3">
                    @forelse ($watchlist as $stock)
                        @php
                            $isPositive = ((float) $stock->price_change_24h) >= 0;
                            $sparkValues = [];
                            $base = max((float) $stock->current_price, 0.01);
                            $points = 8;
                            for ($i = 0; $i < $points; $i++) {
                                $sparkValues[] = $base * (1 + (mt_rand(-25, 25) / 1000));
                            }
                            $minValue = min($sparkValues);
                            $maxValue = max($sparkValues);
                            $range = max($maxValue - $minValue, 0.0001);
                            $path = '';
                            foreach ($sparkValues as $index => $value) {
                                $x = $points > 1 ? ($index / ($points - 1)) * 60 : 0;
                                $y = 24 - (($value - $minValue) / $range * 24);
                                $path .= ($index === 0 ? 'M' : ' L') . round($x, 2) . ' ' . round($y, 2);
                            }
                        @endphp
                        <a href="{{ route('user.liveTrading.trade', ['asset_type' => 'stock', 'symbol' => $stock->symbol]) }}"
                           class="group flex items-center justify-between rounded-2xl border border-[#111111] bg-[#030303] px-4 py-3 hover:border-[#1fff9c]/40 hover:bg-[#060606] transition-colors">
                            <div>
                                <p class="text-sm font-semibold text-white">{{ $stock->symbol }}</p>
                                <p class="text-xs text-gray-500">{{ $stock->name }}</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <svg width="70" height="28" viewBox="0 0 70 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="{{ $path }}" stroke="{{ $isPositive ? '#00ff5f' : '#ff4d4d' }}" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="text-right">
                                    <span class="rounded-lg px-3 py-1 text-xs font-semibold {{ $isPositive ? 'bg-[#1fff9c]/20 text-[#1fff9c]' : 'bg-[#ff4d4d]/20 text-[#ff4d4d]' }}">
                                        ${{ number_format($stock->current_price, 2) }}
                                    </span>
                                    <span class="text-xs block mt-1 {{ $isPositive ? 'text-green-400' : 'text-red-400' }}">
                                        {{ $isPositive ? '+' : '' }}{{ number_format($stock->price_change_24h, 2) }}%
                                    </span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="rounded-2xl border border-[#111111] bg-[#030303] px-4 py-6 text-center text-sm text-gray-500">
                            No stocks available. <a href="{{ route('user.liveTrading.index') }}" class="text-[#1fff9c] underline">Start trading</a>.
                        </div>
                    @endforelse
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ url('user/assets-directory?type=stock') }}" class="inline-flex items-center justify-center rounded-full border border-[#1fff9c]/30 px-5 py-2 text-xs font-semibold text-[#1fff9c] hover:border-[#1fff9c]">
                        View all stocks
                    </a>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-[#151515] bg-[#050505] p-6 mb-48">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <h3 class="text-lg font-semibold text-white">Recent Activity</h3>
                <a href="{{ route('user.deposit') }}" class="text-sm text-gray-400 hover:text-white">View all</a>
            </div>
            <div class="space-y-4">
                @forelse($recentActivity ?? [] as $index => $activity)
                    <div class="flex items-center justify-between {{ $index < count($recentActivity) - 1 ? 'border-b border-[#101010] pb-4' : '' }}">
                        <div class="flex items-center gap-3">
                            @if($activity['type'] === 'deposit')
                                <a href="{{ route('user.deposit') }}" class="flex h-10 w-10 items-center justify-center rounded-full bg-green-500/20 text-green-300">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </a>
                            @elseif($activity['type'] === 'withdrawal')
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-500/20 text-red-300">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                    </svg>
                                </div>
                            @elseif($activity['type'] === 'trade_profit')
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-500/20 text-blue-300">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                </div>
                            @else
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-500/20 text-red-300">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-white">{{ $activity['title'] }}</p>
                                <p class="text-xs text-gray-500">{{ $activity['time_ago'] }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-semibold {{ $activity['amount'] >= 0 ? 'text-green-400' : 'text-red-400' }}">
                            {{ $activity['amount'] >= 0 ? '+' : '-' }}{{ $activity['formatted_amount'] }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div class="flex justify-center mb-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-800">
                                <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm text-gray-400">No recent activity</p>
                        <p class="text-xs text-gray-500 mt-1">Your transactions will appear here</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartCanvas = document.getElementById('portfolioChart');
            const serverChartData = @json($portfolioChartData ?? []);
            const fallbackChartData = {
                '1D': {
                    labels: ['9a', '10a', '11a', '12p', '1p', '2p', '3p'],
                    data: [12.1, 12.12, 12.13, 12.15, 12.12, 12.1, 12.14]
                },
                '1W': {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
                    data: [12.0, 12.05, 12.08, 12.12, 12.14]
                },
                '1M': {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    data: [11.8, 11.95, 12.05, 12.14]
                },
                '3M': {
                    labels: ['Mar', 'Apr', 'May'],
                    data: [11.2, 11.6, 12.14]
                },
                '1Y': {
                    labels: ['Jan', 'Mar', 'May', 'Jul', 'Sep', 'Nov'],
                    data: [9.5, 10.2, 10.8, 11.2, 11.9, 12.14]
                },
                'All': {
                    labels: ['2019', '2020', '2021', '2022', '2023'],
                    data: [3.5, 6.0, 8.4, 10.3, 12.14]
                }
            };
            const chartDataByAccount = serverChartData || {};
            const defaultRange = '1M';
            let portfolioChart = null;
            let currentRange = defaultRange;
            const tabData = @json($accountTabs);
            let currentChartBalance = tabData?.[0]?.raw_balance ?? 0;
            let currentAccountId = tabData?.[0]?.id ?? 'investing';

            const getDataSource = () => {
                const accountData = chartDataByAccount[currentAccountId];
                if (accountData && Object.keys(accountData).length) {
                    return accountData;
                }
                return fallbackChartData;
            };

            const getScaledDataset = (range, balance) => {
                const dataSource = getDataSource();
                const dataset = dataSource?.[range] || dataSource?.[defaultRange];
                if (!dataset) {
                    return { labels: [], data: [], range: null };
                }
                if (dataset?.raw) {
                    return {
                        labels: dataset.labels ?? [],
                        data: dataset.data ?? [],
                        range: dataset.range ?? null,
                    };
                }
                const values = dataset.data;
                const lastBase = values[values.length - 1] || 1;
                if (!values.length) {
                    return {
                        labels: dataset.labels ?? [],
                        data: [],
                        range: null,
                    };
                }
                if (balance <= 0 || lastBase <= 0) {
                    return {
                        labels: dataset.labels,
                        data: values.map(() => 0),
                        range: null,
                    };
                }
                const scale = balance / lastBase;
                const scaledValues = values.map(v => Number((v * scale).toFixed(2)));
                return {
                    labels: dataset.labels,
                    data: scaledValues,
                    range: null,
                };
            };

            if (chartCanvas && typeof Chart !== 'undefined') {
                const ctx = chartCanvas.getContext('2d');
                const initialData = getScaledDataset(defaultRange, currentChartBalance);
                portfolioChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: initialData.labels,
                        datasets: [{
                            label: 'Portfolio Value',
                            data: initialData.data,
                            borderColor: '#00ff5f',
                            backgroundColor: 'transparent',
                            borderWidth: 4,
                            pointRadius: 0,
                            tension: 0.4,
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: { enabled: false }
                        },
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        scales: {
                            y: {
                                display: false,
                                min: initialData.range?.min,
                                max: initialData.range?.max,
                            },
                            x: {
                                ticks: { color: '#404040', font: { size: 12 }},
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

            const updateChartRange = (range) => {
                if (!portfolioChart) return;
                currentRange = range || defaultRange;
                const dataset = getScaledDataset(currentRange, currentChartBalance);
                portfolioChart.data.labels = dataset.labels;
                portfolioChart.data.datasets[0].data = dataset.data;
                if (dataset.range) {
                    portfolioChart.options.scales.y.min = dataset.range.min;
                    portfolioChart.options.scales.y.max = dataset.range.max;
                } else {
                    delete portfolioChart.options.scales.y.min;
                    delete portfolioChart.options.scales.y.max;
                }
                portfolioChart.update();
            };

            const tabs = document.querySelectorAll('#accountTabs button');
            const balanceEl = document.getElementById('activeBalance');
            const changeEl = document.getElementById('activeChange');

            tabs.forEach((tab) => {
                tab.addEventListener('click', () => {
                    const currentId = tab.dataset.account;
                    const currentData = tabData.find(item => item.id === currentId) || tabData[0];
                    currentAccountId = currentId || currentAccountId;
                    currentChartBalance = currentData.raw_balance ?? 0;
                    updateChartRange(currentRange);

                    // Update main balance display
                    if (balanceEl) {
                        balanceEl.textContent = currentData.balance;
                    }
                    
                    if (changeEl) {
                        changeEl.textContent = currentData.change;
                        const isPositive = currentData.isPositive ?? true;
                        if (isPositive) {
                            changeEl.classList.add('text-green-400');
                            changeEl.classList.remove('text-red-400');
                        } else {
                            changeEl.classList.add('text-red-400');
                            changeEl.classList.remove('text-green-400');
                        }
                    }

                    tabs.forEach(btn => {
                        const isActive = btn === tab;
                        const activeIcon = btn.querySelector('[data-icon-active]');
                        const inactiveIcon = btn.querySelector('[data-icon-inactive]');
                        const ring = btn.querySelector('[data-icon-ring]');
                        const fnBalance = btn.querySelector('.tab-balance');
                        const btnDataId = btn.dataset.account;
                        const btnData = tabData.find(item => item.id === btnDataId) || tabData[0];

                        // Toggle icons
                        if (activeIcon && inactiveIcon) {
                            if (isActive) {
                                activeIcon.classList.remove('hidden');
                                inactiveIcon.classList.add('hidden');
                            } else {
                                activeIcon.classList.add('hidden');
                                inactiveIcon.classList.remove('hidden');
                            }
                        }

                        // Toggle ring colors
                        if (ring) {
                            if (isActive) {
                                ring.classList.add('border-[#1fff9c]', 'text-[#1fff9c]');
                                ring.classList.remove('border-[#2c2c2c]', 'text-gray-400');
                            } else {
                                ring.classList.remove('border-[#1fff9c]', 'text-[#1fff9c]');
                                ring.classList.add('border-[#2c2c2c]', 'text-gray-400');
                            }
                        }

                        // Toggle button styles
                        if (isActive) {
                            btn.classList.add('border-[#1fff9c]', 'bg-[#071c11]');
                            btn.classList.remove('border-[#242424]', 'bg-[#050505]');
                        } else {
                            btn.classList.remove('border-[#1fff9c]', 'bg-[#071c11]');
                            btn.classList.add('border-[#242424]', 'bg-[#050505]');
                        }

                        // Show balance for active card, hide for inactive
                        if (fnBalance) {
                            fnBalance.textContent = isActive ? btnData.balance : '•••••';
                        }
                    });
                });
            });

            const timeButtons = document.querySelectorAll('#timeRanges button');
            timeButtons.forEach(button => {
                button.addEventListener('click', () => {
                    timeButtons.forEach(btn => {
                        btn.classList.remove('bg-[#00ff5f]', 'text-black');
                        btn.classList.add('bg-[#0f0f0f]', 'text-gray-300');
                    });
                    button.classList.add('bg-[#00ff5f]', 'text-black');
                    button.classList.remove('bg-[#0f0f0f]', 'text-gray-300');
                    updateChartRange(button.dataset.range);
                });
            });

            updateChartRange(defaultRange);
        });
    </script>
@endpush
