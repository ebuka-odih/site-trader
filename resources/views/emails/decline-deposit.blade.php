<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposit Declined</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #dc2626;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #dc2626;
        }
        .details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #dc2626;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background: #dc2626;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
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
            <h1>{{ \App\Helpers\WebsiteSettingsHelper::getSiteName() }}</h1>
        @endif
        <h1>Deposit Declined</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $deposit->user->name }},</p>
        
        <p>We regret to inform you that your deposit request has been declined by our admin team.</p>
        
        <div class="details">
            <h3>Deposit Details:</h3>
            <p><strong>Transaction ID:</strong> #{{ $deposit->id }}</p>
            <p><strong>Amount:</strong> <span class="amount">${{ number_format($deposit->amount, 2) }}</span></p>
            <p><strong>Payment Method:</strong> {{ optional($deposit->payment_method)->crypto_display_name ?? 'N/A' }}</p>
            <p><strong>Date:</strong> {{ $deposit->created_at ? $deposit->created_at->format('M d, Y H:i') : 'N/A' }}</p>
        </div>
        
        <p><strong>Possible reasons for decline:</strong></p>
        <ul>
            <li>Insufficient or unclear payment proof</li>
            <li>Payment method not supported</li>
            <li>Amount below minimum requirement</li>
            <li>Account verification issues</li>
        </ul>
        
        <p>If you believe this was an error or have any questions, please contact our support team.</p>
        
        <p>You can submit a new deposit request with proper documentation at any time.</p>
        
        <a href="{{ url('/user/deposit') }}" class="button">Submit New Deposit</a>
        
        <p>Thank you for your understanding.</p>
        
        <p>Best regards,<br>
        {{ config('app.name') }} Team</p>
    </div>
    
    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
