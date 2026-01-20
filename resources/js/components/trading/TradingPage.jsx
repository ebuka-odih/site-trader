import React, { useState, useEffect, useMemo } from 'react';
import {
    LineChart,
    Line,
    ResponsiveContainer,
    Tooltip,
    XAxis,
    YAxis,
    CartesianGrid,
} from 'recharts';
import TradeForm from './TradeForm';

const PRIMARY = '#00ff63'; // Bright green color

const formatCurrency = (value = 0, decimals = 2) =>
    new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    }).format(Number(value));

// Generate smooth chart data based on timeframe
const generateChartData = (timeframe, basePrice = 283.32) => {
    const data = [];
    let points = 50;
    let daysBack = 1;
    
    switch (timeframe) {
        case '1D':
            points = 48; // More points for smoother line
            daysBack = 1;
            break;
        case '1W':
            points = 28;
            daysBack = 7;
            break;
        case '1M':
            points = 30;
            daysBack = 30;
            break;
        case '3M':
            points = 90;
            daysBack = 90;
            break;
        case '1Y':
            points = 365;
            daysBack = 365;
            break;
        case 'All':
            points = 500;
            daysBack = 1000;
            break;
    }
    
    const now = new Date();
    const variation = basePrice * 0.08; // 8% variation for smoother movement
    
    // Use a smoother price generation with moving average
    let previousPrice = basePrice;
    let movingAverage = basePrice;
    
    for (let i = points; i >= 0; i--) {
        const date = new Date(now);
        date.setDate(date.getDate() - (daysBack * i / points));
        
        // Generate smoother price movement using exponential smoothing
        const randomChange = (Math.random() - 0.5) * variation * 0.3; // Smaller random changes
        const trend = (points - i) / points * variation * 0.2; // Gentle trend
        
        // Apply exponential smoothing for smoother transitions
        movingAverage = movingAverage * 0.7 + (basePrice + trend) * 0.3;
        const price = previousPrice * 0.85 + (movingAverage + randomChange) * 0.15;
        previousPrice = price;
        
        data.push({
            date: date.toISOString(),
            value: Math.max(price, basePrice * 0.7), // Ensure price doesn't go too low
            label: timeframe === '1D' 
                ? date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' })
                : date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
        });
    }
    
    return data;
};

const CustomTooltip = ({ active, payload }) => {
    if (!active || !payload?.length) return null;
    const value = payload[0].value;
    
    return (
        <div className="bg-black/90 border border-primary/30 rounded-lg px-3 py-2">
            <p className="text-primary font-bold text-sm">
                {formatCurrency(value)}
            </p>
        </div>
    );
};

