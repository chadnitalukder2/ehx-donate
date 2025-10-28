<?php

namespace EHXDonate\Database\Migrations;

use EHXDonate\Database\Database;

/**
 * Migration to create a meta table
 */
class CreateMetaTable
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
            'meta_group' => 'varchar(255) DEFAULT NULL',
            'option_id' => 'bigint(20) UNSIGNED DEFAULT NULL',
            'meta_key' => 'varchar(255) DEFAULT NULL',
            'meta_value' => 'text DEFAULT NULL',
            'campaign_id' => 'bigint(20) UNSIGNED DEFAULT NULL',
            'created_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ];

        $options = [
            'primary_key' => 'id',
            'indexes' => [
                'KEY idx_meta_group (meta_group)',
                'KEY idx_option_id (option_id)',
                'KEY idx_campaign_id (campaign_id)',
                'KEY idx_meta_key (meta_key)'
            ]
        ];

        $this->database->createTable('ehxdo_meta', $columns, $options);
    }

    /**
     * Reverse the migration
     */
    public function down(): void
    {
        $this->database->dropTable('ehxdo_meta');
    }
}
