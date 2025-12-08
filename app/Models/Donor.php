<?php

namespace EHXDonate\Models;

/**
 * Donor model
 */
class Donor extends Model
{
    /**
     * The table name
     */
    protected $table = 'ehxdo_donor';

    /**
     * The attributes that are mass assignable
     */
    protected $fillable = [
        'id',
        'user_id',
        'campaign_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'meta',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays
     */
    protected $hidden = [];

    public function donations(){
           return $this->hasMany(Donation::class, 'donor_id', 'id');
    }


}
