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
        dd( 'Donation store method called' );
        $this->requireAuth();
        
        $data = $this->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'max:1000',
            'email' => 'required|max:255',
            'start_date' => 'required',
            'end_date' => 'required',
            'price' => 'required|numeric',
            'status' => 'required|in:active,inactive'
        ]);
        
        $data['user_id'] = $this->getCurrentUserId();
        
        $trip = Trip::create($data);
        
        $this->success([
            'trip' => $trip->toArray()
        ], 'Trip created successfully', 201);
    }

   


}