const TradingPage = ({ 
    asset, 
    assetType, 
    user, 
    tradeHistory = [], 
    relatedAssets = [],
    allAssets = [],
    quickPicks = [],
    tradingBalance = 0
}) => {
    // Ensure arrays are always arrays
    const safeAllAssets = Array.isArray(allAssets) ? allAssets : [];
    const safeQuickPicks = Array.isArray(quickPicks) ? quickPicks : [];
    const [timeframe, setTimeframe] = useState('3M');
    const [chartData, setChartData] = useState([]);
    const [currentPrice, setCurrentPrice] = useState(asset?.current_price || 0);
    const [priceChange, setPriceChange] = useState(asset?.price_change_24h || 0);
    const [showTradeForm, setShowTradeForm] = useState(false);
    const [isLoadingPrice, setIsLoadingPrice] = useState(false);

    // Initialize chart data
    useEffect(() => {
        const data = generateChartData(timeframe, currentPrice);
        setChartData(data);
    }, [timeframe, currentPrice]);

    // Fetch live price updates
    useEffect(() => {
        if (!asset?.symbol || !assetType) return;

        const fetchPrice = async () => {
            setIsLoadingPrice(true);
            try {
                const url = new URL('/user/live-trading/price', window.location.origin);
                url.searchParams.set('asset_type', assetType);
                url.searchParams.set('symbol', asset.symbol);

                const response = await fetch(url.toString());
                const data = await response.json();

                if (data.success) {
                    setCurrentPrice(parseFloat(data.price) || currentPrice);
                    setPriceChange(parseFloat(data.change_24h) || priceChange);
                }
            } catch (error) {
                console.error('Error fetching price:', error);
            } finally {
                setIsLoadingPrice(false);
            }
        };

        fetchPrice();
        const interval = setInterval(fetchPrice, 60000); // Update every minute

        return () => clearInterval(interval);
    }, [asset?.symbol, assetType]);

    const priceChangePercent = useMemo(() => {
        if (!currentPrice || currentPrice === 0) return 0;
        return ((priceChange / (currentPrice - priceChange)) * 100).toFixed(2);
    }, [currentPrice, priceChange]);

    const isPositive = priceChange >= 0;

    const timeframes = ['1D', '1W', '1M', '3M', '1Y', 'All'];

    return (
        <div className="h-full will-change-transform overflow-visible" style={{ opacity: 1, transform: 'none' }}>
            <div 
                className="p-6 space-y-6 animate-slide-up pb-24 overflow-visible"
                style={{ paddingTop: 'max(1.5rem, calc(1.5rem + env(safe-area-inset-top)))' }}
            >
                {/* Header */}
                <div className="flex items-center justify-between">
                    <a href="/terminal">
                        <button className="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 hover-elevate active-elevate-2 border border-transparent min-h-8 rounded-md px-3 text-xs h-8">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className="lucide lucide-arrow-left h-4 w-4">
                                <path d="m12 19-7-7 7-7"></path>
                                <path d="M19 12H5"></path>
                            </svg>
                        </button>
                    </a>
                    <p className="text-sm text-muted-foreground">● 24 Hour Market</p>
                </div>

                {/* Stock Info */}
                <div className="space-y-2">
                    <p className="text-xs uppercase font-bold text-muted-foreground tracking-widest">
                        {asset?.symbol || 'N/A'}
                    </p>
                    <h1 className="text-3xl font-black text-foreground">
                        {asset?.name || 'Unknown Asset'}
                    </h1>
                    <div className="flex items-end gap-3">
                        <p className="text-4xl font-black text-foreground font-mono">
                            {formatCurrency(currentPrice)}
                        </p>
                        <div 
                            className={`px-2 py-1 rounded-full text-xs font-bold transition-all duration-500 ${
                                isPositive 
                                    ? 'bg-primary/20 text-primary' 
                                    : 'bg-red-500/20 text-red-500'
                            }`}
                        >
                            {isPositive ? '▲' : '▼'} {formatCurrency(Math.abs(priceChange))} ({priceChangePercent}%) Today
                        </div>
                    </div>
                    <p className="text-xs text-muted-foreground">24 Hour Market</p>
                </div>

                {/* Chart */}
                <div className="h-80 -mx-6">
                    <ResponsiveContainer width="100%" height="100%">
                        <LineChart 
                            data={chartData} 
                            margin={{ top: 20, right: 20, left: 20, bottom: 20 }}
                        >
                            <CartesianGrid strokeDasharray="3 3" stroke="#1a1a1a" horizontal={true} vertical={false} opacity={0.3} />
                            <XAxis 
                                dataKey="label" 
                                stroke="#666" 
                                tick={{ fill: '#666', fontSize: 10 }}
                                axisLine={false}
                                tickLine={false}
                                interval="preserveStartEnd"
                                tickMargin={8}
                            />
                            <YAxis hide domain={['auto', 'auto']} />
                            <Tooltip content={<CustomTooltip />} />
                            <Line
                                type="basis"
                                dataKey="value"
                                stroke={PRIMARY}
                                strokeWidth={2.5}
                                dot={false}
                                activeDot={{ r: 4, fill: PRIMARY, strokeWidth: 2, stroke: '#000' }}
                                isAnimationActive={true}
                                animationDuration={1200}
                                connectNulls={false}
                            />
                        </LineChart>
                    </ResponsiveContainer>
                </div>

                {/* Timeframe Selection */}
                <div className="flex items-center justify-center gap-2 -mx-6 px-6 pb-2 overflow-visible">
                    <div className="flex gap-2 w-full justify-center flex-nowrap overflow-visible">
                        {timeframes.map((tf) => (
                            <button
                                key={tf}
                                onClick={() => setTimeframe(tf)}
                                className={`px-3 py-2 rounded-full font-bold text-sm whitespace-nowrap transition-all flex-shrink-0 ${
                                    timeframe === tf
                                        ? 'bg-primary text-black'
                                        : 'text-muted-foreground hover:text-foreground'
                                }`}
                            >
                                {tf}
                            </button>
                        ))}
                    </div>
                </div>

                {/* Quick Trade Section */}
                <div className="space-y-3">
                    <div className="flex items-center justify-between">
                        <p className="text-base font-bold text-foreground">Quick Trade</p>
                        <p className="text-xs text-muted-foreground">Individual</p>
                    </div>
                    <button
                        onClick={() => setShowTradeForm(true)}
                        className="w-full border-2 border-primary rounded-full py-3 px-4 font-bold text-primary hover:bg-primary/10 transition-colors"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className="lucide lucide-plus w-4 h-4 mr-2 inline">
                            <path d="M5 12h14"></path>
                            <path d="M12 5v14"></path>
                        </svg>
                        Buy {asset?.symbol || 'Asset'}
                    </button>
                </div>

                {/* Related Assets */}
                {relatedAssets && relatedAssets.length > 0 && (
                    <div className="space-y-2">
                        {relatedAssets.slice(0, 4).map((relatedAsset) => (
                            <a
                                key={relatedAsset.symbol}
                                href={`/user/live-trading/trade?asset_type=${relatedAsset.type}&symbol=${relatedAsset.symbol}`}
                                className="flex items-center justify-between py-3 px-4 rounded-lg hover:bg-card transition-colors cursor-pointer"
                            >
                                <div className="flex-1">
                                    <p className="font-bold text-foreground text-sm">{relatedAsset.symbol}</p>
                                    <p className="text-xs text-muted-foreground mt-0.5">{relatedAsset.name}</p>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className="lucide lucide-arrow-left w-5 h-5 text-muted-foreground rotate-180 flex-shrink-0">
                                    <path d="m12 19-7-7 7-7"></path>
                                    <path d="M19 12H5"></path>
                                </svg>
                            </a>
                        ))}
                    </div>
                )}

                {/* About Section */}
                <div className="space-y-3 pt-6 border-t border-border">
                    <div className="flex items-center justify-between">
                        <p className="text-base font-bold text-foreground">About {asset?.name || 'Asset'}</p>
                        <p className="text-xs text-muted-foreground">Info</p>
                    </div>
                    <p className="text-sm text-muted-foreground leading-relaxed">
                        {asset?.name || 'Asset'} is actively trading in the market. Monitor key stats, set price alerts, and execute trades with precision.
                    </p>
                </div>

                {/* Stats Grid */}
                <div className="grid grid-cols-2 gap-3">
                    <button className="rounded-lg border border-border p-4 hover:bg-card transition-colors text-left min-w-0">
                        <p className="text-xs text-muted-foreground mb-2">Market Cap</p>
                        <p className="text-sm font-bold text-foreground break-all" title={asset?.market_cap ? formatCurrency(asset.market_cap, 0) : 'N/A'}>
                            {asset?.market_cap ? formatCurrency(asset.market_cap, 0) : 'N/A'}
                        </p>
                    </button>
                    <button className="rounded-lg border border-border p-4 hover:bg-card transition-colors text-left min-w-0">
                        <p className="text-xs text-muted-foreground mb-2">Volume (24h)</p>
                        <p className="text-sm font-bold text-foreground break-all" title={asset?.volume_24h ? formatCurrency(asset.volume_24h, 0) : 'N/A'}>
                            {asset?.volume_24h ? formatCurrency(asset.volume_24h, 0) : 'N/A'}
                        </p>
                    </button>
                </div>

                {/* Activity Section */}
                <div className="space-y-3 pt-6 border-t border-border pb-4">
                    <div className="flex items-center justify-between">
                        <div>
                            <p className="text-xs uppercase font-bold text-primary tracking-widest">Activity</p>
                            <p className="text-base font-bold text-foreground mt-2">Recent Trades</p>
                        </div>
                        <p className="text-xs text-muted-foreground">
                            {tradeHistory?.length || 0} shown
                        </p>
                    </div>
                    {tradeHistory && tradeHistory.length > 0 ? (
                        <div className="space-y-2">
                            {tradeHistory.map((trade) => (
                                <div
                                    key={trade.id}
                                    className="rounded-lg border border-border p-4 hover:bg-card transition-colors"
                                >
                                    <div className="flex items-center justify-between">
                                        <div>
                                            <p className={`text-sm font-bold ${
                                                trade.side === 'buy' ? 'text-primary' : 'text-red-500'
                                            }`}>
                                                {trade.side.toUpperCase()} • {trade.symbol}
                                            </p>
                                            <p className="text-xs text-muted-foreground">
                                                {trade.order_type} • {new Date(trade.created_at).toLocaleDateString()}
                                            </p>
                                        </div>
                                        <div className="text-right">
                                            <p className="text-base font-bold text-foreground">
                                                {formatCurrency(trade.amount)}
                                            </p>
                                            <p className={`text-xs ${
                                                trade.status === 'filled' ? 'text-primary' : 'text-muted-foreground'
                                            }`}>
                                                {trade.status}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <button className="rounded-lg border border-dashed border-border p-4 hover:bg-card transition-colors text-center cursor-pointer w-full">
                            <p className="text-xs text-muted-foreground">
                                No trades yet for this asset. Your activity will appear here once you place an order.
                            </p>
                        </button>
                    )}
                </div>
            </div>

            {/* Trade Form Modal */}
            {showTradeForm && (
                <TradeForm
                    asset={asset}
                    assetType={assetType}
                    tradingBalance={tradingBalance}
                    currentPrice={currentPrice}
                    allAssets={safeAllAssets}
                    quickPicks={safeQuickPicks}
                    onClose={() => setShowTradeForm(false)}
                    onSuccess={() => {
                        setShowTradeForm(false);
                        window.location.reload();
                    }}
                />
            )}
        </div>
    );
};

export default TradingPage;


