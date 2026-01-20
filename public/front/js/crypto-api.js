/**
 * Cryptocurrency API integration
 * Fetches real-time cryptocurrency data and updates the UI
 */

document.addEventListener('DOMContentLoaded', function() {
    // Check if the crypto price elements exist on the page
    if (document.querySelector('.crypto-price-container')) {
        fetchCryptoPrices();
        
        // Update prices every 60 seconds
        setInterval(fetchCryptoPrices, 60000);
    }
});

/**
 * Fetch cryptocurrency prices from CoinGecko API
 */
async function fetchCryptoPrices() {
    try {
        const response = await fetch('https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&ids=bitcoin,ethereum,binancecoin,solana,ripple,cardano,polkadot&order=market_cap_desc&per_page=100&page=1&sparkline=false&price_change_percentage=24h');
        
        if (!response.ok) {
            throw new Error(`API Error: ${response.status}`);
        }
        
        const data = await response.json();
        updateCryptoPriceUI(data);
    } catch (error) {
        console.error('Error fetching cryptocurrency data:', error);
        // If API fails, don't update the UI
    }
}

/**
 * Update the UI with the fetched cryptocurrency data
 */
function updateCryptoPriceUI(cryptoData) {
    const cryptoMap = {
        'bitcoin': 'BTC',
        'ethereum': 'ETH',
        'binancecoin': 'BNB',
        'solana': 'SOL',
        'ripple': 'XRP',
        'cardano': 'ADA',
        'polkadot': 'DOT'
    };
    
    cryptoData.forEach(crypto => {
        const symbol = cryptoMap[crypto.id];
        if (!symbol) return;
        
        const priceElement = document.querySelector(`.crypto-${symbol.toLowerCase()}-price`);
        const changeElement = document.querySelector(`.crypto-${symbol.toLowerCase()}-change`);
        
        if (priceElement) {
            priceElement.textContent = `$${crypto.current_price.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        }
        
        if (changeElement) {
            const changePercent = crypto.price_change_percentage_24h;
            const isPositive = changePercent >= 0;
            
            changeElement.textContent = `${isPositive ? '+' : ''}${changePercent.toFixed(2)}%`;
            changeElement.className = isPositive ? 
                'text-green-500 text-sm crypto-change crypto-' + symbol.toLowerCase() + '-change' : 
                'text-red-500 text-sm crypto-change crypto-' + symbol.toLowerCase() + '-change';
        }
    });
}
