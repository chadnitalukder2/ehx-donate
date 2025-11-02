<?php

namespace EHXDonate\Controllers;

use EHXDonate\Models\Campaign;

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
         dd('campaigns');
        $campaigns = Campaign::all();
       
        $this->success([
            'campaigns' => array_map(function($campaign) {
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
            'description' => 'max:1000',
            'destination' => 'required|max:255',
            'start_date' => 'required',
            'end_date' => 'required',
            'price' => 'required|numeric',
            'status' => 'required|in:active,inactive'
        ]);
        
        $data['user_id'] = $this->getCurrentUserId();

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
            'description' => 'max:1000',
            'destination' => 'max:255',
            'start_date' => '',
            'end_date' => '',
            'price' => 'numeric',
            'status' => 'in:active,inactive'
        ]);
        
        // Remove empty values
        $data = array_filter($data, function($value) {
            return $value !== '';
        });
        
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

        // Check if user owns the campaign or has admin capabilities
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
            'campaigns' => array_map(function($campaign) {
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
            'campaigns' => array_map(function($campaign) {
                return $campaign->toArray();
            }, $campaigns)
        ]);
    }

    /**
     * Get campaigns by status
     */
    public function byStatus(string $status): void
    {
        $campaigns = Campaign::getByStatus($status);

        $this->success([
            'campaigns' => array_map(function($campaign) {
                return $campaign->toArray();
            }, $campaigns)
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
            'campaigns' => array_map(function($campaign) {
                return $campaign->toArray();
            }, $campaigns)
        ]);
    }
}
