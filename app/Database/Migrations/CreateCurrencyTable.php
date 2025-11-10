<?php

namespace EHXDonate\Database\Migrations;

use EHXDonate\Database\Database;

/**
 * Migration to create currency table
 */
class CreateCurrencyTable
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
            'name' => 'bigint(20) UNSIGNED NOT NULL',
            'symbol' => 'decimal(10,2) NOT NULL DEFAULT 0.00',
            'exchange_rate' => 'decimal(16,8) NOT NULL DEFAULT 1.00000000',
            'code' => 'varchar(255) DEFAULT NULL',
            // 'status' => "enum('pending','processed','failed') NOT NULL DEFAULT 'pending'",
            // 'payout_method' => "enum('bank_transfer','paypal','stripe') DEFAULT NULL",
            // 'transaction_id' => 'varchar(255) NOT NULL UNIQUE',
            'created_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP'
        ];

        $options = [
            'primary_key' => 'id',
            'indexes' => [
            ]
        ];

        $this->database->createTable('ehxdo_currency', $columns, $options);
    }

    /**
     * Reverse the migration
     */
    public function down(): void
    {
        $this->database->dropTable('ehxdo_currency');
    }
}
