<?php

namespace EHXDonate\Core\Shortcodes;

use EHXDonate\View;
use EHXDonate\Models\Donation;
use EHXDonate\Models\Campaign;

class ReceiptShortcode
{
    public function register()
    {
        add_shortcode('ehxdo_receipt', function ($shortcodeAttributes) {
            return $this->handelShortcodeCall($shortcodeAttributes);
        });
        add_shortcode('ehxdo_failed_reciept', function ($shortcodeAttributes) {
            return $this->handelFailedShortcodeCall($shortcodeAttributes);
        });
    }

    public function handelShortcodeCall($shortcodeAttributes)
    {
        $id = $shortcodeAttributes['id'] ?? 0;
        wp_head();
        wp_enqueue_style('ehxdo-receipt',  EHXDonate_URL . 'assets/css/frontend/success-receipt.css', [],  EHXDonate_VERSION);
        wp_enqueue_script('ehxdo-receipt',  EHXDonate_URL . 'assets/js/receipt.js', ['jquery'], EHXDonate_VERSION, true);
        wp_localize_script('ehxdo-receipt', 'EHXDonate', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'restUrl' => rest_url('ehx-donate/v1/'),
            'restNonce' => wp_create_nonce('wp_rest'),
        ]);
        wp_footer();
        $donation = Donation::find($id);
        $campaign = Campaign::find($donation->campaign_id);
        $general_settings = get_option('ehx_donate_settings_general', []);
        // var_dump($donation->toArray());
        // var_dump($campaign->toArray());
        // exit;
        return View::make('success-reciept', [
            'donation' => $donation->toArray(),
            'campaign' => $campaign->toArray(),
            'general_settings' => $general_settings,
        ]);
    }

    public function handelFailedShortcodeCall($shortcodeAttributes)
    {
        $id = $shortcodeAttributes['id'] ?? 0;
        wp_head();
        wp_enqueue_style('ehxdo-failed-receipt',  EHXDonate_URL . 'assets/css/frontend/failed-receipt.css', [],  EHXDonate_VERSION);
        wp_footer();
        $donation = Donation::find($id);
        $campaign = Campaign::find($donation->campaign_id);
        $general_settings = get_option('ehx_donate_settings_general', []);
        return View::make('failed-reciept', [
            'donation' => $donation->toArray(),
            'campaign' => $campaign->toArray(),
            'general_settings' => $general_settings,
        ]);
    }
}
