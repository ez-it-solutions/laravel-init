<?php

/**
 * AppDeploy Command
 * 
 * Handles the deployment process for Laravel applications,
 * including database migrations, asset compilation, and environment setup.
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
use ZipArchive;
use Ez_IT_Solutions\AppInit\Commands\AppOptimize;

/**
 * Manages the deployment process of Laravel applications.
 * 
 * This command orchestrates the deployment workflow, including:
 * - Running database migrations
 * - Compiling frontend assets
 * - Optimizing the application
 * - Clearing caches
 * - Setting up environment-specific configurations
 */
class AppDeploy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deploy
                            {--prepare : Prepare the application for upload (creates a deployment package)}
                            {--setup : Set up the application after deployment}
                            {--exclude-vendor : Exclude vendor directory from deployment package}
                            {--exclude-node : Exclude node_modules directory from deployment package}
                            {--output= : Output path for deployment package}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handle deployment tasks (prepare for upload or setup after deployment)';

    /**
     * Files and directories to exclude from deployment package
     */
    protected $defaultExclusions = [
        '.git',
        '.github',
        '.gitattributes',
        '.gitignore',
        'phpunit.xml',
        'tests',
        '.editorconfig',
        'docker-compose.yml',
        'Dockerfile',
        'package-lock.json',
        'yarn.lock',
        'webpack.mix.js',
        'vite.config.js.bak',
        'storage/framework/cache/*',
        'storage/framework/sessions/*',
        'storage/framework/views/*',
        'storage/logs/*',
        'bootstrap/cache/*.php',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('prepare') && !$this->option('setup')) {
            $this->error('You must specify either --prepare or --setup option.');
            return Command::FAILURE;
        }

        if ($this->option('prepare')) {
            return $this->prepareForUpload();
        }

        if ($this->option('setup')) {
            return $this->setupAfterDeployment();
        }

        return Command::SUCCESS;
    }

    /**
     * Prepare the application for upload
     *
     * @return int
     */
    protected function prepareForUpload()
    {
        $this->info('Preparing application for upload...');

        // Determine output path
        $outputPath = $this->option('output') ?: base_path('../syllabus-builder-deploy-' . date('Y-m-d-His') . '.zip');
        
        // Create exclusion list
        $exclusions = $this->defaultExclusions;
        
        if ($this->option('exclude-vendor')) {
            $exclusions[] = 'vendor';
            $this->line('Excluding vendor directory from deployment package.');
        }
        
        if ($this->option('exclude-node')) {
            $exclusions[] = 'node_modules';
            $this->line('Excluding node_modules directory from deployment package.');
        }
        
        // Create deployment instructions file
        $this->createDeploymentInstructions();
        
        // Create deployment package
        $this->createDeploymentPackage($outputPath, $exclusions);
        
        $this->info('Application prepared for upload successfully!');
        $this->line("Deployment package created at: {$outputPath}");
        
        return Command::SUCCESS;
    }

    /**
     * Set up the application after deployment
     *
     * @return int
     */
    protected function setupAfterDeployment()
    {
        $this->info('Setting up application after deployment...');
        
        // Check if composer is installed
        $this->line('Checking for Composer...');
        $composerExists = $this->executeCommand('composer --version', false);
        
        if (!$composerExists) {
            $this->error('Composer not found. Please install Composer before continuing.');
            return Command::FAILURE;
        }
        
        // Install Composer dependencies
        $this->line('Installing Composer dependencies...');
        $this->executeCommand('composer install --no-dev --optimize-autoloader');
        
        // Generate application key if not already set
        $this->line('Checking application key...');
        if (env('APP_KEY') == '') {
            $this->line('Generating application key...');
            Artisan::call('key:generate');
            $this->line(Artisan::output());
        }
        
        // Run migrations
        if ($this->confirm('Would you like to run database migrations?', true)) {
            $this->line('Running migrations...');
            Artisan::call('migrate', ['--force' => true]);
            $this->line(Artisan::output());
        }
        
        // Create storage link
        $this->line('Creating storage link...');
        if (!file_exists(public_path('storage'))) {
            Artisan::call('storage:link');
            $this->line(Artisan::output());
        }
        
        // Optimize the application
        $this->line('Optimizing application...');
        Artisan::call(AppOptimize::class, ['--production' => true]);
        $this->line(Artisan::output());
        
        $this->info('Application setup completed successfully!');
        
        return Command::SUCCESS;
    }

    /**
     * Create deployment instructions file
     *
     * @return void
     */
    protected function createDeploymentInstructions()
    {
        $this->line('Creating deployment instructions...');
        
        $instructions = <<<EOT
# Jacksonville College Syllabus Builder - Deployment Instructions

## Server Requirements

- PHP 8.1 or higher
- MySQL 5.7 or higher
- Composer
- Web server (Apache/Nginx)
- PHP Extensions: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, LDAP, Intl

## Deployment Steps

1. Upload the contents of this package to your server

2. Configure your web server to point to the `public` directory

3. Create a `.env` file (copy from `.env.example` and update with your settings)

4. Run the setup command:
   ```
   php artisan app:deploy --setup
   ```

5. If vendor directory was excluded, run:
   ```
   composer install --no-dev --optimize-autoloader
   ```

6. Set appropriate permissions:
   ```
   chmod -R 755 storage bootstrap/cache
   ```

7. Generate application key (if not already set):
   ```
   php artisan key:generate
   ```

8. Run migrations:
   ```
   php artisan migrate --force
   ```

9. Create storage link:
   ```
   php artisan storage:link
   ```

10. Optimize the application:
    ```
    php artisan app:optimize --production
    ```

## Troubleshooting

If you encounter any issues during deployment, please contact:
help@jacksonville-college.edu

EOT;
        
        File::put(base_path('DEPLOY.md'), $instructions);
        $this->line('Deployment instructions created at: DEPLOY.md');
    }

    /**
     * Create deployment package
     *
     * @param string $outputPath
     * @param array $exclusions
     * @return void
     */
    protected function createDeploymentPackage($outputPath, $exclusions)
    {
        $this->line('Creating deployment package...');
        
        // Check if ZipArchive is available
        if (!class_exists('ZipArchive')) {
            $this->error('ZipArchive class not found. Please install the PHP zip extension.');
            return;
        }
        
        $zip = new ZipArchive();
        
        if ($zip->open($outputPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            $this->error("Could not create zip file at: {$outputPath}");
            return;
        }
        
        $basePath = base_path();
        $this->addFilesToZip($zip, $basePath, '', $exclusions);
        
        $zip->close();
        
        $this->line('Deployment package created successfully.');
    }

    /**
     * Add files to zip archive
     *
     * @param ZipArchive $zip
     * @param string $basePath
     * @param string $relativePath
     * @param array $exclusions
     * @return void
     */
    protected function addFilesToZip($zip, $basePath, $relativePath, $exclusions)
    {
        $fullPath = $basePath . ($relativePath ? '/' . $relativePath : '');
        $files = scandir($fullPath);
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            
            $filePath = $relativePath ? "{$relativePath}/{$file}" : $file;
            
            // Check if file/directory should be excluded
            $exclude = false;
            foreach ($exclusions as $exclusion) {
                if ($this->matchesPattern($filePath, $exclusion)) {
                    $exclude = true;
                    break;
                }
            }
            
            if ($exclude) {
                $this->line("Excluding: {$filePath}");
                continue;
            }
            
            $fullFilePath = "{$basePath}/{$filePath}";
            
            if (is_dir($fullFilePath)) {
                // Create empty directory in zip
                $zip->addEmptyDir($filePath);
                
                // Add files in directory
                $this->addFilesToZip($zip, $basePath, $filePath, $exclusions);
            } else {
                // Add file to zip
                $zip->addFile($fullFilePath, $filePath);
            }
        }
    }

    /**
     * Check if a file path matches an exclusion pattern
     *
     * @param string $filePath
     * @param string $pattern
     * @return bool
     */
    protected function matchesPattern($filePath, $pattern)
    {
        // Convert pattern to regex
        $pattern = preg_quote($pattern, '/');
        
        // Replace wildcards
        $pattern = str_replace('\*', '.*', $pattern);
        
        // Match at the beginning of the string or after a slash
        return preg_match('/^' . $pattern . '($|\/)/i', $filePath) === 1;
    }

    /**
     * Execute a shell command
     *
     * @param string $command
     * @param bool $showOutput
     * @return bool
     */
    protected function executeCommand($command, $showOutput = true)
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
            
            if ($showOutput && !empty($output)) {
                $this->line($output);
            }
            
            if ($exitCode !== 0) {
                if ($showOutput) {
                    $this->warn("Command exited with code {$exitCode}: {$command}");
                    if (!empty($errors)) {
                        $this->error($errors);
                    }
                }
                return false;
            }
            
            return true;
        }
        
        return false;
    }
}
