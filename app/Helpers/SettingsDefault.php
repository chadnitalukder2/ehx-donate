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
                'country' => 'GB',
                'currency' => 'GBP',
                'currency_position' => 'Before',
                'address_name' => '',
                'address_line_1' => '',
                'address_line_2' => '',
                'city' => '',
                'state' => '',
                'postal_code' => '',
                'progressbar' => true,
                'service_fee' => false,
                'service_fee_percentage' => 0,
            ],
            'integration' => [
                'stripe' => [
                    'mode' => 'test', // test, live
                    'clientKey' => '',
                    'clientSecret' => '',
                    'live_clientKey' => '',
                    'live_clientSecret' => '',
                    'enabled' => 'no',
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
