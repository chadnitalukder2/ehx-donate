jQuery(document).ready(function ($) {

    // Get currency settings from hidden inputs or data attributes
    const currencySymbol = $('input[name="currency_symbol"]').val() || '£';
    const currencyPosition = $('input[name="currency_position"]').val() || 'Before';

    // Get service fee settings
    const serviceFeeEnabled = $('#ehxdo_service_fee_enabled').val() === '1' || $('#ehxdo_service_fee_enabled').val() === 'true';
    const serviceFeePercentage = parseFloat($('#ehxdo_service_fee_percentage').val()) || 0;

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

        // Update button states
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

        // Update donate button with icon - show total with fee if enabled
        const displayAmount = (serviceFeeEnabled && serviceFeePercentage > 0) ? formattedTotalWithFee : formattedDonationAmount;
        const numericAmount = (serviceFeeEnabled && serviceFeePercentage > 0) ? totalWithFee : donationAmount;

        $donateBtn.html(`
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.3335 3.33331H2.66683C1.93045 3.33331 1.3335 3.93027 1.3335 4.66665V11.3333C1.3335 12.0697 1.93045 12.6666 2.66683 12.6666H13.3335C14.0699 12.6666 14.6668 12.0697 14.6668 11.3333V4.66665C14.6668 3.93027 14.0699 3.33331 13.3335 3.33331Z" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M1.3335 6.66669H14.6668" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            Donate ${displayAmount}
        `);

        // Update summary amounts
        $summaryAmount.text(formattedDonationAmount);

        // Only show fee calculation if service fee is enabled
        if (serviceFeeEnabled && serviceFeePercentage > 0) {
            $summaryTotalWithFee.text(formattedTotalWithFee);
        } else {
            $summaryTotalWithFee.text(formattedDonationAmount);
        }

        // Update final summary amount (same as donate button)
        if ($finalSummaryAmount.length) {
            $finalSummaryAmount.text(displayAmount);
        }

        //update net amount input
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

    // Donate button click with validation
    $donateBtn.on('click', function () {
        const selectedAmount = parseFloat($amountInput.val()) || 0;
        if (selectedAmount <= 0) {
            $customAmountInput.css('border', '1.5px solid red');
            $('.ehxdo-error-msg').show(); // display:block

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

    //TemporaryError message
      function showTemporaryError(message, duration = 3000, color = 'rgb(221 47 47)') {
        const $msg = $('.ehxd-response-message');
        if (message && message.trim() !== '') {
          $msg.text(message).css('color', color).fadeIn();
          setTimeout(() => {
            $msg.fadeOut(400, function () {
              $(this).text('').hide();
            });
          }, duration);
        } else {
          $msg.hide();
        }
      }

    //form submission handling    
    $('#ehxdo-donation-form').on('submit', function (e) {
        e.preventDefault();

        // Remove any existing error messages
        $('.ehxdo-form-error-msg').remove();

        let errors = [];
        const firstName = $(this).find('input[name="first_name"]')[0];
        const lastName = $(this).find('input[name="last_name"]')[0];
        const email = $(this).find('input[name="email"]')[0];

        // Reset borders
        $(firstName).css('border', '');
        $(lastName).css('border', '');
        $(email).css('border', '');

        // Validate fields
        if (!firstName.value.trim()) {
            $(firstName).css('border', '1.5px solid red');
            errors.push('First name is required');
        }
        if (!lastName.value.trim()) {
            $(lastName).css('border', '1.5px solid red');
            errors.push('Last name is required');
        }

        // More robust email regex
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        if (!email.value.trim() || !emailRegex.test(email.value.trim())) {
            $(email).css('border', '1.5px solid red');
            errors.push('Please enter a valid email address.');
        } 

        // If there are errors, show error message and return
        if (errors.length > 0) {
            const errorMsg = `<p class="ehxdo-form-error-msg" style="color: red; font-size: 14px;">
            Please complete all required fields<br>
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
            .html('<span style="display: inline-block; margin-right: 8px;"></span> Submitting...')
            .css('opacity', '0.7');

        const data = [
            'first_name',
            'last_name',
            'email',
            'phone',
            'donation_hash' ,
            'campaign_id',
            'donor_message',
            'anonymous_donation',
            'gift_aid',
            'net_amount'
        ]
        console.log('Submitting donation with data:', $this.data)
        const formData = $(this).serialize();

        $.ajax({
            url: `${window.EHXDonate.restUrl}donationSubmission`,
            type: 'POST',
            headers: {
                'X-WP-Nonce': window.EHXDonate.restNonce
            },
            data: formData,
            success: function (response) {
                if (response.success) {
                    $submitBtn.html('<span style="display: inline-block; margin-right: 8px;">✓</span> Success! Redirecting...');
                    window.location.href = response.data.redirect_url;
                } else {
                    // Show error message
                    const errorMsg = `<p class="ehxdo-form-error-msg" style="color: red; font-size: 14px; margin: 15px 0; padding: 10px; background-color: #ffebee; border-radius: 4px;">
                    <strong>Error:</strong> ${response.data.message}
                </p>`;
                    $('.ehxdo-section-nav').before(errorMsg);

                    // Reset button
                    $submitBtn.prop('disabled', false)
                        .text(originalText)
                        .css('opacity', '1');
                }
            },
            error: function () {
                // Show error message
                const errorMsg = `<p class="ehxdo-form-error-msg" style="color: red; font-size: 14px; margin: 15px 0; padding: 10px; background-color: #ffebee; border-radius: 4px;">
                <strong>Error:</strong> An unexpected error occurred. Please try again later.
            </p>`;
                $('.ehxdo-section-nav').before(errorMsg);

                // Reset button
                $submitBtn.prop('disabled', false)
                    .text(originalText)
                    .css('opacity', '1');
            }
        });
    });

    
});