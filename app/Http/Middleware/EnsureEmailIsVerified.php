<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if email is verified
            if (!$user->email_verified_at) {
                // Log out the user if they somehow got authenticated without verification
                Auth::logout();
                
                // Clear the session
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // Return JSON for AJAX requests
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Email verification required. Please verify your email address.',
                        'redirect' => route('verification.show', ['email' => $user->email]),
                        'requires_verification' => true
                    ], 401);
                }
                
                // Redirect to verification page with error message for regular requests
                return redirect()->route('verification.show', ['email' => $user->email])
                    ->with('error', 'Please verify your email address before accessing your account.');
            }
        }

        return $next($request);
    }
}
