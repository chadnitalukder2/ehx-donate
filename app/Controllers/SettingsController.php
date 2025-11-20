<?php

namespace EHXDonate\Controllers;

use EHXDonate\Models\Trip;
use EHXDonate\Helpers\SettingsDefault;

/**
 * Transaction Controller
 */
class SettingsController extends Controller
{

    public function getSettings(String $key): void
    {
        $settings_key = 'ehx_donate_settings_' . $key;

        $settings = get_option($settings_key, []);

        if (!$settings) {
            $settings = SettingsDefault::getSettings()[$key];
        }

        $this->success([
            'settings' => $settings
        ]);
    }

    public function updateSettings(string $key): void
    {
        $settings_key = 'ehx_donate_settings_' . $key;

        $settings = $this->validate([
            'settings' => 'required|string'
        ]);

        $settings = json_decode($settings['settings'], true);
        delete_option($settings_key);
        // update_option($settings_key, $settings, false);

        $this->success([
            'message' => 'Settings updated successfully'
        ]);
    }
}
