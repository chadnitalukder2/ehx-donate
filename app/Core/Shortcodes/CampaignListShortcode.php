<?php

namespace EHXDonate\Core\Shortcodes;

use EHXDonate\Models\Campaign;
use EHXDonate\View;

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
        wp_enqueue_style( 'ehx-donate-public',  EHXDonate_URL . 'assets/css/frontend/campaign_list.css', [],  EHXDonate_VERSION  );
        
        
      $campaigns = (new Campaign())
    ->where('status', 'active')
    ->orderBy('id', 'DESC')
    ->get();

        return View::make('CampaignLists/CampaignLists', [
            'data' => $campaigns,
        ]);
    }
}
