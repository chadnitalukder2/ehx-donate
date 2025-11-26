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

$minDonation = $campaign['settings']['min_donation'] ?? 1;
$maxDonation = $campaign['settings']['max_donation'] ?? 999999;
// $default_amount = 0;
$default_amount = $campaign['settings']['pricing_items'][0]['amount'] ?? $minDonation;
// dd($campaign);
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

                <?php if (!empty($campaign['header_image'])): ?>
                    <div class="ehxdo-campaign-header-image">
                        <img src="<?php echo htmlspecialchars($campaign['header_image']); ?>"
                            alt="<?php echo htmlspecialchars($campaign['title']); ?>"
                            class="ehxdo-campaign-image">
                    </div>
                <?php endif; ?>
                <div class="ehxdo_details_campaign_list">


                    <div class="ehxdo_campaign_title_section">
                        <p class="ehxdo-campaign-title"><?php echo htmlspecialchars($campaign['title']); ?></p>
                    </div>

                    <div class="ehxdo-stats-container">
                        <div class="ehxdo-stat-item ehxdo-stat-raised">
                            <div class="ehxdo-stat-value">
                                <?php // echo formatAmount($campaign['raised'], $currencySymbol, $position); 
                                ?>
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
                        <?php if (!empty($campaign['end_date'])): ?>
                            <span class="ehxdo-meta-item">
                                <span class="ehxdo-meta-icon">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5.3335 1.33337V4.00004" stroke="#4A5565" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M10.6665 1.33337V4.00004" stroke="#4A5565" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M12.6667 2.66663H3.33333C2.59695 2.66663 2 3.26358 2 3.99996V13.3333C2 14.0697 2.59695 14.6666 3.33333 14.6666H12.6667C13.403 14.6666 14 14.0697 14 13.3333V3.99996C14 3.26358 13.403 2.66663 12.6667 2.66663Z" stroke="#4A5565" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M2 6.66663H14" stroke="#4A5565" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                Ends <?php echo esc_html($campaign['end_date']); ?>
                            </span>
                        <?php endif; ?>
                        <span class="ehxdo-meta-item">
                            <span class="ehxdo-meta-icon">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.6668 14V12.6667C10.6668 11.9594 10.3859 11.2811 9.88578 10.781C9.38568 10.281 8.70741 10 8.00016 10H4.00016C3.29292 10 2.61464 10.281 2.11454 10.781C1.61445 11.2811 1.3335 11.9594 1.3335 12.6667V14" stroke="#4A5565" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M10.6665 2.08533C11.2383 2.23357 11.7448 2.5675 12.1063 3.0347C12.4678 3.5019 12.664 4.07592 12.664 4.66666C12.664 5.2574 12.4678 5.83142 12.1063 6.29862C11.7448 6.76582 11.2383 7.09975 10.6665 7.24799" stroke="#4A5565" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M14.6665 14V12.6667C14.6661 12.0758 14.4694 11.5019 14.1074 11.0349C13.7454 10.5679 13.2386 10.2344 12.6665 10.0867" stroke="#4A5565" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M6.00016 7.33333C7.47292 7.33333 8.66683 6.13943 8.66683 4.66667C8.66683 3.19391 7.47292 2 6.00016 2C4.5274 2 3.3335 3.19391 3.3335 4.66667C3.3335 6.13943 4.5274 7.33333 6.00016 7.33333Z" stroke="#4A5565" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

                            </span> <?php //echo $campaign['donors']; 
                                    ?> Supporters
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

                        <?php if ($campaign['settings']['predefined_pricing'] === true): ?>
                            <div class="ehxdo-amount-grid">
                                <?php foreach ($campaign['settings']['pricing_items'] as $item): ?>
                                    <button type="button"
                                        class="ehxdo-amount-btn <?php echo $item['amount'] === $default_amount ? 'ehxdo-selected' : '0.00'; ?>"
                                        data-amount="<?php echo $item['amount']; ?>">
                                        <?php echo formatAmount($item['amount'], $currencySymbol, $position); ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($campaign['settings']['allow_custom_amount'] === true): ?>
                            <input type="hidden" id="ehxdo-min-donation" value="<?php echo $minDonation; ?>">
                            <input type="hidden" id="ehxdo-max-donation" value="<?php echo $maxDonation; ?>">

                            <input type="number"
                                placeholder="Custom amount"
                                class="ehxdo-custom-amount"
                                id="ehxdo-custom-amount">
                            <p class="ehxdo-amount-hint" style="font-size: 12px; color: #666; margin-top: 5px;">
                                Minimum: <?php echo formatAmount($minDonation, $currencySymbol, $position); ?> |
                                Maximum: <?php echo formatAmount($maxDonation, $currencySymbol, $position); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" id="ehxdo_service_fee_percentage" value="<?php echo $generalSettings['service_fee_percentage'] ?? ''; ?>">
                    <input type="hidden" id="ehxdo_service_fee_enabled" value="<?php echo $generalSettings['service_fee'] ?? ''; ?>">

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
                                <span class="ehxdo-amount" id="ehxdo-summary-total-with-fee">
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

                        <input type="hidden" name="campaign_id" value="<?php echo esc_attr($campaign['id']); ?>">
                        <input type="hidden" name="net_amount" id="ehxdo-net_amount" value="<?php echo $default_amount; ?>">
                        <div class="ehxdo-form-row">
                            <div class="ehxdo-form-group">
                                <label class="ehxdo-label">First Name <span style="color: #e71111;">*</span></label>
                                <input type="text" name="first_name" class="ehxdo-input">
                            </div>
                            <div class="ehxdo-form-group">
                                <label class="ehxdo-label">Last Name <span style="color: #e71111;">*</span></label>
                                <input type="text" name="last_name" class="ehxdo-input">
                            </div>
                        </div>


                        <div class="ehxdo-form-group">
                            <label class="ehxdo-label">Email Address <span style="color: #e71111;">*</span></label>
                            <input type="email" name="email" class="ehxdo-input">
                        </div>
                        <div class="ehxdo-form-group">
                            <label class="ehxdo-label">Phone Number </label>
                            <input type="tel" name="phone" class="ehxdo-input">
                        </div>

                        <div class="ehxdo-form-group">
                            <label class="ehxdo-label">
                                Add a Note (Optional)
                            </label>
                            <textarea
                                name="donation_note"
                                class="ehxdo-input ehxdo-textarea"
                                rows="10"
                                maxlength="500"
                                placeholder="Say something about your donation..."
                                style="resize: vertical; min-height: 80px; font-family: inherit;"></textarea>
                            <div style="text-align: right; font-size: 0.75rem; color: #999; margin-top: 5px;">
                                <span id="note-char-count">0</span>/500 characters
                            </div>
                        </div>

                        <!-- <div class="ehxdo-gift-aid">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" name="gift_aid" id="gift_aid_checkbox" value="1" style="cursor: pointer;">
                                <span style="font-size: 0.875rem;">Gift Aid</span>
                            </label>
                        </div> -->

                        <!-- Gift Aid Address Fields (Initially Hidden) -->
                        <!-- <div id="gift_aid_fields" style="display: none;">
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
                        </div> -->


                        <div class="ehxdo-anonymous-option" style="padding-top: 15px;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" name="anonymous_donation" id="anonymous_checkbox" value="1" style="cursor: pointer;">
                                <span style="font-size: 0.875rem;">Make this donation anonymous</span>
                            </label>
                            <p style="font-size: 0.75rem; color: #666; margin-top: 0.25rem; margin-left: 1.5rem;">
                                Your donation will be recorded, but your name won't be displayed publicly.
                            </p>
                        </div>

                        <div class="ehxdo-summary" style="padding-top: 20px;">
                            <div class="ehxdo-summary-row">
                                <span>Final Payable Total Amount :</span>
                                <span class="ehxdo-amount" id="ehxdo-final-summary-amount">
                                    <?php echo formatAmount($default_amount, $currencySymbol, $position); ?>
                                </span>
                            </div>
                            <!-- <div class="ehxdo-summary-row ehxdo-highlight">
                                <span>Your Contribution with Gift Aid:</span>
                                <span class="ehxdo-amount" id="ehxdo-summary-total">
                                    <?php echo formatAmount($default_amount, $currencySymbol, $position); ?>
                                </span>
                            </div> -->
                        </div>
                        <div class="ehxdo_button_wrapper">

                            <div class="ehxdo-section-nav">
                                <button type="button" class="ehxdo-nav-btn ehxdo-prev">Previous</button>
                                <button type="submit" class="ehxdo-nav-btn ehxdo-submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    //Gift Aid Toggle-===========================================
    // const giftAidCheckbox = document.getElementById('gift_aid_checkbox');
    // const giftAidFields = document.getElementById('gift_aid_fields');

    // giftAidCheckbox.addEventListener('change', function() {
    //     if (this.checked) {
    //         giftAidFields.style.display = 'block';
    //     } else {
    //         giftAidFields.style.display = 'none';
    //     }
    // });

    //=====================note word count=========================
    // document.addEventListener('DOMContentLoaded', function() {
    //     const noteTextarea = document.querySelector('textarea[name="donation_note"]');
    //     const charCount = document.getElementById('note-char-count');
    //     if (noteTextarea && charCount) {
    //         noteTextarea.addEventListener('input', function() {
    //             const length = this.value.length;
    //             charCount.textContent = length;
    //             if (length > 450) {
    //                 charCount.style.color = '#dc3545';
    //             } else if (length > 400) {
    //                 charCount.style.color = '#ffc107';
    //             } else {
    //                 charCount.style.color = '#999';
    //             }
    //         });
    //     }
    // });

    // Custom Amount Validation and Restriction
    document.addEventListener('DOMContentLoaded', function() {
        // Get all necessary elements
        const customAmountInput = document.getElementById('ehxdo-custom-amount');
        const minDonationInput = document.getElementById('ehxdo-min-donation');
        const maxDonationInput = document.getElementById('ehxdo-max-donation');
        const errorMsg = document.querySelector('.ehxdo-error-msg');
        const donateBtn = document.getElementById('ehxdo-donate-btn');
        const selectedAmountInput = document.getElementById('ehxdo-selected-amount');
        const currencySymbol = document.querySelector('input[name="currency_symbol"]')?.value || '$';
        const currencyPosition = document.querySelector('input[name="currency_position"]')?.value || 'Before';
        const amountBtns = document.querySelectorAll('.ehxdo-amount-btn');

        // Parse min and max values
        const minDonation = minDonationInput ? parseFloat(minDonationInput.value) : 1;
        const maxDonation = maxDonationInput ? parseFloat(maxDonationInput.value) : 999999;

        // Clear default selection on page load
        selectedAmountInput.value = '';
        amountBtns.forEach(btn => btn.classList.remove('ehxdo-selected'));

        // Update displays to show placeholder or minimum
        const donateAmountDisplay = document.getElementById('donate-amount-display');
        if (donateAmountDisplay) {
            donateAmountDisplay.textContent = formatAmount(0);
        }

        // Helper function to format amount
        function formatAmount(amount) {
            const formatted = parseFloat(amount).toFixed(2);
            return currencyPosition === 'Before' ? currencySymbol + formatted : formatted + currencySymbol;
        }

        // Helper function to show error
        function showError(message) {
            if (errorMsg) {
                errorMsg.textContent = message;
                errorMsg.style.display = 'block';
                if (customAmountInput) {
                    customAmountInput.style.borderColor = '#e71111';
                }
            }
        }

        // Helper function to hide error
        function hideError() {
            if (errorMsg) {
                errorMsg.style.display = 'none';
            }
            if (customAmountInput) {
                customAmountInput.style.borderColor = '';
            }
        }

        // Validate amount against min/max
        function validateAmount(value) {
            const amount = parseFloat(value);

            if (isNaN(amount) || amount <= 0 || value === '') {
                return {
                    valid: false,
                    message: 'Please select or enter a donation amount.'
                };
            }

            if (amount < minDonation) {
                return {
                    valid: false,
                    message: `Minimum donation amount is ${formatAmount(minDonation)}`
                };
            }

            if (amount > maxDonation) {
                return {
                    valid: false,
                    message: `Maximum donation amount is ${formatAmount(maxDonation)}`
                };
            }

            return {
                valid: true
            };
        }

        // Update selected amount and display
        function updateSelectedAmount(amount) {
            if (selectedAmountInput) {
                selectedAmountInput.value = amount;
            }

            // Update donate button display
            const donateAmountDisplay = document.getElementById('donate-amount-display');
            if (donateAmountDisplay) {
                donateAmountDisplay.textContent = formatAmount(amount);
            }

            // Update summary amounts
            const summaryAmount = document.getElementById('ehxdo-summary-amount');
            const finalSummaryAmount = document.getElementById('ehxdo-final-summary-amount');

            if (summaryAmount) {
                summaryAmount.textContent = formatAmount(amount);
            }
            if (finalSummaryAmount) {
                finalSummaryAmount.textContent = formatAmount(amount);
            }

            // Update with service fee if enabled
            const serviceFeeEnabled = document.getElementById('ehxdo_service_fee_enabled')?.value;
            const serviceFeePercent = parseFloat(document.getElementById('ehxdo_service_fee_percentage')?.value || 0);

            if (serviceFeeEnabled && serviceFeePercent > 0) {
                const totalWithFee = amount + (amount * serviceFeePercent / 100);
                const summaryTotalWithFee = document.getElementById('ehxdo-summary-total-with-fee');
                if (summaryTotalWithFee) {
                    summaryTotalWithFee.textContent = formatAmount(totalWithFee);
                }
            }
        }

        // Handle predefined amount buttons
        amountBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove selected class from all buttons
                amountBtns.forEach(b => b.classList.remove('ehxdo-selected'));

                // Add selected class to clicked button
                this.classList.add('ehxdo-selected');

                // Clear custom amount input
                if (customAmountInput) {
                    customAmountInput.value = '';
                }

                // Get and update amount
                const amount = parseFloat(this.getAttribute('data-amount'));
                updateSelectedAmount(amount);
                hideError();
            });
        });

        // Handle custom amount input
        if (customAmountInput) {
            customAmountInput.addEventListener('input', function() {
                const value = this.value.trim();

                // Clear predefined button selection
                amountBtns.forEach(btn => btn.classList.remove('ehxdo-selected'));

                // Hide error initially
                hideError();

                if (value === '') {
                    selectedAmountInput.value = '';
                    return;
                }

                let amount = parseFloat(value);

                if (isNaN(amount)) {
                    showError('Please enter a valid number.');
                    return;
                }

                // Enforce minimum
                if (amount < minDonation) {
                    this.value = minDonation;
                    amount = minDonation;
                    showError(`Minimum donation amount is ${formatAmount(minDonation)}`);
                    setTimeout(hideError, 2500);
                }
                // Enforce maximum
                else if (amount > maxDonation) {
                    this.value = maxDonation;
                    amount = maxDonation;
                    showError(`Maximum donation amount is ${formatAmount(maxDonation)}`);
                    setTimeout(hideError, 2500);
                }

                // Update selected amount
                updateSelectedAmount(amount);
            });

            // Validate on blur
            customAmountInput.addEventListener('blur', function() {
                const value = this.value.trim();

                if (value === '') {
                    return;
                }

                let amount = parseFloat(value);

                if (!isNaN(amount)) {
                    if (amount < minDonation) {
                        this.value = minDonation;
                        updateSelectedAmount(minDonation);
                    } else if (amount > maxDonation) {
                        this.value = maxDonation;
                        updateSelectedAmount(maxDonation);
                    }
                }
            });
        }

        // Validate on donate button click
        if (donateBtn) {
            donateBtn.addEventListener('click', function(e) {
                const selectedAmount = selectedAmountInput?.value || '';

                const validation = validateAmount(selectedAmount);

                if (!validation.valid) {
                    e.preventDefault();
                    e.stopPropagation();
                    showError(validation.message);

                    // Scroll to error
                    if (errorMsg) {
                        errorMsg.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }

                    return false;
                }

                // Clear error and proceed to next section
                hideError();

                // Show section 2, hide section 1
                const section1 = document.getElementById('ehxdo-section-1');
                const section2 = document.getElementById('ehxdo-section-2');

                if (section1 && section2) {
                    section1.classList.add('ehxdo-hidden');
                    section2.classList.remove('ehxdo-hidden');

                    // Scroll to top of section 2
                    section2.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        }

        // Handle previous button
        const prevBtn = document.querySelector('.ehxdo-prev');
        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                const section1 = document.getElementById('ehxdo-section-1');
                const section2 = document.getElementById('ehxdo-section-2');

                if (section1 && section2) {
                    section2.classList.add('ehxdo-hidden');
                    section1.classList.remove('ehxdo-hidden');

                    // Scroll to top of section 1
                    section1.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        }

        // Gift Aid Toggle
        const giftAidCheckbox = document.getElementById('gift_aid_checkbox');
        const giftAidFields = document.getElementById('gift_aid_fields');

        if (giftAidCheckbox && giftAidFields) {
            giftAidCheckbox.addEventListener('change', function() {
                giftAidFields.style.display = this.checked ? 'block' : 'none';
            });
        }

        // Note character count
        const noteTextarea = document.querySelector('textarea[name="donation_note"]');
        const charCount = document.getElementById('note-char-count');

        if (noteTextarea && charCount) {
            noteTextarea.addEventListener('input', function() {
                const length = this.value.length;
                charCount.textContent = length;

                if (length > 450) {
                    charCount.style.color = '#dc3545';
                } else if (length > 400) {
                    charCount.style.color = '#ffc107';
                } else {
                    charCount.style.color = '#999';
                }
            });
        }
    });
</script>