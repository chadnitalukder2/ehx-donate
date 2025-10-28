<?php

namespace EHXDonate\Database\Migrations;

use EHXDonate\Database\Database;

/**
 * Migration to create activity log table
 */
class CreateActivityLogTable
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
            'status' => "varchar(20) NOT NULL DEFAULT 'info' COMMENT 'success / warning / failed / info'",
            'module_type' => "varchar(100) NOT NULL DEFAULT 'order' COMMENT 'Full Model Path'",
            'module_id' => 'bigint(20) UNSIGNED NOT NULL',
            'module_name' => "varchar(192) NOT NULL DEFAULT 'order' COMMENT 'donation / transaction / campaign / donor / payment_item'",
            'user_id' => 'bigint(20) UNSIGNED NOT NULL',
            'title' => 'varchar(100) NOT NULL',
            'content' => 'longtext',
            'read_status' => "varchar(20) NOT NULL DEFAULT 'unread' COMMENT 'read / unread'",
            'created_by' => "varchar(100) NOT NULL DEFAULT 'FLUENT-RAISE-BOT' COMMENT 'FLUENT-RAISE-BOT / username'",
            'created_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ];

        $options = [
            'primary_key' => 'id',
            'indexes' => [
                'KEY idx_user_id (user_id)',
                'KEY idx_status (status)',
                'KEY idx_module_id (module_id)',
                'KEY idx_read_status (read_status)'
            ]
        ];

        $this->database->createTable('ehxdo_activity_log', $columns, $options);
    }

    /**
     * Reverse the migration
     */
    public function down(): void
    {
        $this->database->dropTable('ehxdo_activity_log');
    }
}
