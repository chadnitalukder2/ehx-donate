<?php

namespace EHXDonate\Helpers;

class SettingsDefault
{
    public static function getSettings()
    {
        return [
            'general' => [
                'company_name' => '',
                'industry' => 'Social',
                'country' => 'USA',
                'currency' => 'USD',
                'address_name' => '',
                'address_line_1' => '',
                'address_line_2' => '',
                'city' => '',
                'state' => '',
                'postal_code' => '',
            ],
            'integration' => [
                'stripe' => [
                    'title' => 'Stripe',
                    'description' => 'Configuration for Stripe payment gateway integration',
                    'enabled' => false,
                    'enabledOption' => true,
                    'clientKey' => '',
                    'clientSecret' => '',
                ]
            ],
            'email' => [
                'adminEmail' => get_bloginfo('admin_email'),
                'mailFromName' => get_bloginfo('name'),
                'mailFromAddress' => '',
                'enableHtml' => true,
            ],
            'color' => [
                'primary' => '#409EFF',
                'success' => '#67C23A',
                'warning' => '#E6A23C',
                'danger' => '#F56C6C',
                'textPrimary' => '#303133',
                'textRegular' => '#606266',
                'textSecondary' => '#909399',
                'textPlaceholder' => '#C0C4CC',
                'fontFamily' => 'Inter Tight, Arial, sans-serif',
                'fontFamilyMono' => 'Courier New, Courier, monospace',
            ],
        ];
    }
}
