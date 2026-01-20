import React, { useState, useEffect } from 'react';

const formatCurrency = (value = 0, decimals = 2) =>
    new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    }).format(Number(value));

const TradeForm = ({ asset, assetType, tradingBalance, currentPrice, onClose, onSuccess, allAssets = [], quickPicks = [] }) => {
    const [side, setSide] = useState('buy');
    const [orderType, setOrderType] = useState('market');
    const [amount, setAmount] = useState('');
    const [quantity, setQuantity] = useState('');
    const [price, setPrice] = useState('');
    const [leverage, setLeverage] = useState(1);
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [error, setError] = useState('');
    const [success, setSuccess] = useState('');
    const [showAssetDropdown, setShowAssetDropdown] = useState(false);
    const [searchTerm, setSearchTerm] = useState('');
    const [selectedAsset, setSelectedAsset] = useState(asset);

    // Calculate quantity from amount and current price
    useEffect(() => {
        if (orderType === 'market' && amount && currentPrice > 0) {
            const numAmount = parseFloat(amount);
            if (!isNaN(numAmount) && numAmount > 0) {
                const calculatedQuantity = numAmount / currentPrice;
                // Show up to 8 decimal places, remove trailing zeros
                setQuantity(calculatedQuantity.toFixed(8).replace(/\.?0+$/, ''));
            } else {
                setQuantity('');
            }
        } else if (!amount) {
            setQuantity('');
        }
    }, [amount, currentPrice, orderType]);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');
        setSuccess('');

        // Validation
        if (orderType === 'market' && !amount && !quantity) {
            setError('Please enter either amount or quantity');
            return;
        }

        if (orderType === 'limit' && (!price || !amount || !quantity)) {
            setError('Please fill in all required fields for limit orders');
            return;
        }

        setIsSubmitting(true);

        try {
            const currentAsset = selectedAsset || asset;
            const formData = new FormData();
            formData.append('asset_type', currentAsset.type || assetType);
            formData.append('symbol', currentAsset.symbol || asset.symbol);
            formData.append('side', side);
            formData.append('order_type', orderType);
            formData.append('leverage', leverage);
            
            if (orderType === 'market') {
                if (amount) formData.append('amount', amount);
                if (quantity) formData.append('quantity', quantity);
            } else {
                formData.append('price', price);
                if (amount) formData.append('amount', amount);
                if (quantity) formData.append('quantity', quantity);
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            const response = await fetch('/user/live-trading', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            const data = await response.json();

            if (data.success) {
                setSuccess(data.message || 'Trade placed successfully!');
                setTimeout(() => {
                    onSuccess();
                }, 1000);
            } else {
                setError(data.message || 'Failed to place trade. Please try again.');
            }
        } catch (err) {
            console.error('Error placing trade:', err);
            setError('An error occurred while placing the trade. Please try again.');
        } finally {
            setIsSubmitting(false);
        }
    };

    return (
        <div 
            className="fixed inset-0 z-[9999] flex items-center justify-center p-4"
            style={{ 
                position: 'fixed', 
                top: 0, 
                left: 0, 
                right: 0, 
                bottom: 0,
                margin: 0,
                padding: '1rem',
                display: 'flex',
                alignItems: 'flex-start',
                justifyContent: 'center',
                paddingTop: '5vh',
                zIndex: 9999,
                backgroundColor: 'rgba(0, 0, 0, 0.95)',
                backdropFilter: 'blur(12px)'
            }}
            onClick={(e) => e.target === e.currentTarget && onClose()}
        >
            <div 
                className="w-full max-w-md rounded-2xl bg-background shadow-xl overflow-hidden flex flex-col"
                style={{ 
                    maxHeight: '80vh',
                    margin: '0 auto',
                    backgroundColor: 'hsl(var(--background))',
                    boxShadow: '0 25px 50px -12px rgba(0, 0, 0, 0.5)'
                }}
            >
                {/* Header */}
                <div className="px-6 py-5 border-b border-border flex items-start justify-between flex-shrink-0">
                    <div>
                        <p className={`text-sm uppercase font-bold tracking-wider mb-1 ${
                            side === 'buy' ? 'text-primary' : 'text-red-500'
                        }`}>
                            {side.toUpperCase()}
                        </p>
                        <h2 className="text-2xl font-bold text-foreground">
                            {(selectedAsset || asset)?.name || (selectedAsset || asset)?.symbol}
                        </h2>
                    </div>
                    <button
                        onClick={onClose}
                        className="text-muted-foreground hover:text-foreground transition-colors p-1"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className="w-5 h-5">
                            <path d="M18 6 6 18"></path>
                            <path d="m6 6 12 12"></path>
                        </svg>
                    </button>
                </div>

                {/* Form Content - Scrollable */}
                <form onSubmit={handleSubmit} className="flex-1 overflow-y-auto px-6 py-5 space-y-5">
                    {/* Trading Balance */}
                    <div className="flex items-center justify-between py-3 px-4 rounded-lg bg-card border border-border">
                        <span className="text-sm text-muted-foreground">Trading Balance</span>
                        <span className="text-base font-bold text-foreground">
                            {formatCurrency(tradingBalance)}
                        </span>
                    </div>

                    {/* Asset Display */}
                    <button
                        type="button"
                        onClick={() => setShowAssetDropdown(true)}
                        className="w-full rounded-lg border border-border bg-background px-4 py-3.5 text-left text-foreground flex items-center justify-between cursor-pointer hover:border-primary/50 transition-colors"
                    >
                        <div className="flex items-center gap-2 flex-1 min-w-0">
                            <span className="font-mono font-semibold">{selectedAsset?.symbol || asset?.symbol}</span>
                            <span className="text-muted-foreground truncate">{selectedAsset?.name || asset?.name}</span>
                        </div>
                        <svg className="w-5 h-5 text-muted-foreground flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    {/* Buy/Sell Toggle */}
                    <div className="grid grid-cols-2 gap-3">
                        <button
                            type="button"
                            onClick={() => setSide('buy')}
                            className={`rounded-lg px-4 py-3.5 font-semibold text-sm transition ${
                                side === 'buy'
                                    ? 'bg-primary text-black'
                                    : 'border border-border bg-background text-muted-foreground hover:bg-card'
                            }`}
                        >
                            Buy
                        </button>
                        <button
                            type="button"
                            onClick={() => setSide('sell')}
                            className={`rounded-lg px-4 py-3.5 font-semibold text-sm transition ${
                                side === 'sell'
                                    ? 'bg-red-500 text-white'
                                    : 'border border-border bg-background text-muted-foreground hover:bg-card'
                            }`}
                        >
                            Sell
                        </button>
                    </div>

                    {/* Order Type Toggle */}
                    <div className="space-y-2">
                        <label className="text-sm font-medium text-muted-foreground">Order Type</label>
                        <div className="grid grid-cols-2 gap-3">
                            <button
                                type="button"
                                onClick={() => setOrderType('market')}
                                className={`rounded-full border-2 px-4 py-3 font-semibold text-sm transition ${
                                    orderType === 'market'
                                        ? 'border-primary bg-transparent text-primary'
                                        : 'border-border bg-background text-foreground hover:border-foreground/20'
                                }`}
                            >
                                Market
                            </button>
                            <button
                                type="button"
                                onClick={() => setOrderType('limit')}
                                className={`rounded-full border-2 px-4 py-3 font-semibold text-sm transition ${
                                    orderType === 'limit'
                                        ? 'border-primary bg-transparent text-primary'
                                        : 'border-border bg-background text-foreground hover:border-foreground/20'
                                }`}
                            >
                                Limit
                            </button>
                        </div>
                    </div>

                    {/* Amount Input */}
                    <div className="space-y-2">
                        <label className="text-sm font-medium text-muted-foreground">Amount</label>
                        <div className="relative">
                            <input
                                type="number"
                                min="0"
                                step="1"
                                value={amount}
                                onChange={(e) => setAmount(e.target.value)}
                                className="w-full rounded-lg border border-border bg-background px-4 py-3.5 text-foreground placeholder-muted-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
                                placeholder="Enter amount"
                            />
                            <div className="absolute right-3 top-1/2 -translate-y-1/2 flex flex-col gap-0.5">
                                <button
                                    type="button"
                                    onClick={() => setAmount(((parseInt(amount) || 0) + 10).toString())}
                                    className="text-muted-foreground hover:text-foreground p-0.5"
                                >
                                    <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                </button>
                                <button
                                    type="button"
                                    onClick={() => setAmount(Math.max(0, (parseInt(amount) || 0) - 10).toString())}
                                    className="text-muted-foreground hover:text-foreground p-0.5"
                                >
                                    <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    {/* Quantity Input - Readonly, calculated from amount */}
                    <div className="space-y-2">
                        <label className="text-sm font-medium text-muted-foreground">Quantity</label>
                        <input
                            type="text"
                            readOnly
                            value={quantity || ''}
                            className="w-full rounded-lg border border-border bg-background px-4 py-3.5 text-foreground font-medium placeholder-muted-foreground cursor-not-allowed"
                            placeholder="Calculated automatically"
                        />
                    </div>

                    {/* Limit Price Input */}
                    {orderType === 'limit' && (
                        <div className="space-y-2">
                            <label className="text-sm font-medium text-muted-foreground">Price</label>
                            <input
                                type="number"
                                min="0"
                                step="1"
                                value={price}
                                onChange={(e) => setPrice(e.target.value)}
                                className="w-full rounded-lg border border-border bg-background px-4 py-3.5 text-foreground placeholder-muted-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
                                placeholder="Enter price"
                            />
                        </div>
                    )}

                    {/* Leverage Slider */}
                    <div className="space-y-3">
                        <div className="flex items-center justify-between">
                            <label className="text-sm font-medium text-muted-foreground">Leverage</label>
                            <span className="text-lg font-bold text-primary">{leverage}x</span>
                        </div>
                        <div className="relative px-1">
                            <input
                                type="range"
                                min="1"
                                max="50"
                                step="1"
                                value={leverage}
                                onChange={(e) => setLeverage(parseInt(e.target.value))}
                                className="w-full h-2 bg-border rounded-lg appearance-none cursor-pointer slider"
                                style={{
                                    background: `linear-gradient(to right, hsl(var(--primary)) 0%, hsl(var(--primary)) ${((leverage - 1) / 49) * 100}%, hsl(var(--border)) ${((leverage - 1) / 49) * 100}%, hsl(var(--border)) 100%)`
                                }}
                            />
                            <div className="flex justify-between text-xs text-muted-foreground mt-2 px-1">
                                <span>1x</span>
                                <span>50x</span>
                            </div>
                        </div>
                        {leverage > 1 && (
                            <div className="rounded-lg border border-orange-500/30 bg-orange-500/10 px-4 py-3 text-sm text-orange-300">
                                Leverage ×{leverage} will create a futures position. Liquidation risk applies.
                            </div>
                        )}
                    </div>

                    {/* Error/Success Messages */}
                    {error && (
                        <div className="rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300">
                            {error}
                        </div>
                    )}
                    {success && (
                        <div className="rounded-lg border border-primary/30 bg-primary/10 px-4 py-3 text-sm text-primary">
                            {success}
                        </div>
                    )}

                    {/* Submit Button */}
                    <button
                        type="submit"
                        disabled={isSubmitting}
                        className={`w-full rounded-full py-4 text-base font-bold transition flex items-center justify-center gap-2 ${
                            side === 'buy'
                                ? 'bg-primary text-black hover:opacity-90'
                                : 'bg-red-500 text-white hover:opacity-90'
                        } disabled:opacity-50 disabled:cursor-not-allowed`}
                    >
                        {isSubmitting ? (
                            <>
                                <svg className="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                Processing...
                            </>
                        ) : (
                            `Place ${side.charAt(0).toUpperCase() + side.slice(1)} Order`
                        )}
                    </button>
                </form>
            </div>

            {/* Asset Dropdown Modal */}
            {showAssetDropdown && (
                <div 
                    className="fixed inset-0 z-[10000] flex items-start justify-center bg-black/80 p-4"
                    style={{ 
                        position: 'fixed', 
                        top: 0, 
                        left: 0, 
                        right: 0, 
                        bottom: 0,
                        zIndex: 10000,
                        paddingTop: '10vh',
                        alignItems: 'flex-start'
                    }}
                    onClick={(e) => e.target === e.currentTarget && setShowAssetDropdown(false)}
                >
                    <div className="w-full max-w-md rounded-2xl border border-border bg-background max-h-[75vh] overflow-hidden flex flex-col">
                        {/* Dropdown Header */}
                        <div className="p-4 border-b border-border">
                            <div className="flex items-center justify-between mb-4">
                                <h3 className="text-lg font-semibold text-foreground">Select Asset</h3>
                                <button
                                    type="button"
                                    onClick={() => setShowAssetDropdown(false)}
                                    className="text-muted-foreground hover:text-foreground"
                                >
                                    <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            {/* Search Bar */}
                            <div className="relative">
                                <input
                                    type="text"
                                    value={searchTerm}
                                    onChange={(e) => setSearchTerm(e.target.value)}
                                    placeholder="Search by symbol or name..."
                                    className="w-full rounded-xl border border-border bg-background px-4 py-2 pl-10 text-foreground placeholder-muted-foreground focus:border-primary focus:outline-none"
                                />
                                <svg className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        {/* Asset List */}
                        <div className="flex-1 overflow-y-auto p-4 space-y-4">
                            {/* Quick Picks */}
                            {quickPicks && quickPicks.length > 0 && (
                                <div>
                                    <h4 className="text-sm font-semibold text-muted-foreground mb-3">Quick Picks</h4>
                                    <div className="space-y-2">
                                        {quickPicks
                                            .filter(asset => {
                                                if (!searchTerm) return true;
                                                const term = searchTerm.toLowerCase();
                                                return asset.symbol?.toLowerCase().includes(term) || 
                                                       asset.name?.toLowerCase().includes(term);
                                            })
                                            .map((quickAsset) => {
                                                const isSelected = (selectedAsset?.symbol || asset?.symbol) === quickAsset.symbol;
                                                return (
                                                    <button
                                                        key={quickAsset.symbol}
                                                        type="button"
                                                        onClick={() => {
                                                            setSelectedAsset(quickAsset);
                                                            setShowAssetDropdown(false);
                                                            setSearchTerm('');
                                                            // Reload page with new asset
                                                            window.location.href = `/user/live-trading/trade?asset_type=${quickAsset.type}&symbol=${quickAsset.symbol}`;
                                                        }}
                                                        className={`w-full rounded-xl border px-4 py-3 text-left transition flex items-center justify-between ${
                                                            isSelected
                                                                ? 'border-primary bg-primary/10'
                                                                : 'border-border bg-background hover:bg-primary/10 hover:border-primary/30'
                                                        }`}
                                                    >
                                                        <div className="flex-1">
                                                            <div className="font-semibold text-foreground">
                                                                {quickAsset.symbol} {quickAsset.name}
                                                            </div>
                                                        </div>
                                                        <div className="flex items-center gap-2">
                                                            <span className={`px-2 py-1 rounded-full text-xs font-semibold ${
                                                                quickAsset.type === 'crypto' 
                                                                    ? 'bg-primary/20 text-primary' 
                                                                    : 'bg-muted text-muted-foreground'
                                                            }`}>
                                                                {quickAsset.type}
                                                            </span>
                                                            {isSelected && (
                                                                <svg className="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                            )}
                                                        </div>
                                                    </button>
                                                );
                                            })}
                                    </div>
                                </div>
                            )}

                            {/* All Assets */}
                            {allAssets && allAssets.length > 0 && (() => {
                                const filteredAssets = allAssets.filter(assetItem => {
                                    if (!searchTerm) return true;
                                    const term = searchTerm.toLowerCase();
                                    return assetItem.symbol?.toLowerCase().includes(term) || 
                                           assetItem.name?.toLowerCase().includes(term);
                                });
                                
                                const groupedAssets = filteredAssets.reduce((acc, assetItem) => {
                                    const type = assetItem.type || 'other';
                                    if (!acc[type]) acc[type] = [];
                                    acc[type].push(assetItem);
                                    return acc;
                                }, {});

                                return Object.keys(groupedAssets).length > 0 ? (
                                    <div>
                                        <h4 className="text-sm font-semibold text-muted-foreground mb-3">All Assets</h4>
                                        {Object.entries(groupedAssets).map(([type, assets]) => (
                                            <div key={type} className="mb-4">
                                                <h5 className="text-xs font-semibold text-muted-foreground mb-2 uppercase">
                                                    {type === 'crypto' ? 'Cryptocurrencies' : type === 'stock' ? 'Stocks' : type === 'etf' ? 'ETFs' : type}
                                                </h5>
                                                <div className="space-y-2">
                                                    {assets.map((assetItem) => {
                                                        const isSelected = (selectedAsset?.symbol || asset?.symbol) === assetItem.symbol;
                                                        return (
                                                            <button
                                                                key={assetItem.symbol}
                                                                type="button"
                                                                onClick={() => {
                                                                    setSelectedAsset(assetItem);
                                                                    setShowAssetDropdown(false);
                                                                    setSearchTerm('');
                                                                    // Reload page with new asset
                                                                    window.location.href = `/user/live-trading/trade?asset_type=${assetItem.type}&symbol=${assetItem.symbol}`;
                                                                }}
                                                                className={`w-full rounded-xl border px-4 py-3 text-left transition flex items-center justify-between ${
                                                                    isSelected
                                                                        ? 'border-primary bg-primary/10'
                                                                        : 'border-border bg-background hover:bg-primary/10 hover:border-primary/30'
                                                                }`}
                                                            >
                                                                <div className="flex-1">
                                                                    <div className="font-semibold text-foreground">
                                                                        {assetItem.symbol} {assetItem.name}
                                                                    </div>
                                                                </div>
                                                                <div className="flex items-center gap-2">
                                                                    <span className={`px-2 py-1 rounded-full text-xs font-semibold ${
                                                                        assetItem.type === 'crypto' 
                                                                            ? 'bg-primary/20 text-primary' 
                                                                            : 'bg-muted text-muted-foreground'
                                                                    }`}>
                                                                        {assetItem.type}
                                                                    </span>
                                                                    {isSelected && (
                                                                        <svg className="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                                                                        </svg>
                                                                    )}
                                                                </div>
                                                            </button>
                                                        );
                                                    })}
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                ) : null;
                            })()}

                            {(!quickPicks || quickPicks.length === 0) && (!allAssets || allAssets.length === 0) && (
                                <div className="text-center py-8 text-muted-foreground text-sm">
                                    No assets available
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
};

export default TradeForm;
