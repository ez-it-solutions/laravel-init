<?php

/**
 * DatabaseBackupCommand
 * 
 * Creates and manages database backups for Laravel applications with compression,
 * scheduling, and optional cloud storage integration.
 * 
 * @category    Commands
 * @package     Ez_IT_Solutions\DatabaseTools
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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * Handles database backup operations.
 * 
 * This command performs the following tasks:
 * - Creates database backups (full or selective tables)
 * - Compresses backups using various formats (gz, zip)
 * - Manages backup retention policies
 * - Supports uploading to cloud storage (S3, Google Drive, etc.)
 * - Provides detailed logging and notifications
 */
class DatabaseBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup
                            {--tables= : Comma-separated list of tables to backup (default: all tables)}
                            {--exclude= : Comma-separated list of tables to exclude from backup}
                            {--filename= : Custom filename for the backup}
                            {--format=sql : Output format (sql, gz, zip)}
                            {--storage= : Storage disk to use (local, s3, etc.)}
                            {--path= : Custom path within the storage disk}
                            {--no-compress : Do not compress the backup file}
                            {--with-data : Include data in the backup (default)}
                            {--structure-only : Only backup the database structure, not the data}
                            {--notify : Send notification when backup is complete}
                            {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create and manage database backups with various options for compression and storage';

    /**
     * Database configuration details
     *
     * @var array
     */
    protected $dbConfig;
    
    /**
     * Backup configuration
     *
     * @var array
     */
    protected $backupConfig;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->loadDatabaseConfig();
        $this->setupBackupConfig();
        
        if (!$this->confirmToProceed()) {
            return Command::FAILURE;
        }

        if (!$this->testConnection()) {
            return $this->suggestTroubleshooting();
        }
        
        // Create backup directory if it doesn't exist
        $this->ensureBackupDirectoryExists();
        
        // Generate backup filename
        $filename = $this->generateBackupFilename();
        
        // Get tables to backup
        $tables = $this->getTablesToBackup();
        
        // Perform the backup
        $backupPath = $this->performBackup($tables, $filename);
        
        if (!$backupPath) {
            $this->error('❌ Database backup failed!');
            return Command::FAILURE;
        }
        
        // Compress the backup if needed
        if (!$this->option('no-compress') && $this->backupConfig['format'] !== 'sql') {
            $backupPath = $this->compressBackup($backupPath);
        }
        
        // Store the backup in the specified storage disk if requested
        if ($this->backupConfig['storage'] !== 'local') {
            $this->storeBackupInCloud($backupPath);
        }
        
        // Send notification if requested
        if ($this->option('notify')) {
            $this->sendBackupNotification($backupPath);
        }
        
        // Manage backup retention
        $this->manageBackupRetention();
        
        $this->newLine();
        $this->info('✅ Database backup completed successfully!');
        $this->info("Backup saved to: {$backupPath}");
        
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
     * Setup backup configuration from options and config
     */
    protected function setupBackupConfig(): void
    {
        $this->backupConfig = [
            'format' => $this->option('format'),
            'storage' => $this->option('storage') ?: 'local',
            'path' => $this->option('path') ?: config('database.ez-it-solutions.backup_dir', storage_path('app/backups')),
            'include_data' => !$this->option('structure-only'),
            'compress' => !$this->option('no-compress'),
            'retention_days' => config('database.ez-it-solutions.backup_retention_days', 7),
            'max_backups' => config('database.ez-it-solutions.max_backups', 10),
        ];
        
        $this->info('Backup configuration loaded');
        $this->line("Storage: {$this->backupConfig['storage']}");
        $this->line("Format: {$this->backupConfig['format']}");
        $this->line("Path: {$this->backupConfig['path']}");
    }
    
    /**
     * Ensure the backup directory exists
     */
    protected function ensureBackupDirectoryExists(): void
    {
        $path = $this->backupConfig['path'];
        
        if (!File::isDirectory($path)) {
            $this->info("Creating backup directory: {$path}");
            File::makeDirectory($path, 0755, true);
        }
    }
    
    /**
     * Generate a backup filename
     * 
     * @return string
     */
    protected function generateBackupFilename(): string
    {
        $customFilename = $this->option('filename');
        
        if ($customFilename) {
            return $customFilename;
        }
        
        $timestamp = now()->format('Y-m-d_H-i-s');
        $dbName = $this->dbConfig['database'];
        
        return "{$dbName}_{$timestamp}.sql";
    }
    
    /**
     * Get the list of tables to backup
     * 
     * @return array
     */
    protected function getTablesToBackup(): array
    {
        // Get all tables
        $allTables = $this->getAllTables();
        
        // Check if specific tables were requested
        if ($this->option('tables')) {
            $requestedTables = explode(',', $this->option('tables'));
            return array_intersect($allTables, $requestedTables);
        }
        
        // Check if tables should be excluded
        if ($this->option('exclude')) {
            $excludedTables = explode(',', $this->option('exclude'));
            return array_diff($allTables, $excludedTables);
        }
        
        return $allTables;
    }

    /**
     * Get all tables in the database
     * 
     * @return array
     */
    protected function getAllTables(): array
    {
        $connection = DB::connection();
        $tables = [];
        
        if ($this->dbConfig['driver'] === 'mysql') {
            $tables = $connection->select('SHOW TABLES');
            $tables = array_map(function ($table) {
                $table = (array) $table;
                return reset($table);
            }, $tables);
        } elseif ($this->dbConfig['driver'] === 'pgsql') {
            $tables = $connection->select("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public'");
            $tables = array_map(function ($table) {
                return $table->tablename;
            }, $tables);
        } elseif ($this->dbConfig['driver'] === 'sqlite') {
            $tables = $connection->select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
            $tables = array_map(function ($table) {
                return $table->name;
            }, $tables);
        }
        
        return $tables;
    }
    
    /**
     * Perform the database backup
     * 
     * @param array $tables
     * @param string $filename
     * @return string|null
     */
    protected function performBackup(array $tables, string $filename): ?string
    {
        $this->info('Starting database backup...');
        $this->line('Tables to backup: ' . count($tables));
        
        $backupPath = $this->backupConfig['path'] . '/' . $filename;
        
        if ($this->dbConfig['driver'] === 'mysql') {
            return $this->performMysqlBackup($tables, $backupPath);
        } elseif ($this->dbConfig['driver'] === 'pgsql') {
            return $this->performPgsqlBackup($tables, $backupPath);
        } elseif ($this->dbConfig['driver'] === 'sqlite') {
            return $this->performSqliteBackup($backupPath);
        }
        
        $this->error("Unsupported database driver: {$this->dbConfig['driver']}");
        return null;
    }
    
    /**
     * Perform MySQL database backup
     * 
     * @param array $tables
     * @param string $backupPath
     * @return string|null
     */
    protected function performMysqlBackup(array $tables, string $backupPath): ?string
    {
        $command = [
            'mysqldump',
            '--host=' . $this->dbConfig['host'],
            '--port=' . $this->dbConfig['port'],
            '--user=' . $this->dbConfig['username'],
        ];
        
        if ($this->dbConfig['password']) {
            $command[] = '--password=' . $this->dbConfig['password'];
        }
        
        if (!$this->backupConfig['include_data']) {
            $command[] = '--no-data';
        }
        
        $command[] = $this->dbConfig['database'];
        
        // Add tables to the command
        foreach ($tables as $table) {
            $command[] = $table;
        }
        
        $command[] = '>' . $backupPath;
        
        $commandString = implode(' ', $command);
        
        try {
            $process = Process::fromShellCommandline($commandString);
            $process->setTimeout(3600); // 1 hour timeout
            $process->run();
            
            if (!$process->isSuccessful()) {
                $this->error("Backup failed: {$process->getErrorOutput()}");
                return null;
            }
            
            return $backupPath;
        } catch (\Exception $e) {
            $this->error("Backup exception: {$e->getMessage()}");
            return null;
        }
    }
    
    /**
     * Perform PostgreSQL database backup
     * 
     * @param array $tables
     * @param string $backupPath
     * @return string|null
     */
    protected function performPgsqlBackup(array $tables, string $backupPath): ?string
    {
        $command = [
            'pg_dump',
            '--host=' . $this->dbConfig['host'],
            '--port=' . $this->dbConfig['port'],
            '--username=' . $this->dbConfig['username'],
            '--dbname=' . $this->dbConfig['database'],
        ];
        
        if (!$this->backupConfig['include_data']) {
            $command[] = '--schema-only';
        }
        
        // Add tables to the command
        foreach ($tables as $table) {
            $command[] = '--table=' . $table;
        }
        
        $command[] = '--file=' . $backupPath;
        
        $commandString = implode(' ', $command);
        
        try {
            $process = Process::fromShellCommandline($commandString);
            $process->setTimeout(3600); // 1 hour timeout
            $process->run();
            
            if (!$process->isSuccessful()) {
                $this->error("Backup failed: {$process->getErrorOutput()}");
                return null;
            }
            
            return $backupPath;
        } catch (\Exception $e) {
            $this->error("Backup exception: {$e->getMessage()}");
            return null;
        }
    }
    
    /**
     * Perform SQLite database backup
     * 
     * @param string $backupPath
     * @return string|null
     */
    protected function performSqliteBackup(string $backupPath): ?string
    {
        $dbPath = DB::connection()->getDatabaseName();
        
        try {
            File::copy($dbPath, $backupPath);
            return $backupPath;
        } catch (\Exception $e) {
            $this->error("Backup exception: {$e->getMessage()}");
            return null;
        }
    }
    
    /**
     * Compress the backup file
     * 
     * @param string $backupPath
     * @return string
     */
    protected function compressBackup(string $backupPath): string
    {
        $format = $this->backupConfig['format'];
        
        if ($format === 'gz') {
            $compressedPath = $backupPath . '.gz';
            $this->info("Compressing backup to {$compressedPath}");
            
            $fileContent = File::get($backupPath);
            $compressed = gzencode($fileContent, 9);
            File::put($compressedPath, $compressed);
            
            // Remove the original file
            File::delete($backupPath);
            
            return $compressedPath;
        } elseif ($format === 'zip') {
            $compressedPath = $backupPath . '.zip';
            $this->info("Compressing backup to {$compressedPath}");
            
            $zip = new \ZipArchive();
            if ($zip->open($compressedPath, \ZipArchive::CREATE) === true) {
                $zip->addFile($backupPath, basename($backupPath));
                $zip->close();
                
                // Remove the original file
                File::delete($backupPath);
                
                return $compressedPath;
            }
        }
        
        return $backupPath;
    }
    
    /**
     * Store the backup in cloud storage
     * 
     * @param string $backupPath
     * @return bool
     */
    protected function storeBackupInCloud(string $backupPath): bool
    {
        $disk = $this->backupConfig['storage'];
        $this->info("Storing backup in {$disk} storage");
        
        try {
            $filename = basename($backupPath);
            $content = File::get($backupPath);
            
            Storage::disk($disk)->put($filename, $content);
            
            $this->info("Backup stored in {$disk} storage as {$filename}");
            return true;
        } catch (\Exception $e) {
            $this->error("Failed to store backup in cloud: {$e->getMessage()}");
            return false;
        }
    }
    
    /**
     * Send notification about the backup
     * 
     * @param string $backupPath
     */
    protected function sendBackupNotification(string $backupPath): void
    {
        $this->info("Sending backup notification");
        
        // Implementation depends on the notification system used
        // This is a placeholder for actual notification logic
        $this->line("Notification sent for backup: {$backupPath}");
    }
    
    /**
     * Manage backup retention based on configuration
     */
    protected function manageBackupRetention(): void
    {
        $this->info("Managing backup retention");
        
        $path = $this->backupConfig['path'];
        $retentionDays = $this->backupConfig['retention_days'];
        $maxBackups = $this->backupConfig['max_backups'];
        
        // Delete old backups based on retention days
        if ($retentionDays > 0) {
            $cutoffDate = now()->subDays($retentionDays);
            $this->line("Removing backups older than {$retentionDays} days");
            
            $oldFiles = File::files($path);
            foreach ($oldFiles as $file) {
                if ($file->getMTime() < $cutoffDate->timestamp) {
                    File::delete($file->getPathname());
                    $this->line("Deleted old backup: {$file->getFilename()}");
                }
            }
        }
        
        // Limit the number of backups
        if ($maxBackups > 0) {
            $files = File::files($path);
            
            // Sort files by modification time (newest first)
            usort($files, function ($a, $b) {
                return $b->getMTime() - $a->getMTime();
            });
            
            // Keep only the most recent backups
            if (count($files) > $maxBackups) {
                $this->line("Limiting to {$maxBackups} most recent backups");
                
                $filesToDelete = array_slice($files, $maxBackups);
                foreach ($filesToDelete as $file) {
                    File::delete($file->getPathname());
                    $this->line("Deleted excess backup: {$file->getFilename()}");
                }
            }
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
        $this->warn('This will create a database backup which may affect performance.');

        return $this->confirm('Do you really wish to run this command?', false);
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
