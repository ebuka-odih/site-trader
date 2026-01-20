<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HoneypotMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only apply honeypot validation to POST requests on auth routes
        if ($request->isMethod('post') && $request->is('login', 'register')) {
            // Check for suspicious patterns
            $userAgent = $request->userAgent();
            $ip = $request->ip();
            
            // List of known bot user agents (more specific patterns)
            $botPatterns = [
                'curl/', 'wget/', 'python-requests', 'scrapy', 'headless',
                'phantomjs', 'selenium', 'webdriver'
            ];
            
            // Check if user agent contains bot patterns
            $isBot = false;
            foreach ($botPatterns as $pattern) {
                if (stripos($userAgent, $pattern) !== false) {
                    $isBot = true;
                    break;
                }
            }
            
            // Check for completely missing user agent (empty is suspicious)
            if (empty($userAgent)) {
                $isBot = true;
            }
            
            // Check for rapid requests from same IP (more lenient rate limiting)
            $cacheKey = 'honeypot_requests_' . $ip;
            $requestCount = cache()->get($cacheKey, 0);
            
            if ($requestCount > 20) { // More than 20 requests per minute
                $isBot = true;
            }
            
            // Increment request count
            cache()->put($cacheKey, $requestCount + 1, 60); // 1 minute cache
            
            if ($isBot) {
                \Log::warning('Bot detected by middleware', [
                    'ip' => $ip,
                    'user_agent' => $userAgent,
                    'url' => $request->fullUrl(),
                    'request_count' => $requestCount,
                    'timestamp' => now()
                ]);
                
                // Return a generic error response
                return response()->json(['error' => 'Access denied'], 403);
            }
        }
        
        return $next($request);
    }
}