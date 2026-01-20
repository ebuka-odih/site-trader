<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class WebsiteSettingsHelper
{
    /**
     * Get website settings from storage
     */
    public static function getSettings()
    {
        $settingsFile = 'website_settings.json';
        
        if (Storage::exists($settingsFile)) {
            $settings = json_decode(Storage::get($settingsFile), true);
        } else {
            // Default website settings
            $settings = [
                'site_name' => config('app.name'),
                'site_tagline' => 'Your trusted cryptocurrency trading platform',
                'site_email' => config('mail.from.address', 'admin@' . str_replace(['http://', 'https://', 'www.'], '', config('app.url'))),
                'site_logo' => null,
                'text_logo' => '',
                'primary_color' => '#3B82F6',
                'secondary_color' => '#6B7280',
                'facebook_url' => '',
                'twitter_url' => '',
                'linkedin_url' => '',
                'instagram_url' => '',
                'updated_at' => null,
                'updated_by' => null
            ];
        }
        
        return $settings;
    }

    /**
     * Get the site logo URL
     */
    public static function getLogoUrl()
    {
        $settings = self::getSettings();
        $logoPath = $settings['site_logo'] ?? null;
        
        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            return asset('storage/' . $logoPath);
        }
        
        return asset('assets/img/logo-placeholder.svg');
    }

    public static function getTextLogo()
    {
        return self::getSettings()['text_logo'] ?? null;
    }

    public static function hasImageLogo()
    {
        $settings = self::getSettings();
        $logoPath = $settings['site_logo'] ?? null;
        return $logoPath && Storage::disk('public')->exists($logoPath);
    }

    public static function hasTextLogo()
    {
        $textLogo = self::getTextLogo();
        return !empty($textLogo);
    }

    /**
     * Get the site name
     */
    public static function getSiteName()
    {
        $settings = self::getSettings();
        return $settings['site_name'] ?? config('app.name');
    }

    /**
     * Get the site tagline
     */
    public static function getSiteTagline()
    {
        $settings = self::getSettings();
        return $settings['site_tagline'] ?? '';
    }

    /**
     * Get the site email
     */
    public static function getSiteEmail()
    {
        $settings = self::getSettings();
        return $settings['site_email'] ?? config('mail.from.address', 'admin@' . str_replace(['http://', 'https://', 'www.'], '', config('app.url')));
    }

    /**
     * Get primary color
     */
    public static function getPrimaryColor()
    {
        $settings = self::getSettings();
        return $settings['primary_color'] ?? '#3B82F6';
    }

    /**
     * Get secondary color
     */
    public static function getSecondaryColor()
    {
        $settings = self::getSettings();
        return $settings['secondary_color'] ?? '#6B7280';
    }

    /**
     * Get social media links
     */
    public static function getSocialLinks()
    {
        $settings = self::getSettings();
        return [
            'facebook' => $settings['facebook_url'] ?? '',
            'twitter' => $settings['twitter_url'] ?? '',
            'linkedin' => $settings['linkedin_url'] ?? '',
            'instagram' => $settings['instagram_url'] ?? '',
        ];
    }
}
