import React from 'react';

const Watchlist = ({ watchlist = [] }) => {
    // Generate unique mini chart data based on trend direction and price change
    const generateMiniChart = (isPositive, changePercent, index) => {
        const points = 20;
        const data = [];
        
        // Create unique seed per stock using index and changePercent
        const seed1 = Math.abs(changePercent * 1000) % 1000;
        const seed2 = index * 123;
        const combinedSeed = seed1 + seed2;
        
        // Determine overall trend strength (0-40 range)
        const trendStrength = Math.min(40, Math.abs(changePercent) * 30);
        const direction = isPositive ? 1 : -1;
        
        // Start position varies based on seed
        let currentValue = 40 + (combinedSeed % 20); // Start between 40-60
        
        for (let i = 0; i < points; i++) {
            const progress = i / (points - 1);
            
            // Calculate base trend (ending higher/lower based on direction)
            const trendComponent = currentValue + (trendStrength * direction * progress);
            
            // Add unique wave pattern based on seed
            const wave1 = Math.sin(i * 0.3 + combinedSeed * 0.1) * 5;
            const wave2 = Math.sin(i * 0.7 + combinedSeed * 0.2) * 3;
            
            // Add some random-like variation using deterministic pseudo-random
            const pseudoRandom = Math.sin((i + combinedSeed) * 2.7) * 4;
            
            // Apply all components
            currentValue = trendComponent + wave1 + wave2 + pseudoRandom;
            
            // Clamp to visible range with padding
            currentValue = Math.max(12, Math.min(88, currentValue));
            data.push(currentValue);
        }
        
        // Normalize to fill the height range better
        const min = Math.min(...data);
        const max = Math.max(...data);
        const range = max - min || 1;
        const normalized = data.map(val => 12 + ((val - min) / range) * 76);
        
        return normalized;
    };

    const chartHeight = 24;
    const chartWidth = 48;

    const handleAddFavorite = () => {
        // Navigate to assets directory page
        window.location.href = '/user/assets-directory?type=stock';
    };

    return (
        <div className="space-y-3">
            {/* Watchlist Header */}
            <div className="flex justify-between items-center px-1 mb-2">
                <p className="text-xs uppercase font-semibold text-gray-400 tracking-widest">Watchlist</p>
                <button
                    type="button"
                    className="text-xs text-[#00ff63] cursor-pointer hover:underline"
                    onClick={handleAddFavorite}
                >
                    Add favorite
                </button>
            </div>

            {/* Watchlist Items */}
            <div className="space-y-2">
                {(watchlist || []).slice(0, 5).map((item, index) => {
                    const price = item.price || 0;
                    const change = item.change || 0;
                    const changePercent = item.change_percentage || ((change / (price - change)) * 100) || 0;
                    const isPositive = change >= 0;
                    const chartData = generateMiniChart(isPositive, changePercent, index);

                    return (
                        <div
                            key={`${item.symbol}-${index}`}
                            className="rounded-xl border bg-[#1a1a1a] text-white shadow-sm border-[#1a1a1a] hover:border-[#00ff63]/30 transition-colors cursor-pointer"
                        >
                            <div className="p-2.5">
                                <div className="flex items-center justify-between gap-1.5">
                                    {/* Left: Symbol and Name */}
                                    <div className="flex-1 min-w-0">
                                        <p className="font-bold text-white text-sm truncate">
                                            {item.symbol || 'N/A'}
                                        </p>
                                        <p className="text-xs text-gray-400 truncate">
                                            {item.name || '—'}
                                        </p>
                                    </div>

                                    {/* Middle: Mini Chart */}
                                    <div 
                                        className="w-12 h-6 flex-shrink-0 overflow-hidden"
                                        style={{ 
                                            maxWidth: `${chartWidth}px`,
                                            maxHeight: `${chartHeight}px`
                                        }}
                                    >
                                        <svg
                                            width={chartWidth}
                                            height={chartHeight}
                                            viewBox={`0 0 ${chartWidth} ${chartHeight}`}
                                            style={{ 
                                                display: 'block',
                                                width: '100%',
                                                height: '100%',
                                                maxWidth: `${chartWidth}px`,
                                                maxHeight: `${chartHeight}px`
                                            }}
                                            preserveAspectRatio="none"
                                        >
                                            {/* ClipPath for padding like the reference design */}
                                            <defs>
                                                <clipPath id={`chart-clip-${index}`}>
                                                    <rect x="5" y="5" height="14" width="38" />
                                                </clipPath>
                                            </defs>
                                            <g clipPath={`url(#chart-clip-${index})`}>
                                                <polyline
                                                    points={chartData.map((val, i) => {
                                                        // Map values to chart area (with 5px padding on all sides)
                                                        const x = 5 + (i / (chartData.length - 1)) * 38;
                                                        // Map values from 0-100 to 5-19 (14px height range)
                                                        const y = 5 + 14 - ((val / 100) * 14);
                                                        return `${x},${y}`;
                                                    }).join(' ')}
                                                    fill="none"
                                                    stroke={isPositive ? "#00ff63" : "#ef4444"}
                                                    strokeWidth="1.5"
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                />
                                            </g>
                                        </svg>
                                    </div>

                                    {/* Right: Price and Change */}
                                    <div className="text-right flex-shrink-0">
                                        <div className={`px-2 py-0.5 rounded-lg text-xs font-bold whitespace-nowrap ${isPositive ? 'bg-[#00ff63]/20 text-[#00ff63]' : 'bg-red-400/20 text-red-400'}`}>
                                            ${typeof price === 'number' ? price.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 2 }) : price}
                                        </div>
                                        <p className={`text-xs mt-0.5 font-semibold ${isPositive ? 'text-[#00ff63]' : 'text-red-400'}`}>
                                            {isPositive ? '+' : ''}{changePercent.toFixed(2)}%
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    );
                })}
            </div>

            {/* Show message if watchlist is empty */}
            {(!watchlist || watchlist.length === 0) && (
                <div className="rounded-lg bg-[#1a1a1a] p-4 text-center">
                    <p className="text-sm text-gray-400">No items in watchlist</p>
                </div>
            )}
        </div>
    );
};

export default Watchlist;

