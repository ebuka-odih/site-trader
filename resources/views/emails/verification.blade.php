<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Your Email</title>
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
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #007bff;
        }
        .content {
            padding: 30px 0;
        }
        .verification-code {
            background: #f8f9fa;
            border: 2px solid #007bff;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 3px;
            color: #007bff;
        }
        .footer {
            text-align: center;
            padding: 20px 0;
            color: #666;
            font-size: 14px;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
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
        <h2>Email Verification</h2>
    </div>

    <div class="content">
        <p>Hello {{ $name }},</p>
        
        <p>Thank you for registering with {{ config('app.name') }}. To complete your registration, please use the verification code below:</p>
        
        <div class="verification-code">
            {{ $code }}
        </div>
        
        <p>This code will expire at <strong>{{ $expires_at }}</strong>.</p>
        
        <div class="warning">
            <strong>Important:</strong> Never share this verification code with anyone. Our team will never ask for this code.
        </div>
        
        <p>If you didn't create an account with {{ config('app.name') }}, please ignore this email.</p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        <p>This is an automated email, please do not reply.</p>
    </div>
</body>
</html>
