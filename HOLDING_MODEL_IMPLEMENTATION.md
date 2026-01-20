# Holding Model Implementation Documentation

## Overview
The holding model will allow users to buy, hold, and track real crypto and stock assets with live price updates and portfolio value calculations.

## 1. API Selection & Data Sources

### 1.1 Cryptocurrency APIs
**Recommended: CoinMarketCap Pro API**
- **URL**: https://pro-api.coinmarketcap.com/v1
- **Rate Limits**: Varies by plan (Basic: 10,000 calls/month, Pro: 100,000 calls/month)
- **Features**: 
  - Real-time crypto prices
  - Historical data
  - Market cap, volume, 24h changes
  - 2,000+ cryptocurrencies
  - Professional-grade data

**API Endpoints Needed:**
```
GET /cryptocurrency/quotes/latest?symbol=BTC,ETH&convert=USD
GET /cryptocurrency/map?limit=100&sort=cmc_rank
GET /cryptocurrency/info?symbol=BTC
```

### 1.2 Stock Market APIs
**Recommended: Alpha Vantage API (Free Tier)**
- **URL**: https://www.alphavantage.co/
- **Rate Limits**: 5 API calls per minute, 500 per day (free tier)
- **Features**:
  - Real-time stock prices
  - Historical data
  - Company information
  - Global markets support

## 2. Database Structure

### 2.1 New Tables Required

#### `assets` Table
```sql
CREATE TABLE assets (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    symbol VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    type ENUM('crypto', 'stock') NOT NULL,
    current_price DECIMAL(20,8) DEFAULT 0,
    market_cap DECIMAL(20,2) DEFAULT 0,
    volume_24h DECIMAL(20,2) DEFAULT 0,
    price_change_24h DECIMAL(10,4) DEFAULT 0,
    price_change_percentage_24h DECIMAL(10,4) DEFAULT 0,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### `user_holdings` Table
```sql
CREATE TABLE user_holdings (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    asset_id BIGINT UNSIGNED NOT NULL,
    quantity DECIMAL(20,8) NOT NULL DEFAULT 0,
    average_buy_price DECIMAL(20,8) NOT NULL DEFAULT 0,
    total_invested DECIMAL(20,8) NOT NULL DEFAULT 0,
    current_value DECIMAL(20,8) NOT NULL DEFAULT 0,
    unrealized_pnl DECIMAL(20,8) NOT NULL DEFAULT 0,
    unrealized_pnl_percentage DECIMAL(10,4) NOT NULL DEFAULT 0,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (asset_id) REFERENCES assets(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_asset (user_id, asset_id)
);
```

#### `holding_transactions` Table
```sql
CREATE TABLE holding_transactions (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    asset_id BIGINT UNSIGNED NOT NULL,
    type ENUM('buy', 'sell') NOT NULL,
    quantity DECIMAL(20,8) NOT NULL,
    price_per_unit DECIMAL(20,8) NOT NULL,
    total_amount DECIMAL(20,8) NOT NULL,
    fee DECIMAL(20,8) DEFAULT 0,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'completed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (asset_id) REFERENCES assets(id) ON DELETE CASCADE
);
```

## 3. Implementation Steps

### Phase 1: Database & Models (Week 1)
1. Create database migrations for all tables
2. Create Eloquent models with relationships
3. Add basic CRUD operations
4. Test database structure

### Phase 2: API Integration (Week 2)
1. Set up API keys and services
2. Implement price update services
3. Create scheduled commands
4. Test API rate limits and error handling

### Phase 3: Core Functionality (Week 3)
1. Implement buy/sell logic
2. Create portfolio calculation service
3. Add transaction tracking
4. Test all business logic

### Phase 4: Frontend (Week 4)
1. Create holding page view
2. Implement asset search
3. Add buy/sell modals
4. Create responsive design

### Phase 5: Testing & Optimization (Week 5)
1. Test all functionality
2. Optimize performance
3. Add error handling
4. Security review

## 4. Key Features to Implement

1. **Real-time Price Updates**: Automatic price updates every 5-15 minutes
2. **Portfolio Tracking**: Real-time portfolio value calculation
3. **Buy/Sell Operations**: Complete trading functionality
4. **Transaction History**: Full audit trail of all transactions
5. **P&L Calculation**: Real-time profit/loss tracking
6. **Asset Search**: Search functionality for crypto and stocks
7. **Responsive Design**: Mobile-friendly interface

## 5. Security Considerations

1. **API Rate Limiting**: Implement proper rate limiting for external APIs
2. **Input Validation**: Validate all user inputs for buy/sell operations
3. **Transaction Security**: Use database transactions for all financial operations
4. **Price Verification**: Verify prices before executing trades
5. **User Balance Checks**: Ensure users have sufficient balance before purchases

## 6. Performance Considerations

1. **Caching**: Cache API responses to reduce external calls
2. **Batch Updates**: Update prices in batches to reduce API calls
3. **Database Indexing**: Add proper indexes for frequently queried fields
4. **Background Jobs**: Use queues for price updates and calculations
5. **Pagination**: Implement pagination for large datasets

This documentation provides the foundation for implementing a comprehensive holding system with real-time crypto and stock data integration.
