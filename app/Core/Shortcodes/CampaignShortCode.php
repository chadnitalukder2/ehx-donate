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
        $post_id = $shortcodeAttributes['post_id'] ?? 0;
      
          return View::make('CampaignDetails/CampaignDetails', [
          
        ]);
    }
}
