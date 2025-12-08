<?php

namespace EHXDonate\Models;

/**
 * Campaign model
 */
class Campaign extends Model
{
    /**
     * The table name
     */
    protected $table = 'ehxdo_campaigns';

    /**
     * The attributes that are mass assignable
     */
    protected $fillable = [
        'id',
        'post_id',
        'post_url',
        'title',
        'short_description',
        'goal_amount',
        'start_date',
        'end_date',
        'is_p2p',
        'template',
        'header_image',
        'status',
        'categories',
        'tags',
        'settings',
        'created_at',
        'updated_at'   
    ];

    /**
     * The attributes that should be hidden for arrays
     */
    protected $hidden = [];

    public function donations()
    {
        return $this->hasMany(Donation::class, 'campaign_id', 'id');
    }

}
