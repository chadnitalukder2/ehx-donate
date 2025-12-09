<?php

namespace EHXDonate\Controllers;

use EHXDonate\Models\Campaign;
use EHXDonate\Core\CPTHandler;
use EHXDonate\Models\Donation;
use EHXDonate\Helpers\Currency;

/**
 * Campaign Controller
 */
class CampaignController extends Controller
{
    /**
     * Get all campaigns
     */
     private function updateCampaignStatusAuto($campaign): void
    {
        // Skip if already completed
        if ($campaign->status === 'completed') {
            return;
        }

        $shouldUpdate = false;
        $newStatus = $campaign->status;

        // Calculate total raised amount
        $donations = (new Donation)->where('campaign_id', $campaign->id)
            ->where('payment_status', 'completed')
            ->get();
        
        $totalRaised = 0;
        foreach ($donations as $donation) {
            $totalRaised += floatval($donation->total_payment);
        }

        // Check if end_date exists
        if (!empty($campaign->end_date)) {
            $endDate = strtotime($campaign->end_date);
            $currentDate = strtotime(date('Y-m-d'));
            
            // Only mark as completed if end_date has expired
            if ($currentDate > $endDate) {
                $shouldUpdate = true;
                $newStatus = 'completed';
            }
        } else {
            // If no end_date, check if goal is reached
            if (!empty($campaign->goal_amount) && $totalRaised >= floatval($campaign->goal_amount)) {
                $shouldUpdate = true;
                $newStatus = 'completed';
            }
        }

        // Update status if needed
        if ($shouldUpdate && $newStatus !== $campaign->status) {
            $campaign->status = $newStatus;
            $campaign->save();
        }
    }

    /**
     * Get all campaigns with auto status update
     */
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

        $res = (new Campaign)->orderBy('created_at', 'desc')->paginate($limit, $page, $search, $status);

        $data = array_map(function ($campaign) {
            // Auto-update status before displaying
            $this->updateCampaignStatusAuto($campaign);

            $campaignArray = $campaign->with('donations')->toArray();

            // Calculate totals
            $totalDonations = 0;
            $totalRaised = 0;

            if (!empty($campaignArray['donations'])) {
                foreach ($campaignArray['donations'] as $donation) {
                    $totalDonations++;
                    if ($donation['payment_status'] === 'completed') {
                        $totalRaised += floatval($donation['total_payment']);
                    }
                }
            }

            $campaignArray['total_donations'] = $totalDonations;
            $campaignArray['total_raised'] = $totalRaised;

            return $campaignArray;
        }, $res['data']);

        $generalSettings = get_option('ehx_donate_settings_general', []);

        $totalActiveCampaigns = (new Campaign)->where('status', 'active')->count();
        $totalPendingCampaigns = (new Campaign)->where('status', 'pending')->count();
        $totalCompletedCampaigns = (new Campaign)->where('status', 'completed')->count();

        // Last month calculations
        $lastMonthStart = date('Y-m-01', strtotime('first day of last month'));
        $lastMonthEnd = date('Y-m-t', strtotime('last day of last month'));

        $lastMonthActive = (new Campaign)
            ->where('status', 'active')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        $lastMonthPending = (new Campaign)
            ->where('status', 'pending')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        $lastMonthCompleted = (new Campaign)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        $percentActive = $lastMonthActive > 0 ? round((($totalActiveCampaigns - $lastMonthActive) / $lastMonthActive) * 100, 2) : 0;
        $percentPending = $lastMonthPending > 0 ? round((($totalPendingCampaigns - $lastMonthPending) / $lastMonthPending) * 100, 2) : 0;
        $percentCompleted = $lastMonthCompleted > 0 ? round((($totalCompletedCampaigns - $lastMonthCompleted) / $lastMonthCompleted) * 100, 2) : 0;

