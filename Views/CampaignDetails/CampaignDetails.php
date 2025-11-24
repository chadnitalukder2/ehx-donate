<?php
$primaryBtnColor = $colorSettings['primary_btn'] ?? '#079455';
$primaryBtnTextColor = $colorSettings['primary_btn_text'] ?? '#ececec';
$fontFamily = $colorSettings['fontFamily'] ?? 'Inter Tight, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif';

$raised = 20;
$goal = isset($campaign['goal_amount']) ? (float) $campaign['goal_amount'] : 0;
$progress = ($goal > 0) ? round(($raised / $goal) * 100) : 0;
$progress = min($progress, 100);

$currency = $generalSettings['currency'] ?? 'GBP';
$currencySymbol = $currencySymbols[$currency] ?? '£';
$position = $generalSettings['currency_position'] ?? 'Before';

$serviceFeePercent = $generalSettings['service_fee_percentage'] ?? 0;

function formatAmount($amount, $symbol, $position)
{
    $formatted = number_format($amount, 2);
    return $position === 'Before' ? $symbol  . $formatted : $formatted . $symbol;
}

?>

<style>
    :root {
        --ehxdo-primary-btn: <?php echo $primaryBtnColor; ?>;
        --ehxdo-primary-btn-text: <?php echo $primaryBtnTextColor; ?>;
        --ehxdo-font-family: <?php echo $fontFamily; ?>;
    }
</style>

