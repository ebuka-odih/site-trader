<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdateSystemSettingsRequest;
use App\Http\Requests\UpdateLivechatSettingsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        $systemSettings = $this->getSystemSettings();
        $livechatSettings = $this->getLivechatSettings();
        
        return view('admin.settings.index', compact('admin', 'systemSettings', 'livechatSettings'));
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $admin = Auth::user();
        
        $data = $request->validated();
        
        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old profile image if exists
            if ($admin->profile_image && Storage::disk('public')->exists($admin->profile_image)) {
                Storage::disk('public')->delete($admin->profile_image);
            }
            
            $data['profile_image'] = $request->file('profile_image')->store('admin/profiles', 'public');
        }
        
        // Update password if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }
        
        $admin->update($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully!'
        ]);
    }

    public function updateSystemSettings(UpdateSystemSettingsRequest $request)
    {
        $settings = $request->validated();
        
        // Update system settings in config or database
        foreach ($settings as $key => $value) {
            // You can store these in a settings table or config files
            // For now, we'll use a simple approach with config
            config([$key => $value]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'System settings updated successfully!'
        ]);
    }

    public function updateLivechatSettings(UpdateLivechatSettingsRequest $request)
    {
        \Log::info('Livechat settings update request', $request->all());
        $requestData = $request->validated();
        \Log::info('Validated settings', $requestData);
        
        // Get current settings to merge with new data
        $currentSettings = $this->getLivechatSettings();
        
        // Merge new data with current settings
        $settings = array_merge($currentSettings, $requestData);
        
        // Add timestamp
        $settings['updated_at'] = now()->toISOString();
        $settings['updated_by'] = auth()->user()->id;
        
        // Store livechat settings in config or database
        // For now, we'll store them in a JSON file in storage
        $settingsFile = 'livechat_settings.json';
        
        try {
            Storage::put($settingsFile, json_encode($settings, JSON_PRETTY_PRINT));
            
            // Return JSON for AJAX requests, redirect for regular form submissions
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Livechat settings updated successfully!'
                ]);
            }
            
            return redirect()->back()->with('success', 'Livechat settings updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Error saving livechat settings', ['error' => $e->getMessage()]);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error saving settings: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Error saving settings: ' . $e->getMessage());
        }
    }

    public function updateWebsiteSettings(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_tagline' => 'nullable|string|max:500',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'primary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
        ]);

        $settings = $request->except(['site_logo', '_token']);
        
        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            // Delete old logo if exists
            $oldSettings = \App\Helpers\WebsiteSettingsHelper::getSettings();
            if (isset($oldSettings['site_logo']) && Storage::disk('public')->exists($oldSettings['site_logo'])) {
                Storage::disk('public')->delete($oldSettings['site_logo']);
            }
            
            $settings['site_logo'] = $request->file('site_logo')->store('website', 'public');
        }
        
        // Store website settings in JSON file
        $settingsFile = 'website_settings.json';
        
        // Add timestamp
        $settings['updated_at'] = now()->toISOString();
        $settings['updated_by'] = auth()->user()->id;
        
        try {
            Storage::put($settingsFile, json_encode($settings, JSON_PRETTY_PRINT));
            
            return redirect()->back()->with('success', 'Website settings updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Error saving website settings', ['error' => $e->getMessage()]);
            
            return redirect()->back()->with('error', 'Error saving settings: ' . $e->getMessage());
        }
    }

    private function getSystemSettings()
    {
        return [
            'site_name' => config('app.name'),
            'site_email' => config('mail.from.address', 'admin@' . str_replace(['http://', 'https://', 'www.'], '', config('app.url'))),
            'maintenance_mode' => config('app.maintenance_mode', false),
            'registration_enabled' => config('auth.registration_enabled', true),
            'email_verification_required' => config('auth.email_verification_required', true),
            'max_file_upload_size' => config('app.max_file_upload_size', 2048), // in KB
            'default_currency' => config('app.default_currency', 'USD'),
            'timezone' => config('app.timezone', 'UTC'),
        ];
    }

    private function getLivechatSettings()
    {
        $settingsFile = 'livechat_settings.json';
        
        // Default settings
        $defaultSettings = [
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
        
        if (Storage::exists($settingsFile)) {
            $savedSettings = json_decode(Storage::get($settingsFile), true);
            // Merge saved settings with defaults to ensure all keys exist
            $settings = array_merge($defaultSettings, $savedSettings);
        } else {
            $settings = $defaultSettings;
        }
        
        return $settings;
    }

}
