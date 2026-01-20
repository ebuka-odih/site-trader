@component('mail::message')
# Withdrawal Request Declined

Dear {{ $withdrawal->user->name }},

We regret to inform you that your withdrawal request has been declined.

**Withdrawal Details:**
- Amount: ${{ number_format($withdrawal->amount, 2) }}
- Request Date: {{ $withdrawal->created_at->format('M d, Y H:i') }}
- Payment Method: {{ ucfirst($withdrawal->payment_method) }}

**Good News:** The withdrawal amount has been refunded to your {{ str_replace('_', ' ', $withdrawal->from_account ?? 'main') }} account and is now available for use.

If you have any questions about this decision, please contact our support team.

@component('mail::button', ['url' => route('user.dashboard')])
View Dashboard
@endcomponent

Thanks,<br>
{{ config('app.name') }} Team
@endcomponent
