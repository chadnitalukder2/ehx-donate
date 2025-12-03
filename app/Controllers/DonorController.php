<?php

namespace EHXDonate\Controllers;

use EHXDonate\Models\Donor;
use EHXDonate\Helpers\Currency;
use EHXDonate\Models\Donation;

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
            $totalAmount = 0;
           
            if (!empty($donorArray['donations'])) {
                foreach ($donorArray['donations'] as $donation) {

                    if ($donation['payment_status'] === 'completed') {
                        $totalDonations++;
                        $totalAmount += floatval($donation['net_amount']);
                    }
                }
            }

            $donorArray['total_donations'] = $totalDonations;
            $donorArray['total_amount'] = $totalAmount;
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

    public function destroy(int $id): void
    {
        $this->requireAuth();

        $donor = Donor::find($id);

        if (!$donor) {
            $this->error('Donor not found', 404);
            return;
        }
        if ($donor->user_id !== $this->getCurrentUserId() && !$this->can('manage_options')) {
            $this->error('Unauthorized', 403);
            return;
        }

        $donor->delete();

        $this->success([], 'Donor deleted successfully');
    }

    public function export_donor_csv()
    {
        // Set headers for CSV download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=campaigns-' . date('Y-m-d-H-i-s') . '.csv');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Create output stream
        $output = fopen('php://output', 'w');

        // Add BOM for UTF-8 encoding
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Add CSV headers
        fputcsv($output, array(
            'ID',
            'Name',
            'Email',
            'phone',
            'Total Donations',
            'Total Amount',

        ));
        // Get general settings for currency formatting
        $generalSettings = get_option('ehx_donate_settings_general', []);
        $currency = $generalSettings['currency'] ?? 'GBP';
        $currencySymbols = Currency::getCurrencySymbol('');
        $symbol = $currencySymbols[$currency] ?? $currency;

        // Fetch all campaigns
        $donors = (new Donor)->orderBy('created_at', 'desc')->get();
        foreach ($donors as $donor) {
            $donations = (new Donation())->where('donor_id', $donor->id)->get();

            $totalDonations = 0;
            $totalAmount = 0;
            foreach ($donations as $donation) {
                if ($donation->payment_status === 'completed') {
                    $totalDonations++;
                    $totalAmount += floatval($donation->net_amount);
                }
            }

            fputcsv($output, array(
                $donor->id,
                $donor->first_name . ' ' .  $donor->last_name,
                $donor->email,
                $donor->phone ?? '---',
                $totalDonations,
                $symbol . ' ' . number_format($totalAmount ?? 0, 2),
            ));
        }

        fclose($output);
        exit();
    }
}
