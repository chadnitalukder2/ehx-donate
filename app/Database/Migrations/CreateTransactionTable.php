<?php

namespace EHXDonate\Database\Migrations;

use EHXDonate\Database\Database;

/**
 * Migration to create transaction table
 */
class CreateTransactionTable
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
            'campaign_id' => 'bigint(20) UNSIGNED DEFAULT NULL',
            'user_id' => 'bigint(20) UNSIGNED DEFAULT NULL',
            'donor_id' => 'bigint(20) UNSIGNED DEFAULT NULL',
            'donation_id' => 'bigint(20) UNSIGNED DEFAULT NULL',
            'subscription_id' => 'bigint(20) UNSIGNED DEFAULT NULL',
            'transaction_type' => "varchar(255) NOT NULL DEFAULT 'charge'",
            'vendor_charge_id' => "varchar(192) NOT NULL DEFAULT ''",
            'payment_method' => 'varchar(255) DEFAULT NULL',
            'payment_method_type' => 'varchar(255) DEFAULT NULL',
            'rate' => 'bigint(20) NOT NULL DEFAULT 1',
            'card_last_4' => 'bigint(20) DEFAULT NULL',
            'card_brand' => 'varchar(255) DEFAULT NULL',
            'payment_total' => 'DECIMAL(10,2) NOT NULL DEFAULT 0.00',
            'reporting_total' => 'DECIMAL(10,2) NOT NULL DEFAULT 0.00',
            'reporting_currency' => 'varchar(255) DEFAULT NULL',
            'reporting_exchange_rate' => 'decimal(16,8) NOT NULL DEFAULT 1.00000000',
            'status' => 'varchar(255) DEFAULT NULL',
            'currency' => 'varchar(255) DEFAULT NULL',
            'payment_mode' => 'varchar(255) DEFAULT NULL',
            'payment_note' => 'text DEFAULT NULL',
            'meta' => 'json DEFAULT NULL',
            // 'uuid' => 'varchar(100) DEFAULT NULL',
            'created_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ];

        $options = [
            'primary_key' => 'id',
            'indexes' => [
                'KEY idx_campaign_id (campaign_id)',
                'KEY idx_user_id (user_id)',
                'KEY idx_donation_id (donation_id)',
                'KEY idx_subscription_id (subscription_id)',
                'KEY idx_transaction_type (transaction_type)',
                'KEY idx_status (status)',
                'KEY idx_currency (currency)'
            ]
        ];

        $this->database->createTable('ehxdo_transaction', $columns, $options);
    }

    /**
     * Reverse the migration
     */
    public function down(): void
    {
        $this->database->dropTable('ehxdo_transaction');
    }
}
