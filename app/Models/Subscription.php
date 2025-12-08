<?php

namespace EHXDonate\Models;

/**
 * Subscription model
 */
class Subscription extends Model
{
    /**
     * The table name
     */
    protected $table = 'ehxdo_subscription';

    /**
     * The attributes that are mass assignable
     */
    protected $fillable = [
        'user_id',
        'donor_id',
        'campaign_id',
        'donation_id',
        'interval',
        'interval_count',
        'amount',
        'status',
        'start_date',
        'cancelled_at',
        'next_payment_date',
        'vendor_subscription_id',
        'meta',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [];

}
