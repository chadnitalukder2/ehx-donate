<?php

namespace EHXDonate\Controllers;

use EHXDonate\Models\Donation;
use EHXDonate\Models\Donor;
use EHXDonate\Models\Trip;
use EHXDonate\Services\DonationService;
use EHXDonate\Services\Payment\Stripe;
use EHXDonate\Models\Transaction;
use EHXDonate\Models\Subscription;
use EHXDonate\Models\Campaign;
use EHXDonate\Helpers\Currency;

/**
 * Donation Controller
 */
class DonationController extends Controller
{

    public function index(): void
    {
        $data = $this->request;

        $page = 1;
        $limit = 10;
        $search = null;
        $status = null;

        if ($data['page']) {
            $page = intval($data['page']);
        }
        if ($data['limit']) {
            $limit =  intval($data['limit']);
        }
        if ($data['search']) {
            $search = sanitize_text_field($data['search']);
        }
        if ($data['status']) {
            $status = sanitize_text_field($data['status']);
        }
        $res = (new Donation)
        ->orderBy('created_at', 'desc')
        ->paginateDonation($limit, $page, $search, $status);
        $data = array_map(function ($donation) {
            $arr = $donation->toArray();
            $campaign = (new Campaign())->find($arr['campaign_id']);
            $arr['campaign'] = $campaign ? $campaign->toArray() : null;
            return $arr;
        }, $res['data']);

        //  $donations = (new Donation())->orderBy('created_at', 'DESC')->get();
        $generalSettings = get_option('ehx_donate_settings_general', []);

        $this->success([
            'donations' => $data,
            'generalSettings' => $generalSettings,
            'total' => $res['total'],
            'per_page' => $res['per_page'],
            'current_page' => $res['current_page'],
            'last_page' => $res['last_page'],
        ]);
    }

