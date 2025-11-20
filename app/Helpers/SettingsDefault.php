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
                'primary_btn' => '#067a3b',
                'primary_btn_text' => '#FFFFFF',
                'fontFamily' =>  'Inter Tight, Arial, sans-serif',
            ],
        ];
    }
}
