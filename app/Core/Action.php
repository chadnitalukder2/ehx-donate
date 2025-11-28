<?php

namespace EHXDonate\Core;

use EHXDonate\Services\Payment\Stripe;

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
        add_action('init', [new Shortcodes\CampaignListShortcode(), 'register']);
        add_action('init', [new Stripe(), 'register']);
    }
}
