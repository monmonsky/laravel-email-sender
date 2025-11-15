<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        // Default settings if not in database
        $defaultSettings = [
            'app_name' => 'Email Sender',
            'default_sender_email' => '',
            'default_sender_name' => '',
            'emails_per_hour' => '100',
            'enable_tracking' => 'true',
            'enable_analytics' => 'true',
        ];

        $settings = array_merge($defaultSettings, $settings);

        // Convert string boolean to actual boolean for Vue
        $settings['enable_tracking'] = $settings['enable_tracking'] === 'true' || $settings['enable_tracking'] === '1';
        $settings['enable_analytics'] = $settings['enable_analytics'] === 'true' || $settings['enable_analytics'] === '1';
        $settings['emails_per_hour'] = (int) $settings['emails_per_hour'];

        return Inertia::render('Settings/Index', [
            'settings' => $settings
        ]);
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'default_sender_email' => 'nullable|email|max:255',
            'default_sender_name' => 'nullable|string|max:255',
            'emails_per_hour' => 'required|integer|min:1',
            'enable_tracking' => 'boolean',
            'enable_analytics' => 'boolean',
        ]);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => is_bool($value) ? ($value ? 'true' : 'false') : $value]
            );
        }

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully!');
    }
}