        $this->success([
            'campaigns' => $data,
            'generalSettings' => $generalSettings,
            'total' => $res['total'],
            'per_page' => $res['per_page'],
            'current_page' => $res['current_page'],
            'last_page' => $res['last_page'],
            'totalActiveCampaigns' => $totalActiveCampaigns,
            'totalPendingCampaigns' => $totalPendingCampaigns,
            'totalCompletedCampaigns' => $totalCompletedCampaigns,
            'percentActive' => $percentActive,
            'percentPending' => $percentPending,
            'percentCompleted' => $percentCompleted,
        ]);
    }

    /**
     * Get a specific trip
     */
    public function show(int $id): void
    {
        $campaign = Campaign::find($id);

        if (!$campaign) {
            $this->error('Campaign not found', 404);
            return;
        }

        $campaign->settings = json_decode($campaign->settings, true);
        $campaign->categories = json_decode($campaign->categories, true);
        $campaign->tags = json_decode($campaign->tags, true);

        $post = get_post($campaign->post_id);
        $campaign->post = $post;
        $generalSettings = get_option('ehx_donate_settings_general', []);
        $this->success([
            'campaign' => $campaign->toArray(),
            'generalSettings' => $generalSettings,
        ]);
    }
    /**
     * Create a new campaign
     */
    public function store(): void
    {
        $this->requireAuth();
        $data = $this->validate([
            'title' => 'required|max:255',
            'short_description' => 'max:255',
            'goal_amount' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'is_p2p' => 'nullable|in:0,1',
            'template' => 'max:255',
            'header_image' => 'nullable|string',
            'status' => 'in:active,pending,completed',
            'categories' => 'json',
            'tags' => 'json',
            'settings' => 'json',
        ]);

        $data['user_id'] = $this->getCurrentUserId();

        if (!empty($data['start_date'])) {
            $data['start_date'] = substr($data['start_date'], 0, 10);
        } else {
            $data['start_date'] = null;
        }

        if (!empty($data['end_date'])) {
            $data['end_date'] = substr($data['end_date'], 0, 10);
        } else {
            $data['end_date'] = null;
        }

        if (!post_type_exists('ehxdo_campaign')) {
            (new CPTHandler())->registerCPT();
        }

        $post = [
            'post_title' => $data['title'],
            'post_status' => 'publish',
            'post_type' => 'ehxdo_campaign',
            'post_content' => '',
            'post_author' => $data['user_id']
        ];

        $post_id = wp_insert_post($post);

        if (is_wp_error($post_id)) {
            // error_log('Campaign post creation failed: ' . $post_id->get_error_message());
            $this->error('Failed to create campaign post.', 400);
            return;
        }

        wp_update_post([
            'ID' => $post_id,
            'post_content' => '[ehxdo_campaign post_id="' . $post_id . '"]',
        ]);

        $data['post_id'] = $post_id;

        $data['post_url'] = get_permalink($post_id);
        $data["categories"] = json_encode($data["categories"]);
        $data["tags"] = json_encode($data["tags"]);
        $data["settings"] = json_encode($data["settings"]);

        $campaign = Campaign::create($data);

        $this->success([
            'campaign' => $campaign->toArray()
        ], 'Campaign created successfully', 201);
    }

    /**
     * Update a campaign
     */
    public function update(int $id): void
    {
        $this->requireAuth();

        $campaign = Campaign::find($id);

        if (!$campaign) {
            $this->error('Campaign not found', 404);
            return;
        }

        if ($campaign->user_id !== $this->getCurrentUserId() && !$this->can('manage_options')) {
            $this->error('Unauthorized', 403);
            return;
        }

        $data = $this->validate([
            'title' => 'max:255',
            'short_destination' => 'max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'price' => 'numeric',
            'status' => 'in:active,pending,completed',
            'categories' => 'json',
            'tags' => 'json',
            'settings' => 'json',
            'header_image' => 'nullable|string',
            'goal_amount' => 'nullable|string',
            'currency' => 'max:255',
            'is_p2p' => 'in:0,1',
            'template' => 'max:255',
        ]);

        if (!empty($data['start_date'])) {
            $data['start_date'] = substr($data['start_date'], 0, 10);
        }

        if (!empty($data['end_date'])) {
            $data['end_date'] = substr($data['end_date'], 0, 10);
        }
        // dd($data, 'data');
        $data["categories"] = json_encode($data["categories"]);
        $data["tags"] = json_encode($data["tags"]);
        $data["settings"] = json_encode($data["settings"]);

        $post = [
            'ID' => $campaign->post_id,
            'post_title' => $data['title'],
            'post_content' => '[ehxdo_campaign id="' . $campaign->post_id . '"]',
        ];

        wp_update_post($post);

        // delete post from data
        unset($data['post']);

        // Remove empty values
        // $data = array_filter($data, function ($value) {
        //     return $value !== '';
        // });

        // $campaign->fill($data);

        $header_image = isset($data['header_image']) ? $data['header_image'] : null;
        $has_header_image = array_key_exists('header_image', $data);

        $data = array_filter($data, function ($value, $key) {
            if ($key === 'header_image') {
                return true;
            }
            return $value !== '' && $value !== null;
        }, ARRAY_FILTER_USE_BOTH);

        if ($has_header_image) {
            $data['header_image'] = $header_image;
        }

        $campaign->fill($data);

        $campaign->save();

        $this->success([
            'campaign' => $campaign->toArray()
        ], 'Campaign updated successfully');
    }

    /**
     * Delete a campaign
     */
    public function destroy(int $id): void
    {
        $this->requireAuth();

        $campaign = Campaign::find($id);

        if (!$campaign) {
            $this->error('Campaign not found', 404);
            return;
        }
        if ($campaign->user_id !== $this->getCurrentUserId() && !$this->can('manage_options')) {
            $this->error('Unauthorized', 403);
            return;
        }

        $campaign->delete();

        $this->success([], 'Campaign deleted successfully');
    }


    public function updateCampaignStatus(string $id): void
    {
        $this->requireAuth();

        $status = $this->validate([
            'status' => 'required|in:active,pending,completed',
        ])['status'];

        $campaign = Campaign::find($id);

        if (!$campaign) {
            $this->error('Campaign not found', 404);
            return;
        }

        $campaign->status = $status;
        $campaign->save();

        $this->success([
            'message' => 'Status updated successfully',
            'campaign' => $campaign->toArray(),
        ]);
    }


    public function getCampaignByPostId(int $id)
    {
        $campaign = Campaign::getCampaignByPostId($id);
        if (!$campaign) {
            $this->error('Campaign not found', 404);
            return;
        }

        $campaign->settings   = json_decode($campaign->settings, true);
        $campaign->categories = json_decode($campaign->categories, true);
        $campaign->tags       = json_decode($campaign->tags, true);

        $post = get_post($campaign->post_id);
        $campaign->post = $post;

        return $campaign->toArray();
    }

    function export_campaigns_csv()
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
            'SI',
            'Title',
            'Goal Amount',
            'Total Donations',
            'Total Raised',
            'Start Date',
            'End Date',
            'Status',
            'Created Date'
        ));

        // Get general settings for currency formatting
        $generalSettings = get_option('ehx_donate_settings_general', []);
        $currency = $generalSettings['currency'] ?? 'USD';
        $currencySymbols = Currency::getCurrencySymbol('');
        $symbol = $currencySymbols[$currency] ?? $currency;

        // Fetch all campaigns
        $campaigns = (new Campaign)->orderBy('created_at', 'desc')->get();
        $si = 1;
        // Process and write each campaign
        foreach ($campaigns as $campaign) {
            $donations = (new Donation)->where('campaign_id', $campaign->id)->get();
            $totalDonations = 0;
            $totalRaised = 0;

            foreach ($donations as $donation) {
                $totalDonations++;
                if ($donation->payment_status === 'completed') {
                    $totalRaised += floatval($donation->total_payment);
                }
            }

            // Format dates properly - just date without time
            $startDate = !empty($campaign->start_date) ? date('d/m/Y', strtotime($campaign->start_date)) : 'N/A';
            $endDate = !empty($campaign->end_date) ? date('d/m/Y', strtotime($campaign->end_date)) : 'N/A';
            $createdDate = !empty($campaign->created_at) ? date('d/m/Y', strtotime($campaign->created_at)) : 'N/A';


            fputcsv($output, array(
                $si,
                $campaign->title,
                $symbol . ' ' . number_format($campaign->goal_amount ?? 0, 2),
                $totalDonations,
                $symbol . ' ' . number_format($totalRaised, 2),
                $startDate,
                $endDate,
                ucfirst($campaign->status ?? 'pending'),
                $createdDate
            ));
            $si++;
        }

        fclose($output);
        exit();
    }
}
