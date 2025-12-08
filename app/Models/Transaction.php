<?php

namespace EHXDonate\Models;

/**
 * Transaction model
 */
class Transaction extends Model
{
    /**
     * The table name
     */
    protected $table = 'ehxdo_transaction';

    /**
     * The attributes that are mass assignable
     */
    protected $fillable = [
        'id',
        'campaign_id',
        'user_id',
        'donor_id',
        'donation_id',
        'transaction_type',
        'vendor_charge_id',
        'payment_method',
        'payment_method_type',
        'rate',
        'card_last_4',
        'card_brand',
        'payment_total',
        'reporting_total',
        'reporting_currency',
        'reporting_exchange_rate',
        'status',
        'currency',
        'payment_mode',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays
     */
    protected $hidden = [];

    public function donor(){
         return $this->belongsTo(Donor::class, 'donor_id', 'id');
    }
     public function campaign(){
         return $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }


}
