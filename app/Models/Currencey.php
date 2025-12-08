<?php

namespace EHXDonate\Models;

/**
 * Campaign model
 */
class Currency extends Model
{
    /**
     * The table name
     */
    protected $table = 'ehxdo_currency';

    /**
     * The attributes that are mass assignable
     */
    protected $fillable = [
        'id',
        'name',
        'symbol',
        'exchange_rate',
        'status',
        'code',
        'created_at',
        'updated_at'   
    ];

    /**
     * The attributes that should be hidden for arrays
     */
    protected $hidden = [];

}
