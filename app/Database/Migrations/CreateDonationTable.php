<?php

namespace EHXDonate\Database\Migrations;

use EHXDonate\Database\Database;

/**
 * Migration to create donation table
 */
class CreateDonationTable
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
        'donation_hash' => 'varchar(255) DEFAULT NULL',
        'transaction_id' => 'varchar(255) NOT NULL UNIQUE',
        'campaign_id' => 'bigint(20) UNSIGNED DEFAULT NULL',
        'user_id' => 'bigint(20) UNSIGNED DEFAULT NULL',
        'donor_id' => 'bigint(20) UNSIGNED DEFAULT NULL',
        'donor_name' => 'varchar(255) DEFAULT NULL',
        'donor_email' => 'varchar(255) DEFAULT NULL',
        'donor_message' => 'text DEFAULT NULL',
        'anonymous_donation' => 'tinyint(1) NOT NULL DEFAULT 0',
        'gift_aid' => 'tinyint(1) NOT NULL DEFAULT 0',
        'charge' => 'decimal(10,2) NOT NULL DEFAULT 0.00',
        'total_payment' => 'decimal(10,2) NOT NULL DEFAULT 0.00',
        'processing_fee' => 'decimal(10,2) NOT NULL DEFAULT 0.00',
        'net_amount' => 'decimal(10,2) NOT NULL DEFAULT 0.00',
        'tip_amount' => 'decimal(10,2) NOT NULL DEFAULT 0.00',
        'custom_form_data' => 'json DEFAULT NULL',
        'currency' => "varchar(10) NOT NULL DEFAULT 'GBP'",
        '`interval`' => "varchar(50) DEFAULT NULL",
        '`interval_count`' => 'int(11) NOT NULL DEFAULT 1',
        'payment_status' => "enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending'",
        'comment_status' => "enum('publish','pending','spam') NOT NULL DEFAULT 'pending'",
        'message_replies' => 'json DEFAULT NULL',
        'payment_method' => 'varchar(255) DEFAULT NULL',
        'payment_mode' => "enum('test','live') NOT NULL DEFAULT 'test'",
        'donation_type' => "varchar(50) NOT NULL DEFAULT 'one-time'",
        'created_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
        'updated_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
    ];

    $options = [
        'primary_key' => 'id',
        'indexes' => [
            'KEY idx_transaction_id (transaction_id)',
            'KEY idx_campaign_id (campaign_id)',
            'KEY idx_user_id (user_id)',
            'KEY idx_payment_status (payment_status)',
            'KEY idx_donation_type (donation_type)'
        ]
    ];

        $this->database->createTable('ehxdo_donation', $columns, $options);
    }

    /**
     * Reverse the migration
     */
    public function down(): void
    {
        $this->database->dropTable('ehxdo_donation');
    }
}
