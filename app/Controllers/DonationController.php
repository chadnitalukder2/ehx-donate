<?php

namespace EHXDonate\Controllers;

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
            // Check if user with this email already exists
            $existing_user = get_user_by('email', $data['email']);
            if ($existing_user) {
                $data['user_id'] = $existing_user->ID;
            } else { // Create new user
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
        dd($existing_donor->id, 'existing_donor');
        if ($existing_donor) {
            // Donor exists, use existing donor_id
            $donor_id = $existing_donor->id;
        } else {
            // Create new donor
            $donor_id = (new DonorController())->createOrUpdateDonor($data);

            if (!$donor_id) {
                $this->error('Failed to create donor record', 400);
                return;
            }
        }






        $this->success([
            //'trip' => $trip->toArray()
        ], 'Trip created successfully', 201);
    }
}
