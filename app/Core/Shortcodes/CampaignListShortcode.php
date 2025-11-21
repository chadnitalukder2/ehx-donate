<?php

namespace EHXDonate\Core\Shortcodes;

use EHXDonate\Models\Campaign;
use EHXDonate\View;
use EHXDonate\Helpers\Currency;

class CampaignListShortcode
{

    public function register()
    {
        add_shortcode('ehxdo_campaign_lists', function ($shortcodeAttributes) {
            return $this->handelShortcodeCall($shortcodeAttributes);
        });
    }


    public function handelShortcodeCall($shortcodeAttributes)
    {
        wp_enqueue_style('ehx-donate-public',  EHXDonate_URL . 'assets/css/frontend/campaign_list.css', [],  EHXDonate_VERSION);


        $campaigns = (new Campaign())
            ->where('status', 'active')
            ->orderBy('id', 'DESC')
            ->get();
        $generalSettings = get_option('ehx_donate_settings_general', []);
        $currencySymbols = Currency::getCurrencySymbol('');
        $colorSettings = get_option('ehx_donate_settings_color', []);
   
        return View::make('CampaignLists/CampaignLists', [
            'data' => $campaigns,
            'generalSettings' => $generalSettings,
            'currencySymbols' => $currencySymbols,
            'colorSettings' => $colorSettings,
        ]);
    }
}
