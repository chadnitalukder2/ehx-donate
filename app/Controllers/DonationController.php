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



        $this->success([
        ], 'Donation created successfully', 201);
    }
}
