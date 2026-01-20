<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLivechatSettingsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'provider' => 'required|string|in:jivochat,tawk,intercom,zendesk,other',
            'widget_script' => 'required|string|max:10000',
            'is_enabled' => 'boolean',
            'show_on_support_page' => 'boolean',
            'show_on_contact_page' => 'boolean',
            'show_on_homepage' => 'boolean',
            'widget_position' => 'required|string|in:bottom-right,bottom-left,top-right,top-left',
            'business_hours.enabled' => 'nullable|boolean',
            'business_hours.timezone' => 'nullable|string|timezone',
            'business_hours.schedule.*.start' => 'nullable|string|regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/',
            'business_hours.schedule.*.end' => 'nullable|string|regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/',
            'business_hours.schedule.*.enabled' => 'nullable|boolean',
            'custom_css' => 'nullable|string|max:5000',
            'custom_js' => 'nullable|string|max:5000',
        ];
    }

    public function messages()
    {
        return [
            'provider.required' => 'Livechat provider is required.',
            'provider.in' => 'Please select a valid livechat provider.',
            'widget_script.required' => 'Livechat script is required.',
            'widget_script.max' => 'Livechat script cannot exceed 10000 characters.',
            'widget_position.required' => 'Widget position is required.',
            'widget_position.in' => 'Please select a valid widget position.',
            'business_hours.timezone.required' => 'Timezone is required.',
            'business_hours.timezone.timezone' => 'Please select a valid timezone.',
            'business_hours.schedule.*.start.required' => 'Start time is required.',
            'business_hours.schedule.*.start.regex' => 'Please enter a valid time format (HH:MM).',
            'business_hours.schedule.*.end.required' => 'End time is required.',
            'business_hours.schedule.*.end.regex' => 'Please enter a valid time format (HH:MM).',
            'custom_css.max' => 'Custom CSS cannot exceed 5000 characters.',
            'custom_js.max' => 'Custom JavaScript cannot exceed 5000 characters.',
        ];
    }
}

