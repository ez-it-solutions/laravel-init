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
    | Application Initialization Settings
    |--------------------------------------------------------------------------
    |
    | These settings control the behavior of the Laravel Initialization Utility
    | commands and provide default values for various initialization tasks.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Default Paths and Directories
    |--------------------------------------------------------------------------
    */
    'paths' => [
        // Directory where application stubs are stored
        'stubs' => resource_path('stubs'),
        
        // Directory for temporary files during initialization
        'temp' => storage_path('app/temp'),
        
        // Directory for logs specific to initialization processes
        'logs' => storage_path('logs/init'),
        
        // Directory for backups created during initialization
        'backups' => storage_path('app/backups'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Command Behavior Options
    |--------------------------------------------------------------------------
    */
    'commands' => [
        // Default options for app:init command
        'app_init' => [
            'skip_confirmation' => env('APP_INIT_SKIP_CONFIRMATION', false),
            'default_environment' => env('APP_INIT_DEFAULT_ENV', 'local'),
            'run_migrations' => env('APP_INIT_RUN_MIGRATIONS', true),
            'run_seeders' => env('APP_INIT_RUN_SEEDERS', false),
            'optimize_after_init' => env('APP_INIT_OPTIMIZE', true),
        ],
        
        // Default options for app:cleanup command
        'app_cleanup' => [
            'preserve_logs_days' => env('APP_CLEANUP_PRESERVE_LOGS_DAYS', 7),
            'backup_before_cleanup' => env('APP_CLEANUP_BACKUP_FIRST', true),
        ],
        
        // Default options for app:optimize command
        'app_optimize' => [
            'skip_config_cache' => env('APP_OPTIMIZE_SKIP_CONFIG', false),
            'skip_route_cache' => env('APP_OPTIMIZE_SKIP_ROUTES', false),
            'skip_view_cache' => env('APP_OPTIMIZE_SKIP_VIEWS', false),
        ],
        
        // Default options for app:serve command
        'app_serve' => [
            'default_host' => env('APP_SERVE_HOST', '127.0.0.1'),
            'start_port' => env('APP_SERVE_START_PORT', 8000),
            'max_attempts' => env('APP_SERVE_MAX_ATTEMPTS', 10),
            'open_browser' => env('APP_SERVE_OPEN_BROWSER', true),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Frontend Asset Configuration
    |--------------------------------------------------------------------------
    */
    'frontend' => [
        // Default frontend stack to install
        'default_stack' => env('APP_INIT_FRONTEND_STACK', 'vite'),
        
        // TailwindCSS configuration
        'tailwind' => [
            'install_by_default' => env('APP_INIT_INSTALL_TAILWIND', true),
            'with_plugins' => env('APP_INIT_TAILWIND_PLUGINS', true),
            'with_dark_mode' => env('APP_INIT_TAILWIND_DARK_MODE', true),
        ],
        
        // Node package manager preference
        'package_manager' => env('APP_INIT_PACKAGE_MANAGER', 'npm'), // npm, yarn, pnpm
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Configuration
    |--------------------------------------------------------------------------
    */
    'database' => [
        // Default character set and collation for database initialization
        'default_charset' => env('DB_DEFAULT_CHARSET', 'utf8mb4'),
        'default_collation' => env('DB_DEFAULT_COLLATION', 'utf8mb4_unicode_ci'),
        
        // Backup configuration
        'backup_retention_days' => env('APP_BACKUP_RETENTION_DAYS', 7),
        'max_backups' => env('APP_MAX_BACKUPS', 10),
        'backup_compression' => env('APP_BACKUP_COMPRESSION', 'gz'), // Options: gz, zip, none
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    */
    'security' => [
        // Whether to generate a new application key during initialization
        'generate_app_key' => env('APP_INIT_GENERATE_KEY', true),
        
        // Whether to set secure file permissions
        'secure_file_permissions' => env('APP_INIT_SECURE_PERMISSIONS', true),
        
        // Default file permission settings
        'file_permissions' => env('APP_INIT_FILE_PERMISSIONS', 0644),
        'directory_permissions' => env('APP_INIT_DIR_PERMISSIONS', 0755),
    ],

    /*
    |--------------------------------------------------------------------------
    | Status Command Configuration
    |--------------------------------------------------------------------------
    |
    | These settings control what the status command checks and displays
    |
    */
    'status' => [
        // Configuration files to check
        'config_files' => [
            'vite.config.js' => 'Vite configuration',
            'tailwind.config.js' => 'Tailwind CSS configuration',
            'postcss.config.js' => 'PostCSS configuration',
            '.env' => 'Environment configuration',
            'composer.json' => 'Composer configuration',
            'package.json' => 'NPM configuration',
        ],
        
        // Required Node packages to check
        'node_packages' => [
            'vite' => 'Vite build tool',
            'laravel-vite-plugin' => 'Laravel Vite integration',
            'tailwindcss' => 'Tailwind CSS framework',
            'postcss' => 'PostCSS processor',
            'autoprefixer' => 'PostCSS autoprefixer',
        ],
        
        // Optional Node packages to check
        'optional_node_packages' => [
            '@tailwindcss/forms' => 'Tailwind forms plugin',
            '@tailwindcss/typography' => 'Tailwind typography plugin',
            'prettier' => 'Code formatter',
            'prettier-plugin-tailwindcss' => 'Tailwind class sorter',
        ],
        
        // Required Composer packages to check
        'composer_packages' => [
            'laravel/framework' => 'Laravel Framework',
        ],
    ],
];
