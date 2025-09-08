<?php

/**
 * AppInit Command
 * 
 * Initializes Laravel applications by performing all necessary setup steps
 * including system checks, database initialization, and application preparation.
 * 
 * @category    Commands
 * @package     Ez_IT_Solutions\App_Init
 * @author      Chris Hultberg <chrishultberg@ez-it-solutions.com>
 * @website     https://www.Ez-IT-Solutions.com
 * @license     MIT
 * @link        https://github.com/ez-it-solutions/laravel-init
 * @copyright   Copyright (c) 2025 EZ IT Solutions
 * @version     1.0.0
 * @since       2025-09-07
 */


namespace Ez_IT_Solutions\AppInit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Ez_IT_Solutions\DatabaseTools\Commands\DatabaseInitCommand;

/**
 * Initializes Laravel applications.
 * 
 * This command serves as the main entry point for setting up the application.
 * It orchestrates the following steps:
 * 1. System requirements check
 * 2. Database initialization
 * 3. Application preparation
 * 4. Environment optimization
 * 
 * The command provides detailed progress feedback and handles errors gracefully.
 */
class AppInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init
                            {--skip-requirements : Skip system requirements check}
                            {--skip-db : Skip database initialization}
                            {--skip-prepare : Skip application preparation}
                            {--skip-optimize : Skip application optimization}
                            {--force : Force the operation to run when in production}
                            {--show-config : Show database configuration and exit}
                            {--migrate : Run database migrations}
                            {--seed : Seed the database with records} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize Laravel applications with all required setup steps';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info('🚀 Laravel Initialization');
        $this->info('========================');
        $this->newLine();

        // Show config and exit if requested
        if ($this->option('show-config')) {
            return $this->call(DatabaseInitCommand::class, ['--show-config' => true]);
        }

        // Check system requirements
        if (!$this->option('skip-requirements') && !$this->checkRequirements()) {
            return CommandAlias::FAILURE;
        }

        // Initialize database
        if (!$this->option('skip-db') && !$this->initializeDatabase()) {
            return CommandAlias::FAILURE;
        }

        // Prepare application
        if (!$this->option('skip-prepare') && !$this->prepareApplication()) {
            return CommandAlias::FAILURE;
        }

        // Optimize application
        if (!$this->option('skip-optimize') && !$this->optimizeApplication()) {
            return CommandAlias::FAILURE;
        }

        $this->newLine(2);
        $this->info('✅ Application has been initialized successfully!');
        $this->info('   You can now access your application at: http://localhost:8000');
        $this->newLine();

        return CommandAlias::SUCCESS;
    }

    /**
     * Check system requirements.
     *
     * @return bool
     */
    protected function checkRequirements(): bool
    {
        $this->info('🔍 Checking system requirements...');
        
        $result = $this->call(CheckRequirementsCommand::class);
        
        if ($result !== CommandAlias::SUCCESS) {
            $this->newLine();
            $this->warn('⚠️  Some requirements are not met. You may encounter issues during installation.');
            
            if (!$this->confirm('Continue with initialization?', true)) {
                $this->info('Initialization cancelled.');
                return false;
            }
        }
        
        return true;
    }

    /**
     * Initialize the database.
     *
     * @return bool
     */
    protected function initializeDatabase(): bool
    {
        $this->info('💾 Initializing database...');
        
        $options = [
            '--migrate' => $this->option('migrate'),
            '--seed' => $this->option('seed'),
            '--force' => $this->option('force'),
        ];
        
        $result = $this->call(DatabaseInitCommand::class, $options);
        
        if ($result !== CommandAlias::SUCCESS) {
            $this->error('❌ Database initialization failed');
            return false;
        }
        
        return true;
    }

    /**
     * Prepare the application.
     *
     * @return bool
     */
    /**
     * Set file and directory permissions.
     *
     * @return void
     */
    protected function setPermissions(): void
    {
        $this->info('🔒 Setting file and directory permissions...');
        
        $directories = [
            storage_path(),
            base_path('bootstrap/cache'),
            base_path('storage/framework/views'),
            base_path('storage/framework/sessions'),
            base_path('storage/framework/cache'),
            base_path('storage/logs'),
        ];
        
        $files = [
            base_path('.env'),
            base_path('storage/oauth-private.key'),
            base_path('storage/oauth-public.key'),
        ];
        
        // Set directory permissions (755 for directories, 644 for files on Unix-like systems)
        foreach ($directories as $directory) {
            if (is_dir($directory)) {
                if (PHP_OS_FAMILY === 'Windows') {
                    // On Windows, we can't set permissions in the same way
                    $this->line("  ✓ Directory: {$directory} (Windows - ensure proper permissions are set)");
                } else {
                    // On Unix-like systems, set 755 for directories
                    if (@chmod($directory, 0755)) {
                        $this->line("  ✓ Directory: {$directory} (0755)");
                    } else {
                        $this->warn("  ⚠️  Could not set permissions for: {$directory}");
                    }
                }
            }
        }
        
        // Set file permissions
        foreach ($files as $file) {
            if (file_exists($file)) {
                if (PHP_OS_FAMILY === 'Windows') {
                    // On Windows, we can't set permissions in the same way
                    $this->line("  ✓ File: {$file} (Windows - ensure proper permissions are set)");
                } else {
                    // On Unix-like systems, set 644 for files
                    if (@chmod($file, 0644)) {
                        $this->line("  ✓ File: {$file} (0644)");
                    } else {
                        $this->warn("  ⚠️  Could not set permissions for: {$file}");
                    }
                }
            }
        }
        
        // Special handling for storage and bootstrap/cache
        if (PHP_OS_FAMILY !== 'Windows') {
            $writableDirs = [
                storage_path(),
                base_path('bootstrap/cache'),
            ];
            
            foreach ($writableDirs as $dir) {
                if (is_dir($dir) && !is_writable($dir)) {
                    $this->warn("  ⚠️  Directory is not writable: {$dir}");
                    $this->warn("      Run: chmod -R 775 {$dir}");
                }
            }
        }
    }
    
    /**
     * Create necessary directories if they don't exist.
     *
     * @return void
     */
    protected function ensureDirectoriesExist(): void
    {
        $directories = [
            storage_path('app/public'),
            storage_path('framework/views'),
            storage_path('framework/sessions'),
            storage_path('framework/cache'),
            storage_path('logs'),
            base_path('bootstrap/cache'),
        ];
        
        foreach ($directories as $directory) {
            if (!is_dir($directory)) {
                if (mkdir($directory, 0755, true)) {
                    $this->line("  ✓ Created directory: {$directory}");
                } else {
                    $this->warn("  ⚠️  Could not create directory: {$directory}");
                }
            }
        }
    }
    
    protected function prepareApplication(): bool
    {
        $this->info('🔧 Preparing application...');
        
        // Ensure required directories exist
        $this->info('📂 Ensuring required directories exist...');
        $this->ensureDirectoriesExist();
        
        // Set file and directory permissions
        $this->setPermissions();
        
        // Create storage symlink if it doesn't exist
        $this->info('🔗 Creating storage symlink...');
        $symlink = public_path('storage');
        
        if (!file_exists($symlink)) {
            try {
                $this->call('storage:link');
                $this->info('  ✓ Storage symlink created successfully');
            } catch (\Exception $e) {
                $this->warn('  ⚠️  Could not create storage symlink: ' . $e->getMessage());
                $this->warn('      You may need to create it manually with: php artisan storage:link');
            }
        } else {
            $this->info('  ✓ Storage symlink already exists');
        }
        
        $environment = app()->environment();
        $options = [
            'environment' => $environment,
            '--clean' => true,
            '--permissions' => true,
            '--optimize' => $this->option('optimize'),
            '--force' => $this->option('force'),
        ];
        
        $result = $this->call(AppPrepare::class, $options);
        
        if ($result !== CommandAlias::SUCCESS) {
            $this->error('❌ Application preparation failed');
            return false;
        }
        
        $this->info('✅ Application preparation completed successfully');
        return true;
    }

    /**
     * Optimize the application.
     *
     * @return bool
     */
    protected function optimizeApplication(): bool
    {
        $this->info('⚡ Optimizing application...');
        
        $result = $this->call(AppOptimize::class, [
            '--force' => $this->option('force'),
        ]);
        
        if ($result !== CommandAlias::SUCCESS) {
            $this->error('❌ Application optimization failed');
            return false;
        }
        
        return true;
    }
}
