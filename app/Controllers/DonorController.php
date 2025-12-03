<?php

namespace EHXDonate\Controllers;

use EHXDonate\Models\Donor;
use EHXDonate\Models\Trip;

/**
 * Trip Controller
 */
class DonorController extends Controller
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

        // $donors = (new Donor())->orderBy('created_at', 'DESC')->get();
        // $donors = (new Donor())->paginate($limit, $page, $search, $status);
        $res = (new Donor)->orderBy('created_at', 'desc')->paginateDonor($limit, $page, $search, $status);

        $data = array_map(function ($donor) {
            $donorArray = $donor->with('donations')->toArray();
            $totalDonations = 0;
            if (!empty($donorArray['donations'])) {
                foreach ($donorArray['donations'] as $donation) {
                    $totalDonations++;
                }
            }

            $donorArray['total_donations'] = $totalDonations;
            return $donorArray;
        }, $res['data']);

        $generalSettings = get_option('ehx_donate_settings_general', []);

        $this->success([
            'donors' => $data,
            'generalSettings' => $generalSettings,
            'total' => $res['total'],
            'per_page' => $res['per_page'],
            'current_page' => $res['current_page'],
            'last_page' => $res['last_page'],
        ]);
    }

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
        return $donor->id;
    }
    public function updateDonor($id, $data)
    {
        $this->requireAuth();
        $donor = Donor::find($id);
        if (!$donor) {
            return false;
        }
        $donor->fill($data);
        $donor->save();
        return true;
    }
}
