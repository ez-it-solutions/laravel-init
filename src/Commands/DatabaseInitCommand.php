<?php

/**
 * DatabaseInitCommand
 * 
 * Initializes and configures the database for Laravel applications with proper
 * character set, collation, and optional migrations and seeding.
 * 
 * @category    Commands
 * @package     Ez_IT_Solutions\App_Init
 * @author      Chris Hultberg <chrishultberg@ez-it-solutions.com>
 * @see         https://www.Ez-IT-Solutions.com
 * @license     MIT
 * @link        https://github.com/ez-it-solutions/laravel-init
 * @copyright   Copyright (c) 2025 EZ IT Solutions
 * @version     1.0.0
 * @since       2025-09-07
 */

namespace Ez_IT_Solutions\DatabaseTools\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PDO;
use PDOException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Ez_IT_Solutions\AppInit\Commands\CheckRequirementsCommand;

/**
 * Handles database initialization and configuration.
 * 
 * This command performs the following tasks:
 * - Creates the database if it doesn't exist
 * - Sets the proper character set and collation
 * - Runs migrations (optional)
 * - Seeds the database (optional)
 * - Provides detailed error reporting for connection issues
 */
class DatabaseInitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:init
                            {--m|migrate : Run migrations after initialization}
                            {--s|seed : Seed the database after migrations}
                            {--f|force : Force the operation to run when in production}
                            {--show-config : Show database configuration and exit}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize the database and optionally run migrations and seeders';

    /**
     * Database configuration details
     *
     * @var array
     */
    protected $dbConfig;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->loadDatabaseConfig();

        if ($this->option('show-config')) {
            return $this->showConfig();
        }

        // Run requirements check first
        if (!$this->checkRequirements()) {
            return Command::FAILURE;
        }

        if (!$this->confirmToProceed()) {
            return Command::FAILURE;
        }

        if (!$this->testConnection()) {
            return $this->suggestTroubleshooting();
        }

        if (!$this->createDatabase()) {
            return Command::FAILURE;
        }

        $this->runMigrations();
        $this->runSeeders();

        $this->newLine();
        $this->info('✅ Database initialization completed successfully!');
        $this->info('You can now access your application at: http://localhost:8000');

        return Command::SUCCESS;
    }

    /**
     * Load and validate database configuration
     */
    protected function loadDatabaseConfig(): void
    {
        $connection = config('database.default');
        $this->dbConfig = [
            'connection' => $connection,
            'driver' => config("database.connections.{$connection}.driver"),
            'host' => config("database.connections.{$connection}.host"),
            'port' => config("database.connections.{$connection}.port", 3306),
            'database' => config("database.connections.{$connection}.database"),
            'username' => config("database.connections.{$connection}.username"),
            'password' => config("database.connections.{$connection}.password"),
            'charset' => config("database.connections.{$connection}.charset", 'utf8mb4'),
            'collation' => config("database.connections.{$connection}.collation", 'utf8mb4_unicode_ci'),
        ];
    }

    /**
     * Display database configuration
     */
    protected function showConfig(): int
    {
        $this->info('Database Configuration:');
        $this->table(
            ['Parameter', 'Value'],
            collect($this->dbConfig)
                ->reject(fn ($value) => empty($value))
                ->map(fn ($value, $key) => [
                    'Parameter' => $key,
                    'Value' => $key === 'password' ? str_repeat('*', 8) : $value,
                ])
        );

        return Command::SUCCESS;
    }

    /**
     * Test database connection
     */
    protected function testConnection(): bool
    {
        $this->info('Testing database connection...');

        try {
            config(['database.connections.mysql.database' => null]);
            
            $dsn = "mysql:host={$this->dbConfig['host']};port={$this->dbConfig['port']}";
            $pdo = new PDO(
                $dsn,
                $this->dbConfig['username'],
                $this->dbConfig['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            $this->info('✅ Successfully connected to database server');
            return true;

        } catch (PDOException $e) {
            $this->error("❌ Failed to connect to database server: " . $e->getMessage());
            $this->line('');
            $this->warn('Please check your database configuration in .env:');
            $this->line('DB_CONNECTION=mysql');
            $this->line('DB_HOST=' . $this->dbConfig['host']);
            $this->line('DB_PORT=' . $this->dbConfig['port']);
            $this->line('DB_DATABASE=' . $this->dbConfig['database']);
            $this->line('DB_USERNAME=' . $this->dbConfig['username']);
            $this->line('DB_PASSWORD=' . ($this->dbConfig['password'] ? '********' : 'null'));
            
            return false;
        }
    }

    /**
     * Create the database if it doesn't exist
     */
    protected function createDatabase(): bool
    {
        $this->info("Initializing database: {$this->dbConfig['database']}");
        $this->info("Character Set: {$this->dbConfig['charset']}");
        $this->info("Collation: {$this->dbConfig['collation']}");

        try {
            $dsn = "mysql:host={$this->dbConfig['host']};port={$this->dbConfig['port']}";
            $pdo = new PDO(
                $dsn,
                $this->dbConfig['username'],
                $this->dbConfig['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            $pdo->exec(
                "CREATE DATABASE IF NOT EXISTS `{$this->dbConfig['database']}` " .
                "CHARACTER SET {$this->dbConfig['charset']} " .
                "COLLATE {$this->dbConfig['collation']};"
            );

            $this->info("✅ Database '{$this->dbConfig['database']}' initialized successfully");
            
            // Set the database back in config
            config(['database.connections.mysql.database' => $this->dbConfig['database']]);
            
            return true;
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to initialize database: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Run database migrations
     */
    protected function runMigrations(): void
    {
        if (!$this->option('migrate') && !$this->option('seed')) {
            return;
        }

        $this->info('Running database migrations...');
        
        try {
            $this->call('migrate', [
                '--force' => $this->option('force'),
            ]);
            $this->info('✅ Database migrations completed successfully');
        } catch (\Exception $e) {
            $this->error("❌ Database migrations failed: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Run database seeders
     */
    protected function runSeeders(): void
    {
        if (!$this->option('seed')) {
            return;
        }

        $this->info('Seeding database...');
        
        try {
            $this->call('db:seed', [
                '--force' => $this->option('force'),
            ]);
            $this->info('✅ Database seeded successfully');
        } catch (\Exception $e) {
            $this->error("❌ Database seeding failed: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Check system requirements
     *
     * @return bool
     */
    protected function checkRequirements(): bool
    {
        $this->info('🔍 Checking system requirements...');
        
        try {
            // Resolve the command through the application container
            $requirementsCommand = $this->getApplication()->find(CheckRequirementsCommand::class);
            
            // Create input/output for the command
            $input = new ArrayInput([]);
            $output = new BufferedOutput();
            
            // Run the requirements check
            $result = $requirementsCommand->run($input, $output);
            
            // Output the buffered output
            $this->line($output->fetch());
            
            if ($result !== Command::SUCCESS) {
                $this->newLine();
                $this->warn('⚠️  Some requirements are not met. You may encounter issues during installation.');
                
                if (!$this->confirm('Continue with database initialization?', true)) {
                    $this->info('Database initialization cancelled.');
                    return false;
                }
            }
            
            $this->newLine();
            return true;
            
        } catch (\Exception $e) {
            $this->warn('⚠️  Could not run requirements check: ' . $e->getMessage());
            $this->warn('Skipping system requirements check...');
            return true; // Continue with initialization
        }
    }

    /**
     * Show troubleshooting suggestions
     */
    protected function suggestTroubleshooting(): int
    {
        $this->newLine(2);
        $this->error('❌ Database connection failed. Please check the following:');
        $this->newLine();
        
        $this->line('1. Verify your database server is running');
        $this->line('2. Check your database credentials in .env');
        $this->line('3. Ensure your database user has proper permissions');
        $this->line('4. Check if the database host is accessible');
        $this->line('5. Verify the database port is correct');
        
        $this->newLine();
        $this->info('You can check your current configuration with:');
        $this->line('  php artisan db:init --show-config');
        
        $this->newLine();
        $this->info('For more details, please refer to the documentation at:');
        $this->line('  https://github.com/your-org/jc-portal/blob/main/DOCS/README.md#troubleshooting');
        
        return Command::FAILURE;
    }
    
    /**
     * Confirm the operation with the user if in production environment
     */
    protected function confirmToProceed(): bool
    {
        if (app()->environment('local') || $this->option('force')) {
            return true;
        }

        $this->warn('Application In Production!');
        $this->warn('This will initialize the database and may modify data.');

        return $this->confirm('Do you really wish to run this command?', false);
    }
}
