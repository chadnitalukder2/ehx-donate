<?php
use EHXDonate\Helpers\Currency;

$primaryBtnColor = $colorSettings['primary_btn'] ?? '#079455';
$primaryBtnTextColor = $colorSettings['primary_btn_text'] ?? '#ececec';
$fontFamily = $colorSettings['fontFamily'] ?? 'Inter Tight, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif';
// Format donation type
$donation_type_display = ucwords(str_replace('-', ' ', $donation['donation_type']));

// Calculate gift aid amount if applicable
$gift_aid_amount = $donation['gift_aid'] == '1' ? number_format(floatval($donation['total_payment']) * 0.25, 2) : '0.00';

// Format date
$donation_date = date('F d, Y', strtotime($donation['created_at']));

// Prepare share URLs
$share_text = urlencode("I just donated to " . $campaign['title'] . "! Join me in making a difference.");
$share_url = urlencode($campaign['post_url']);

$currencySymbols = Currency::getCurrencySymbol('');
$currency = $general_settings['currency'] ?? 'GBP';
$currencySymbol = $currencySymbols[$currency] ?? '£';
$position = $general_settings['currency_position'] ?? 'Before';


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

<div class="ehxdo_reciept_container" id="receiptContent" data-donation-id="<?php echo $donation['id']; ?>">
    <!-- PDF Header (only visible in PDF) -->
    <div class="ehxdo_reciept_pdf_header">
        <div class="ehxdo_reciept_icon">
            <div class="ehxdo_reciept_checkmark"></div>
        </div>
        <div class="ehxdo_reciept_header_right">
            <div class="ehxdo_reciept_pdf_logo"><?php echo $general_settings['company_name'] ?? 'EHx Donate'; ?></div>
            <div class="ehxdo_reciept_pdf_subtitle">Tax-Deductible Donation Receipt</div>
        </div>
    </div>

    <h1 class="ehxdo_reciept_title">Thank You for Your Donation!</h1>
    <p class="ehxdo_reciept_subtitle">Your generosity makes a real difference. We're grateful for your support.</p>

    <div class="ehxdo_reciept_details">
        <div class="ehxdo_reciept_row">
            <span class="ehxdo_reciept_label">Donation Amount:</span>
            <span class="ehxdo_reciept_value">
                 <?php echo formatAmount($donation['total_payment'], $currencySymbol, $position); ?>
               
                </span>
        </div>
         <div class="ehxdo_reciept_row">
            <span class="ehxdo_reciept_label">Service Fee:</span>
            <span class="ehxdo_reciept_value"> 
                 <?php echo formatAmount($donation['charge'], $currencySymbol, $position); ?>
            </span>
        </div>
        <div class="ehxdo_reciept_row">
            <span class="ehxdo_reciept_label">Campaign:</span>
            <span class="ehxdo_reciept_value"><?php echo htmlspecialchars($campaign['title']); ?></span>
        </div>
        <div class="ehxdo_reciept_row">
            <span class="ehxdo_reciept_label">Donation Type:</span>
            <span class="ehxdo_reciept_value"><?php echo $donation_type_display; ?></span>
        </div>
        <div class="ehxdo_reciept_row">
            <span class="ehxdo_reciept_label">Transaction ID:</span>
            <span class="ehxdo_reciept_value"><?php echo htmlspecialchars($donation['donation_hash']); ?></span>
        </div>
        <div class="ehxdo_reciept_row">
            <span class="ehxdo_reciept_label">Date:</span>
            <span class="ehxdo_reciept_value"><?php echo $donation_date; ?></span>
        </div>
        <div class="ehxdo_reciept_row">
            <span class="ehxdo_reciept_label">Donor Name:</span>
            <span class="ehxdo_reciept_value"><?php echo htmlspecialchars($donation['donor_name']); ?></span>
        </div>
        <div class="ehxdo_reciept_row">
            <span class="ehxdo_reciept_label">Email:</span>
            <span class="ehxdo_reciept_value"><?php echo htmlspecialchars($donation['donor_email']); ?></span>
        </div>
    </div>

    <?php if ($donation['gift_aid'] == '1'): ?>
        <div class="ehxdo_reciept_gift_aid">
            <svg class="ehxdo_reciept_gift_icon" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 6h-2.18c.11-.31.18-.65.18-1a2.996 2.996 0 00-5.5-1.65l-.5.67-.5-.68C10.96 2.54 10.05 2 9 2 7.34 2 6 3.34 6 5c0 .35.07.69.18 1H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-5-2c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zM9 4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm11 15H4v-2h16v2zm0-5H4V8h5.08L7 10.83 8.62 12 11 8.76l1-1.36 1 1.36L15.38 12 17 10.83 14.92 8H20v6z" />
            </svg>
            Gift Aid claimed: +$<?php echo $gift_aid_amount; ?>
        </div>
    <?php endif; ?>

    <h2 class="ehxdo_reciept_section_title">What happens next?</h2>

    <div class="ehxdo_reciept_step">
        <div class="ehxdo_reciept_step_icon"></div>
        <p class="ehxdo_reciept_step_text">A confirmation email has been sent to your inbox with your donation receipt</p>
    </div>

    <div class="ehxdo_reciept_step">
        <div class="ehxdo_reciept_step_icon"></div>
        <p class="ehxdo_reciept_step_text">You'll receive regular updates about how your donation is making an impact</p>
    </div>

    <div class="ehxdo_reciept_step">
        <div class="ehxdo_reciept_step_icon"></div>
        <p class="ehxdo_reciept_step_text">Your donation is tax-deductible, and we'll process your Gift Aid claim</p>
    </div>

    <!-- PDF Footer (only visible in PDF) -->
    <div class="ehxdo_reciept_pdf_footer">
        <p>This is an official donation receipt for tax purposes.</p>
        <p>Organization Tax ID: XX-XXXXXXX | Registered Charity Number: XXXXXX</p>
        <p>For questions, contact us at support@yourorg.com</p>
    </div>

    <div class="ehxdo_reciept_buttons">
        <a href="<?php echo htmlspecialchars($campaign['post_url']); ?>" class="ehxdo_reciept_btn ehxdo_reciept_btn_primary">Donate Again</a>
        <button id="ehxdo_download-receipt-btn" class="ehxdo_reciept_btn ehxdo_reciept_btn_secondary">Download Receipt</button>
    </div>

    <div class="ehxdo_reciept_divider"></div>

    <p class="ehxdo_reciept_social_title">Share the impact you're making</p>
    <div class="ehxdo_reciept_social_buttons">
        <a href="https://twitter.com/intent/tweet?text=<?php echo $share_text; ?>&url=<?php echo $share_url; ?>"
            target="_blank"
            class="ehxdo_reciept_social_btn">Share on Twitter</a>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>"
            target="_blank"
            class="ehxdo_reciept_social_btn">Share on Facebook</a>
        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $share_url; ?>"
            target="_blank"
            class="ehxdo_reciept_social_btn">Share on LinkedIn</a>
    </div>
</div>