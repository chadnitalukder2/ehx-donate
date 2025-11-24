jQuery(document).ready(function ($) {

    // Get currency settings from hidden inputs or data attributes
    const currencySymbol = $('input[name="currency_symbol"]').val() || '£';
    const currencyPosition = $('input[name="currency_position"]').val() || 'Before';

    // Helper function to format currency
    function formatCurrency(amount) {
        const formatted = parseFloat(amount).toFixed(2);
        if (currencyPosition === 'Before') {
            return currencySymbol + formatted;
        } else {
            return formatted + currencySymbol;
        }
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
    const $summaryTotal = $('#ehxdo-summary-total');

    function updateAmount(amount) {
        const formattedAmount = formatCurrency(amount);

        $amountInput.val(amount);

        // Update donate button with icon
        $donateBtn.html(`
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.3335 3.33331H2.66683C1.93045 3.33331 1.3335 3.93027 1.3335 4.66665V11.3333C1.3335 12.0697 1.93045 12.6666 2.66683 12.6666H13.3335C14.0699 12.6666 14.6668 12.0697 14.6668 11.3333V4.66665C14.6668 3.93027 14.0699 3.33331 13.3335 3.33331Z" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M1.3335 6.66669H14.6668" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            Donate ${formattedAmount}
        `);

        $summaryAmount.text(formattedAmount);
        $summaryTotal.text(formattedAmount);
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
            $customAmountInput.css('border', '2px solid red');
            $('.ehxdo-error-msg').show(); // display:block
          
            setTimeout(function () {
                  $customAmountInput.css('border', '');
                $('.ehxdo-error-msg').hide(); // display:none
            }, 3000);

            return false;
        }

        if (currentSection < $sections.length - 1) {
            currentSection++;
            showSection(currentSection);
        }
    });

    // Initialize first section
    showSection(currentSection);
});