    public function show($id)
    {
        $donation = Donation::find($id);
        if (!$donation) {
            $this->error('Donation not found', 404);
            return;
        }

        $transactions = (new Transaction())->where('donation_id', $id)->get();
        $subscription = (new Subscription())->where('donation_id', $id)->first();
        $donor = Donor::find($donation->donor_id);
        $donor->profile = get_avatar_url($donor->email);

        $this->success([
            'donation' => $donation->toArray(),
            'transactions' => $transactions,
            'donor' => $donor,
            'subscription' => $subscription,
        ]);
    }
    
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
            'interval' => 'string',
            // Gift Aid address fields
            'address_line_1' => 'nullable|string',
            'address_line_2' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'country' => 'nullable|string',
            'post_code' => 'nullable|string',
        ]);

        $data['payment_method'] = 'stripe';

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
        $existing_donor = $donorModel->where('email', $data['email'])->limit(1)->get();
        if ($existing_donor) {
            $existing_donor = $existing_donor[0];
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
        $stipe = new Stripe();
        $donationData = [
            'donor_id' => $data['donor_id'],
            'campaign_id' => $data['campaign_id'],
            'user_id' => $data['user_id'],
            'donation_hash' => $data['donation_hash'],
            'transaction_id' => uniqid('don_', true),
            'donor_name' => trim($data['first_name'] . ' ' . $data['last_name']),
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
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
            'payment_mode' => $stipe->getMode(),

            'gift_aid' => $data['gift_aid'] ?? 0,
            'gift_aid_amount' => $data['gift_aid'] ? number_format(floatval($data['net_amount']) * 0.25, 2) : '0.00',
            'address_line_1' => $data['address_line_1'] ?? '',
            'address_line_2' => $data['address_line_2'] ?? '',
            'city' => $data['city'] ?? '',
            'state' => $data['state'] ?? '',
            'country' => $data['country'] ?? '',
            'post_code' => $data['post_code'] ?? '',
            'charge' => ($data['service_fee'] ?? 0),
            'net_amount' => $data['net_amount'],
            'amount' => $data['amount'],
            'currency' => $data['currency'] ?? 'GBP',
            'donation_type' => $data['donation_type'] ?? 'one-time',
            'interval' => $data['interval'] ?? 'monthly',
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql'),
        ];

        $donation = Donation::create($donationData);
        if ($donation->donation_type === 'recurring') {
            $this->createSubscription($donation);
        }
        $emailSettings = get_option('ehx_donate_settings_email', []);

        $this->sendDonationEmails($donation, $data, $emailSettings);

        do_action('ehxdo_handle_payment_' . $data['payment_method'], $donation, $data);

        $this->success([], 'Donation created successfully', 201);
    }

    public function createSubscription($donation)
    {
        Subscription::create([
            'user_id'               => $donation->user_id,
            'donation_id'           => $donation->id,
            'donor_id'              => $donation->donor_id,
            'campaign_id'           => $donation->campaign_id,
            'amount'                => $donation->net_amount, // base donation amount per cycle
            'interval'              => $donation->interval ?? 'month',
            'status'                => 'active',
            'start_date'            => current_time('mysql'),
            'next_payment_date'     => current_time('mysql'), 
            'created_at'            => current_time('mysql'),
            'updated_at'            => current_time('mysql'),
        ]);
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
                        <span class="amount"><?php echo esc_html($donation->currency . ' ' . number_format($donation->total_payment, 2)); ?></span>
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


    public function destroy(int $id): void
    {
        $this->requireAuth();

        $donation = Donation::find($id);

        if (!$donation) {
            $this->error('Donation not found', 404);
            return;
        }
        if ($donation->user_id !== $this->getCurrentUserId() && !$this->can('manage_options')) {
            $this->error('Unauthorized', 403);
            return;
        }

        $donation->delete();

        $this->success([], 'Donation deleted successfully');
    }

    function export_donation_csv()
    {
        // Set CSV headers
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=donations-' . date('Y-m-d-H-i-s') . '.csv');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM

        // CSV headers
        fputcsv($output, [
            'SI',
            'Donation ID',
            'Transaction ID',
            'Donation Date',
            'Donation Type',
            'Status',
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'Address Line 1',
            'City/Town',
            'Postcode',
            'Donation Amount',
            'Gift Aid', // Gift Aid Eligible
            'Gift Aid Amount',
            'Payment Method',
            'Recurring Frequency',
            'Recurring Start Date',
            'Recurring Next Payment Date',
            'Total Payments Made'
        ]);

        // Currency setup
        $generalSettings = get_option('ehx_donate_settings_general', []);
        $currency = $generalSettings['currency'] ?? 'GBP';
        $currencySymbols = Currency::getCurrencySymbol($currency);
        $symbol = $currencySymbols[$currency] ?? $currency;
        $si = 1;
        // Fetch all donations
        $donations = (new Donation())->orderBy('created_at', 'desc')->get();

        foreach ($donations as $donation) {

            // Get campaign manually
            $campaign = (new Campaign())->find($donation->campaign_id);
            $transactions = (new Transaction())->where('donation_id', $donation->id)->get();
            $campaignTitle = $campaign ? $campaign->title : '';
            $totalTransactions = count($transactions) > 0 ? count($transactions) : 1;
            fputcsv($output, [
                $si,
                $donation->id,
                $donation->transaction_id,
                $donation->created_at ? date('d/m/Y', strtotime($donation->created_at)) : 'N/A',
                $donation->donation_type,
                $donation->payment_status,
                $donation->first_name,
                $donation->last_name,
                $donation->donor_email,
                $donation->phone ?? '---',
                $donation->address_line_1,
                $donation->city,
                $donation->post_code,
                $symbol . ' ' . number_format($donation->net_amount ?? 0, 2),
                $donation->gift_aid ? 'Yes' : 'No',
                $symbol . ' ' . number_format($donation->gift_aid_amount ?? 0, 2) * $totalTransactions,
                $donation->payment_method,
                $donation->interval ?? '---',
                $donation->start_date ? date('d/m/Y', strtotime($donation->start_date)) : 'N/A',
                $donation->next_payment_date ? date('d/m/Y', strtotime($donation->next_payment_date)) : 'N/A',
                $totalTransactions
            ]);
            $si++;
        }

        fclose($output);
        exit();
    }
}
