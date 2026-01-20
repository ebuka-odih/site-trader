<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
    <body  class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            <div>
                <a href="{{ route('index') }}">
                    @if(\App\Helpers\WebsiteSettingsHelper::hasTextLogo())
                        <!-- Text Logo -->
                        <div class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg" style="width: 200px; height: 160px;">
                            <span class="text-white font-bold text-3xl">{{ \App\Helpers\WebsiteSettingsHelper::getTextLogo() }}</span>
                        </div>
                    @elseif(\App\Helpers\WebsiteSettingsHelper::hasImageLogo())
                        <!-- Image Logo -->
                        <img src="{{ \App\Helpers\WebsiteSettingsHelper::getLogoUrl() }}" 
                             alt="{{ \App\Helpers\WebsiteSettingsHelper::getSiteName() }}" 
                             width="200" height="160" 
                             style="background-color: white; object-fit: contain;">
                    @else
                        <!-- Site Name as Logo (fallback) -->
                        <div class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg" style="width: 200px; height: 160px;">
                            <span class="text-white font-bold text-2xl text-center">{{ \App\Helpers\WebsiteSettingsHelper::getSiteName() }}</span>
                        </div>
                    @endif
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
