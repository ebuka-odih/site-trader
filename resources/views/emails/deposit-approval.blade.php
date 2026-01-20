<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposit Approved - {{ config('app.name') }}</title>
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
        .status-approved {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            background-color: #f0fff4;
            color: #38a169;
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
            <h1>Deposit Approved</h1>
        </div>
        
        <div class="content">
            <div class="success-icon">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            
            <div class="greeting">
                Hello {{ $deposit->user->name }},
            </div>
            
            <div class="message">
                Great news! Your deposit has been approved and the funds have been credited to your account.
            </div>
            
            <div class="details">
                <div class="detail-row">
                    <span class="detail-label">Deposit ID:</span>
                    <span class="detail-value">#{{ $deposit->id }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Amount:</span>
                    <span class="detail-value amount">${{ number_format($deposit->amount, 2) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Wallet Type:</span>
                    <span class="detail-value">{{ ucfirst($deposit->wallet_type) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Payment Method:</span>
                    <span class="detail-value">{{ $deposit->paymentMethod->wallet ?? 'N/A' }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">
                        <span class="status-approved">Approved</span>
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Approval Date:</span>
                    <span class="detail-value">{{ $deposit->updated_at->format('M d, Y \a\t g:i A') }}</span>
                </div>
            </div>
            
            <div class="message">
                Your funds are now available in your {{ str_replace('_', ' ', ucfirst($deposit->wallet_type)) }} and ready for trading or other activities on our platform.
            </div>
            
            <div class="action-buttons">
                <a href="{{ url('/user/dashboard') }}" class="btn">Go to Dashboard</a>
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
