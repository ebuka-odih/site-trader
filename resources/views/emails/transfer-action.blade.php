<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fund Transfer Confirmation</title>
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
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
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
        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-completed {
            background-color: #f0fff4;
            color: #38a169;
        }
        .status-pending {
            background-color: #fef5e7;
            color: #d69e2e;
        }
        .status-failed {
            background-color: #fed7d7;
            color: #e53e3e;
        }
        .transfer-arrow {
            display: inline-block;
            margin: 0 10px;
            color: #48bb78;
            font-weight: bold;
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
        .support {
            margin-top: 20px;
            padding: 15px;
            background-color: #ebf8ff;
            border: 1px solid #bee3f8;
            border-radius: 6px;
        }
        .support h3 {
            margin: 0 0 10px 0;
            color: #2b6cb0;
            font-size: 16px;
        }
        .support p {
            margin: 0;
            color: #4a5568;
            font-size: 14px;
        }
        .amount {
            font-size: 24px;
            font-weight: 700;
            color: #2d3748;
        }
        .account-name {
            font-weight: 600;
            color: #2d3748;
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
            <h1>Fund Transfer Confirmation</h1>
        </div>
        
        <div class="content">
            <div class="greeting">
                Hello {{ $transfer->user->name }},
            </div>
            
            <div class="message">
                Your fund transfer has been successfully completed. Here are the details of your transaction:
            </div>
            
            <div class="details">
                <div class="detail-row">
                    <span class="detail-label">Transaction ID:</span>
                    <span class="detail-value">#{{ $transfer->id }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Amount:</span>
                    <span class="detail-value amount">${{ number_format($transfer->amount, 2) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Transfer:</span>
                    <span class="detail-value">
                        <span class="account-name">{{ str_replace('_', ' ', ucfirst($transfer->from_account)) }}</span>
                        <span class="transfer-arrow">→</span>
                        <span class="account-name">{{ str_replace('_', ' ', ucfirst($transfer->to_account)) }}</span>
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">
                        <span class="status status-{{ $transfer->status }}">
                            {{ ucfirst($transfer->status) }}
                        </span>
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Transfer Date:</span>
                    <span class="detail-value">{{ $transfer->created_at->format('M d, Y \a\t g:i A') }}</span>
                </div>
                
                @if($transfer->description)
                <div class="detail-row">
                    <span class="detail-label">Description:</span>
                    <span class="detail-value">{{ $transfer->description }}</span>
                </div>
                @endif
                
                @if($transfer->reference)
                <div class="detail-row">
                    <span class="detail-label">Reference:</span>
                    <span class="detail-value">{{ $transfer->reference }}</span>
                </div>
                @endif
            </div>
            
            <div class="message">
                @if($transfer->status === 'completed')
                    Your funds have been successfully transferred between your accounts. The transfer was processed instantly and is now available in your destination account.
                @elseif($transfer->status === 'pending')
                    Your transfer is currently being processed. This usually takes a few moments to complete.
                @elseif($transfer->status === 'failed')
                    Unfortunately, your transfer could not be completed. Please try again or contact our support team for assistance.
                @endif
            </div>
            
            <div class="support">
                <h3>Need Help?</h3>
                <p>If you have any questions about this transfer or notice any discrepancies, please contact our support team immediately. We're here to help!</p>
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
