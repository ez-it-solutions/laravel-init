<?php

/**
 * Part of the Laravel Init package by EZ IT Solutions.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the terms of the MIT license https://opensource.org/licenses/MIT
 *
 * @version    1.0.0
 *
 * @author     Chris Hultberg <chrishultberg@ez-it-solutions.com>
 * @license    MIT https://opensource.org/licenses/MIT
 * @copyright  (c) 2025, EZ IT Solutions
 *
 * @see       https://www.ez-it-solutions.com
 */

return [
    /*
    |--------------------------------------------------------------------------
    | EZ IT Solutions Laravel Init variables
    |--------------------------------------------------------------------------
    |
    | Configuration options for the Laravel Init package database tools
    |
    */

    'ez-it-solutions' => [
        // Backup configuration
        'backup_dir' => env('APP_BACKUP_DIR', storage_path().'/app/dumps'),
        'backup_retention_days' => env('APP_BACKUP_RETENTION_DAYS', 7),
        'max_backups' => env('APP_MAX_BACKUPS', 10),
        'backup_compression' => env('APP_BACKUP_COMPRESSION', 'gz'), // Options: gz, zip, none
        'backup_notification_email' => env('APP_BACKUP_NOTIFICATION_EMAIL'),
        
        // Database initialization configuration
        'default_charset' => env('DB_DEFAULT_CHARSET', 'utf8mb4'),
        'default_collation' => env('DB_DEFAULT_COLLATION', 'utf8mb4_unicode_ci'),
        'force_charset' => env('DB_FORCE_CHARSET', false),
        
        // Cloud storage options for backups
        'backup_cloud_storage' => env('APP_BACKUP_CLOUD_STORAGE', 'local'), // Options: local, s3, etc.
        'backup_cloud_path' => env('APP_BACKUP_CLOUD_PATH', 'backups'),
        
        // Database tools options
        'mysql_path' => env('MYSQL_PATH'), // Custom path to MySQL binaries if not in PATH
        'mysqldump_path' => env('MYSQLDUMP_PATH'), // Custom path to mysqldump if not in PATH
        'pg_dump_path' => env('PG_DUMP_PATH'), // Custom path to pg_dump if not in PATH
        
        // Scheduling options
        'auto_backup_frequency' => env('APP_AUTO_BACKUP_FREQUENCY', 'daily'), // Options: hourly, daily, weekly
        'auto_backup_time' => env('APP_AUTO_BACKUP_TIME', '01:00'), // Time for scheduled backups (24h format)
        
        // Database monitoring
        'monitor_db_size' => env('APP_MONITOR_DB_SIZE', true),
        'db_size_warning_threshold' => env('APP_DB_SIZE_WARNING_THRESHOLD', 1000), // Size in MB
    ],
];