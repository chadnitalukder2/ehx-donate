<?php

namespace EHXDonate\Core\Shortcodes;

use EHXDonate\Controllers\CampaignController;
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
        wp_enqueue_script('ehx-donate-public',  EHXDonate_URL . 'assets/js/campaign/campaign_details.js',  ['jquery'], EHXDonate_VERSION, true);
        $post_id = $shortcodeAttributes['id'] ?? 0;

        $controller = new CampaignController();
        $getCampaign = $controller->getCampaignByPostId($post_id);

        return View::make('CampaignDetails/CampaignDetails', [
            'campaign'=> $getCampaign,
        ]);
    }
}
