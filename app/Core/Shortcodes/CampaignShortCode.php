<?php

namespace EHXDonate\Core\Shortcodes;

use EHXDonate\Controllers\CampaignController;
use EHXDonate\View;
use EHXDonate\Helpers\Currency;

class CampaignShortCode
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
    }
    public function register()
    {
        add_shortcode('ehxdo_campaign', function ($shortcodeAttributes) {
            return $this->handelShortcodeCall($shortcodeAttributes);
        });
    }

    public function enqueueScripts()
    {
        wp_enqueue_style('ehx-donate-public',  EHXDonate_URL . 'assets/css/frontend/campaign_details.css', [],  EHXDonate_VERSION);
        wp_enqueue_script('ehx-donate-public',  EHXDonate_URL . 'assets/js/campaign/campaign_details.js',  ['jquery'], EHXDonate_VERSION, true);

        wp_localize_script('ehx-donate-public', 'EHXDonate', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'restUrl' => rest_url('ehx-donate/v1/'),
            'restNonce' => wp_create_nonce('wp_rest'),
        ]);
    }
    public function handelShortcodeCall($shortcodeAttributes)
    {

        $controller = new CampaignController();
        $post_id = $shortcodeAttributes['post_id'] ?? 0;
        if (!$post_id) {
            $id = $shortcodeAttributes['id'] ?? 0;
            $getCampaign = $controller->getCampaignByPostId($id);
        } else {
            $getCampaign = $controller->getCampaignByPostId($post_id);
        }
        $generalSettings = get_option('ehx_donate_settings_general', []);
        $currencySymbols = Currency::getCurrencySymbol('');
        $colorSettings = get_option('ehx_donate_settings_color', []);
        $integrationsSettings = get_option('ehx_donate_settings_integration', []);
        $isTestMode = $integrationsSettings['stripe']['test_mode'] ?? false;
        $stripeEnabled = $integrationsSettings['stripe']['enabled'] ?? false;

        return View::make('CampaignDetails/CampaignDetails', [
            'campaign' => $getCampaign,
            'generalSettings' => $generalSettings,
            'currencySymbols' => $currencySymbols,
            'colorSettings' => $colorSettings,
            'integrationsSettings' => $integrationsSettings,
            'isTestMode' => $isTestMode,
            'stripeEnabled' => $stripeEnabled,
        ]);
    }
}
