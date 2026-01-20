<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Honeypot validation - if any honeypot field is filled, it's likely a bot
        $honeypotFields = ['website', 'phone_alt', 'company'];
        $filledHoneypotFields = [];
        
        foreach ($honeypotFields as $field) {
            if (!empty($request->input($field))) {
                $filledHoneypotFields[] = $field;
            }
        }
        
        // Time-based honeypot - if form is submitted too quickly (less than 2 seconds), it's likely a bot
        $loginTime = $request->input('login_time');
        $currentTime = time();
        $timeDifference = $currentTime - $loginTime;
        
        if (!empty($filledHoneypotFields) || $timeDifference < 2) {
            \Log::warning('Bot detected during login attempt', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'filled_honeypot_fields' => $filledHoneypotFields,
                'time_difference' => $timeDifference,
                'timestamp' => now()
            ]);
            
            // Return a generic error message to avoid revealing the honeypot
            return redirect()->back()
                ->withInput($request->except('password'))
                ->withErrors(['email' => 'Login failed. Please try again.']);
        }

        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();
        
        // Check if email is verified
        if (!$user->email_verified_at) {
            // Log out the user since email is not verified
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // Redirect to verification page with error message
            return redirect()->route('verification.show', ['email' => $user->email])
                ->with('error', 'Please verify your email address before logging in. We\'ve sent you a verification code.');
        }
        
        $user->last_login_at = now();
        $user->save();

        if ($user->IsAdmin()) {
            return redirect()->intended('admin/dashboard');  // Redirect admins
        }

        return redirect()->intended(route('user.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

}
