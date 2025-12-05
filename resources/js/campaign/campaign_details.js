jQuery(document).ready(function ($) {

    // Get currency settings
    const currencySymbol = $('input[name="currency_symbol"]').val() || '£';
    const currencyPosition = $('input[name="currency_position"]').val() || 'Before';

    // Get service fee settings
    const serviceFeeEnabled = $('#ehxdo_service_fee_enabled').val() === '1' || $('#ehxdo_service_fee_enabled').val() === 'true';
    const serviceFeePercentage = parseFloat($('#ehxdo_service_fee_percentage').val()) || 0;

    // Get reCAPTCHA settings
    const recaptchaEnabled = $('input[name="recaptcha_enabled"]').length > 0;
    const recaptchaMode = $('input[name="recaptcha_mode"]').val() || '';
    const recaptchaSiteKey = $('#recaptcha-site-key').val() || '';

    // Helper function to format currency
    function formatCurrency(amount) {
        const formatted = parseFloat(amount).toFixed(2);
        if (currencyPosition === 'Before') {
            return currencySymbol + formatted;
        } else {
            return formatted + currencySymbol;
        }
    }

    // Helper function to calculate service fee
    function calculateServiceFee(amount) {
        if (!serviceFeeEnabled || serviceFeePercentage === 0) {
            return 0;
        }
        return (amount * serviceFeePercentage) / 100;
    }

    // Helper function to calculate total with fee
    function calculateTotalWithFee(amount) {
        const serviceFee = calculateServiceFee(amount);
        return amount + serviceFee;
    }

    // Section Navigation
    const $sections = $('.ehxdo-donation-card');
    let currentSection = 0;

    function showSection(index) {
        $sections.each(function (i) {
            $(this).toggleClass('ehxdo-hidden', i !== index);
        });

        $('.ehxdo-prev').prop('disabled', index === 0);
        $('.ehxdo-next').css('display', index === $sections.length - 1 ? 'none' : 'inline-block');
    }

    $('.ehxdo-next').on('click', function () {
        if (currentSection < $sections.length - 1) {
            currentSection++;
            showSection(currentSection);
        }
    });

    $('.ehxdo-prev').on('click', function () {
        if (currentSection > 0) {
            currentSection--;
            showSection(currentSection);
        }
    });

    // Amount selection
    const $amountInput = $('#ehxdo-selected-amount');
    const $donateBtn = $('#ehxdo-donate-btn');
    const $customAmountInput = $('#ehxdo-custom-amount');
    const $summaryAmount = $('#ehxdo-summary-amount');
    const $summaryTotalWithFee = $('#ehxdo-summary-total-with-fee');
    const $finalSummaryAmount = $('#ehxdo-final-summary-amount');
    const $netAmount = $('#ehxdo-net_amount');

    function updateAmount(amount) {
        const donationAmount = parseFloat(amount);
        const totalWithFee = calculateTotalWithFee(donationAmount);

        const formattedDonationAmount = formatCurrency(donationAmount);
        const formattedTotalWithFee = formatCurrency(totalWithFee);

        $amountInput.val(donationAmount);

        const displayAmount = (serviceFeeEnabled && serviceFeePercentage > 0) ? formattedTotalWithFee : formattedDonationAmount;
        const numericAmount = (serviceFeeEnabled && serviceFeePercentage > 0) ? totalWithFee : donationAmount;

        $donateBtn.html(`
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.3335 3.33331H2.66683C1.93045 3.33331 1.3335 3.93027 1.3335 4.66665V11.3333C1.3335 12.0697 1.93045 12.6666 2.66683 12.6666H13.3335C14.0699 12.6666 14.6668 12.0697 14.6668 11.3333V4.66665C14.6668 3.93027 14.0699 3.33331 13.3335 3.33331Z" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M1.3335 6.66669H14.6668" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            Donate ${displayAmount}
        `);

        $summaryAmount.text(formattedDonationAmount);

        if (serviceFeeEnabled && serviceFeePercentage > 0) {
            $summaryTotalWithFee.text(formattedTotalWithFee);
        } else {
            $summaryTotalWithFee.text(formattedDonationAmount);
        }

        if ($finalSummaryAmount.length) {
            $finalSummaryAmount.text(displayAmount);
        }

        if ($netAmount.length) {
            $netAmount.val(numericAmount.toFixed(2));
        }
    }

    $('.ehxdo-amount-btn').on('click', function () {
        $('.ehxdo-amount-btn').removeClass('ehxdo-selected');
        $(this).addClass('ehxdo-selected');
        $customAmountInput.val('');

        const amount = $(this).data('amount');
        updateAmount(amount);
    });

    $customAmountInput.on('input', function () {
        const customAmount = parseFloat($(this).val()) || 0;
        if (customAmount > 0) {
            $('.ehxdo-amount-btn').removeClass('ehxdo-selected');
            updateAmount(customAmount);
        }
    });

    $donateBtn.on('click', function () {
        const selectedAmount = parseFloat($amountInput.val()) || 0;
        if (selectedAmount <= 0) {
            $customAmountInput.css('border', '1.5px solid red');
            $('.ehxdo-error-msg').show();

            setTimeout(function () {
                $customAmountInput.css('border', '');
                $('.ehxdo-error-msg').hide();
            }, 5000);

            return false;
        }

        if (currentSection < $sections.length - 1) {
            currentSection++;
            showSection(currentSection);
        }
    });

    showSection(currentSection);

    function generateDonationHash() {
        const timestamp = Date.now();
        const random = Math.random().toString(36).substring(2, 15);
        const hash = `DON-${timestamp}-${random}`.toUpperCase();
        return hash;
    }

    // ===================reCAPTCHA V2 (Visible) Callbacks=========================
    window.onRecaptchaSuccess = function (token) {
        $('#recaptcha-response').val(token);
        $('.ehxdo-recaptcha-error').hide();
        console.log('reCAPTCHA V2 verified successfully');
    };

    window.onRecaptchaExpired = function () {
        $('#recaptcha-response').val('');
        console.log('reCAPTCHA V2 expired');
    };

    // ====================reCAPTCHA V3 (Invisible) - Execute on form submit========================
    function executeRecaptchaV3() {
        return new Promise((resolve, reject) => {
            if (typeof grecaptcha === 'undefined') {
                reject('reCAPTCHA not loaded');
                return;
            }

            grecaptcha.ready(function () {
                grecaptcha.execute(recaptchaSiteKey, { action: 'donation_submit' })
                    .then(function (token) {
                        $('#recaptcha-response').val(token);
                        console.log('reCAPTCHA V3 token generated');
                        resolve(token);
                    })
                    .catch(function (error) {
                        console.error('reCAPTCHA V3 error:', error);
                        reject(error);
                    });
            });
        });
    }

    // ====================Form Submission Handler========================
    $('#ehxdo-donation-form').on('submit', async function (e) {
        e.preventDefault();

        $('.ehxdo-form-error-msg').remove();

        let errors = [];
        const firstName = $(this).find('input[name="first_name"]')[0];
        const lastName = $(this).find('input[name="last_name"]')[0];
        const email = $(this).find('input[name="email"]')[0];

        $(firstName).css('border', '');
        $(lastName).css('border', '');
        $(email).css('border', '');

        if (!firstName.value.trim()) {
            $(firstName).css('border', '1.5px solid red');
            errors.push('First name is required');
        }
        if (!lastName.value.trim()) {
            $(lastName).css('border', '1.5px solid red');
            errors.push('Last name is required');
        }

        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!email.value.trim() || !emailRegex.test(email.value.trim())) {
            $(email).css('border', '1.5px solid red');
            errors.push('Please enter a valid email address.');
        }

        // reCAPTCHA Validation by Mode
        if (recaptchaEnabled) {
            if (recaptchaMode === 'visible') {
                // V2 Visible - Check if user clicked checkbox
                const recaptchaResponse = $('#recaptcha-response').val();
                if (!recaptchaResponse || recaptchaResponse.trim() === '') {
                    $('.ehxdo-recaptcha-error').show();
                    errors.push('Please complete the reCAPTCHA verification.');

                    $('.ehxdo-recaptcha-container')[0]?.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            } else if (recaptchaMode === 'invisible') {
                // V3 Invisible - Generate token automatically
                try {
                    const $submitBtn = $(this).find('.ehxdo-submit');
                    $submitBtn.prop('disabled', true).text('Verifying...');

                    await executeRecaptchaV3();

                    $submitBtn.prop('disabled', false).text('Submit');
                } catch (error) {
                    console.error('reCAPTCHA V3 failed:', error);
                    errors.push('reCAPTCHA verification failed. Please try again.');
                }
            }
        }

        // If there are errors, show and return
        if (errors.length > 0) {
            const errorMsg = `<p class="ehxdo-form-error-msg" style="color: red; font-size: 14px; margin: 15px 0; padding: 10px; background-color: #ffebee; border-radius: 4px;">
                <strong>Error:</strong> Please complete all required fields
            </p>`;

            $('.ehxdo-section-nav').before(errorMsg);

            setTimeout(function () {
                $(firstName).css('border', '');
                $(lastName).css('border', '');
                $(email).css('border', '');
                $('.ehxdo-form-error-msg').remove();
            }, 5000);

            return false;
        }

        // Show loading state
        const $submitBtn = $(this).find('.ehxdo-submit');
        const originalText = $submitBtn.text();

        $submitBtn.prop('disabled', true)
            .html('Submitting...')
            .css('opacity', '0.7');

        const donationAmount = parseFloat($('#ehxdo-selected-amount').val()) || 0;
        const serviceFee = calculateServiceFee(donationAmount);
        const totalAmount = parseFloat($('#ehxdo-net_amount').val()) || donationAmount;

        const data = {
            first_name: $('input[name="first_name"]').val() || '',
            last_name: $('input[name="last_name"]').val() || '',
            email: $('input[name="email"]').val() || '',
            phone: $('input[name="phone"]').val() || '',
            donation_hash: generateDonationHash(),
            campaign_id: $('input[name="campaign_id"]').val() || '',
            donation_note: $('textarea[name="donation_note"]').val() || '',
            anonymous_donation: $('input[name="anonymous_donation"]').is(':checked') ? 1 : 0,
            gift_aid: $('input[name="gift_aid"]').is(':checked') ? 1 : 0,

            amount: donationAmount.toFixed(2),
            service_fee: serviceFee.toFixed(2),
            net_amount: totalAmount.toFixed(2),

            currency: $('input[name="currency"]').val() || '',
            donation_type: $('#donation_type').val() || 'one-time',

            address_line_1: $('input[name="address_line_1"]').val() || '',
            address_line_2: $('input[name="address_line_2"]').val() || '',
            city: $('input[name="city"]').val() || '',
            state: $('input[name="state"]').val() || '',
            country: $('input[name="country"]').val() || '',
            post_code: $('input[name="post_code"]').val() || '',

            payment_mode: $('input[name="payment_mode"]').val() || 'live',
        };

        // Add reCAPTCHA data
        if (recaptchaEnabled) {
            data.recaptcha_response = $('#recaptcha-response').val();
            data.recaptcha_mode = recaptchaMode;
        }

        console.log('Submitting donation with data:', data);

        $.ajax({
            url: `${window.EHXDonate.restUrl}donationSubmission`,
            type: 'POST',
            headers: {
                'X-WP-Nonce': window.EHXDonate.restNonce
            },
            data: data,
            success: function (response) {
                if (response.success) {
                    $submitBtn.html('<span style="display: inline-block; margin-right: 8px;">✓</span> Success!');
                    let redirect_url = response?.data?.redirect_url;
                    console.log('Redirecting to:', response);
                    if (redirect_url) {
                        window.location.href = redirect_url;
                    }
                } else {
                    const errorMsg = `<p class="ehxdo-form-error-msg" style="color: red; font-size: 14px; margin: 15px 0; padding: 10px; background-color: #ffebee; border-radius: 4px;">
                        <strong>Error:</strong> ${response.data.message}
                    </p>`;
                    $('.ehxdo-section-nav').before(errorMsg);

                    $submitBtn.prop('disabled', false)
                        .text(originalText)
                        .css('opacity', '1');

                    // Reset reCAPTCHA
                    if (recaptchaEnabled) {
                        if (recaptchaMode === 'visible' && typeof grecaptcha !== 'undefined') {
                            grecaptcha.reset();
                        }
                        $('#recaptcha-response').val('');
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', status, error);

                const errorMsg = `<p class="ehxdo-form-error-msg" style="color: red; font-size: 14px; margin: 15px 0; padding: 10px; background-color: #ffebee; border-radius: 4px;">
                    <strong>Error:</strong> An unexpected error occurred. Please try again later.
                </p>`;
                $('.ehxdo-section-nav').before(errorMsg);

                $submitBtn.prop('disabled', false)
                    .text(originalText)
                    .css('opacity', '1');

                // Reset reCAPTCHA
                if (recaptchaEnabled) {
                    if (recaptchaMode === 'visible' && typeof grecaptcha !== 'undefined') {
                        grecaptcha.reset();
                    }
                    $('#recaptcha-response').val('');
                }
            }
        });
    });

});