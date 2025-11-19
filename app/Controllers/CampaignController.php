<?php

namespace EHXDonate\Controllers;

use EHXDonate\Models\Campaign;
use EHXDonate\Core\CPTHandler;

/**
 * Campaign Controller
 */
class CampaignController extends Controller
{
    /**
     * Get all campaigns
     */
    public function index(): void
    {

        $campaigns = (new Campaign())->orderBy('created_at', 'DESC')->get();

        $this->success([
            'campaigns' => array_map(function ($campaign) {
                return $campaign->toArray();
            }, $campaigns)
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

        $this->success([
            'campaign' => $campaign->toArray()
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
            error_log('Campaign post creation failed: ' . $post_id->get_error_message());
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

        // Check if user owns the campaign or has admin capabilities
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

    /**
     * Get upcoming campaigns
     */
    public function upcoming(): void
    {
        $campaigns = Campaign::getUpcoming();

        $this->success([
            'campaigns' => array_map(function ($campaign) {
                return $campaign->toArray();
            }, $campaigns)
        ]);
    }

    /**
     * Get past campaigns
     */
    public function past(): void
    {
        $campaigns = Campaign::getPast();

        $this->success([
            'campaigns' => array_map(function ($campaign) {
                return $campaign->toArray();
            }, $campaigns)
        ]);
    }

    /**
     * Get campaigns by status
     */
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

    /**
     * Get user's campaigns
     */
    public function myCampaigns(): void
    {
        $this->requireAuth();

        $campaigns = Campaign::getByUser($this->getCurrentUserId());

        $this->success([
            'campaigns' => array_map(function ($campaign) {
                return $campaign->toArray();
            }, $campaigns)
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
}
