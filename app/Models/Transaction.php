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


    /**
     * Get upcoming trips
     */
    public static function getUpcoming(): array
    {
        $instance = new static();
        $table = $instance->wpdb->prefix . $instance->table;
        
        $results = $instance->wpdb->get_results(
            "SELECT * FROM {$table} WHERE start_date > NOW() AND status = 'active' ORDER BY start_date ASC"
        );
        
        $trips = [];
        foreach ($results as $result) {
            $trip = new static();
            $trip->fill((array) $result);
            $trip->exists = true;
            $trips[] = $trip;
        }
        
        return $trips;
    }

    /**
     * Get past trips
     */
    public static function getPast(): array
    {
        $instance = new static();
        $table = $instance->wpdb->prefix . $instance->table;
        
        $results = $instance->wpdb->get_results(
            "SELECT * FROM {$table} WHERE end_date < NOW() ORDER BY end_date DESC"
        );
        
        $trips = [];
        foreach ($results as $result) {
            $trip = new static();
            $trip->fill((array) $result);
            $trip->exists = true;
            $trips[] = $trip;
        }
        
        return $trips;
    }

    /**
     * Get trip duration in days
     */
    public function getDuration(): int
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }
        
        $start = new \DateTime($this->start_date);
        $end = new \DateTime($this->end_date);
        $diff = $start->diff($end);
        
        return $diff->days;
    }

    /**
     * Check if trip is upcoming
     */
    public function isUpcoming(): bool
    {
        return $this->start_date && new \DateTime($this->start_date) > new \DateTime();
    }

    /**
     * Check if trip is past
     */
    public function isPast(): bool
    {
        return $this->end_date && new \DateTime($this->end_date) < new \DateTime();
    }

    /**
     * Check if trip is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get formatted price
     */
    public function getFormattedPrice(): string
    {
        return '$' . number_format($this->price, 2);
    }

    /**
     * Get formatted date range
     */
    public function getFormattedDateRange(): string
    {
        if (!$this->start_date || !$this->end_date) {
            return '';
        }
        
        $start = new \DateTime($this->start_date);
        $end = new \DateTime($this->end_date);
        
        return $start->format('M j, Y') . ' - ' . $end->format('M j, Y');
    }
}
