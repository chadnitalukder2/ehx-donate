  <?php

    //   dd($data, 'hello');

    ?>


  <div class="ehxdo-campaign_listing_container">
      <div class="ehxdo-header">
          <h1 class="ehxdo-title">Active Campaigns</h1>
          <p class="ehxdo-subtitle">3 campaigns need your support</p>
      </div>

      <div class="ehxdo-campaigns-grid">

          <?php foreach ($data as $campaign): ?>
              <div class="ehxdo-campaign-card">
                  <div class="ehxdo-campaign-image">
                      <img src="<?php echo esc_url($campaign->header_image ?: 'https://placehold.co/400x250'); ?>" alt="<?php echo esc_attr($campaign->title); ?>">
                  </div>
                  <div class="ehxdo-campaign-content">
                      <h2 class="ehxdo-campaign-title"><?php echo esc_html($campaign->title); ?></h2>
                      <p class="ehxdo-campaign-description">
                          <?php echo esc_html(wp_trim_words($campaign->short_description, 20, '...')); ?>
                      </p>

                      <div class="ehxdo-progress-section">
                          <div class="ehxdo-progress-bar">
                              <div class="ehxdo-progress-fill" style="width: <?php echo rand(20, 90); ?>%;"></div>
                          </div>
                          <div class="ehxdo-campaign-stats">
                              <div class="ehxdo-stat-item">
                                  <span class="ehxdo-stat-label">Goals</span>
                                  <span class="ehxdo-stat-value">$<?php echo number_format($campaign->goal_amount, 2); ?></span>
                              </div>
                              <div class="ehxdo-stat-item">
                                  <span class="ehxdo-time-left">Started: <?php echo date('M d, Y', strtotime($campaign->start_date)); ?></span>
                              </div>
                          </div>
                      </div>

                      <button class="ehxdo-donate-btn"
                          onclick="window.open('<?php echo esc_url(get_permalink($campaign->post_id)); ?>', '_blank')">
                          Donate Now
                      </button>
                  </div>
              </div>
          <?php endforeach; ?>

      </div>
  </div>