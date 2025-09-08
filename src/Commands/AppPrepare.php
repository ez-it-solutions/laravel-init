<?php

/**
 * AppPrepare Command
 * 
 * Prepares Laravel applications for different environments by configuring
 * the necessary settings, directories, and permissions.
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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Ez_IT_Solutions\AppInit\Commands\AppOptimize;

/**
 * Prepares the application for a specific environment.
 * 
 * This command handles the preparation of Laravel applications for different environments
 * by performing tasks such as:
 * - Setting up directory structures
 * - Configuring environment-specific settings
 * - Managing file permissions
 * - Cleaning up unnecessary files
 */
class AppPrepare extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:prepare
                            {environment=development : The environment to prepare for (development or production)}
                            {--clean : Remove all unnecessary files and directories}
                            {--permissions : Set appropriate file and directory permissions}
                            {--optimize : Run optimization commands for the target environment}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepare the application for different environments (development or production)';

    /**
     * Files and directories to remove in production environment
     */
    protected $productionRemoveList = [
        // Development files
        '.git',
        '.github',
        '.gitattributes',
        '.gitignore',
        'phpunit.xml',
        'tests',
        'phpstan.neon',
        '.editorconfig',
        '.env.example',
        'README.md',
        'CHANGELOG.md',
        'CONTRIBUTING.md',
        'docker-compose.yml',
        'Dockerfile',
        'package-lock.json',
        'yarn.lock',
        'webpack.mix.js',
        'vite.config.js.bak',
        
        // Node modules (after assets are built)
        'node_modules',
    ];

    /**
     * Files and directories to remove in development environment
     */
    protected $developmentRemoveList = [
        // Compiled assets (will be rebuilt)
        'public/build',
        'public/hot',
        'bootstrap/cache/*.php',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $environment = $this->argument('environment');
        
        if (!in_array($environment, ['development', 'production'])) {
            $this->error("Invalid environment: {$environment}. Must be 'development' or 'production'.");
            return Command::FAILURE;
        }
        
        $this->info("Preparing application for {$environment} environment...");
        
        // Clean unnecessary files if requested
        if ($this->option('clean')) {
            $this->cleanFiles($environment);
        }
        
        // Set appropriate permissions if requested
        if ($this->option('permissions')) {
            $this->setPermissions($environment);
        }
        
        // Run optimization commands if requested
        if ($this->option('optimize')) {
            $this->optimizeForEnvironment($environment);
        }
        
        $this->info("Application prepared for {$environment} environment successfully!");
        
        return Command::SUCCESS;
    }
    
    /**
     * Clean unnecessary files for the specified environment
     *
     * @param string $environment
     * @return void
     */
    protected function cleanFiles($environment)
    {
        $this->info("Cleaning unnecessary files for {$environment} environment...");
        
        $removeList = $environment === 'production' 
            ? $this->productionRemoveList 
            : $this->developmentRemoveList;
        
        foreach ($removeList as $path) {
            $fullPath = base_path($path);
            
            if (File::exists($fullPath)) {
                $this->line("Removing: {$path}");
                
                if (File::isDirectory($fullPath)) {
                    File::deleteDirectory($fullPath);
                } else {
                    File::delete($fullPath);
                }
            }
        }
        
        $this->info('Cleaning completed.');
    }
    
    /**
     * Set appropriate permissions for the specified environment
     *
     * @param string $environment
     * @return void
     */
    protected function setPermissions($environment)
    {
        $this->info("Setting appropriate permissions for {$environment} environment...");
        
        // Common directories that need write permissions
        $writableDirs = [
            'storage',
            'bootstrap/cache',
        ];
        
        if ($environment === 'production') {
            // In production, we want to be more restrictive
            $this->line('Setting production permissions...');
            
            foreach ($writableDirs as $dir) {
                $path = base_path($dir);
                
                if (File::isDirectory($path)) {
                    $this->line("Setting permissions for: {$dir}");
                    
                    // 755 for directories
                    $this->executeCommand('chmod -R 755 ' . $path);
                    
                    // Find directories and set 755
                    $this->executeCommand('find ' . $path . ' -type d -exec chmod 755 {} \;');
                    
                    // Find files and set 644
                    $this->executeCommand('find ' . $path . ' -type f -exec chmod 644 {} \;');
                }
            }
        } else {
            // In development, we want to be more permissive
            $this->line('Setting development permissions...');
            
            foreach ($writableDirs as $dir) {
                $path = base_path($dir);
                
                if (File::isDirectory($path)) {
                    $this->line("Setting permissions for: {$dir}");
                    
                    // 777 for directories in development
                    $this->executeCommand('chmod -R 777 ' . $path);
                }
            }
        }
        
        $this->info('Permissions set successfully.');
    }
    
    /**
     * Run optimization commands for the specified environment
     *
     * @param string $environment
     * @return void
     */
    protected function optimizeForEnvironment($environment)
    {
        $this->info("Running optimization commands for {$environment} environment...");
        
        if ($environment === 'production') {
            // Production optimizations
            $this->line('Running production optimizations...');
            
            // Use our custom optimize command with production flag
            Artisan::call(AppOptimize::class, [
                '--production' => true,
            ]);
            
            $this->line(Artisan::output());
        } else {
            // Development optimizations
            $this->line('Running development optimizations...');
            
            // Use our custom optimize command with dev flag
            Artisan::call(AppOptimize::class, [
                '--dev' => true,
            ]);
            
            $this->line(Artisan::output());
        }
        
        $this->info('Optimization completed.');
    }
    
    /**
     * Execute a shell command
     *
     * @param string $command
     * @return void
     */
    protected function executeCommand($command)
    {
        $process = proc_open($command, [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ], $pipes);
        
        if (is_resource($process)) {
            $output = stream_get_contents($pipes[1]);
            $errors = stream_get_contents($pipes[2]);
            
            fclose($pipes[0]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            
            $exitCode = proc_close($process);
            
            if ($exitCode !== 0) {
                $this->warn("Command exited with code {$exitCode}: {$command}");
                if (!empty($errors)) {
                    $this->error($errors);
                }
            }
        }
    }
}
