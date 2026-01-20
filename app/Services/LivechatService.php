<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class LivechatService
{
    private $settings;

    public function __construct()
    {
        $this->loadSettings();
    }

    private function loadSettings()
    {
        $settingsFile = 'livechat_settings.json';
        
        if (Storage::exists($settingsFile)) {
            $this->settings = json_decode(Storage::get($settingsFile), true);
        } else {
            // Default settings
            $this->settings = [
                'provider' => 'jivochat',
                'widget_script' => '',
                'is_enabled' => true,
                'show_on_support_page' => true,
                'show_on_contact_page' => true,
                'show_on_homepage' => false,
                'widget_position' => 'bottom-right',
                'business_hours' => [
                    'enabled' => false,
                    'timezone' => 'UTC',
                    'schedule' => [
                        'monday' => ['start' => '09:00', 'end' => '17:00', 'enabled' => true],
                        'tuesday' => ['start' => '09:00', 'end' => '17:00', 'enabled' => true],
                        'wednesday' => ['start' => '09:00', 'end' => '17:00', 'enabled' => true],
                        'thursday' => ['start' => '09:00', 'end' => '17:00', 'enabled' => true],
                        'friday' => ['start' => '09:00', 'end' => '17:00', 'enabled' => true],
                        'saturday' => ['start' => '10:00', 'end' => '15:00', 'enabled' => false],
                        'sunday' => ['start' => '10:00', 'end' => '15:00', 'enabled' => false],
                    ]
                ],
                'custom_css' => '',
                'custom_js' => '',
                'updated_at' => null,
                'updated_by' => null
            ];
        }
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function isEnabled()
    {
        return $this->settings['is_enabled'] ?? false;
    }

    public function shouldShowOnPage($page)
    {
        if (!$this->isEnabled()) {
            return false;
        }

        switch ($page) {
            case 'support':
                return $this->settings['show_on_support_page'] ?? false;
            case 'contact':
                return $this->settings['show_on_contact_page'] ?? false;
            case 'homepage':
                return $this->settings['show_on_homepage'] ?? false;
            default:
                return false;
        }
    }

    public function getWidgetScript()
    {
        if (!$this->isEnabled()) {
            return null;
        }

        return $this->settings['widget_script'] ?? null;
    }

    public function getWidgetConfig()
    {
        return [
            'provider' => $this->settings['provider'] ?? 'jivochat',
            'widget_script' => $this->settings['widget_script'] ?? '',
            'position' => $this->settings['widget_position'] ?? 'bottom-right',
            'custom_css' => $this->settings['custom_css'] ?? '',
            'custom_js' => $this->settings['custom_js'] ?? '',
        ];
    }

    public function getPositionClass()
    {
        $position = $this->settings['widget_position'] ?? 'bottom-right';
        
        switch ($position) {
            case 'bottom-right':
                return 'fixed bottom-4 right-4 z-50';
            case 'bottom-left':
                return 'fixed bottom-4 left-4 z-50';
            case 'top-right':
                return 'fixed top-4 right-4 z-50';
            case 'top-left':
                return 'fixed top-4 left-4 z-50';
            default:
                return 'fixed bottom-4 right-4 z-50';
        }
    }

    public function isWithinBusinessHours()
    {
        $businessHours = $this->settings['business_hours'] ?? [];
        
        if (!($businessHours['enabled'] ?? false)) {
            return true; // Always available if business hours not enabled
        }

        $timezone = $businessHours['timezone'] ?? 'UTC';
        $schedule = $businessHours['schedule'] ?? [];
        
        $now = now()->setTimezone($timezone);
        $dayOfWeek = strtolower($now->format('l')); // monday, tuesday, etc.
        
        if (!isset($schedule[$dayOfWeek]) || !($schedule[$dayOfWeek]['enabled'] ?? false)) {
            return false;
        }
        
        $startTime = $schedule[$dayOfWeek]['start'] ?? '09:00';
        $endTime = $schedule[$dayOfWeek]['end'] ?? '17:00';
        
        $currentTime = $now->format('H:i');
        
        return $currentTime >= $startTime && $currentTime <= $endTime;
    }
}


