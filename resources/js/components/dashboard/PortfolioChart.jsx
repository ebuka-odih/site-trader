import React, { useEffect, useState, useMemo } from 'react';
import {
    LineChart,
    Line,
    ResponsiveContainer,
    Tooltip,
    XAxis,
    YAxis,
    CartesianGrid,
} from 'recharts';

const PRIMARY = '#00ff63';

const formatCurrency = (value = 0) =>
    new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        maximumFractionDigits: 2,
    }).format(Number(value));

const CustomTooltip = ({ active, payload, label }) => {
    if (!active || !payload?.length) return null;
    const value = payload[0].value;
    
    // Format value without currency symbol for "Portfolio Value : $X,XXX" format
    const formattedValue = typeof value === 'number' 
        ? new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(value)
        : value;
    
    return (
        <div 
            className="recharts-default-tooltip"
            style={{
                margin: 0,
                padding: '10px',
                backgroundColor: 'rgba(10, 10, 10, 0.9)',
                border: '1px solid hsl(var(--border))',
                whiteSpace: 'nowrap',
                borderRadius: 'var(--radius)',
            }}
        >
            <p 
                className="recharts-tooltip-label" 
                style={{ margin: 0, color: 'rgb(0, 255, 0)' }}
            >
                {label}
            </p>
            <ul 
                className="recharts-tooltip-item-list" 
                style={{ padding: 0, margin: 0 }}
            >
                <li 
                    className="recharts-tooltip-item" 
                    style={{ 
                        display: 'block', 
                        paddingTop: '4px', 
                        paddingBottom: '4px', 
                        color: 'rgb(0, 255, 0)' 
                    }}
                >
                    <span className="recharts-tooltip-item-name">PNL</span>
                    <span className="recharts-tooltip-item-separator"> : </span>
                    <span className="recharts-tooltip-item-value">${formattedValue}</span>
                </li>
            </ul>
        </div>
    );
};

const LoadingSkeleton = () => (
    <div className="w-full h-64 bg-black rounded-2xl relative overflow-hidden">
        <div className="absolute inset-0 flex items-center justify-center">
            <div className="flex flex-col items-center space-y-3">
                <div className="w-8 h-8 border-2 border-[#00ff63] border-t-transparent rounded-full animate-spin"></div>
                <p className="text-gray-400 text-sm">Loading portfolio data...</p>
            </div>
        </div>
        {/* Animated shimmer effect */}
        <div className="absolute inset-0 bg-gradient-to-r from-transparent via-[#00ff63]/5 to-transparent animate-shimmer"></div>
    </div>
);

