<?php

namespace EHXDonate\Helpers;

class SettingsDefault
{
    public static function getSettings()
    {
        return [
            'general' => [
                'company_name' => 'EHx Donate',
                'industry' => 'Social',
                'country' => 'GB',
                'currency' => 'GBP',
                'currency_position' => 'Before',
                'address_name' => '',
                'city' => '',
                'postal_code' => '',
                'progressbar' => true,
                'anonymous' => false,
                'service_fee' => false,
                'service_fee_percentage' => 0,
                'gift_aid' => true,
            ],
            'integration' => [
                'stripe' => [
                    'mode' => 'test', // test, live
                    'clientKey' => '',
                    'clientSecret' => '',
                    'live_clientKey' => '',
                    'live_clientSecret' => '',
                    'enabled' => 'yes',
                ]
            ],
            'email' => [
                'adminEmail' => 'example@eh.studio',
                'mailFromName' => 'EHx Studio',
                'mailFromAddress' => 'example@eh.studio',
                'enableHtml' => true,
            ],
            'color' => [
                'primary_btn' => '#067a3b',
                'primary_btn_text' => '#FFFFFF',
                'fontFamily' =>  'Inter Tight, Arial, sans-serif',
            ],
            'recaptcha' => [
                'siteKey' => '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI',
                'secretKey' => '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe',
                'mode' =>  'disabled',
            ],
        ];
    }
}
