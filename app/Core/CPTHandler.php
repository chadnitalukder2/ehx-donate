<?php

namespace EHXDonate\Core;

/**
 * Custom Post Type Handler
 */
class CPTHandler
{
    /**
     * Register custom post types
     */
    public function registerCPT(): void
    {
        // Register campaign post type
        register_post_type('ehxdo_campaign', [
            'labels' => [
                'name' => __('Campaigns', 'ehx-donate'),
                'singular_name' => __('Campaign', 'ehx-donate')
            ],
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'campaigns'],
            'supports' => ['title', 'editor', 'thumbnail']
        ]);

        // refresh rules
        flush_rewrite_rules();
    }
}
