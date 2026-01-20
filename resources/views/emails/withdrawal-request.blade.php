<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdrawal Request Confirmation</title>
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
        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-pending {
            background-color: #fef5e7;
            color: #d69e2e;
        }
        .status-completed {
            background-color: #f0fff4;
            color: #38a169;
        }
        .status-rejected {
            background-color: #fed7d7;
            color: #e53e3e;
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
            <h1>Withdrawal Request Confirmation</h1>
        </div>
        
        <div class="content">
            <div class="greeting">
                Hello {{ $withdrawal->user->name }},
            </div>
            
            <div class="message">
                We have received your withdrawal request. Here are the details of your transaction:
            </div>
            
            <div class="details">
                <div class="detail-row">
                    <span class="detail-label">Transaction ID:</span>
                    <span class="detail-value">#{{ $withdrawal->id }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Amount:</span>
                    <span class="detail-value amount">${{ number_format($withdrawal->amount, 2) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Payment Method:</span>
                    <span class="detail-value">{{ ucfirst($withdrawal->payment_method) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">From Account:</span>
                    <span class="detail-value">{{ str_replace('_', ' ', ucfirst($withdrawal->from_account)) }}</span>
                </div>
                
                @if($withdrawal->payment_method === 'crypto')
                <div class="detail-row">
                    <span class="detail-label">Wallet Type:</span>
                    <span class="detail-value">{{ $withdrawal->wallet ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Address:</span>
                    <span class="detail-value">{{ $withdrawal->address ?? '—' }}</span>
                </div>
                @elseif($withdrawal->payment_method === 'bank')
                <div class="detail-row">
                    <span class="detail-label">Bank Details:</span>
                    <span class="detail-value">
                        @php($bank = json_decode($withdrawal->bank, true))
                        {{ $bank['bank_name'] ?? '' }} - {{ $bank['acct_name'] ?? '' }}
                    </span>
                </div>
                @elseif($withdrawal->payment_method === 'paypal')
                <div class="detail-row">
                    <span class="detail-label">PayPal Email:</span>
                    <span class="detail-value">{{ $withdrawal->paypal ?? '—' }}</span>
                </div>
                @endif
                
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">
                                        <span class="status status-{{ $withdrawal->status_text }}">
                    {{ ucfirst($withdrawal->status_text) }}
                </span>
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Request Date:</span>
                    <span class="detail-value">{{ $withdrawal->created_at->format('M d, Y \a\t g:i A') }}</span>
                </div>
            </div>
            
            <div class="message">
                @if($withdrawal->status === 'pending')
                    Your withdrawal request is currently being reviewed by our team. We typically process withdrawals within 24-48 hours during business days.
                @elseif($withdrawal->status === 'completed')
                    Your withdrawal has been successfully processed and the funds have been sent to your specified destination.
                @elseif($withdrawal->status === 'rejected')
                    Unfortunately, your withdrawal request has been rejected. Please contact our support team for more information.
                @endif
            </div>
            
            <div class="support">
                <h3>Need Help?</h3>
                <p>If you have any questions about this withdrawal request, please don't hesitate to contact our support team. We're here to help!</p>
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
