<?php

namespace App\Http\Controllers;

use App\Helpers\WebsiteSettingsHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PWAController extends Controller
{
    /**
     * Serve the PWA manifest.json file
     */
    public function manifest()
    {
        $siteName = WebsiteSettingsHelper::getSiteName();
        $shortName = strlen($siteName) > 12 ? substr($siteName, 0, 12) . '...' : $siteName;
        
        // Dynamic start URL based on authentication
        $startUrl = auth()->check() ? '/user/dashboard' : '/login';
        
        $manifest = [
            'name' => $siteName . ' - Trading Platform',
            'short_name' => $shortName,
            'description' => WebsiteSettingsHelper::getSiteTagline() ?: 'Secure cryptocurrency trading platform with advanced features',
            'start_url' => $startUrl,
            'scope' => '/',
            'display' => 'standalone',
            'background_color' => '#000000',
            'theme_color' => '#1fff9c',
            'orientation' => 'portrait-primary',
            'icons' => [
                [
                    'src' => asset('pwa-icons/icon-192x192.png'),
                    'sizes' => '192x192',
                    'type' => 'image/png',
                    'purpose' => 'any maskable'
                ],
                [
                    'src' => asset('pwa-icons/icon-512x512.png'),
                    'sizes' => '512x512',
                    'type' => 'image/png',
                    'purpose' => 'any maskable'
                ]
            ],
            'screenshots' => [],
            'categories' => ['finance', 'business'],
            'shortcuts' => [
                [
                    'name' => 'Dashboard',
                    'short_name' => 'Dashboard',
                    'description' => 'Go to your dashboard',
                    'url' => '/user/dashboard',
                    'icons' => [
                        [
                            'src' => asset('pwa-icons/icon-192x192.png'),
                            'sizes' => '192x192'
                        ]
                    ]
                ]
            ],
            'related_applications' => [],
            'prefer_related_applications' => false
        ];

        return response()->json($manifest)
            ->header('Content-Type', 'application/manifest+json')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    /**
     * Serve the service worker file
     */
    public function serviceWorker()
    {
        $swContent = file_get_contents(public_path('sw.js'));
        
        return response($swContent)
            ->header('Content-Type', 'application/javascript')
            ->header('Service-Worker-Allowed', '/')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }
}

