<?php

namespace EHXDonate\Database\Migrations;

use EHXDonate\Database\Database;

/**
 * Migration to create subscription table
 */
class CreateSubscriptionTable
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
            'user_id' => 'bigint(20) UNSIGNED DEFAULT NULL',
            'doner_id' => 'bigint(20) UNSIGNED DEFAULT NULL',
            'campaign_id' => 'bigint(20) UNSIGNED DEFAULT NULL',
            // 'interval' => 'varchar(50) DEFAULT NULL',
            'amount' => 'decimal(10,2) NOT NULL DEFAULT 0.00',
            'status' => "varchar(50) NOT NULL DEFAULT 'active'",
            'start_date' => 'date DEFAULT NULL',
            'recurring' => "enum('one-off','weekly','monthly','quarterly','yearly') NOT NULL DEFAULT 'one-off'",
            'cancelled_at' => 'date DEFAULT NULL',
            'next_payment_date' => 'date DEFAULT NULL',
            'vendor_subscription_id' => 'varchar(255) DEFAULT NULL',
            'meta' => 'json DEFAULT NULL',
            'created_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ];

        $options = [
            'primary_key' => 'id',
            'indexes' => [
                'KEY idx_user_id (user_id)',
                'KEY idx_doner_id (doner_id)',
                'KEY idx_campaign_id (campaign_id)',
                'KEY idx_status (status)',
                'KEY idx_recurring (recurring)',
                'KEY idx_next_payment_date (next_payment_date)'
            ]
        ];

        $this->database->createTable('ehxdo_subscription', $columns, $options);
    }

    /**
     * Reverse the migration
     */
    public function down(): void
    {
        $this->database->dropTable('ehxdo_subscription');
    }
}