<div class="ehxdo_campaign_list_wrapper">

    <?php if ($isTestMode && $stripeEnabled): ?>
        <div class="ehxdo_test_mode_container">
            <p class="ehxdo-back-link">
                Test Mode
            </p>
        </div>
    <?php endif; ?>


    <div class="ehxdo-campaign_details_container">
        <!-- Left Section -->

        <div class="ehxdo-left-section">

            <div class="ehxdo_campaign_details_wrapper">
                <div class="ehxdo-campaign-header-image">
                    <img src="<?php echo htmlspecialchars($campaign['header_image']); ?>"
                        alt="<?php echo htmlspecialchars($campaign['title']); ?>"
                        class="ehxdo-campaign-image">
                </div>
                <div class="ehxdo_details_campaign_list">


                    <div class="ehxdo_campaign_title_section">
                        <p class="ehxdo-campaign-title"><?php echo htmlspecialchars($campaign['title']); ?></p>
                    </div>

                    <div class="ehxdo-stats-container">
                        <div class="ehxdo-stat-item ehxdo-stat-raised">
                            <div class="ehxdo-stat-value">
                                <?php echo formatAmount($campaign['raised'], $currencySymbol, $position); ?>
                            </div>

                            <div class="ehxdo-stat-label">Raised</div>
                        </div>
                        <div class="ehxdo-stat-item ehxdo-stat-goal">
                            <div class="ehxdo-stat-value">
                                <?php echo formatAmount($campaign['goal_amount'], $currencySymbol, $position); ?>
                            </div>
                            <div class="ehxdo-stat-label">Goal</div>
                        </div>
                        <div class="ehxdo-stat-item ehxdo-stat-donors">
                            <div class="ehxdo-stat-value"> <?php echo $campaign['donors'] ?? 0; ?></div>
                            <div class="ehxdo-stat-label">Donors</div>
                        </div>
                    </div>
                    <?php if ($generalSettings['progressbar'] ?? true): ?>
                        <div class="ehxdo-progress-container">
                            <div class="ehxdo-progress-label">
                                <span>Campaign Progress</span>
                                <span class="ehxdo-progress-percent"><?php echo $progress; ?>%</span>
                            </div>
                            <div class="ehxdo-progress-bar">
                                <div class="ehxdo-progress-fill" style="width: <?php echo $progress; ?>%"></div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="ehxdo-about-section">
                        <h2 class="ehxdo-about-title">About This Campaign</h2>
                        <p class="ehxdo-about-text"><?php echo htmlspecialchars($campaign['short_description']); ?></p>
                        <p class="ehxdo-about-text"><?php echo htmlspecialchars($campaign['settings']['description']) ?></p>
                    </div>

                    <div class="ehxdo-campaign-meta">
                        <span class="ehxdo-meta-item">
                            <span class="ehxdo-meta-icon">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.3335 1.33337V4.00004" stroke="#4A5565" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M10.6665 1.33337V4.00004" stroke="#4A5565" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M12.6667 2.66663H3.33333C2.59695 2.66663 2 3.26358 2 3.99996V13.3333C2 14.0697 2.59695 14.6666 3.33333 14.6666H12.6667C13.403 14.6666 14 14.0697 14 13.3333V3.99996C14 3.26358 13.403 2.66663 12.6667 2.66663Z" stroke="#4A5565" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M2 6.66663H14" stroke="#4A5565" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span> Ends <?php echo htmlspecialchars($campaign['end_date']); ?>
                        </span>
                        <span class="ehxdo-meta-item">
                            <span class="ehxdo-meta-icon">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.6668 14V12.6667C10.6668 11.9594 10.3859 11.2811 9.88578 10.781C9.38568 10.281 8.70741 10 8.00016 10H4.00016C3.29292 10 2.61464 10.281 2.11454 10.781C1.61445 11.2811 1.3335 11.9594 1.3335 12.6667V14" stroke="#4A5565" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M10.6665 2.08533C11.2383 2.23357 11.7448 2.5675 12.1063 3.0347C12.4678 3.5019 12.664 4.07592 12.664 4.66666C12.664 5.2574 12.4678 5.83142 12.1063 6.29862C11.7448 6.76582 11.2383 7.09975 10.6665 7.24799" stroke="#4A5565" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M14.6665 14V12.6667C14.6661 12.0758 14.4694 11.5019 14.1074 11.0349C13.7454 10.5679 13.2386 10.2344 12.6665 10.0867" stroke="#4A5565" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M6.00016 7.33333C7.47292 7.33333 8.66683 6.13943 8.66683 4.66667C8.66683 3.19391 7.47292 2 6.00016 2C4.5274 2 3.3335 3.19391 3.3335 4.66667C3.3335 6.13943 4.5274 7.33333 6.00016 7.33333Z" stroke="#4A5565" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

                            </span> <?php echo $campaign['donors']; ?> Supporters
                        </span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Section -->
        <div class="ehxdo-right-section">

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="ehxdo-donation-form">
                <!-- Section 1: Make a Donation -->
                <div class="ehxdo-donation-card" id="ehxdo-section-1">
                    <h3 class="ehxdo-card-title">Make a Donation</h3>


                    <?php if ($campaign['settings']['allow_recurring_amount'] === true): ?>
                        <div class="ehxdo-donation-type">
                            <label for="donation_type" class="ehxdo-label">Donation Type</label>
                            <select id="donation_type" name="donation_type" class="ehxdo-select-type">
                                <option value="one-time" selected>One-time</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="ehxdo-amount-section">
                        <label class="ehxdo-label">Select Amount</label>
                        <input type="hidden" name="amount" id="ehxdo-selected-amount" value="<?php echo $default_amount; ?>">
                        <input type="hidden" name="currency" value="<?php echo $currency; ?>">
                        <input type="hidden" name="currency_symbol" value="<?php echo $currencySymbol; ?>">
                        <input type="hidden" name="currency_position" value="<?php echo $position; ?>">

                        <?php if ($campaign['settings']['allow_custom_amount'] === true): ?>
                            <div class="ehxdo-amount-grid">
                                <?php foreach ($campaign['settings']['pricing_items'] as $item): ?>
                                    <button type="button"
                                        class="ehxdo-amount-btn <?php echo $item['amount'] === $default_amount ? 'ehxdo-selected' : ''; ?>"
                                        data-amount="<?php echo $item['amount']; ?>">
                                        <?php echo formatAmount($item['amount'], $currencySymbol, $position); ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <input type="text"
                            placeholder="Custom amount"
                            class="ehxdo-custom-amount"
                            id="ehxdo-custom-amount">
                    </div>
                    <p id="ehxdo_service_fee_percentage" style="display: none;"> <?php echo $generalSettings['service_fee_percentage'] ?? ''; ?></p>

                    <div class="ehxdo-summary" style="padding-top: 20px; margin-bottom: 20px;">
                        <div class="ehxdo-summary-row">
                            <span>Total Payable Amount :</span>
                            <span class="ehxdo-amount" id="ehxdo-summary-amount">
                                <?php echo formatAmount($default_amount, $currencySymbol, $position); ?>
                            </span>
                        </div>
                        <?php if ($generalSettings['service_fee'] ?? false): ?>
                            <div class="ehxdo-summary-row ehxdo-highlight">
                                <span>Final Payable with Fee :</span>
                                <span class="ehxdo-amount" id="ehxdo-summary-total">
                                    <?php echo formatAmount($default_amount, $currencySymbol, $position); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <p class="ehxdo-error-msg" style="color: red; font-size: 14px; margin-top: 10px; display:none">Please select or enter a donation amount.</p>
                    <button type="button" class="ehxdo-donate-btn" id="ehxdo-donate-btn">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.3335 3.33331H2.66683C1.93045 3.33331 1.3335 3.93027 1.3335 4.66665V11.3333C1.3335 12.0697 1.93045 12.6666 2.66683 12.6666H13.3335C14.0699 12.6666 14.6668 12.0697 14.6668 11.3333V4.66665C14.6668 3.93027 14.0699 3.33331 13.3335 3.33331Z" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M1.3335 6.66669H14.6668" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Donate <span id="donate-amount-display"><?php echo formatAmount($default_amount, $currencySymbol, $position); ?></span>
                    </button>

                    <p class="ehxdo-disclaimer">Protected by Google reCAPTCHA. Issues payment via Stripe.</p>

                </div>

                <!-- Section 2: Personal Information -->
                <div class="ehxdo-donation-card ehxdo-hidden" id="ehxdo-section-2">
                    <h3 class="ehxdo-card-title">Personal Information</h3>

                    <div class="ehxdo-form">
                        <!-- <div class="ehxdo-form-group">
                            <label class="ehxdo-label">Title *</label>
                            <select name="title" class="ehxdo-input" required>
                                <option value="">Select title</option>
                                <option value="mr">Mr.</option>
                                <option value="mrs">Mrs.</option>
                                <option value="ms">Ms.</option>
                                <option value="dr">Dr.</option>
                            </select>
                        </div> -->

                        <div class="ehxdo-form-row">
                            <div class="ehxdo-form-group">
                                <label class="ehxdo-label">First Name *</label>
                                <input type="text" name="first_name" class="ehxdo-input" required>
                            </div>
                            <div class="ehxdo-form-group">
                                <label class="ehxdo-label">Last Name *</label>
                                <input type="text" name="last_name" class="ehxdo-input" required>
                            </div>
                        </div>


                        <div class="ehxdo-form-group">
                            <label class="ehxdo-label">Email Address *</label>
                            <input type="email" name="email" class="ehxdo-input" required>
                        </div>
                        <div class="ehxdo-form-group">
                            <label class="ehxdo-label">Phone Number *</label>
                            <input type="tel" name="phone" class="ehxdo-input" required>
                        </div>


                        <div class="ehxdo-gift-aid">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" name="gift_aid" id="gift_aid_checkbox" value="1" style="cursor: pointer;">
                                <span style="font-size: 0.875rem;">Gift Aid</span>
                            </label>
                        </div>

                        <!-- Gift Aid Address Fields (Initially Hidden) -->
                        <div id="gift_aid_fields" style="display: none;">
                            <div class="ehxdo-form-group">
                                <label class="ehxdo-label">Address line 1</label>
                                <input type="text" name="address_line_1" class="ehxdo-input">
                            </div>
                            <div class="ehxdo-form-group" style="padding-top: 15px;">
                                <label class="ehxdo-label">Address line 2</label>
                                <input type="text" name="address_line_2" class="ehxdo-input">
                            </div>

                            <div class="ehxdo-form-row" style="padding-top: 15px;">
                                <div class="ehxdo-form-group">
                                    <label class="ehxdo-label">City</label>
                                    <input type="text" name="city" class="ehxdo-input">
                                </div>
                                <div class="ehxdo-form-group">
                                    <label class="ehxdo-label">State</label>
                                    <input type="text" name="state" class="ehxdo-input">
                                </div>
                            </div>

                            <div class="ehxdo-form-row" style="padding-top: 15px;">
                                <div class="ehxdo-form-group">
                                    <label class="ehxdo-label">Country</label>
                                    <input type="text" name="country" class="ehxdo-input">
                                </div>
                                <div class="ehxdo-form-group">
                                    <label class="ehxdo-label">Post Code</label>
                                    <input type="text" name="post_code" class="ehxdo-input">
                                </div>
                            </div>
                        </div>

                        <div class="ehxdo-summary" style="padding-top: 20px;">
                            <div class="ehxdo-summary-row">
                                <span>Total Payable Amount :</span>
                                <span class="ehxdo-amount" id="ehxdo-summary-amount">
                                    <?php echo formatAmount($default_amount, $currencySymbol, $position); ?>
                                </span>
                            </div>
                            <div class="ehxdo-summary-row ehxdo-highlight">
                                <span>Your Contribution with Gift Aid:</span>
                                <span class="ehxdo-amount" id="ehxdo-summary-total">
                                    <?php echo formatAmount($default_amount, $currencySymbol, $position); ?>
                                </span>
                            </div>
                        </div>

                        <div class="ehxdo-section-nav">
                            <button type="button" class="ehxdo-nav-btn ehxdo-prev">Previous</button>
                            <button type="submit" class="ehxdo-nav-btn ehxdo-submit">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    //Gift Aid Toggle-===========================================
    const giftAidCheckbox = document.getElementById('gift_aid_checkbox');
    const giftAidFields = document.getElementById('gift_aid_fields');

    giftAidCheckbox.addEventListener('change', function() {
        if (this.checked) {
            giftAidFields.style.display = 'block';
        } else {
            giftAidFields.style.display = 'none';
        }
    });

    //=====================donate must amount validation=========================
    const donateButton = document.getElementById('donate-amount-display');
</script>