<?php

/**
 * StatusCommand
 * 
 * Provides a comprehensive status report of the Laravel application's initialization,
 * including configuration files, installed packages, and available stubs.
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

namespace Ez_IT_Solutions\AppInit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Process\Process;

class StatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:status
                            {--v|verbose : Display detailed information}
                            {--config-only : Only check configuration files}
                            {--packages-only : Only check installed packages}';  

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the status of Laravel Init packages and configurations';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info('📊 Laravel Init Package Status');
        $this->info('===========================');
        $this->newLine();

        // Check if we should only check configurations
        if (!$this->option('packages-only')) {
            $this->checkConfigurationFiles();
            $this->newLine();
            
            $this->checkEnvironmentVariables();
            $this->newLine();
        }
        
        // Check if we should only check packages
        if (!$this->option('config-only')) {
            $this->checkNodePackages();
            $this->newLine();
            
            $this->checkComposerPackages();
            $this->newLine();
        }
        
        // Show system information
        $this->showSystemInfo();
        
        return Command::SUCCESS;
    }

    /**
     * Check configuration files
     *
     * @return void
     */
    private function checkConfigurationFiles(): void
    {
        $this->info('Configuration Files:');
        
        // Get configuration files from config
        $configs = config('init.status.config_files', [
            'vite.config.js' => 'Vite configuration',
            'tailwind.config.js' => 'Tailwind CSS configuration',
            'postcss.config.js' => 'PostCSS configuration',
            '.env' => 'Environment configuration',
            'composer.json' => 'Composer configuration',
            'package.json' => 'NPM configuration',
        ]);

        foreach ($configs as $file => $description) {
            $path = base_path($file);
            $exists = File::exists($path);
            
            $status = $exists ? '✓' : '✗';
            $color = $exists ? 'info' : 'comment';
            
            $this->line("  [{$status}] {$description}");
            
            if ($this->option('verbose')) {
                $this->line("      Path: {$path}", $color);
                if ($exists) {
                    $size = File::size($path);
                    $modified = date('Y-m-d H:i:s', File::lastModified($path));
                    $this->line("      Size: {$size} bytes, Modified: {$modified}", 'comment');
                }
            }
        }
    }
    
    /**
     * Check environment variables
     *
     * @return void
     */
    private function checkEnvironmentVariables(): void
    {
        $this->info('Environment Variables:');
        
        $envVars = [
            'APP_ENV' => 'Application environment',
            'APP_DEBUG' => 'Debug mode',
            'DB_CONNECTION' => 'Database connection',
            'CACHE_DRIVER' => 'Cache driver',
            'QUEUE_CONNECTION' => 'Queue connection',
            'SESSION_DRIVER' => 'Session driver',
        ];
        
        foreach ($envVars as $var => $description) {
            $value = env($var, 'not set');
            $status = $value !== 'not set' ? '✓' : '✗';
            $color = $value !== 'not set' ? 'info' : 'comment';
            
            $this->line("  [{$status}] {$description}");
            
            if ($this->option('verbose')) {
                $displayValue = $var === 'APP_KEY' && $value !== 'not set' ? 'set (hidden)' : $value;
                $this->line("      {$var}={$displayValue}", $color);
            }
        }
    }

    /**
     * Check Node packages
     *
     * @return void
     */
    private function checkNodePackages(): void
    {
        $this->info('Node Packages:');
        
        if (! File::exists(base_path('package.json'))) {
            $this->warn('  package.json not found');
            return;
        }

        // Get required packages from config
        $requiredPackages = config('init.status.node_packages', [
            'vite' => 'Vite build tool',
            'laravel-vite-plugin' => 'Laravel Vite integration',
            'tailwindcss' => 'Tailwind CSS framework',
            'postcss' => 'PostCSS processor',
            'autoprefixer' => 'PostCSS autoprefixer',
        ]);
        
        // Get optional packages from config
        $optionalPackages = config('init.status.optional_node_packages', [
            '@tailwindcss/forms' => 'Tailwind forms plugin',
            '@tailwindcss/typography' => 'Tailwind typography plugin',
            'prettier' => 'Code formatter',
            'prettier-plugin-tailwindcss' => 'Tailwind class sorter',
        ]);

        $this->line('  Required Packages:');
        $this->checkPackages($requiredPackages, true);
        
        $this->newLine();
        $this->line('  Optional Packages:');
        $this->checkPackages($optionalPackages, false);
    }
    
    /**
     * Check packages against package.json
     *
     * @param array $packages
     * @param bool $required
     * @return void
     */
    private function checkPackages(array $packages, bool $required): void
    {
        $packageJson = json_decode(File::get(base_path('package.json')), true);
        $dependencies = array_merge(
            $packageJson['dependencies'] ?? [], 
            $packageJson['devDependencies'] ?? []
        );
        
        foreach ($packages as $package => $description) {
            $installed = isset($dependencies[$package]);
            $status = $installed ? '✓' : '✗';
            $color = $installed ? 'info' : ($required ? 'error' : 'comment');
            
            $this->line("    [{$status}] {$package}", $color);
            
            if ($this->option('verbose')) {
                $version = $installed ? $dependencies[$package] : 'not installed';
                $this->line("        {$description} ({$version})", 'comment');
            }
        }
    }

    /**
     * Check Composer packages
     *
     * @return void
     */
    private function checkComposerPackages(): void
    {
        $this->info('Composer Packages:');
        
        if (! File::exists(base_path('composer.json'))) {
            $this->warn('  composer.json not found');
            return;
        }
        
        // Get required packages from config
        $requiredPackages = config('init.status.composer_packages', [
            'laravel/framework' => 'Laravel Framework',
            'ez-it-solutions/laravel-init' => 'Laravel Init Package',
        ]);
        
        // Get optional packages that might be useful
        $optionalPackages = [
            'laravel/sanctum' => 'API Authentication',
            'laravel/telescope' => 'Debug Assistant',
            'laravel/horizon' => 'Queue Dashboard',
            'spatie/laravel-permission' => 'Permissions Manager',
            'barryvdh/laravel-debugbar' => 'Debug Bar',
        ];

        $this->line('  Required Packages:');
        $this->checkComposerPackagesList($requiredPackages, true);
        
        $this->newLine();
        $this->line('  Optional Packages:');
        $this->checkComposerPackagesList($optionalPackages, false);
    }
    
    /**
     * Check a list of Composer packages
     *
     * @param array $packages
     * @param bool $required
     * @return void
     */
    private function checkComposerPackagesList(array $packages, bool $required): void
    {
        $composerJson = json_decode(File::get(base_path('composer.json')), true);
        $dependencies = array_merge(
            $composerJson['require'] ?? [], 
            $composerJson['require-dev'] ?? []
        );
        
        foreach ($packages as $package => $description) {
            $installed = isset($dependencies[$package]);
            $status = $installed ? '✓' : '✗';
            $color = $installed ? 'info' : ($required ? 'error' : 'comment');
            
            $this->line("    [{$status}] {$package}", $color);
            
            if ($this->option('verbose')) {
                $version = $installed ? $dependencies[$package] : 'not installed';
                $this->line("        {$description} ({$version})", 'comment');
            }
        }
    }
    
    /**
     * Show system information
     *
     * @return void
     */
    private function showSystemInfo(): void
    {
        $this->info('System Information:');
        
        // PHP version
        $phpVersion = phpversion();
        $this->line("  PHP Version: {$phpVersion}");
        
        // Laravel version
        $laravelVersion = app()->version();
        $this->line("  Laravel Version: {$laravelVersion}");
        
        // Node.js version (if available)
        $nodeVersion = $this->getCommandOutput('node -v');
        $this->line("  Node.js Version: {$nodeVersion ?: 'Not installed'}");
        
        // NPM version (if available)
        $npmVersion = $this->getCommandOutput('npm -v');
        $this->line("  NPM Version: {$npmVersion ?: 'Not installed'}");
        
        // Operating system
        $os = PHP_OS_FAMILY;
        $this->line("  Operating System: {$os}");
        
        // Server software
        $serverSoftware = $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown';
        $this->line("  Server Software: {$serverSoftware}");
    }
    
    /**
     * Get command output
     *
     * @param string $command
     * @return string|null
     */
    private function getCommandOutput(string $command): ?string
    {
        try {
            $process = Process::fromShellCommandline($command);
            $process->setTimeout(5);
            $process->run();
            
            if ($process->isSuccessful()) {
                return trim($process->getOutput());
            }
            
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

}