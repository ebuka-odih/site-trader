<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        th, td {
      padding: 5px;
      text-align: left;
    }
    </style>
</head>
<body>


    <div class="container my-5">
        <div class="card shadow-sm">
            <div class="card-header bg-black text-white">
                <div class="text-center mb-3">
                    @if(\App\Helpers\WebsiteSettingsHelper::hasTextLogo())
                        <!-- Text Logo -->
                        <div style="display: inline-block; background: linear-gradient(135deg, #3B82F6 0%, #8B5CF6 100%); color: white; padding: 8px 16px; border-radius: 6px;">
                            <span style="font-size: 20px; font-weight: bold;">{{ \App\Helpers\WebsiteSettingsHelper::getTextLogo() }}</span>
                        </div>
                    @elseif(\App\Helpers\WebsiteSettingsHelper::hasImageLogo())
                        <!-- Image Logo -->
                        <img src="{{ \App\Helpers\WebsiteSettingsHelper::getLogoUrl() }}" 
                             alt="{{ \App\Helpers\WebsiteSettingsHelper::getSiteName() }}" 
                             style="height: 40px; width: auto;">
                    @else
                        <!-- Site Name as Logo (fallback) -->
                        <h3 style="margin: 0; color: white;">{{ \App\Helpers\WebsiteSettingsHelper::getSiteName() }}</h3>
                    @endif
                </div>
                <h2 class="text-center">Deposit Confirmation</h2>
            </div>
            <div class="card-body">
                <p>Dear <strong>{{ $deposit->user->fullname() }}</strong>,</p>
                <p>We have received your deposit request. Here are the details of your transaction:</p>
                <table style="width:100%" class="table table-striped">
                  <tr>
                    <th>Transaction ID:</th>
                     <td>{{ $deposit->id."#" }}</td>
                  </tr>
                  <tr>
                    <th>Amount:</th>
                    <td>${{ number_format($deposit->amount, 2) ?? ''}}</td>
                  </tr>
                  <tr>
                    <th>Deposit Method:</th>
                    <td>{{ $deposit->payment_method->crypto_display_name ?? '' }}</td>
                  </tr>
                  <tr>
                    <th>Date:</th>
                    <td>{{ date('d M, Y', strtotime($deposit->created_at)) }}</td>
                  </tr>
                </table>

                <p class="mt-4">If you have any questions or concerns regarding this transaction, feel free to contact us <a target="_blank" href="mailto:support@coincachex.com">support@coincachex.com</a>.</p>

                <p>Thank you for choosing our service!</p>

                <p>Best regards,<br><strong>The Finance Team</strong></p>
            </div>
            <div class="card-footer text-center text-muted">
                © {{ Date('Y') }} {{ env('APP_NAME') }} - All Rights Reserved
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
