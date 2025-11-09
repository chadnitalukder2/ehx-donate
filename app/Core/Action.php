<?php

namespace EHXDonate\Core;

/**
 * Action class for handling WordPress actions
 */
class Action
{
    /**
     * Register actions
     */
    public function register(): void
    {
        // Register custom post types
        add_action('init', [new CPTHandler(), 'registerCPT']);
         add_action('init', [new Shortcodes\CampaignShortCode(), 'register']);
    }
}