const PortfolioChart = ({ chartData = {}, currentBalance = 0, timeframes = ['LIVE', '1D', '1W', '1M', '3M', 'YTD', '1Y'] }) => {
    const [activeRange, setActiveRange] = useState('LIVE');
    const [isLoading, setIsLoading] = useState(true);
    const [chartDataState, setChartDataState] = useState([]);

    // Get available ranges from chartData
    const availableRanges = useMemo(() => {
        if (!chartData || typeof chartData !== 'object') return [];
        const account = 'pnl';
        return chartData[account] ? Object.keys(chartData[account]) : [];
    }, [chartData]);

    // Process chart data from backend - use it directly
    const processedChartData = useMemo(() => {
        if (!chartData || typeof chartData !== 'object') return null;

        const account = 'pnl';
        const series = chartData[account]?.[activeRange];
        
        if (!series || !series.labels || !series.data) return null;

        const values = series.data;
        const labels = series.labels;
        
        if (values.length === 0 || labels.length === 0) return null;
        
        // Use backend data directly - it's already structured correctly
        return labels.map((label, idx) => ({
            label,
            value: values[idx] ?? 0,
            timestamp: Date.now() - (values.length - idx) * 86400000, // Approximate timestamp
        })).filter(item => item.value > 0);
    }, [chartData, activeRange]);

    // Generate fallback chart data using current balance
    const generateDefaultData = useMemo(() => {
        const now = new Date();
        const data = [];

        // Get current balance (portfolio value)
        const balance = parseFloat(
            String(currentBalance).replace(/[^0-9.-]+/g, '') || '0'
        ) || 0;

        // If no balance, return empty
        if (balance <= 0) {
            return [];
        }

        // Calculate start value (80% of current balance for visible growth)
        const startValue = balance * 0.80;
        const endValue = balance;
        const valueRange = endValue - startValue;

        // Define timeframe configurations
        let numPoints, daysPerPoint;

        switch (activeRange) {
            case 'LIVE':
                numPoints = 31; // 30 days + today
                daysPerPoint = 1;
                break;
            case '1D':
                numPoints = 2; // Yesterday and today
                daysPerPoint = 1;
                break;
            case '1W':
                numPoints = 7; // 7 days
                daysPerPoint = 1;
                break;
            case '1M':
                numPoints = 5; // 4 weeks + today
                daysPerPoint = 7;
                break;
            case '3M':
                numPoints = 91; // 90 days + today
                daysPerPoint = 1;
                break;
            case 'YTD':
                numPoints = now.getMonth() + 1; // Months from Jan to current
                daysPerPoint = 30;
                break;
            case '1Y':
                numPoints = 366; // 365 days + today
                daysPerPoint = 1;
                break;
            default:
                numPoints = 31;
                daysPerPoint = 1;
        }

        for (let i = 0; i < numPoints; i++) {
            let date = new Date(now);

            // Calculate date based on timeframe
            if (activeRange === 'YTD') {
                // For YTD, go back by months
                date.setMonth(date.getMonth() - (numPoints - 1 - i));
                if (i < numPoints - 1) {
                    date.setDate(1);
                }
            } else if (activeRange === '1M') {
                // For 1M, go back by weeks
                const weeksBack = (numPoints - 1 - i) * 7;
                date.setDate(date.getDate() - weeksBack);
            } else {
                // For others, go back by days
                const daysBack = (numPoints - 1 - i) * daysPerPoint;
                date.setDate(date.getDate() - daysBack);
            }

            date.setHours(0, 0, 0, 0);

            // Calculate value with smooth growth curve (easing function)
            const progress = i / (numPoints - 1);
            const easeOut = 1 - Math.pow(1 - progress, 2); // Quadratic ease-out for smooth curve
            const baseValue = startValue + (valueRange * easeOut);

            // Add small realistic fluctuations (using fixed seed for consistency)
            const seed = 123; // Fixed seed for consistent demo
            const fluctuation = Math.sin((i + seed) * 0.5) * 0.015 * endValue; // Small variations
            const value = Math.max(startValue * 0.95, baseValue + fluctuation);
            
            // Format label based on timeframe
            let label;
            if (activeRange === '1W') {
                // Show day names: Mon, Tue, Wed, etc.
                label = date.toLocaleDateString('en-US', { weekday: 'short' });
            } else if (activeRange === 'YTD') {
                // Show month names: Jan, Feb, Mar, etc.
                label = date.toLocaleDateString('en-US', { month: 'short' });
            } else {
                // Show dates: "Nov 23", "Nov 24", etc.
                const month = date.toLocaleDateString('en-US', { month: 'short' });
                const day = date.getDate();
                label = `${month} ${day}`;
            }
            
            data.push({
                label,
                value: i === numPoints - 1 ? endValue : value, // Last point is exact end value
                timestamp: date.getTime(),
            });
        }
        
        return data;
    }, [activeRange, currentBalance]);

    // Set initial range - only run once on mount if needed
    useEffect(() => {
        // Initialize with LIVE or first available range
        if (availableRanges.length > 0 && !availableRanges.includes(activeRange)) {
            // If current range is not available, switch to first available
            // But preserve LIVE if user hasn't interacted yet
            if (activeRange !== 'LIVE' || !timeframes.includes('LIVE')) {
                setActiveRange(availableRanges[0]);
            }
        }
    }, []); // Only run once on mount

    // Update chart data when range or balance changes
    useEffect(() => {
        setIsLoading(true);
        const timer = setTimeout(() => {
            // Use backend data if available, otherwise use generated data
            const dataToUse = processedChartData || generateDefaultData;

            if (dataToUse && dataToUse.length > 0) {
                const updatedData = [...dataToUse];
                // Ensure last point is exactly current balance
                if (currentBalance > 0 && updatedData.length > 0) {
                    updatedData[updatedData.length - 1] = {
                        ...updatedData[updatedData.length - 1],
                        value: currentBalance,
                    };
                }
                setChartDataState(updatedData);
            } else {
                setChartDataState([]);
            }

            setIsLoading(false);
        }, 200);

        return () => clearTimeout(timer);
    }, [processedChartData, generateDefaultData, activeRange, currentBalance]);

    // Calculate min/max for professional chart visualization - Robinhood style
    const { minValue, maxValue } = useMemo(() => {
        if (chartDataState.length === 0) {
            // Default domain if no data
            const defaultMax = currentBalance || 1000;
            return { minValue: defaultMax * 0.8, maxValue: defaultMax * 1.05 };
        }
        
        const values = chartDataState.map(d => d.value).filter(v => v > 0);
        
        if (values.length === 0) {
            const defaultMax = currentBalance || 1000;
            return { minValue: defaultMax * 0.8, maxValue: defaultMax * 1.05 };
        }
        
        const min = Math.min(...values);
        const max = Math.max(...values);
        const range = max - min;
        
        // Robinhood-style padding: show meaningful range with smart padding
        // Bottom padding: show at least 5% below minimum, or use minimum itself
        const bottomPadding = Math.max(range * 0.05, min * 0.02);
        
        // Top padding: show at least 5% above maximum
        const topPadding = Math.max(range * 0.05, max * 0.02);
        
        // Ensure we never go below zero
        const calculatedMin = Math.max(0, min - bottomPadding);
        const calculatedMax = max + topPadding;
        
        // Ensure minimum is always significantly lower than current balance for visual growth
        // This creates the "growth from bottom" effect like Robinhood
        const finalMin = currentBalance > 0 && calculatedMin >= currentBalance * 0.85
            ? currentBalance * 0.7
            : calculatedMin;
        
        return {
            minValue: finalMin,
            maxValue: calculatedMax,
        };
    }, [chartDataState, currentBalance]);

    // Get domain for Y-axis - Robinhood-style with proper scaling
    const domain = [Math.max(0, minValue), maxValue];

    // Calculate X-axis configuration based on timeframe
    const xAxisConfig = useMemo(() => {
        if (activeRange === '1D') {
            // 1D: show yesterday and today (2 dates)
            return {
                interval: 0, // Show both dates
                angle: 0,
                textAnchor: 'middle',
                height: 30,
            };
        } else if (activeRange === '1W') {
            // 7 days: show all dates
            return {
                interval: 0,
                angle: 0,
                textAnchor: 'middle',
                height: 30,
            };
        } else if (activeRange === 'LIVE') {
            // LIVE (30 days): show dates with professional spacing - every 5 days
            // This shows approximately 6-7 dates across 30 days for clean, professional look
            return {
                interval: 4, // Show every 5th date (0, 5, 10, 15, 20, 25, 30)
                angle: 0,
                textAnchor: 'middle',
                height: 35,
            };
        } else if (activeRange === '1M') {
            // 1 month: show weekly dates (4-5 weeks)
            return {
                interval: 0, // Show all weeks
                angle: 0,
                textAnchor: 'middle',
                height: 35,
            };
        } else if (activeRange === '3M') {
            // 3 months: show every 15 days (approx 2 weeks)
            return {
                interval: 14,
                angle: 0,
                textAnchor: 'middle',
                height: 35,
            };
        } else if (activeRange === '1Y') {
            // 1Y: show dates with professional spacing across the year
            return {
                interval: 29, // Show approximately every 30th date for clean spacing (about 12 dates across the year)
                angle: 0,
                textAnchor: 'middle',
                height: 35,
            };
        } else if (activeRange === 'YTD') {
            // YTD: show all months horizontally
            return {
                interval: 0, // Show all months
                angle: 0,
                textAnchor: 'middle',
                height: 30,
            };
        } else {
            return {
                interval: 'preserveStartEnd',
                angle: 0,
                textAnchor: 'middle',
                height: 30,
            };
        }
    }, [activeRange]);

    if (isLoading) {
        return <LoadingSkeleton />;
    }

    return (
        <div className="space-y-4">
            {/* Chart Container */}
            <div 
                className="w-full h-64 bg-black rounded-2xl relative overflow-hidden focus:outline-none focus:ring-0"
                style={{ outline: 'none', border: 'none' }}
                tabIndex={-1}
            >
                {/* Chart Area */}
                <div 
                    className="absolute inset-0 p-2 focus:outline-none"
                    style={{ outline: 'none' }}
                >
                    <style>{`
                        .recharts-wrapper:focus,
                        .recharts-wrapper:focus-visible,
                        .recharts-responsive-container:focus,
                        .recharts-responsive-container:focus-visible,
                        .recharts-surface:focus,
                        .recharts-surface:focus-visible,
                        .recharts-line:focus,
                        .recharts-line:focus-visible,
                        .recharts-line-curve:focus,
                        .recharts-line-curve:focus-visible,
                        .recharts-active-dot:focus,
                        .recharts-active-dot:focus-visible,
                        .recharts-dot:focus,
                        .recharts-dot:focus-visible {
                            outline: none !important;
                            border: none !important;
                            box-shadow: none !important;
                        }
                        .recharts-wrapper,
                        .recharts-line,
                        .recharts-line-curve,
                        .recharts-active-dot,
                        .recharts-dot {
                            outline: none !important;
                            border: none !important;
                        }
                        .recharts-line-curve {
                            cursor: crosshair !important;
                        }
                    `}</style>
                    <ResponsiveContainer width="100%" height="100%">
                        <LineChart
                            data={chartDataState}
                            margin={{
                                top: 5,
                                right: 10,
                                left: 5,
                                bottom: xAxisConfig.angle !== 0 ? 50 : (activeRange === 'LIVE' || activeRange === '1M' || activeRange === '1Y' ? 35 : 25)
                            }}
                        >
                            <CartesianGrid
                                strokeDasharray="3 3"
                                stroke="#1a1a1a"
                                horizontal={true}
                                vertical={false}
                                strokeOpacity={0.5}
                            />
                            <XAxis
                                dataKey="label"
                                stroke="#666"
                                tick={{ fill: '#666', fontSize: 10 }}
                                axisLine={false}
                                tickLine={false}
                                interval={xAxisConfig.interval}
                                tickMargin={10}
                                angle={xAxisConfig.angle}
                                textAnchor={xAxisConfig.textAnchor}
                                height={xAxisConfig.height}
                                minTickGap={activeRange === 'LIVE' ? 50 : undefined}
                                allowDuplicatedCategory={false}
                            />
                            <YAxis
                                hide
                                domain={domain}
                                allowDataOverflow={false}
                            />
                            <Tooltip
                                content={<CustomTooltip />}
                                cursor={{
                                    stroke: PRIMARY,
                                    strokeWidth: 1,
                                    strokeDasharray: '0',
                                    opacity: 0.5,
                                    pointerEvents: 'none',
                                }}
                                animationDuration={400}
                                allowEscapeViewBox={{ x: true, y: true }}
                                wrapperStyle={{ 
                                    outline: 'none',
                                    pointerEvents: 'auto',
                                    zIndex: 1000
                                }}
                                shared={false}
                            />
                            <Line
                                type="monotone"
                                dataKey="value"
                                stroke={PRIMARY}
                                strokeWidth={2}
                                dot={false}
                                isAnimationActive={true}
                                animationDuration={1200}
                                animationEasing="ease-out"
                                activeDot={{
                                    r: 4,
                                    fill: PRIMARY,
                                    strokeWidth: 2,
                                    stroke: '#fff',
                                    className: 'recharts-dot',
                                }}
                                connectNulls={false}
                            />
                        </LineChart>
                    </ResponsiveContainer>
                </div>
            </div>

            {/* Timeframe Buttons */}
            <div className="grid grid-cols-7 gap-2 pb-1">
                {timeframes.map((timeframe) => {
                    const isActive = timeframe === activeRange;
                    
                    return (
                        <button
                            key={timeframe}
                            type="button"
                            onClick={(e) => {
                                e.preventDefault();
                                e.stopPropagation();
                                setActiveRange(timeframe);
                            }}
                            className={`inline-flex items-center justify-center gap-2 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-[#00ff63] disabled:pointer-events-none disabled:opacity-50 border shadow-xs active:shadow-none min-h-8 rounded-md px-2 text-xs h-7 font-semibold whitespace-nowrap transition-all duration-200 ${isActive
                                    ? 'bg-[#00ff63] text-black border-[#00ff63]'
                                    : 'bg-[#1a1a1a] border-[#1a1a1a] text-white hover:border-[#00ff63]/50 hover:bg-[#1f1f1f]'
                            }`}
                            data-testid={`button-period-${timeframe.toLowerCase()}`}
                        >
                            {timeframe}
                        </button>
                    );
                })}
            </div>
        </div>
    );
};

export default PortfolioChart;
