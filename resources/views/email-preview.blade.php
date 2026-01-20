<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Template Preview - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header h1 {
            color: #2d3748;
            margin-bottom: 10px;
        }
        .header p {
            color: #718096;
        }
        .email-section {
            margin-bottom: 60px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .section-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            margin: 0;
        }
        .email-container {
            padding: 20px;
        }
        .email-frame {
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .nav-tabs {
            display: flex;
            background: #f7fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .nav-tab {
            flex: 1;
            padding: 15px;
            text-align: center;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 500;
            color: #4a5568;
            transition: all 0.3s ease;
        }
        .nav-tab.active {
            background: white;
            color: #2d3748;
            border-bottom: 2px solid #667eea;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Email Template Preview</h1>
            <p>Preview all email templates with dummy data</p>
        </div>

        <div class="email-section">
            <h2 class="section-header">Asset Purchase Email</h2>
            <div class="email-container">
                <div class="nav-tabs">
                    <button class="nav-tab active" onclick="showTab('crypto-purchase')">Crypto Purchase</button>
                    <button class="nav-tab" onclick="showTab('stock-purchase')">Stock Purchase</button>
                </div>
                
                <div id="crypto-purchase" class="tab-content active">
                    <div class="email-frame">
                        @include('emails.asset-purchase', [
                            'transaction' => (object)[
                                'id' => 123,
                                'user' => (object)['name' => 'John Doe'],
                                'asset' => (object)[
                                    'symbol' => 'BTC',
                                    'name' => 'Bitcoin',
                                    'type' => 'crypto',
                                    'current_price' => 43250.00
                                ],
                                'quantity' => 0.00231481,
                                'price_per_unit' => 43250.00,
                                'total_amount' => 100.00,
                                'fee' => 0,
                                'transaction_date' => now()
                            ],
                            'portfolioSummary' => [
                                'total_holdings' => 0.00231481,
                                'average_buy_price' => 43250.00,
                                'total_invested' => 100.00,
                                'current_value' => 100.00,
                                'unrealized_pnl' => 0.00,
                                'unrealized_pnl_percentage' => 0.00
                            ]
                        ])
                    </div>
                </div>
                
                <div id="stock-purchase" class="tab-content">
                    <div class="email-frame">
                        @include('emails.asset-purchase', [
                            'transaction' => (object)[
                                'id' => 124,
                                'user' => (object)['name' => 'John Doe'],
                                'asset' => (object)[
                                    'symbol' => 'AAPL',
                                    'name' => 'Apple Inc.',
                                    'type' => 'stock',
                                    'current_price' => 195.50
                                ],
                                'quantity' => 5.00000000,
                                'price_per_unit' => 195.50,
                                'total_amount' => 977.50,
                                'fee' => 0,
                                'transaction_date' => now()
                            ],
                            'portfolioSummary' => [
                                'total_holdings' => 5.00000000,
                                'average_buy_price' => 195.50,
                                'total_invested' => 977.50,
                                'current_value' => 977.50,
                                'unrealized_pnl' => 0.00,
                                'unrealized_pnl_percentage' => 0.00
                            ]
                        ])
                    </div>
                </div>
            </div>
        </div>

        <div class="email-section">
            <h2 class="section-header">Withdrawal Request Email</h2>
            <div class="email-container">
                <div class="nav-tabs">
                    <button class="nav-tab active" onclick="showTab('crypto-withdrawal')">Crypto Withdrawal</button>
                    <button class="nav-tab" onclick="showTab('bank-withdrawal')">Bank Withdrawal</button>
                    <button class="nav-tab" onclick="showTab('paypal-withdrawal')">PayPal Withdrawal</button>
                </div>
                
                <div id="crypto-withdrawal" class="tab-content active">
                    <div class="email-frame">
                        @include('emails.withdrawal-request', [
                            'withdrawal' => (object)[
                                'id' => 456,
                                'user' => (object)['name' => 'John Doe'],
                                'amount' => 500.00,
                                'payment_method' => 'crypto',
                                'from_account' => 'balance',
                                'wallet' => 'Bitcoin',
                                'address' => '1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa',
                                'status' => 'pending',
                                'created_at' => now()
                            ]
                        ])
                    </div>
                </div>
                
                <div id="bank-withdrawal" class="tab-content">
                    <div class="email-frame">
                        @include('emails.withdrawal-request', [
                            'withdrawal' => (object)[
                                'id' => 457,
                                'user' => (object)['name' => 'John Doe'],
                                'amount' => 1000.00,
                                'payment_method' => 'bank',
                                'from_account' => 'trading_balance',
                                'bank' => json_encode([
                                    'bank_name' => 'Chase Bank',
                                    'acct_name' => 'John Doe',
                                    'acct_number' => '****1234'
                                ]),
                                'status' => 'completed',
                                'created_at' => now()
                            ]
                        ])
                    </div>
                </div>
                
                <div id="paypal-withdrawal" class="tab-content">
                    <div class="email-frame">
                        @include('emails.withdrawal-request', [
                            'withdrawal' => (object)[
                                'id' => 458,
                                'user' => (object)['name' => 'John Doe'],
                                'amount' => 250.00,
                                'payment_method' => 'paypal',
                                'from_account' => 'mining_balance',
                                'paypal' => 'john.doe@example.com',
                                'status' => 'rejected',
                                'created_at' => now()
                            ]
                        ])
                    </div>
                </div>
            </div>
        </div>

        <div class="email-section">
            <h2 class="section-header">Fund Transfer Email</h2>
            <div class="email-container">
                <div class="email-frame">
                    @include('emails.transfer-action', [
                        'transfer' => (object)[
                            'id' => 789,
                            'user' => (object)['name' => 'John Doe'],
                            'amount' => 500.00,
                            'from_account' => 'balance',
                            'to_account' => 'trading_balance',
                            'status' => 'completed',
                            'description' => 'Transfer for trading activities',
                            'reference' => 'TRF-2024-001',
                            'created_at' => now()
                        ]
                    ])
                </div>
            </div>
        </div>

        <div class="email-section">
            <h2 class="section-header">Deposit Emails</h2>
            <div class="email-container">
                <div class="nav-tabs">
                    <button class="nav-tab active" onclick="showTab('admin-deposit')">Admin Notification</button>
                    <button class="nav-tab" onclick="showTab('user-deposit-approval')">User Approval</button>
                </div>
                
                <div id="admin-deposit" class="tab-content active">
                    <div class="email-frame">
                        @include('emails.admin-deposit-notification', [
                            'deposit' => (object)[
                                'id' => 321,
                                'user' => (object)['name' => 'John Doe', 'email' => 'john@example.com'],
                                'amount' => 1000.00,
                                'wallet_type' => 'trading',
                                'paymentMethod' => (object)['wallet' => 'Bitcoin'],
                                'created_at' => now()
                            ]
                        ])
                    </div>
                </div>
                
                <div id="user-deposit-approval" class="tab-content">
                    <div class="email-frame">
                        @include('emails.deposit-approval', [
                            'deposit' => (object)[
                                'id' => 321,
                                'user' => (object)['name' => 'John Doe'],
                                'amount' => 1000.00,
                                'wallet_type' => 'trading',
                                'paymentMethod' => (object)['wallet' => 'Bitcoin'],
                                'updated_at' => now()
                            ]
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabId) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Remove active class from all tabs
            const tabs = document.querySelectorAll('.nav-tab');
            tabs.forEach(tab => tab.classList.remove('active'));
            
            // Show selected tab content
            document.getElementById(tabId).classList.add('active');
            
            // Add active class to clicked tab
            event.target.classList.add('active');
        }
    </script>
</body>
</html>
