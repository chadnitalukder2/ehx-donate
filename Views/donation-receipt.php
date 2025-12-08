<?php
use EHXDonate\Helpers\Currency;

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
    <title>Donation Receipt</title>
</head>

<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; color: #1a1a1a;">
    <div style="max-width: 650px; margin: 0 auto; background-color: #ffffff;">

        <!-- Header -->
        <div style="padding: 40px 40px 30px 40px; border-bottom: 1px solid #e5e5e5;">
            <div style="display: table; width: 100%;">
                <div style="display: table-cell; vertical-align: top; padding-left: 16px;">
                    <h1 style="margin: 0 0 6px 0; font-size: 18px; font-weight: 600; color: #1a1a1a;">
                        <?php echo htmlspecialchars($general_settings['company_name']); ?>
                    </h1>
                    <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.5;">
                        <?php echo htmlspecialchars($general_settings['address']); ?><br>
                        <?php echo htmlspecialchars($general_settings['city']); ?>, <?php echo htmlspecialchars($general_settings['postal_code']); ?>
                    </p>
                </div>
                <div style="display: table-cell; vertical-align: top; text-align: right; width: 180px;">
                    <h2 style="margin: 0 0 4px 0; font-size: 20px; font-weight: 600; color: #1a1a1a;">Donation Receipt</h2>
                </div>
            </div>
        </div>

        <!-- Receipt Info Bar -->
        <div style="padding: 20px 40px; background-color: #fafafa; border-bottom: 1px solid #e5e5e5;">
            <div style="display: table; width: 100%;">
                <div style="display: table-cell; width: 50%;">
                    <p style="margin: 0 0 4px 0; font-size: 11px; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">Receipt Number:</p>
                    <p style="margin: 0; font-size: 14px; font-weight: 600; color: #1a1a1a;">
                        <?php echo htmlspecialchars($donation['donation_hash']); ?>
                    </p>
                </div>
                <div style="display: table-cell; width: 50%; text-align: right;">
                    <p style="margin: 0 0 4px 0; font-size: 11px; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">Date Issued:</p>
                    <p style="margin: 0; font-size: 14px; font-weight: 600; color: #1a1a1a;">
                        <?php echo date('F d, Y', strtotime($donation['created_at'])); ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div style="padding: 40px;">

            <!-- Donor Information -->
            <div style="margin-bottom: 30px;">
                <h3 style="margin: 0 0 16px 0; font-size: 14px; font-weight: 600; color: #1a1a1a;">Donor Information</h3>
                <div style="border-bottom: 1px solid #e5e5e5; padding-bottom: 16px;">
                    <p style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600; color: #1a1a1a;">
                        <?php
                        if ($donation['anonymous_donation'] == '1') {
                            echo 'Anonymous Donor';
                        } else {
                            echo htmlspecialchars($donation['donor_name']);
                        }
                        ?>
                    </p>
                    <?php if ($donation['anonymous_donation'] != '1'): ?>
                        <p style="margin: 0 0 4px 0; font-size: 13px; color: #666;">
                        </p>
                    <?php endif; ?>
                    <p style="margin: 0; font-size: 13px; color: #666;">
                        <?php echo htmlspecialchars($donation['donor_email']); ?>
                    </p>
                </div>
            </div>

            <!-- Donation Details -->
            <div style="margin-bottom: 30px;">
                <h3 style="margin: 0 0 16px 0; font-size: 14px; font-weight: 600; color: #1a1a1a;">Donation Details</h3>

                <!-- Total Amount -->
                <div style="background-color: #fafafa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                    <div style="display: table; width: 100%;">
                        <div style="display: table-cell; vertical-align: middle;">
                            <p style="margin: 0 0 4px 0; font-size: 13px; color: #666;">Total Amount</p>
                        </div>
                        <div style="display: table-cell; vertical-align: middle; text-align: right;">
                            <p style="margin: 0; font-size: 28px; font-weight: 700; color: #1a1a1a;">
                                <?php echo formatAmount($donation['net_amount'], $currencySymbol, $position); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <tr>
                        <td style="padding: 12px 0; color: #666; border-bottom: 1px solid #e5e5e5;">Donation Date</td>
                        <td style="padding: 12px 0; text-align: right; color: #1a1a1a; font-weight: 500; border-bottom: 1px solid #e5e5e5;">
                            <?php echo date('F d, Y', strtotime($donation['created_at'])); ?>
                        </td>
                    </tr>

                    <?php if (!empty($donation['payment_method'])): ?>
                        <tr>
                            <td style="padding: 12px 0; color: #666; border-bottom: 1px solid #e5e5e5;">Payment Method</td>
                            <td style="padding: 12px 0; text-align: right; color: #1a1a1a; font-weight: 500; border-bottom: 1px solid #e5e5e5;">
                                <?php echo htmlspecialchars($donation['payment_method']); ?>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <tr>
                        <td style="padding: 12px 0; color: #666; border-bottom: 1px solid #e5e5e5;">Transaction ID</td>
                        <td style="padding: 12px 0; text-align: right; color: #1a1a1a; font-weight: 500; border-bottom: 1px solid #e5e5e5;">
                            #<?php echo htmlspecialchars($donation['transaction_id']); ?>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Campaign Info -->
            <?php if (!empty($campaign['title'])): ?>
                <div style="margin-bottom: 30px;">
                    <h3 style="margin: 0 0 12px 0; font-size: 14px; font-weight: 600; color: #1a1a1a;">Campaign: <?php echo htmlspecialchars($campaign['title']); ?></h3>
                    <?php if (!empty($campaign['short_description'])): ?>
                        <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.6;">
                            <?php echo htmlspecialchars($campaign['short_description']); ?>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Tax Info -->
            <div style="background-color: #f0f7ff; border: 1px solid #d0e4ff; border-radius: 8px; padding: 20px; margin-bottom: 30px;">
                <h3 style="margin: 0 0 12px 0; font-size: 13px; font-weight: 700; color: #1a1a1a; text-transform: uppercase; letter-spacing: 0.5px;">
                    TAX DEDUCTION INFORMATION
                </h3>
                <p style="margin: 0 0 10px 0; font-size: 13px; color: #333; line-height: 1.6;">
                    This donation is tax-deductible to the full extent allowed by law. No goods or services were provided in exchange for this donation.
                </p>
                <p style="margin: 0; font-size: 13px; color: #333; line-height: 1.6;">
                    Please retain this receipt for your tax records.
                </p>
            </div>

        </div>

        <!-- Footer -->
        <div style="padding: 30px 40px; background-color: #fafafa; border-top: 1px solid #e5e5e5;">
            <p style="margin: 0 0 8px 0; font-size: 12px; color: #666; text-align: center; line-height: 1.6;">
                This receipt serves as official proof of donation.
            </p>
        </div>

    </div>
</body>

</html>