<?php

namespace EHXDonate\Database\Migrations;

use EHXDonate\Database\Database;

/**
 * Migration to create campaign table
 */
class CreateCampaignTable
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
            'post_id' => 'bigint(20) UNSIGNED NOT NULL',
            'short_description' => 'varchar(255) DEFAULT NULL',
            'goal_amount' => 'decimal(15,2) NOT NULL DEFAULT 0.00',
            'currency' => 'varchar(255) NOT NULL',
            'start_date' => 'date NOT NULL',
            'end_date' => 'date NOT NULL',
            'type' => "enum('campaign','support-me') NOT NULL DEFAULT 'campaign'",
            'is_p2p' => 'tinyint(1) NOT NULL DEFAULT 0',
            'template' => 'varchar(255) DEFAULT NULL',
            'header_image' => 'varchar(255) DEFAULT NULL',
            'visibility' => "enum('public','private','unlisted') NOT NULL DEFAULT 'public'",
            'categories' => 'json DEFAULT NULL',
            'tags' => 'json DEFAULT NULL',
            'settings' => 'json DEFAULT NULL',
            'created_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ];

        $options = [
            'primary_key' => 'id',
            'indexes' => [
                'KEY idx_post_id (post_id)',
                'KEY idx_type (type)',
                'KEY idx_visibility (visibility)',
                'KEY idx_start_date (start_date)',
                'KEY idx_end_date (end_date)'
            ]
        ];

        $this->database->createTable('ehxdo_campaigns', $columns, $options);
    }

    /**
     * Reverse the migration
     */
    public function down(): void
    {
        $this->database->dropTable('ehxdo_campaigns');
    }
}
