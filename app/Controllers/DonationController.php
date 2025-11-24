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
            'title' => 'required|max:255',
            'description' => 'max:1000',
            'destination' => 'required|max:255',
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
