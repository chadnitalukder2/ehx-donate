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
        'title',
        'donor_name',
        'first_name',
        'last_name',
        'donor_email',
        'donor_message',
        'anonymous_donation',
        'gift_aid',
        'gift_aid_amount',
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
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'country',
        'post_code',
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
