<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Referral;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        // Honeypot validation - if any honeypot field is filled, it's likely a bot
        $honeypotFields = ['hp_tag_a', 'hp_tag_b', 'hp_tag_c'];
        $filledHoneypotFields = [];
        
        foreach ($honeypotFields as $field) {
            if (!empty($request->input($field))) {
                $filledHoneypotFields[] = $field;
            }
        }
        
        // Time-based honeypot - if form is submitted too quickly (less than 3 seconds), it's likely a bot
        $registrationTime = $request->input('registration_time');
        $currentTime = time();
        $timeDifference = $currentTime - $registrationTime;
        
        if (!empty($filledHoneypotFields) || $timeDifference < 3) {
            \Log::warning('Bot detected during registration attempt', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'filled_honeypot_fields' => $filledHoneypotFields,
                'time_difference' => $timeDifference,
                'timestamp' => now()
            ]);
            
            // Return a generic error message to avoid revealing the honeypot
            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['email' => 'Registration failed. Please try again.']);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
            'currency' => ['required', 'string', 'in:USD,EUR,GBP,JPY,CAD,AUD,CHF,CNY,INR,BRL'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $referralCodeInput = $request->input('referral_code') ?? session('referrer_code');
        $referrer = $referralCodeInput ? User::where('referral_code', $referralCodeInput)->first() : null;

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'country' => $request->country,
            'currency' => $request->currency,
            'password' => Hash::make($request->password),
        ]);

        $user->referral_code = $this->generateReferralCode();
        if ($referrer) {
            $user->referred_by_id = $referrer->id;
        }
        $user->save();

        if ($referrer) {
            Referral::create([
                'referrer_id' => $referrer->id,
                'referred_user_id' => $user->id,
                'status' => 'registered',
            ]);
            session()->forget('referrer_code');
        }

        // Generate verification code
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $user->update([
            'verification_code' => $verificationCode,
            'verification_code_expires_at' => now()->addMinutes(10),
        ]);

        // Send verification email
        $this->sendVerificationEmail($user, $verificationCode);

        // Redirect to verification page instead of logging in
        return redirect()->route('verification.show', ['email' => $user->email])->with('success', 'Registration successful! Please check your email for the verification code.');
    }

    /**
     * Send verification email.
     */
    private function sendVerificationEmail(User $user, string $code): void
    {
        $data = [
            'name' => $user->name,
            'code' => $code,
            'expires_at' => now()->addMinutes(10)->format('H:i'),
        ];

        try {
            Mail::send('emails.verification', $data, function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Verify Your Email Address');
            });
            
            \Log::info('Verification email sent successfully to: ' . $user->email);
        } catch (\Exception $e) {
            \Log::error('Failed to send verification email to: ' . $user->email . ' Error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function generateReferralCode(): string
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (User::where('referral_code', $code)->exists());

        return $code;
    }
}
