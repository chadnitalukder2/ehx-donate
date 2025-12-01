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
        wp_enqueue_style('ehx-donate-public', EHXDonate_URL . 'assets/css/frontend/campaign_list.css', [], EHXDonate_VERSION);

        // Get pagination and search parameters
        $current_page = isset($_GET['campaign_page']) ? max(1, intval($_GET['campaign_page'])) : 1;
        $search_query = isset($_GET['campaign_search']) ? sanitize_text_field($_GET['campaign_search']) : '';
        $per_page = 12; // Items per page

        // Calculate offset
        $offset = ($current_page - 1) * $per_page;

        // Build base query
        $query = (new Campaign())->where('status', 'active');

        // Get total count for pagination
        $total = $query->count();
        $total_pages = ceil($total / $per_page);

        // Get campaigns with pagination
        $campaigns = $query->orderBy('id', 'DESC')->where('status', 'active')
            ->limit($per_page)
            ->offset($offset)
            ->get();

        $generalSettings = get_option('ehx_donate_settings_general', []);
        $currencySymbols = Currency::getCurrencySymbol('');
        $colorSettings = get_option('ehx_donate_settings_color', []);

        return View::make('CampaignLists/CampaignLists', [
            'data' => $campaigns,
            'total' => $total,
            'current_page' => $current_page,
            'total_pages' => $total_pages,
            'per_page' => $per_page,
            'search_query' => $search_query,
            'generalSettings' => $generalSettings,
            'currencySymbols' => $currencySymbols,
            'colorSettings' => $colorSettings,
        ]);
    }
}
