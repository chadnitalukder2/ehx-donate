<?php

namespace EHXDonate\Database\Migrations;

use EHXDonate\Database\Database;

/**
 * Migration to create trips table
 */
class CreateTripsTable
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
            'title' => 'varchar(255) NOT NULL',
            'description' => 'text',
            'destination' => 'varchar(255) NOT NULL',
            'start_date' => 'date NOT NULL',
            'end_date' => 'date NOT NULL',
            'price' => 'decimal(10,2) NOT NULL DEFAULT 0.00',
            'status' => "enum('active','inactive') NOT NULL DEFAULT 'active'",
            'user_id' => 'bigint(20) UNSIGNED NOT NULL',
            'created_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ];

        $options = [
            'primary_key' => 'id',
            'indexes' => [
                'KEY idx_user_id (user_id)',
                'KEY idx_status (status)',
                'KEY idx_start_date (start_date)',
                'KEY idx_end_date (end_date)'
            ]
        ];

        $this->database->createTable('ehx_donate', $columns, $options);
    }

    /**
     * Reverse the migration
     */
    public function down(): void
    {
        $this->database->dropTable('trips');
    }
}
