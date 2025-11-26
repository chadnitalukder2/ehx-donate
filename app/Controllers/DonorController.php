<?php

namespace EHXDonate\Controllers;

use EHXDonate\Models\Donor;
use EHXDonate\Models\Trip;

/**
 * Trip Controller
 */
class DonorController extends Controller
{
    public function createDonor($data)
    {
     
        $donor_data = [
            'user_id' => $data['user_id'] ?? null,
            'campaign_id' => $data['campaign_id'] ?? null,
            'first_name' => $data['first_name'] ?? '',
            'last_name' => $data['last_name'] ?? '',
            'email' => $data['email'] ?? '',
            'phone' => $data['phone'] ?? '',
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql'),
        ];

        $donor = Donor::create($donor_data);
        $this->success([
            'donor_id' => $donor->id
        ], 'Donor created successfully', 201);
    }

    public function updateDonor( $id, $data)
    {
        $this->requireAuth();
        $donor = Donor::find($id);

        if (!$donor) {
            $this->error('Donor not found', 404);
            return;
        }

        $donor->fill($data);

        $donor->save();

        $this->success([
            'donor' => $donor->toArray()
        ], 'Donor updated successfully');
    }
}
