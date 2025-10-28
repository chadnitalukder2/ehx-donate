<?php

namespace EHXDonate\Database\Migrations;

use EHXDonate\Database\Database;

/**
 * Migration to create donation item table
 */
class CreateDonationItemTable
{
    /**
     * Database instance
     */
    protected $database;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->database = new Database();
    }

    /**
     * Run the migration
     */
    public function up(): void
    {
        $columns = [
            'id' => 'bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT',
            'campaign_id' => 'bigint(20) UNSIGNED NOT NULL',
            'donation_id' => 'bigint(20) UNSIGNED NOT NULL',
            'type' => "varchar(255) NOT NULL DEFAULT 'one_time'",
            'parent_holder' => 'varchar(255) DEFAULT NULL',
            'billing_interval' => 'varchar(255) DEFAULT NULL',
            'item_name' => 'varchar(255) NOT NULL',
            'quantity' => 'bigint(20) NOT NULL DEFAULT 1',
            'item_price' => 'float NOT NULL DEFAULT 0',
            'line_total' => 'float NOT NULL DEFAULT 0',
            'item_meta' => 'text DEFAULT NULL',
            'created_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ];

        $options = [
            'primary_key' => 'id',
            'indexes' => [
                'KEY idx_campaign_id (campaign_id)',
                'KEY idx_donation_id (donation_id)',
                'KEY idx_type (type)'
            ]
        ];

        $this->database->createTable('ehxdo_donation_item', $columns, $options);
    }

    /**
     * Reverse the migration
     */
    public function down(): void
    {
        $this->database->dropTable('ehxdo_donation_item');
    }
}
