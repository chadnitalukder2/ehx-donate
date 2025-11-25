<?php

namespace EHXDonate\Controllers;

use EHXDonate\Models\Trip;

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
            'donor_message' => 'nullable|string',
            'anonymous_donation' => 'boolean',
            'gift_aid' => 'boolean',
            'net_amount' => 'required|numeric',
        ]);
        
        $data['user_id'] = $this->getCurrentUserId();
        dd($data);
        
               
        $this->success([
            //'trip' => $trip->toArray()
        ], 'Trip created successfully', 201);
    }

   


}
