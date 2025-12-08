<?php

namespace EHXDonate\Models;

/**
 * Donation model
 */
class Donation extends Model
{
    /**
     * The table name
     */
    protected $table = 'ehxdo_donation';

    /**
     * The attributes that are mass assignable
     */
    protected $fillable = [
        'id',
        'donation_hash',
        'transaction_id',
        'campaign_id',
        'user_id',
        'donor_id',
        'donor_name',
        'donor_email',
        'donor_message',
        'anonymous_donation',
        'gift_aid',
        'charge',
        'total_payment',
        'processing_fee',
        'interval',
        'interval_count',
        'net_amount',
        'tip_amount',
        'custom_form_data',
        'currency',
        'payment_status',
        'comment_status',
        'message_replies',
        'payment_method',
        'payment_mode',
        'donation_type',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays
     */
    protected $hidden = [];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'donation_id', 'id');
    }
}
