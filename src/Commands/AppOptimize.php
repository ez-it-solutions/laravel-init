<?php

/**
 * AppOptimize Command
 * 
 * Optimizes Laravel applications for better performance by running
 * various optimization commands and clearing caches.
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

/**
 * Optimizes the application for better performance.
 * 
 * This command performs various optimization tasks to improve the performance
 * of Laravel applications, including:
 * - Clearing application caches
 * - Rebuilding configuration caches
 * - Compiling assets
 * - Creating storage links
 * - Cleaning up temporary files
 */
class AppOptimize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:optimize
                            {--no-vite : Skip Vite asset compilation}
                            {--no-cache : Skip cache rebuilding}
                            {--no-storage-link : Skip storage link creation}
                            {--production : Optimize for production environment}
                            {--dev : Optimize for development environment (default)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all caches, rebuild routes, and optimize the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting application optimization...');
        
        // Check for production flag
        $isProduction = $this->option('production');
        if ($isProduction) {
            $this->info('Optimizing for PRODUCTION environment');
            // Set environment to production
            app()->environment('production');
        } else {
            $this->info('Optimizing for DEVELOPMENT environment');
        }
        
        // Clear all caches
        $this->info('Clearing caches...');
        $this->executeArtisanCommand('cache:clear');
        $this->executeArtisanCommand('config:clear');
        $this->executeArtisanCommand('route:clear');
        $this->executeArtisanCommand('view:clear');
        
        // Clear compiled files
        $this->info('Clearing compiled files...');
        $this->executeArtisanCommand('clear-compiled');
        
        // Rebuild caches if not skipped
        if (!$this->option('no-cache')) {
            $this->info('Rebuilding caches...');
            
            if ($isProduction) {
                // In production, we always want to cache everything
                $this->executeArtisanCommand('config:cache');
                $this->executeArtisanCommand('route:cache');
                $this->executeArtisanCommand('view:cache');
                $this->executeArtisanCommand('event:cache');
            } else {
                // In development, we might want to be more selective
                $this->executeArtisanCommand('config:cache');
                $this->executeArtisanCommand('route:cache');
                
                // View caching can slow down development
                $this->warn('Skipping view:cache in development environment');
            }
            
            // Optimize the application
            $this->info('Optimizing application...');
            $this->executeArtisanCommand('optimize');
        } else {
            $this->warn('Skipping cache rebuilding (--no-cache option used)');
        }
        
        // Compile Vite assets if not skipped
        if (!$this->option('no-vite')) {
            $this->info('Compiling Vite assets...');
            
            // Determine which npm script to run based on environment
            $npmScript = $isProduction ? 'build' : 'build';
            $this->line("Running: npm run {$npmScript}");
            
            $process = proc_open("npm run {$npmScript}", [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ], $pipes, base_path());
            
            if (is_resource($process)) {
                $output = stream_get_contents($pipes[1]);
                $errors = stream_get_contents($pipes[2]);
                
                fclose($pipes[0]);
                fclose($pipes[1]);
                fclose($pipes[2]);
                
                $exitCode = proc_close($process);
                
                if ($exitCode === 0) {
                    $this->info('Vite assets compiled successfully.');
                } else {
                    $this->error('Vite asset compilation failed.');
                    $this->line($output);
                    $this->error($errors);
                }
            } else {
                $this->error('Failed to start npm process.');
            }
        } else {
            $this->warn('Skipping Vite asset compilation (--no-vite option used)');
        }
        
        // Link storage if it's not already linked and not skipped
        if (!$this->option('no-storage-link')) {
            if (!file_exists(public_path('storage'))) {
                $this->info('Creating storage symlink...');
                $this->executeArtisanCommand('storage:link');
            } else {
                $this->info('Storage symlink already exists.');
            }
        } else {
            $this->warn('Skipping storage link creation (--no-storage-link option used)');
        }
        
        $this->info('Application optimization completed successfully!');
        
        return Command::SUCCESS;
    }
    
    /**
     * Execute an Artisan command and display its output.
     *
     * @param string $command
     * @return void
     */
    protected function executeArtisanCommand($command)
    {
        $this->line("Running: $command");
        $exitCode = Artisan::call($command);
        $output = Artisan::output();
        
        if (!empty(trim($output))) {
            $this->line($output);
        }
        
        if ($exitCode === 0) {
            $this->line("<info>✓</info> $command completed successfully.");
        } else {
            $this->error("$command failed with exit code: $exitCode");
        }
    }
}
