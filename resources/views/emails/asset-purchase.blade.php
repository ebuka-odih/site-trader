<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset Purchase Confirmation - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #2d3748;
        }
        .message {
            font-size: 16px;
            margin-bottom: 30px;
            color: #4a5568;
        }
        .details {
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 25px;
            margin: 25px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .detail-label {
            font-weight: 600;
            color: #2d3748;
        }
        .detail-value {
            color: #4a5568;
            text-align: right;
        }
        .footer {
            background-color: #f7fafc;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 5px 0;
            color: #718096;
            font-size: 14px;
        }
        .amount {
            font-size: 24px;
            font-weight: 700;
            color: #2d3748;
        }
        .asset-info {
            background-color: #ebf8ff;
            border: 1px solid #bee3f8;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .asset-symbol {
            font-size: 28px;
            font-weight: 700;
            color: #2b6cb0;
            margin-bottom: 5px;
        }
        .asset-name {
            font-size: 16px;
            color: #4a5568;
            margin-bottom: 10px;
        }
        .asset-price {
            font-size: 20px;
            font-weight: 600;
            color: #2d3748;
        }
        .action-buttons {
            margin-top: 30px;
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin: 0 10px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            background-color: #48bb78;
            color: white;
        }
        .success-icon {
            text-align: center;
            margin: 20px 0;
        }
        .success-icon svg {
            width: 60px;
            height: 60px;
            color: #48bb78;
        }
        .portfolio-summary {
            background-color: #f0fff4;
            border: 1px solid #c6f6d5;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .portfolio-summary h3 {
            margin: 0 0 15px 0;
            color: #38a169;
            font-size: 18px;
        }
        .portfolio-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .portfolio-row:last-child {
            margin-bottom: 0;
            border-top: 1px solid #c6f6d5;
            padding-top: 8px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if(\App\Helpers\WebsiteSettingsHelper::hasTextLogo())
                <!-- Text Logo -->
                <div style="display: inline-block; background: linear-gradient(135deg, #3B82F6 0%, #8B5CF6 100%); color: white; padding: 12px 24px; border-radius: 8px; margin-bottom: 16px;">
                    <span style="font-size: 24px; font-weight: bold;">{{ \App\Helpers\WebsiteSettingsHelper::getTextLogo() }}</span>
                </div>
            @elseif(\App\Helpers\WebsiteSettingsHelper::hasImageLogo())
                <!-- Image Logo -->
                <img src="{{ \App\Helpers\WebsiteSettingsHelper::getLogoUrl() }}" 
                     alt="{{ \App\Helpers\WebsiteSettingsHelper::getSiteName() }}" 
                     style="height: 60px; width: auto; margin-bottom: 16px;">
            @else
                <!-- Site Name as Logo (fallback) -->
                <h1 style="margin: 0; font-size: 24px; color: white;">{{ \App\Helpers\WebsiteSettingsHelper::getSiteName() }}</h1>
            @endif
            <h1>Asset Purchase Confirmation</h1>
        </div>
        
        <div class="content">
            <div class="success-icon">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            
            <div class="greeting">
                Hello {{ $transaction->user->name }},
            </div>
            
            <div class="message">
                Your asset purchase has been successfully completed! Here are the details of your transaction:
            </div>
            
            <div class="asset-info">
                <div class="asset-symbol">{{ $transaction->asset->symbol }}</div>
                <div class="asset-name">{{ $transaction->asset->name }}</div>
                <div class="asset-price">${{ number_format($transaction->asset->current_price, 8) }}</div>
            </div>
            
            <div class="details">
                <div class="detail-row">
                    <span class="detail-label">Transaction ID:</span>
                    <span class="detail-value">#{{ $transaction->id }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Asset Type:</span>
                    <span class="detail-value">{{ ucfirst($transaction->asset->type) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Quantity Purchased:</span>
                    <span class="detail-value">{{ number_format($transaction->quantity, 8) }} {{ $transaction->asset->symbol }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Price per Unit:</span>
                    <span class="detail-value">${{ number_format($transaction->price_per_unit, 8) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Total Amount:</span>
                    <span class="detail-value amount">${{ number_format($transaction->total_amount, 2) }}</span>
                </div>
                
                @if($transaction->fee > 0)
                <div class="detail-row">
                    <span class="detail-label">Transaction Fee:</span>
                    <span class="detail-value">${{ number_format($transaction->fee, 2) }}</span>
                </div>
                @endif
                
                <div class="detail-row">
                    <span class="detail-label">Purchase Date:</span>
                    <span class="detail-value">{{ $transaction->transaction_date->format('M d, Y \a\t g:i A') }}</span>
                </div>
            </div>
            
            @if(isset($portfolioSummary))
            <div class="portfolio-summary">
                <h3>Portfolio Update</h3>
                <div class="portfolio-row">
                    <span>Total Holdings:</span>
                    <span>{{ number_format($portfolioSummary['total_holdings'], 8) }} {{ $transaction->asset->symbol }}</span>
                </div>
                <div class="portfolio-row">
                    <span>Average Buy Price:</span>
                    <span>${{ number_format($portfolioSummary['average_buy_price'], 8) }}</span>
                </div>
                <div class="portfolio-row">
                    <span>Total Invested:</span>
                    <span>${{ number_format($portfolioSummary['total_invested'], 2) }}</span>
                </div>
                <div class="portfolio-row">
                    <span>Current Value:</span>
                    <span>${{ number_format($portfolioSummary['current_value'], 2) }}</span>
                </div>
                <div class="portfolio-row">
                    <span>Unrealized P&L:</span>
                    <span style="color: {{ $portfolioSummary['unrealized_pnl'] >= 0 ? '#38a169' : '#e53e3e' }}">
                        {{ $portfolioSummary['unrealized_pnl'] >= 0 ? '+' : '' }}${{ number_format($portfolioSummary['unrealized_pnl'], 2) }}
                        ({{ $portfolioSummary['unrealized_pnl_percentage'] >= 0 ? '+' : '' }}{{ number_format($portfolioSummary['unrealized_pnl_percentage'], 2) }}%)
                    </span>
                </div>
            </div>
            @endif
            
            <div class="message">
                Your {{ $transaction->asset->symbol }} has been added to your portfolio. You can view your holdings and track performance in your dashboard.
            </div>
            
            <div class="action-buttons">
                <a href="{{ url('/user/holding') }}" class="btn">View Portfolio</a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>{{ config('app.name') }}</strong></p>
            <p>Your trusted cryptocurrency trading platform</p>
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
