<?php

use EHXDonate\Models\Donation;

// Get color and font settings
$primaryBtnColor = $colorSettings['primary_btn'] ?? '#079455';
$primaryBtnTextColor = $colorSettings['primary_btn_text'] ?? '#ececec';
$fontFamily = $colorSettings['fontFamily'] ?? 'Inter Tight, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif';
$goalsAmountColor = $colorSettings['primary_btn'] ?? '#079455';
$progressBarColor = $colorSettings['primary_btn'] ?? '#079455';

// Get current URL without query parameters for building pagination links
$base_url = strtok($_SERVER['REQUEST_URI'], '?');

?>

<style>
    :root {
        --ehxdo-primary-btn: <?php echo $primaryBtnColor; ?>;
        --ehxdo-primary-btn-text: <?php echo $primaryBtnTextColor; ?>;
        --ehxdo-font-family: <?php echo $fontFamily; ?>;
        --ehxdo-goals-amount-color: <?php echo $goalsAmountColor; ?>;
        --ehxdo-progress-bar-color: <?php echo $progressBarColor; ?>;
    }
</style>

<div class="ehxdo-campaign_listing_container">
    <div class="ehxdo-header">
        <h1 class="ehxdo-title">Active Campaigns</h1>
        <p class="ehxdo-subtitle"><?php echo $total; ?> campaigns need your support</p>
    </div>


    <?php if (empty($data)): ?>
        <div style="text-align: center; padding: 60px 20px; color: #6b7280;">
            <svg style="margin: 0 auto 20px; opacity: 0.5;" width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <h3 style="margin: 0 0 10px; font-size: 18px; color: #374151;">No campaigns found</h3>
            <p style="margin: 0; font-size: 15px;">
                <?php if (!empty($search_query)): ?>
                    Try adjusting your search terms or <a href="<?php echo esc_url($base_url); ?>" style="color: var(--ehxdo-primary-btn); text-decoration: none;">clear the search</a>
                <?php else: ?>
                    There are no active campaigns at the moment.
                <?php endif; ?>
            </p>
        </div>
    <?php else: ?>

        <div class="ehxdo-campaigns-grid">
            <?php foreach ($data as $campaign):

                $id = $campaign->id;

                $donations = (new Donation())
                    ->where('campaign_id', $id)->get();

                $raised = 0;
                $donationsCount = 0;

                foreach ($donations as $donation) {
                    if ($donation->payment_status === 'completed') {
                        $donationsCount++;
                        $raised += $donation->total_payment;
                    }
                }
                $goal = isset($campaign->goal_amount) ? (float) $campaign->goal_amount : 0;
                $progress = ($goal > 0) ? round(($raised / $goal) * 100) : 0;
                $progress = min($progress, 100);

            ?>

                <div class="ehxdo-campaign-card">

                    <?php if (!empty($campaign->header_image)) : ?>
                        <div class="ehxdo-campaign-image">
                            <img src="<?php echo esc_url($campaign->header_image); ?>"
                                alt="<?php echo esc_attr($campaign->title); ?>">
                        </div>
                    <?php endif; ?>

                    <div class="ehxdo-campaign-content">
                        <h2 class="ehxdo-campaign-title"><?php echo esc_html($campaign->title); ?></h2>
                        <p class="ehxdo-campaign-description">
                            <?php
                            $description = $campaign->short_description;
                            echo esc_html(mb_strlen($description) > 120 ? mb_substr($description, 0, 120) . '...' : $description);
                            ?>
                        </p>

                        <div class="ehxdo-progress-section">
                            <?php if ($generalSettings['progressbar'] ?? true): ?>
                                <div class="ehxdo-progress-bar">
                                    <div class="ehxdo-progress-fill"
                                        style="width: <?php echo $progress; ?>%;"></div>
                                </div>
                            <?php endif; ?>

                            <div class="ehxdo-campaign-stats">
                                <div class="ehxdo-stat-item">
                                    <span class="ehxdo-stat-label">Goals</span>
                                    <span class="ehxdo-stat-value">
                                        <?php
                                        $currency = $generalSettings['currency'] ?? 'GBP';
                                        $currencySymbol = $currencySymbols[$currency] ?? '£';
                                        $position = $generalSettings['currency_position'] ?? 'Before';
                                        $amount = number_format($campaign->goal_amount, 2);

                                        if ($position === 'Before') {
                                            echo esc_html($currencySymbol . $amount);
                                        } else {
                                            echo esc_html($amount . $currencySymbol);
                                        }
                                        ?>
                                    </span>
                                </div>
                                <?php if (!empty($campaign->end_date)) : ?>
                                    <div class="ehxdo-stat-item">
                                        <?php
                                        $start_date = strtotime($campaign->start_date);
                                        $end_date = strtotime($campaign->end_date);
                                        $current_date = time();
                                        $days_left = floor(($end_date - $current_date) / (60 * 60 * 24));

                                        if ($days_left > 0) {
                                            echo '<span class="ehxdo-time-left">' . $days_left . ' days left</span>';
                                        } elseif ($days_left == 0) {
                                            echo '<span class="ehxdo-time-left">Last day!</span>';
                                        } else {
                                            echo '<span class="ehxdo-time-left">Ended</span>';
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="ehxdo-donate-btn-wrapper" style="    padding-top: 50px;">
                            <button class="ehxdo-donate-btn" style="position: absolute; bottom:24px; width: -webkit-fill-available; right: 0; left: 0;
    margin: 0px 24px;"
                                onclick="window.open('<?php echo esc_url(get_permalink($campaign->post_id)); ?>')">
                                Donate Now
                            </button>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>

        </div>

    <?php endif; // End empty data check 
    ?>

    <?php if ($total_pages > 1): ?>
        <div class="ehxdo-pagination">
            <?php
            // Build query parameters
            $query_params = array_filter([
                'campaign_search' => $search_query
            ]);

            // Previous button
            $prev_disabled = ($current_page <= 1) ? 'disabled' : '';
            if ($current_page > 1) {
                $prev_params = array_merge($query_params, ['campaign_page' => $current_page - 1]);
                $prev_url = add_query_arg($prev_params, $base_url);
            } else {
                $prev_url = '#';
            }
            ?>
            <a href="<?php echo esc_url($prev_url); ?>"
                class="ehxdo-pagination-btn <?php echo $prev_disabled; ?>">
                ← Previous
            </a>

            <?php
            // Page numbers with smart ellipsis
            $range = 2;

            for ($i = 1; $i <= $total_pages; $i++) {
                if (
                    $i == 1 ||
                    $i == $total_pages ||
                    ($i >= $current_page - $range && $i <= $current_page + $range)
                ) {

                    $active_class = ($i == $current_page) ? 'active' : '';
                    $page_params = array_merge($query_params, ['campaign_page' => $i]);
                    $page_url = add_query_arg($page_params, $base_url);
            ?>
                    <a href="<?php echo esc_url($page_url); ?>"
                        class="ehxdo-pagination-btn <?php echo $active_class; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php
                } elseif ($i == $current_page - $range - 1 || $i == $current_page + $range + 1) {
                ?>
                    <span class="ehxdo-pagination-ellipsis">...</span>
            <?php
                }
            }
            ?>

            <?php
            // Next button
            $next_disabled = ($current_page >= $total_pages) ? 'disabled' : '';
            if ($current_page < $total_pages) {
                $next_params = array_merge($query_params, ['campaign_page' => $current_page + 1]);
                $next_url = add_query_arg($next_params, $base_url);
            } else {
                $next_url = '#';
            }
            ?>
            <a href="<?php echo esc_url($next_url); ?>"
                class="ehxdo-pagination-btn <?php echo $next_disabled; ?>">
                Next →
            </a>
        </div>
    <?php endif; ?>
</div>