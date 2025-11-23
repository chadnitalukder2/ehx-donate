<?php
// Get color and font settings
$primaryBtnColor = $colorSettings['primary_btn'] ?? '#079455';
$primaryBtnTextColor = $colorSettings['primary_btn_text'] ?? '#ececec';
$fontFamily = $colorSettings['fontFamily'] ?? 'Inter Tight, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif';
$goalsAmountColor = $colorSettings['primary_btn'] ?? '#079455';
$progressBarColor = $colorSettings['primary_btn'] ?? '#079455';
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
        <p class="ehxdo-subtitle">3 campaigns need your support</p>
    </div>

    <div class="ehxdo-campaigns-grid">

        <?php foreach ($data as $campaign): ?>
            <div class="ehxdo-campaign-card">
                <div class="ehxdo-campaign-image">
                    <img src="<?php echo esc_url($campaign->header_image ?: 'https://placehold.co/500x260'); ?>" alt="<?php echo esc_attr($campaign->title); ?>">
                </div>
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
                                <div class="ehxdo-progress-fill" style="width: <?php echo rand(20, 90); ?>%;"></div>
                            </div>
                        <?php endif; ?>

                        <div class="ehxdo-campaign-stats">
                            <div class="ehxdo-stat-item">
                                <span class="ehxdo-stat-label">Goals</span>
                                <span class="ehxdo-stat-value">
                                    <?php
                                    $currency = $generalSettings['currency'] ?? 'USD';
                                    $currencySymbol = $currencySymbols[$currency] ?? '$';
                                    $position = $generalSettings['currency_position'] ?? 'Before';
                                    $amount = number_format($campaign->goal_amount, 2);

                                    if ($position === 'Before') {
                                        echo esc_html($currencySymbol . $amount);
                                    } else {
                                        echo esc_html($amount . $currencySymbol);
                                    }
                                    ?>
                                    <!-- $<?php echo number_format($campaign->goal_amount, 2); ?> -->
                                </span>
                            </div>
                            <div class="ehxdo-stat-item">
                                <?php
                                $start_date = strtotime($campaign->start_date);
                                $end_date = strtotime($campaign->end_date); // Assuming you have an end_date field
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
                        </div>
                    </div>

                    <button class="ehxdo-donate-btn"
                        onclick="window.open('<?php echo esc_url(get_permalink($campaign->post_id)); ?>')">
                        Donate Now
                    </button>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</div>