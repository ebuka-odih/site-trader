<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asset;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        // Crypto Assets (57 popular cryptocurrencies with realistic 24h changes)
        $cryptoAssets = [
            ['symbol' => 'BTC', 'name' => 'Bitcoin', 'type' => 'crypto', 'current_price' => 45000.00, 'price_change_24h' => 2.5, 'market_cap' => 850000000000],
            ['symbol' => 'ETH', 'name' => 'Ethereum', 'type' => 'crypto', 'current_price' => 3200.00, 'price_change_24h' => -1.2, 'market_cap' => 380000000000],
            ['symbol' => 'BNB', 'name' => 'Binance Coin', 'type' => 'crypto', 'current_price' => 320.00, 'price_change_24h' => 0.8, 'market_cap' => 48000000000],
            ['symbol' => 'ADA', 'name' => 'Cardano', 'type' => 'crypto', 'current_price' => 0.45, 'price_change_24h' => 3.1, 'market_cap' => 15000000000],
            ['symbol' => 'SOL', 'name' => 'Solana', 'type' => 'crypto', 'current_price' => 95.00, 'price_change_24h' => -0.5, 'market_cap' => 42000000000],
            ['symbol' => 'XRP', 'name' => 'Ripple', 'type' => 'crypto', 'current_price' => 0.52, 'price_change_24h' => 1.8, 'market_cap' => 28000000000],
            ['symbol' => 'DOT', 'name' => 'Polkadot', 'type' => 'crypto', 'current_price' => 7.25, 'price_change_24h' => -2.1, 'market_cap' => 8500000000],
            ['symbol' => 'MATIC', 'name' => 'Polygon', 'type' => 'crypto', 'current_price' => 0.85, 'price_change_24h' => 4.2, 'market_cap' => 8500000000],
            ['symbol' => 'LINK', 'name' => 'Chainlink', 'type' => 'crypto', 'current_price' => 15.80, 'price_change_24h' => -0.7, 'market_cap' => 8500000000],
            ['symbol' => 'UNI', 'name' => 'Uniswap', 'type' => 'crypto', 'current_price' => 8.45, 'price_change_24h' => 1.5, 'market_cap' => 5000000000],
            ['symbol' => 'AVAX', 'name' => 'Avalanche', 'type' => 'crypto', 'current_price' => 35.60, 'price_change_24h' => -1.8, 'market_cap' => 12000000000],
            ['symbol' => 'ATOM', 'name' => 'Cosmos', 'type' => 'crypto', 'current_price' => 12.30, 'price_change_24h' => 2.3, 'market_cap' => 4500000000],
            ['symbol' => 'LTC', 'name' => 'Litecoin', 'type' => 'crypto', 'current_price' => 68.50, 'price_change_24h' => 0.9, 'market_cap' => 4800000000],
            ['symbol' => 'BCH', 'name' => 'Bitcoin Cash', 'type' => 'crypto', 'current_price' => 245.80, 'price_change_24h' => -0.4, 'market_cap' => 4800000000],
            ['symbol' => 'XLM', 'name' => 'Stellar', 'type' => 'crypto', 'current_price' => 0.12, 'price_change_24h' => 1.2, 'market_cap' => 3200000000],
            ['symbol' => 'VET', 'name' => 'VeChain', 'type' => 'crypto', 'current_price' => 0.025, 'price_change_24h' => 3.5, 'market_cap' => 1800000000],
            ['symbol' => 'FIL', 'name' => 'Filecoin', 'type' => 'crypto', 'current_price' => 5.80, 'price_change_24h' => -1.5, 'market_cap' => 2800000000],
            ['symbol' => 'TRX', 'name' => 'TRON', 'type' => 'crypto', 'current_price' => 0.085, 'price_change_24h' => 2.1, 'market_cap' => 7800000000],
            ['symbol' => 'ETC', 'name' => 'Ethereum Classic', 'type' => 'crypto', 'current_price' => 22.40, 'price_change_24h' => -0.8, 'market_cap' => 3200000000],
            ['symbol' => 'THETA', 'name' => 'Theta Network', 'type' => 'crypto', 'current_price' => 1.85, 'price_change_24h' => 4.7, 'market_cap' => 1800000000],
            ['symbol' => 'XMR', 'name' => 'Monero', 'type' => 'crypto', 'current_price' => 165.30, 'price_change_24h' => 1.3, 'market_cap' => 3000000000],
            ['symbol' => 'EOS', 'name' => 'EOS', 'type' => 'crypto', 'current_price' => 0.75, 'price_change_24h' => -2.4, 'market_cap' => 850000000],
            ['symbol' => 'AAVE', 'name' => 'Aave', 'type' => 'crypto', 'current_price' => 285.50, 'price_change_24h' => 3.2, 'market_cap' => 4200000000],
            ['symbol' => 'ALGO', 'name' => 'Algorand', 'type' => 'crypto', 'current_price' => 0.18, 'price_change_24h' => 1.8, 'market_cap' => 1200000000],
            ['symbol' => 'NEO', 'name' => 'Neo', 'type' => 'crypto', 'current_price' => 12.80, 'price_change_24h' => -1.2, 'market_cap' => 900000000],
            ['symbol' => 'XTZ', 'name' => 'Tezos', 'type' => 'crypto', 'current_price' => 1.25, 'price_change_24h' => 2.7, 'market_cap' => 1100000000],
            ['symbol' => 'DASH', 'name' => 'Dash', 'type' => 'crypto', 'current_price' => 45.60, 'price_change_24h' => -0.9, 'market_cap' => 500000000],
            ['symbol' => 'ZEC', 'name' => 'Zcash', 'type' => 'crypto', 'current_price' => 28.90, 'price_change_24h' => 1.6, 'market_cap' => 400000000],
            ['symbol' => 'COMP', 'name' => 'Compound', 'type' => 'crypto', 'current_price' => 65.40, 'price_change_24h' => -2.1, 'market_cap' => 650000000],
            ['symbol' => 'MKR', 'name' => 'Maker', 'type' => 'crypto', 'current_price' => 1850.00, 'price_change_24h' => 1.8, 'market_cap' => 1800000000],
            ['symbol' => 'SNX', 'name' => 'Synthetix', 'type' => 'crypto', 'current_price' => 3.85, 'price_change_24h' => 4.2, 'market_cap' => 1200000000],
            ['symbol' => 'YFI', 'name' => 'yearn.finance', 'type' => 'crypto', 'current_price' => 8500.00, 'price_change_24h' => -1.5, 'market_cap' => 280000000],
            ['symbol' => 'SUSHI', 'name' => 'SushiSwap', 'type' => 'crypto', 'current_price' => 1.25, 'price_change_24h' => 2.8, 'market_cap' => 240000000],
            ['symbol' => 'CRV', 'name' => 'Curve DAO Token', 'type' => 'crypto', 'current_price' => 0.65, 'price_change_24h' => 1.9, 'market_cap' => 700000000],
            ['symbol' => '1INCH', 'name' => '1inch', 'type' => 'crypto', 'current_price' => 0.45, 'price_change_24h' => 3.4, 'market_cap' => 280000000],
            ['symbol' => 'ENJ', 'name' => 'Enjin Coin', 'type' => 'crypto', 'current_price' => 0.35, 'price_change_24h' => -1.7, 'market_cap' => 350000000],
            ['symbol' => 'MANA', 'name' => 'Decentraland', 'type' => 'crypto', 'current_price' => 0.45, 'price_change_24h' => 2.1, 'market_cap' => 850000000],
            ['symbol' => 'SAND', 'name' => 'The Sandbox', 'type' => 'crypto', 'current_price' => 0.65, 'price_change_24h' => 5.2, 'market_cap' => 1200000000],
            ['symbol' => 'AXS', 'name' => 'Axie Infinity', 'type' => 'crypto', 'current_price' => 8.50, 'price_change_24h' => -2.8, 'market_cap' => 1100000000],
            ['symbol' => 'GALA', 'name' => 'Gala', 'type' => 'crypto', 'current_price' => 0.025, 'price_change_24h' => 4.5, 'market_cap' => 650000000],
            ['symbol' => 'CHZ', 'name' => 'Chiliz', 'type' => 'crypto', 'current_price' => 0.085, 'price_change_24h' => 1.2, 'market_cap' => 500000000],
            ['symbol' => 'HOT', 'name' => 'Holochain', 'type' => 'crypto', 'current_price' => 0.0025, 'price_change_24h' => 6.8, 'market_cap' => 400000000],
            ['symbol' => 'BAT', 'name' => 'Basic Attention Token', 'type' => 'crypto', 'current_price' => 0.25, 'price_change_24h' => -0.8, 'market_cap' => 380000000],
            ['symbol' => 'ZRX', 'name' => '0x Protocol', 'type' => 'crypto', 'current_price' => 0.35, 'price_change_24h' => 2.4, 'market_cap' => 300000000],
            ['symbol' => 'REP', 'name' => 'Augur', 'type' => 'crypto', 'current_price' => 12.50, 'price_change_24h' => -1.3, 'market_cap' => 130000000],
            ['symbol' => 'KNC', 'name' => 'Kyber Network', 'type' => 'crypto', 'current_price' => 1.85, 'price_change_24h' => 3.1, 'market_cap' => 320000000],
            ['symbol' => 'BAND', 'name' => 'Band Protocol', 'type' => 'crypto', 'current_price' => 2.45, 'price_change_24h' => -0.6, 'market_cap' => 180000000],
            ['symbol' => 'REN', 'name' => 'Ren', 'type' => 'crypto', 'current_price' => 0.085, 'price_change_24h' => 2.7, 'market_cap' => 85000000],
            ['symbol' => 'RSR', 'name' => 'Reserve Rights', 'type' => 'crypto', 'current_price' => 0.015, 'price_change_24h' => 4.8, 'market_cap' => 75000000],
            ['symbol' => 'STORJ', 'name' => 'Storj', 'type' => 'crypto', 'current_price' => 0.65, 'price_change_24h' => 1.9, 'market_cap' => 250000000],
            ['symbol' => 'ANKR', 'name' => 'Ankr', 'type' => 'crypto', 'current_price' => 0.035, 'price_change_24h' => 3.2, 'market_cap' => 350000000],
            ['symbol' => 'OCEAN', 'name' => 'Ocean Protocol', 'type' => 'crypto', 'current_price' => 0.45, 'price_change_24h' => -1.1, 'market_cap' => 250000000],
            ['symbol' => 'ALPHA', 'name' => 'Alpha Finance Lab', 'type' => 'crypto', 'current_price' => 0.85, 'price_change_24h' => 5.4, 'market_cap' => 120000000],
            ['symbol' => 'PERP', 'name' => 'Perpetual Protocol', 'type' => 'crypto', 'current_price' => 1.25, 'price_change_24h' => 2.8, 'market_cap' => 85000000],
            ['symbol' => 'BADGER', 'name' => 'Badger DAO', 'type' => 'crypto', 'current_price' => 8.50, 'price_change_24h' => -2.1, 'market_cap' => 180000000],
            ['symbol' => 'FARM', 'name' => 'Harvest Finance', 'type' => 'crypto', 'current_price' => 45.60, 'price_change_24h' => 1.7, 'market_cap' => 150000000],
            ['symbol' => 'KP3R', 'name' => 'Keep3rV1', 'type' => 'crypto', 'current_price' => 125.80, 'price_change_24h' => 3.9, 'market_cap' => 85000000],
        ];

        // Stock Assets (31 popular stocks with realistic 24h changes)
        $stockAssets = [
            ['symbol' => 'AAPL', 'name' => 'Apple Inc.', 'type' => 'stock', 'current_price' => 175.50, 'price_change_24h' => 1.2, 'market_cap' => 2750000000000],
            ['symbol' => 'GOOGL', 'name' => 'Alphabet Inc.', 'type' => 'stock', 'current_price' => 142.80, 'price_change_24h' => -0.8, 'market_cap' => 1800000000000],
            ['symbol' => 'MSFT', 'name' => 'Microsoft Corporation', 'type' => 'stock', 'current_price' => 330.25, 'price_change_24h' => 0.9, 'market_cap' => 2450000000000],
            ['symbol' => 'TSLA', 'name' => 'Tesla Inc.', 'type' => 'stock', 'current_price' => 245.60, 'price_change_24h' => 2.1, 'market_cap' => 780000000000],
            ['symbol' => 'AMZN', 'name' => 'Amazon.com Inc.', 'type' => 'stock', 'current_price' => 135.40, 'price_change_24h' => -0.3, 'market_cap' => 1400000000000],
            ['symbol' => 'NVDA', 'name' => 'NVIDIA Corporation', 'type' => 'stock', 'current_price' => 485.20, 'price_change_24h' => 3.5, 'market_cap' => 1200000000000],
            ['symbol' => 'META', 'name' => 'Meta Platforms Inc.', 'type' => 'stock', 'current_price' => 325.80, 'price_change_24h' => -1.2, 'market_cap' => 850000000000],
            ['symbol' => 'BRK.A', 'name' => 'Berkshire Hathaway Inc.', 'type' => 'stock', 'current_price' => 485000.00, 'price_change_24h' => 0.8, 'market_cap' => 700000000000],
            ['symbol' => 'JNJ', 'name' => 'Johnson & Johnson', 'type' => 'stock', 'current_price' => 165.40, 'price_change_24h' => 0.5, 'market_cap' => 400000000000],
            ['symbol' => 'JPM', 'name' => 'JPMorgan Chase & Co.', 'type' => 'stock', 'current_price' => 145.60, 'price_change_24h' => -0.7, 'market_cap' => 420000000000],
            ['symbol' => 'V', 'name' => 'Visa Inc.', 'type' => 'stock', 'current_price' => 245.80, 'price_change_24h' => 1.1, 'market_cap' => 500000000000],
            ['symbol' => 'PG', 'name' => 'Procter & Gamble Co.', 'type' => 'stock', 'current_price' => 155.20, 'price_change_24h' => 0.3, 'market_cap' => 370000000000],
            ['symbol' => 'HD', 'name' => 'The Home Depot Inc.', 'type' => 'stock', 'current_price' => 325.40, 'price_change_24h' => -0.4, 'market_cap' => 320000000000],
            ['symbol' => 'MA', 'name' => 'Mastercard Inc.', 'type' => 'stock', 'current_price' => 385.60, 'price_change_24h' => 1.8, 'market_cap' => 380000000000],
            ['symbol' => 'UNH', 'name' => 'UnitedHealth Group Inc.', 'type' => 'stock', 'current_price' => 485.20, 'price_change_24h' => 0.9, 'market_cap' => 450000000000],
            ['symbol' => 'DIS', 'name' => 'The Walt Disney Company', 'type' => 'stock', 'current_price' => 85.40, 'price_change_24h' => -2.1, 'market_cap' => 155000000000],
            ['symbol' => 'ADBE', 'name' => 'Adobe Inc.', 'type' => 'stock', 'current_price' => 485.60, 'price_change_24h' => 2.3, 'market_cap' => 220000000000],
            ['symbol' => 'CRM', 'name' => 'Salesforce Inc.', 'type' => 'stock', 'current_price' => 245.80, 'price_change_24h' => -1.5, 'market_cap' => 240000000000],
            ['symbol' => 'NFLX', 'name' => 'Netflix Inc.', 'type' => 'stock', 'current_price' => 485.20, 'price_change_24h' => 3.2, 'market_cap' => 210000000000],
            ['symbol' => 'PYPL', 'name' => 'PayPal Holdings Inc.', 'type' => 'stock', 'current_price' => 65.40, 'price_change_24h' => -0.8, 'market_cap' => 75000000000],
            ['symbol' => 'INTC', 'name' => 'Intel Corporation', 'type' => 'stock', 'current_price' => 35.60, 'price_change_24h' => 1.2, 'market_cap' => 150000000000],
            ['symbol' => 'WMT', 'name' => 'Walmart Inc.', 'type' => 'stock', 'current_price' => 165.80, 'price_change_24h' => 0.6, 'market_cap' => 420000000000],
            ['symbol' => 'KO', 'name' => 'The Coca-Cola Company', 'type' => 'stock', 'current_price' => 55.40, 'price_change_24h' => 0.4, 'market_cap' => 240000000000],
            ['symbol' => 'PEP', 'name' => 'PepsiCo Inc.', 'type' => 'stock', 'current_price' => 175.60, 'price_change_24h' => 0.7, 'market_cap' => 240000000000],
            ['symbol' => 'ABT', 'name' => 'Abbott Laboratories', 'type' => 'stock', 'current_price' => 105.80, 'price_change_24h' => -0.3, 'market_cap' => 185000000000],
            ['symbol' => 'TMO', 'name' => 'Thermo Fisher Scientific Inc.', 'type' => 'stock', 'current_price' => 485.40, 'price_change_24h' => 1.1, 'market_cap' => 200000000000],
            ['symbol' => 'COST', 'name' => 'Costco Wholesale Corporation', 'type' => 'stock', 'current_price' => 685.20, 'price_change_24h' => 0.8, 'market_cap' => 300000000000],
            ['symbol' => 'ABBV', 'name' => 'AbbVie Inc.', 'type' => 'stock', 'current_price' => 145.60, 'price_change_24h' => -0.5, 'market_cap' => 260000000000],
            ['symbol' => 'LLY', 'name' => 'Eli Lilly and Company', 'type' => 'stock', 'current_price' => 685.40, 'price_change_24h' => 2.1, 'market_cap' => 650000000000],
            ['symbol' => 'TXN', 'name' => 'Texas Instruments Inc.', 'type' => 'stock', 'current_price' => 165.80, 'price_change_24h' => 0.9, 'market_cap' => 150000000000],
            ['symbol' => 'QCOM', 'name' => 'QUALCOMM Inc.', 'type' => 'stock', 'current_price' => 125.40, 'price_change_24h' => -0.6, 'market_cap' => 140000000000],
            ['symbol' => 'SPY', 'name' => 'SPDR S&P 500 ETF Trust', 'type' => 'stock', 'current_price' => 445.70, 'price_change_24h' => 0.4, 'market_cap' => 430000000000],
            ['symbol' => 'QQQ', 'name' => 'Invesco QQQ Trust', 'type' => 'stock', 'current_price' => 372.20, 'price_change_24h' => 0.7, 'market_cap' => 200000000000],
            ['symbol' => 'IWM', 'name' => 'iShares Russell 2000 ETF', 'type' => 'stock', 'current_price' => 190.30, 'price_change_24h' => -0.5, 'market_cap' => 65000000000],
            ['symbol' => 'MO', 'name' => 'Altria Group Inc.', 'type' => 'stock', 'current_price' => 44.10, 'price_change_24h' => 0.3, 'market_cap' => 77000000000],
            ['symbol' => 'HOOD', 'name' => 'Robinhood Markets Inc.', 'type' => 'stock', 'current_price' => 12.85, 'price_change_24h' => 1.6, 'market_cap' => 11000000000],
            ['symbol' => 'AMD', 'name' => 'Advanced Micro Devices Inc.', 'type' => 'stock', 'current_price' => 115.20, 'price_change_24h' => 2.4, 'market_cap' => 180000000000],
            ['symbol' => 'BA', 'name' => 'The Boeing Company', 'type' => 'stock', 'current_price' => 208.50, 'price_change_24h' => -1.1, 'market_cap' => 125000000000],
            ['symbol' => 'NKE', 'name' => 'Nike Inc.', 'type' => 'stock', 'current_price' => 101.40, 'price_change_24h' => 0.8, 'market_cap' => 155000000000],
            ['symbol' => 'MCD', 'name' => 'McDonald\'s Corporation', 'type' => 'stock', 'current_price' => 286.10, 'price_change_24h' => 0.5, 'market_cap' => 210000000000],
            ['symbol' => 'ORCL', 'name' => 'Oracle Corporation', 'type' => 'stock', 'current_price' => 117.80, 'price_change_24h' => -0.9, 'market_cap' => 330000000000],
            ['symbol' => 'IBM', 'name' => 'International Business Machines Corporation', 'type' => 'stock', 'current_price' => 138.20, 'price_change_24h' => 0.2, 'market_cap' => 125000000000],
            ['symbol' => 'GS', 'name' => 'The Goldman Sachs Group Inc.', 'type' => 'stock', 'current_price' => 330.40, 'price_change_24h' => -0.6, 'market_cap' => 110000000000],
            ['symbol' => 'CAT', 'name' => 'Caterpillar Inc.', 'type' => 'stock', 'current_price' => 265.10, 'price_change_24h' => 1.0, 'market_cap' => 135000000000],
            ['symbol' => 'SBUX', 'name' => 'Starbucks Corporation', 'type' => 'stock', 'current_price' => 97.20, 'price_change_24h' => 0.4, 'market_cap' => 110000000000],
            ['symbol' => 'INTU', 'name' => 'Intuit Inc.', 'type' => 'stock', 'current_price' => 525.90, 'price_change_24h' => 1.9, 'market_cap' => 150000000000],
            ['symbol' => 'BKNG', 'name' => 'Booking Holdings Inc.', 'type' => 'stock', 'current_price' => 3180.00, 'price_change_24h' => 2.2, 'market_cap' => 110000000000],
            ['symbol' => 'SHOP', 'name' => 'Shopify Inc.', 'type' => 'stock', 'current_price' => 65.40, 'price_change_24h' => 3.1, 'market_cap' => 83000000000],
            ['symbol' => 'UBER', 'name' => 'Uber Technologies Inc.', 'type' => 'stock', 'current_price' => 48.75, 'price_change_24h' => 1.5, 'market_cap' => 96000000000],
            ['symbol' => 'LYFT', 'name' => 'Lyft Inc.', 'type' => 'stock', 'current_price' => 12.30, 'price_change_24h' => -0.4, 'market_cap' => 4500000000],
            ['symbol' => 'SQ', 'name' => 'Block Inc.', 'type' => 'stock', 'current_price' => 58.60, 'price_change_24h' => 2.0, 'market_cap' => 36000000000],
            ['symbol' => 'PLTR', 'name' => 'Palantir Technologies Inc.', 'type' => 'stock', 'current_price' => 18.40, 'price_change_24h' => 1.9, 'market_cap' => 39000000000],
            ['symbol' => 'SNOW', 'name' => 'Snowflake Inc.', 'type' => 'stock', 'current_price' => 180.20, 'price_change_24h' => -1.4, 'market_cap' => 58000000000],
            ['symbol' => 'PANW', 'name' => 'Palo Alto Networks Inc.', 'type' => 'stock', 'current_price' => 245.70, 'price_change_24h' => 2.7, 'market_cap' => 78000000000],
            ['symbol' => 'DOCU', 'name' => 'DocuSign Inc.', 'type' => 'stock', 'current_price' => 42.80, 'price_change_24h' => -0.7, 'market_cap' => 8800000000],
            ['symbol' => 'CRWD', 'name' => 'CrowdStrike Holdings Inc.', 'type' => 'stock', 'current_price' => 206.50, 'price_change_24h' => 2.9, 'market_cap' => 48000000000],
            ['symbol' => 'TWLO', 'name' => 'Twilio Inc.', 'type' => 'stock', 'current_price' => 55.10, 'price_change_24h' => 1.0, 'market_cap' => 10000000000],
            ['symbol' => 'ZM', 'name' => 'Zoom Video Communications Inc.', 'type' => 'stock', 'current_price' => 67.40, 'price_change_24h' => -0.8, 'market_cap' => 20000000000],
            ['symbol' => 'RIVN', 'name' => 'Rivian Automotive Inc.', 'type' => 'stock', 'current_price' => 21.60, 'price_change_24h' => 4.4, 'market_cap' => 20500000000],
            ['symbol' => 'LCID', 'name' => 'Lucid Group Inc.', 'type' => 'stock', 'current_price' => 5.10, 'price_change_24h' => -1.9, 'market_cap' => 11500000000],
            ['symbol' => 'F', 'name' => 'Ford Motor Company', 'type' => 'stock', 'current_price' => 12.40, 'price_change_24h' => 0.6, 'market_cap' => 49500000000],
            ['symbol' => 'GM', 'name' => 'General Motors Company', 'type' => 'stock', 'current_price' => 34.20, 'price_change_24h' => 0.5, 'market_cap' => 48000000000],
        ];

        foreach ($cryptoAssets as $asset) {
            Asset::updateOrCreate(
                ['symbol' => $asset['symbol'], 'type' => $asset['type']],
                $asset
            );
        }

        foreach ($stockAssets as $asset) {
            Asset::updateOrCreate(
                ['symbol' => $asset['symbol'], 'type' => $asset['type']],
                $asset
            );
        }

        $this->command->info('Assets seeded successfully!');
        $this->command->info('Crypto assets: ' . count($cryptoAssets));
        $this->command->info('Stock assets: ' . count($stockAssets));
    }
}
