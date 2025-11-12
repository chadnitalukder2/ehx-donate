<?php

namespace EHXDonate\Core\Shortcodes;

use EHXDonate\View;

class CampaignShortCode
{
    public function register()
    {
        add_shortcode('ehxdo_campaign', function ($shortcodeAttributes) {
            return $this->handelShortcodeCall($shortcodeAttributes);
        });
    }

    public function handelShortcodeCall($shortcodeAttributes)
    {
        wp_enqueue_style('ehx-donate-public',  EHXDonate_URL . 'assets/css/frontend/campaign_details.css', [],  EHXDonate_VERSION);
        wp_enqueue_script(  'ehx-donate-public',  EHXDonate_URL . 'assets/js/campaign/campaign_details.js',  ['jquery'], EHXDonate_VERSION, true );
        $post_id = $shortcodeAttributes['post_id'] ?? 0;

        return View::make('CampaignDetails/CampaignDetails', []);
    }
}
