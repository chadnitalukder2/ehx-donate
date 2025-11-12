jQuery(document).ready(function($) {

    // Section Navigation
    const $sections = $('.ehxdo-donation-card');
    let currentSection = 0;

    function showSection(index) {
        $sections.each(function(i) {
            $(this).toggleClass('ehxdo-hidden', i !== index);
        });

        // Update button states
        $('.ehxdo-prev').prop('disabled', index === 0);
        $('.ehxdo-next').css('display', index === $sections.length - 1 ? 'none' : 'inline-block');
    }

    $('.ehxdo-next').on('click', function() {
        if (currentSection < $sections.length - 1) {
            currentSection++;
            showSection(currentSection);
        }
    });

    $('.ehxdo-prev').on('click', function() {
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
        $amountInput.val(amount);
        $donateBtn.text(`💳 Donate $${amount}`);
        $summaryAmount.text(`£${amount}.00`);
        $summaryTotal.text(`£${amount}.00`);
    }

    $('.ehxdo-amount-btn').on('click', function() {
        $('.ehxdo-amount-btn').removeClass('ehxdo-selected');
        $(this).addClass('ehxdo-selected');
        $customAmountInput.val('');

        const amount = $(this).data('amount');
        updateAmount(amount);
    });

    $customAmountInput.on('input', function() {
        const customAmount = parseFloat($(this).val()) || 0;
        if (customAmount > 0) {
            $('.ehxdo-amount-btn').removeClass('ehxdo-selected');
            updateAmount(customAmount);
        }
    });

    // Donate button click (next section)
    $donateBtn.on('click', function() {
        if (currentSection < $sections.length - 1) {
            currentSection++;
            showSection(currentSection);
        }
    });

    // Initialize first section
    showSection(currentSection);
});
