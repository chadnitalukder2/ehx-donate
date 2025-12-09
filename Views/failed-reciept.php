<?php
use EHXDonate\Helpers\Currency;

$primaryBtnColor = $colorSettings['primary_btn'] ?? '#079455';
$primaryBtnTextColor = $colorSettings['primary_btn_text'] ?? '#ececec';
$fontFamily = $colorSettings['fontFamily'] ?? 'Inter Tight, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif';
// Format donation type
$donation_type_display = ucwords(str_replace('-', ' ', $donation['donation_type']));

// Format date
$donation_date = date('F d, Y', strtotime($donation['created_at']));


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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Failed</title>
    <style>
        :root {
            --ehxdo-primary-btn: <?php echo $primaryBtnColor; ?>;
            --ehxdo-primary-btn-text: <?php echo $primaryBtnTextColor; ?>;
            --ehxdo-font-family: <?php echo $fontFamily; ?>;
        }

        .ehxdo_failed_pdf_header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
            font-family: var(--ehxdo-font-family);
        }

        .ehxdo_failed_pdf_logo {
            font-size: 24px;
            font-weight: bold;
            color: #3d444c;
            font-family: var(--ehxdo-font-family);
        }

        .ehxdo_failed_pdf_subtitle {
            color: #3d444c;
            font-size: 16px;
        }

        .ehxdo_failed_container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            max-width: 620px;
            width: 100%;
            padding: 25px;
            margin: 20px auto;
        }

        .ehxdo_failed_icon {
            width: 60px;
            height: 60px;
            background: #fee2e2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 0px;
        }

        .ehxdo_failed_cross {
            width: 28px;
            height: 28px;
            position: relative;
        }

        .ehxdo_failed_cross::before,
        .ehxdo_failed_cross::after {
            content: '';
            position: absolute;
            left: 50%;
            top: 50%;
            width: 3px;
            height: 20px;
            background: #ef4444;
            border-radius: 2px;
        }

        .ehxdo_failed_cross::before {
            transform: translate(-50%, -50%) rotate(45deg);
        }

        .ehxdo_failed_cross::after {
            transform: translate(-50%, -50%) rotate(-45deg);
        }

        .ehxdo_failed_title {
            text-align: center;
            font-size: 20px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .ehxdo_failed_subtitle {
            text-align: center;
            font-size: 14px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .ehxdo_failed_alert {
            font-family: var(--ehxdo-font-family);
            background: #fef2f2;
            border-left: 4px solid #ef4444;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 25px;
        }

        .ehxdo_failed_alert_title {
            font-size: 14px;
            font-weight: 600;
            color: #991b1b;
            margin-bottom: 5px;
        }

        .ehxdo_failed_alert_text {
            font-size: 13px;
            color: #7f1d1d;
            line-height: 1.5;
            margin: 0px;
        }

        .ehxdo_failed_details {
            background: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            font-family: var(--ehxdo-font-family);
        }

        .ehxdo_failed_row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .ehxdo_failed_row:last-child {
            margin-bottom: 0;
        }

        .ehxdo_failed_label {
            font-size: 13px;
            color: #666;
            font-weight: 500;
        }

        .ehxdo_failed_value {
            font-size: 14px;
            color: #333;
            font-weight: 500;
            text-align: right;
        }

        .ehxdo_failed_section_title {
            font-size: 15px;
            font-weight: 600;
            color: #333;
            font-family: var(--ehxdo-font-family);
            margin-bottom: 15px;
        }

        .ehxdo_failed_reason_list {
            list-style: none;
            padding: 0;
            margin-bottom: 25px;
        }

        .ehxdo_failed_reason_item {
            display: flex;
            gap: 12px;
            margin-bottom: 12px;
            padding: 12px;
            background: #fef9f5;
            border-radius: 6px;
            align-items: center;
            border-left: 3px solid #f59e0b;
            font-family: var(--ehxdo-font-family);
        }

        .ehxdo_failed_reason_icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .ehxdo_failed_reason_icon svg {
            width: 100%;
            height: 100%;
            fill: #f59e0b;
        }

        .ehxdo_failed_reason_text {
            font-size: 13px;
            color: #78350f;
            line-height: 1.5;
            margin: 5px 0px;

        }

        .ehxdo_failed_buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .ehxdo_failed_btn {
            flex: 1;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border: none;
            font-family: var(--ehxdo-font-family);
        }

        .ehxdo_failed_btn_primary {
            background: #ef4444;
            color: white; 
        }

        .ehxdo_failed_btn_primary:hover {
            background: #dc2626;
        }

        .ehxdo_failed_btn_secondary {
            background: white;
            color: #666;
            border: 1px solid #e5e7eb;
        }

        .ehxdo_failed_btn_secondary:hover {
            background: #f9fafb;
        }

        .ehxdo_failed_divider {
            height: 1px;
            background: #e5e7eb;
            margin: 25px 0;
        }

        .ehxdo_failed_help {
            background: #f0f9ff;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }

        .ehxdo_failed_help_title {
            font-size: 14px;
            font-weight: 600;
            color: #0369a1;
            margin-bottom: 8px;
        }

        .ehxdo_failed_help_text {
            font-size: 13px;
            color: #075985;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .ehxdo_failed_help_contact {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .ehxdo_failed_contact_item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #0369a1;
            text-decoration: none;
        }

        .ehxdo_failed_contact_item:hover {
            color: #075985;
        }

        .ehxdo_failed_contact_icon {
            width: 16px;
            height: 16px;
        }

        @media (max-width: 600px) {
            .ehxdo_failed_container {
                padding: 30px 20px;
            }

            .ehxdo_failed_buttons {
                flex-direction: column;
            }

            .ehxdo_failed_help_contact {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="ehxdo_failed_container">
        <div class="ehxdo_failed_pdf_header">
            <div class="ehxdo_failed_icon">
                <div class="ehxdo_failed_cross"></div>
            </div>
            <div class="ehxdo_failed_header_right">
                <div class="ehxdo_failed_pdf_logo"><?php echo $general_settings['company_name'] ?? 'EHx Donate'; ?></div>
                <div class="ehxdo_failed_pdf_subtitle">Donation Failed</div>
            </div>
        </div>

        <div class="ehxdo_failed_alert">
            <div class="ehxdo_failed_alert_title">Transaction Declined</div>
            <p class="ehxdo_failed_alert_text">
                <?php
                if (!empty($donation['failure_reason'])) {
                    echo htmlspecialchars($donation['failure_reason']);
                } else {
                    echo "Your payment could not be processed. Please check your payment details and try again.";
                }
                ?>
            </p>
        </div>

        <div class="ehxdo_failed_details">
            <div class="ehxdo_failed_row">
                <span class="ehxdo_failed_label">Attempted Amount:</span>
                <span class="ehxdo_failed_value">
                     <?php echo formatAmount($donation['net_amount'], $currencySymbol, $position); ?>
                </span>
            </div>
            <div class="ehxdo_failed_row">
                <span class="ehxdo_failed_label">Campaign:</span>
                <span class="ehxdo_failed_value"><?php echo htmlspecialchars($campaign['title']); ?></span>
            </div>
            <div class="ehxdo_failed_row">
                <span class="ehxdo_failed_label">Transaction ID:</span>
                <span class="ehxdo_failed_value"><?php echo htmlspecialchars($donation['donation_hash']); ?></span>
            </div>
            <div class="ehxdo_failed_row">
                <span class="ehxdo_failed_label">Date:</span>
                <span class="ehxdo_failed_value"><?php echo $donation_date; ?></span>
            </div>
            <div class="ehxdo_failed_row">
                <span class="ehxdo_failed_label">Payment Method:</span>
                <span class="ehxdo_failed_value"><?php echo htmlspecialchars($donation['payment_method'] ?? 'N/A'); ?></span>
            </div>
        </div>

        <h2 class="ehxdo_failed_section_title">Common Reasons for Payment Failure</h2>

        <ul class="ehxdo_failed_reason_list">
            <li class="ehxdo_failed_reason_item">
                <div class="ehxdo_failed_reason_icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                    </svg>
                </div>
                <p class="ehxdo_failed_reason_text"><strong>Insufficient Funds:</strong> Your account doesn't have enough balance to complete this transaction.</p>
            </li>
            <li class="ehxdo_failed_reason_item">
                <div class="ehxdo_failed_reason_icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                    </svg>
                </div>
                <p class="ehxdo_failed_reason_text"><strong>Incorrect Card Details:</strong> The card number, expiration date, or CVV might be entered incorrectly.</p>
            </li>
            <li class="ehxdo_failed_reason_item">
                <div class="ehxdo_failed_reason_icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                    </svg>
                </div>
                <p class="ehxdo_failed_reason_text"><strong>Card Expired or Blocked:</strong> Your card may have expired or been blocked by your bank for security reasons.</p>
            </li>
            <li class="ehxdo_failed_reason_item">
                <div class="ehxdo_failed_reason_icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                    </svg>
                </div>
                <p class="ehxdo_failed_reason_text"><strong>Bank Declined:</strong> Your bank has declined the transaction. Contact your bank for more details.</p>
            </li>
        </ul>

        <div class="ehxdo_failed_buttons">
            <a href="<?php echo htmlspecialchars($campaign['post_url']); ?>" class="ehxdo_failed_btn ehxdo_failed_btn_primary">Try Again</a>
            <a href="/" class="ehxdo_failed_btn ehxdo_failed_btn_secondary">Back to Home</a>
        </div>
    </div>
</body>

</html>