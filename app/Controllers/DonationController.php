<?php

namespace EHXDonate\Controllers;

use EHXDonate\Models\Donation;
use EHXDonate\Models\Donor;
use EHXDonate\Models\Trip;
use EHXDonate\Services\DonationService;

/**
 * Donation Controller
 */
class DonationController extends Controller
{

    /**
     * Store a new donation
     */
    public function store(): void
    {
        $this->requireAuth();

        $data = $this->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'max:1000',
            'email' => 'required|max:255',
            'phone' => 'string|max:20',
            'donation_hash' => 'required',
            'campaign_id' => 'required|numeric',
            'donation_note' => 'nullable|string',
            'anonymous_donation' => 'boolean',
            'gift_aid' => 'boolean',
            'net_amount' => 'required|numeric',
            'amount' => 'required|numeric',
            'service_fee' => 'numeric',
            'currency' => 'string',
            'donation_type' => 'string',
            // Gift Aid address fields
            'address_line_1' => 'nullable|string',
            'address_line_2' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'country' => 'nullable|string',
            'post_code' => 'nullable|string',
        ]);

        $data['user_id'] = $this->getCurrentUserId();

        if ($data['user_id'] === null) {
            $existing_user = get_user_by('email', $data['email']);
            if ($existing_user) {
                $data['user_id'] = $existing_user->ID;
            } else {
                $username = DonationService::generateUsername($data['email']);
                $password = wp_generate_password(12, true, true);
                $user_data = array(
                    'user_login' => $username,
                    'user_email' => $data['email'],
                    'user_pass'  => $password,
                    'first_name' => $data['first_name'],
                    'last_name'  => $data['last_name'],
                    'role'       => 'subscriber'
                );

                $user_id = wp_insert_user($user_data);
                if (is_wp_error($user_id)) {
                    $this->error('Failed to create user: ' . $user_id->get_error_message(), 400);
                    return;
                }
                $data['user_id'] = $user_id;
                wp_new_user_notification($user_id, null, 'both');
            }
        }

        $donorModel = new Donor();
        $existing_donor = $donorModel->where('email', $data['email'])->first();

        if ($existing_donor) {
            $donor_id = $existing_donor->id;
            $update_data = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'] ?? '',
                'updated_at' => current_time('mysql'),
            ];

            $updateResult = (new DonorController())->updateDonor($donor_id, $update_data);

            if (!$updateResult) {
                $this->error('Failed to update donor record', 400);
                return;
            }
        } else {
            $donor_id = (new DonorController())->createDonor($data);

            if (!$donor_id) {
                $this->error('Failed to create donor record', 400);
                return;
            }
        }


        $data['donor_id'] = $donor_id;

        $donationData = [
            'donor_id' => $data['donor_id'],
            'campaign_id' => $data['campaign_id'],
            'user_id' => $data['user_id'],
            'donation_hash' => $data['donation_hash'],
            'transaction_id' => uniqid('don_', true),
            'donor_name' => trim($data['first_name'] . ' ' . $data['last_name']),
            'donor_email' => $data['email'],
            'donor_message' => $data['donation_note'] ?? '',
            'anonymous_donation' => $data['anonymous_donation'] ?? 0,
            //
            'total_payment' => $data['amount'],
            'processing_fee' => ($data['service_fee'] ?? 0),
            'tip_amount' => 0.00,
            'payment_status' => 'pending',
            'comment_status' => 'pending',
            'message_replies' => null,
            'payment_method' => null,
            'payment_mode' => $data['payment_mode'] ?? 'live',

            'gift_aid' => $data['gift_aid'] ?? 0,
            'charge' => ($data['service_fee'] ?? 0),
            'net_amount' => $data['net_amount'],
            'amount' => $data['amount'],
            'currency' => $data['currency'] ?? 'GBP',
            'donation_type' => $data['donation_type'] ?? 'one-time',
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql'),
        ];

        $donation = Donation::create($donationData);

        $emailSettings = get_option('ehx_donate_settings_email', []);

        $this->sendDonationEmails($donation, $data, $emailSettings);

        $this->success([], 'Donation created successfully', 201);
    }

    private function sendDonationEmails($donation, $data, $emailSettings)
    {
        $adminEmail = $emailSettings['adminEmail'] ?? get_option('admin_email');
        $fromName = $emailSettings['mailFromName'] ?? get_bloginfo('name');
        $fromAddress = $emailSettings['mailFromAddress'] ?? get_option('admin_email');
        $enableHtml = $emailSettings['enableHtml'] ?? true;

        // Email headers setup
        $headers = [];
        $headers[] = 'From: ' . $fromName . ' <' . $fromAddress . '>';

        if ($enableHtml) {
            $headers[] = 'Content-Type: text/html; charset=UTF-8';
        }

        // 1. Admin email
        $this->sendAdminEmail($adminEmail, $donation, $data, $headers);

        // 2. Donor email
        $this->sendDonorEmail($data['email'], $donation, $data, $headers);
    }

    private function sendAdminEmail($adminEmail, $donation, $data, $headers)
    {
        $subject = 'New Donation Received - ' . $donation->transaction_id;

        $message = $this->getAdminEmailTemplate($donation, $data);

        wp_mail($adminEmail, $subject, $message, $headers);
    }

    /**
     * Send email to donor
     */
    private function sendDonorEmail($donorEmail, $donation, $data, $headers)
    {
        $subject = 'Thank you for your donation!';

        $message = $this->getDonorEmailTemplate($donation, $data);

        wp_mail($donorEmail, $subject, $message, $headers);
    }

    private function getAdminEmailTemplate($donation, $data)
    {
        ob_start();
?>
        <!DOCTYPE html>
        <html>

        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                }

                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                }

                .header {
                    background: #4CAF50;
                    color: white;
                    padding: 20px;
                    text-align: center;
                }

                .content {
                    padding: 20px;
                    background: #f9f9f9;
                }

                .info-row {
                    margin: 10px 0;
                    padding: 10px;
                    background: white;
                }

                .label {
                    font-weight: bold;
                    color: #555;
                }

                .amount {
                    font-size: 24px;
                    color: #4CAF50;
                    font-weight: bold;
                }
            </style>
        </head>

        <body>
            <div class="container">
                <div class="header">
                    <h2>New Donation Received</h2>
                </div>
                <div class="content">
                    <div class="info-row">
                        <span class="label">Transaction ID:</span> <?php echo esc_html($donation->transaction_id); ?>
                    </div>
                    <div class="info-row">
                        <span class="label">Donor Name:</span> <?php echo esc_html($donation->donor_name); ?>
                    </div>
                    <div class="info-row">
                        <span class="label">Email:</span> <?php echo esc_html($donation->donor_email); ?>
                    </div>
                    <div class="info-row">
                        <span class="label">Phone:</span> <?php echo esc_html($data['phone'] ?? 'N/A'); ?>
                    </div>
                    <div class="info-row">
                        <span class="label">Amount:</span>
                        <span class="amount"><?php echo esc_html($donation->currency . ' ' . number_format($donation->amount, 2)); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Net Amount:</span> <?php echo esc_html($donation->currency . ' ' . number_format($donation->net_amount, 2)); ?>
                    </div>
                    <div class="info-row">
                        <span class="label">Processing Fee:</span> <?php echo esc_html($donation->currency . ' ' . number_format($donation->processing_fee, 2)); ?>
                    </div>
                    <div class="info-row">
                        <span class="label">Donation Type:</span> <?php echo esc_html(ucfirst($donation->donation_type)); ?>
                    </div>
                    <div class="info-row">
                        <span class="label">Payment Mode:</span> <?php echo esc_html(ucfirst($donation->payment_mode)); ?>
                    </div>
                    <?php if (!empty($donation->donor_message)): ?>
                        <div class="info-row">
                            <span class="label">Message:</span><br>
                            <?php echo nl2br(esc_html($donation->donor_message)); ?>
                        </div>
                    <?php endif; ?>
                    <div class="info-row">
                        <span class="label">Anonymous:</span> <?php echo $donation->anonymous_donation ? 'Yes' : 'No'; ?>
                    </div>
                    <div class="info-row">
                        <span class="label">Date:</span> <?php echo esc_html($donation->created_at); ?>
                    </div>
                </div>
            </div>
        </body>

        </html>
    <?php
        return ob_get_clean();
    }

    /**
     * Donor email template
     */
    private function getDonorEmailTemplate($donation, $data)
    {
        ob_start();
    ?>
        <!DOCTYPE html>
        <html>

        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                }

                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                }

                .header {
                    background: #4CAF50;
                    color: white;
                    padding: 20px;
                    text-align: center;
                }

                .content {
                    padding: 20px;
                    background: #f9f9f9;
                }

                .thank-you {
                    font-size: 24px;
                    color: #4CAF50;
                    text-align: center;
                    margin: 20px 0;
                }

                .info-box {
                    background: white;
                    padding: 20px;
                    margin: 20px 0;
                    border-left: 4px solid #4CAF50;
                }

                .amount {
                    font-size: 28px;
                    color: #4CAF50;
                    font-weight: bold;
                    text-align: center;
                    margin: 20px 0;
                }

                .footer {
                    text-align: center;
                    padding: 20px;
                    color: #666;
                    font-size: 14px;
                }
            </style>
        </head>

        <body>
            <div class="container">
                <div class="header">
                    <h1>Thank You for Your Donation!</h1>
                </div>
                <div class="content">
                    <p class="thank-you">Dear <?php echo esc_html($data['first_name']); ?>,</p>

                    <p>Thank you for your generous donation! Your support makes a real difference.</p>

                    <div class="amount">
                        <?php echo esc_html($donation->currency . ' ' . number_format($donation->amount, 2)); ?>
                    </div>

                    <div class="info-box">
                        <h3>Donation Details</h3>
                        <p><strong>Transaction ID:</strong> <?php echo esc_html($donation->transaction_id); ?></p>
                        <p><strong>Date:</strong> <?php echo esc_html($donation->created_at); ?></p>
                        <p><strong>Donation Type:</strong> <?php echo esc_html(ucfirst($donation->donation_type)); ?></p>
                        <p><strong>Net Amount:</strong> <?php echo esc_html($donation->currency . ' ' . number_format($donation->net_amount, 2)); ?></p>
                        <?php if ($donation->processing_fee > 0): ?>
                            <p><strong>Processing Fee:</strong> <?php echo esc_html($donation->currency . ' ' . number_format($donation->processing_fee, 2)); ?></p>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($donation->donor_message)): ?>
                        <div class="info-box">
                            <h3>Your Message</h3>
                            <p><?php echo nl2br(esc_html($donation->donor_message)); ?></p>
                        </div>
                    <?php endif; ?>

                    <p>This email serves as a receipt for your donation. Please keep it for your records.</p>

                    <p>If you have any questions, please don't hesitate to contact us.</p>

                    <p>With gratitude,<br>
                        <strong><?php echo get_bloginfo('name'); ?></strong>
                    </p>
                </div>

                <div class="footer">
                    <p>This is an automated message. Please do not reply to this email.</p>
                </div>
            </div>
        </body>

        </html>
<?php
        return ob_get_clean();
    }
}
