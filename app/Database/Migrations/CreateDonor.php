<?php

namespace EHXDonate\Database\Migrations;

use EHXDonate\Database\Database;

/**
 * Migration to create donor table
 */
class CreateDonorTable
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
            'campaign_id' => 'bigint(20) UNSIGNED DEFAULT NULL',
            'first_name' => 'varchar(191) DEFAULT NULL',
            'last_name' => 'varchar(191) DEFAULT NULL',
            'email' => 'varchar(191) DEFAULT NULL',
            'phone' => 'varchar(50) DEFAULT NULL',
            'address' => 'text DEFAULT NULL',
            'meta' => 'json DEFAULT NULL',
            'created_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ];

        $options = [
            'primary_key' => 'id',
            'indexes' => [
                'KEY idx_user_id (user_id)',
                'KEY idx_campaign_id (campaign_id)',
                'KEY idx_email (email)'
            ]
        ];

        $this->database->createTable('ehxdo_donor', $columns, $options);
    }

    /**
     * Reverse the migration
     */
    public function down(): void
    {
        $this->database->dropTable('ehxdo_donor');
    }
}
