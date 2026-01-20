# Environment Configuration for Holding System

## Required Environment Variables

Add the following variables to your `.env` file:

```env
# Alpha Vantage API (for stock market data)
ALPHA_VANTAGE_API_KEY=your_alpha_vantage_api_key_here

# CoinMarketCap Pro API (for cryptocurrency data)
COINMARKETCAP_API_KEY=your_coinmarketcap_api_key_here
```

## How to Get API Keys

### 1. Alpha Vantage API Key
1. Go to https://www.alphavantage.co/
2. Sign up for a free account
3. Get your API key from the dashboard
4. Add it to your `.env` file

### 2. CoinMarketCap Pro API Key
1. Go to https://pro.coinmarketcap.com/account
2. Sign up for a Pro account
3. Get your API key from the dashboard
4. Add it to your `.env` file

## API Rate Limits

### Alpha Vantage (Free Tier)
- **Rate limit**: 5 API calls per minute, 500 per day
- **Update frequency**: Every 15 minutes for stocks
- **Features**: Real-time stock prices, historical data

### CoinMarketCap Pro
- **Rate limit**: Varies by plan (Basic: 10,000 calls/month, Pro: 100,000 calls/month)
- **Update frequency**: Every 5 minutes for crypto
- **Features**: 2,000+ cryptocurrencies, market data, historical data

## Configuration Notes

1. **Alpha Vantage Key**: Required for stock market data
2. **CoinMarketCap Pro Key**: Required for cryptocurrency data
3. **Rate Limiting**: Built into the services to respect API limits
4. **Error Handling**: Comprehensive logging for API failures

## Testing the Configuration

After adding the environment variables:

1. Clear config cache:
```bash
php artisan config:clear
```

2. Test the configuration:
```bash
php artisan tinker
```

```php
// Test Alpha Vantage configuration
config('services.alpha_vantage.key');

// Test CoinMarketCap configuration
config('services.coinmarketcap.key');

// Test Asset Price Service
$service = app(\App\Services\AssetPriceService::class);
```

## Security Notes

- Never commit API keys to version control
- Use environment variables for all sensitive data
- Monitor API usage to stay within rate limits
- Implement proper error handling for API failures
