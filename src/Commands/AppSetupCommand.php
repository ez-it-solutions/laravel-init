<?php

/**
 * AppSetupCommand
 * 
 * Provides a wizard-like setup interface for configuring and using various features
 * of the Laravel Initialization Utility.
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
use Symfony\Component\Console\Helper\Table;

class AppSetupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup
                            {--non-interactive : Run in non-interactive mode with default options}
                            {--skip-intro : Skip the introduction screen}
                            {--web : Launch the web-based setup wizard}
                            {--port=8000 : The port to use for the web-based setup wizard}
                            {--host=127.0.0.1 : The host to use for the web-based setup wizard}
                            {--no-open : Do not open the browser automatically for web-based setup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Interactive setup wizard for Laravel Initialization Utility';

    /**
     * Selected features to configure
     *
     * @var array
     */
    protected $selectedFeatures = [];

    /**
     * Configuration options
     *
     * @var array
     */
    protected $configOptions = [];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Check if web-based setup is requested
        if ($this->option('web')) {
            return $this->launchWebSetup();
        }
        
        if (!$this->option('skip-intro')) {
            $this->showIntroduction();
        }

        if ($this->option('non-interactive')) {
            return $this->runNonInteractive();
        }

        // Main menu
        $this->showMainMenu();

        return Command::SUCCESS;
    }
    
    /**
     * Launch the web-based setup wizard
     *
     * @return int
     */
    protected function launchWebSetup()
    {
        $this->info('Launching web-based setup wizard...');
        
        // Get options
        $port = $this->option('port');
        $host = $this->option('host');
        $noOpen = $this->option('no-open');
        
        // Build the command
        $command = 'app:web-setup';
        $options = [
            '--port' => $port,
            '--host' => $host,
        ];
        
        if ($noOpen) {
            $options['--no-open'] = true;
        }
        
        // Call the web setup command
        return $this->call($command, $options);
    }

    /**
     * Show introduction screen
     *
     * @return void
     */
    protected function showIntroduction()
    {
        $this->newLine();
        $this->info('╔═══════════════════════════════════════════════════════════════╗');
        $this->info('║                                                               ║');
        $this->info('║   🚀 Laravel Initialization Utility - Interactive Setup       ║');
        $this->info('║                                                               ║');
        $this->info('╚═══════════════════════════════════════════════════════════════╝');
        $this->newLine();
        
        $this->line('Welcome to the Laravel Initialization Utility setup wizard!');
        $this->line('This wizard will help you configure and use various features of the utility.');
        $this->newLine();
        
        $this->line('You can use this wizard to:');
        $this->line(' • Configure your Laravel application');
        $this->line(' • Set up database connections and backups');
        $this->line(' • Install and configure frontend assets');
        $this->line(' • Optimize your application for production');
        $this->line(' • View documentation and help');
        $this->newLine();
        
        if (!$this->confirm('Continue with the setup wizard?', true)) {
            $this->info('Setup wizard cancelled. You can run it again with: php artisan app:setup');
            exit;
        }
    }

    /**
     * Show main menu
     *
     * @return void
     */
    protected function showMainMenu()
    {
        while (true) {
            $this->newLine();
            $this->info('╔═══════════════════════════════════════════════════════════════╗');
            $this->info('║                       MAIN MENU                               ║');
            $this->info('╚═══════════════════════════════════════════════════════════════╝');
            $this->newLine();
            
            $option = $this->choice(
                'What would you like to do?',
                [
                    'view_readme' => 'View README Documentation',
                    'view_help' => 'View Command Help Documentation',
                    'check_status' => 'Check Application Status',
                    'configure_app' => 'Configure Application',
                    'setup_database' => 'Setup Database',
                    'setup_frontend' => 'Setup Frontend Assets',
                    'optimize' => 'Optimize Application',
                    'backup' => 'Backup Database',
                    'build_config' => 'Build Configuration File',
                    'exit' => 'Exit Setup Wizard',
                ],
                'view_readme'
            );
            
            switch ($option) {
                case 'view_readme':
                    $this->viewReadme();
                    break;
                case 'view_help':
                    $this->viewHelp();
                    break;
                case 'check_status':
                    $this->checkStatus();
                    break;
                case 'configure_app':
                    $this->configureApp();
                    break;
                case 'setup_database':
                    $this->setupDatabase();
                    break;
                case 'setup_frontend':
                    $this->setupFrontend();
                    break;
                case 'optimize':
                    $this->optimizeApp();
                    break;
                case 'backup':
                    $this->backupDatabase();
                    break;
                case 'build_config':
                    $this->buildConfig();
                    break;
                case 'exit':
                    $this->info('Exiting setup wizard. Goodbye!');
                    return;
            }
            
            $this->newLine();
            $this->line('Returning to main menu...');
            sleep(1);
        }
    }

    /**
     * View README.md file
     *
     * @return void
     */
    protected function viewReadme()
    {
        $this->newLine();
        $this->info('╔═══════════════════════════════════════════════════════════════╗');
        $this->info('║                    README DOCUMENTATION                        ║');
        $this->info('╚═══════════════════════════════════════════════════════════════╝');
        $this->newLine();
        
        $readmePath = base_path('README.md');
        
        if (!File::exists($readmePath)) {
            $this->error('README.md file not found!');
            return;
        }
        
        $content = File::get($readmePath);
        $lines = explode("\n", $content);
        
        $currentPage = 1;
        $linesPerPage = 20;
        $totalPages = ceil(count($lines) / $linesPerPage);
        
        while (true) {
            $this->newLine();
            $startLine = ($currentPage - 1) * $linesPerPage;
            $endLine = min($startLine + $linesPerPage, count($lines));
            
            for ($i = $startLine; $i < $endLine; $i++) {
                $this->line($lines[$i]);
            }
            
            $this->newLine();
            $this->info("Page {$currentPage} of {$totalPages}");
            
            $navigation = $this->choice(
                'Navigation',
                [
                    'next' => 'Next Page',
                    'prev' => 'Previous Page',
                    'exit' => 'Return to Main Menu',
                ],
                $currentPage < $totalPages ? 'next' : 'exit'
            );
            
            if ($navigation === 'next' && $currentPage < $totalPages) {
                $currentPage++;
            } elseif ($navigation === 'prev' && $currentPage > 1) {
                $currentPage--;
            } elseif ($navigation === 'exit') {
                break;
            }
        }
    }

    /**
     * View help documentation
     *
     * @return void
     */
    protected function viewHelp()
    {
        $this->newLine();
        $this->info('╔═══════════════════════════════════════════════════════════════╗');
        $this->info('║                    HELP DOCUMENTATION                          ║');
        $this->info('╚═══════════════════════════════════════════════════════════════╝');
        $this->newLine();
        
        $commands = [
            'app:init' => 'Initialize Laravel applications with all required setup steps',
            'app:deploy' => 'Deploy Laravel applications to various environments',
            'app:optimize' => 'Optimize Laravel applications for better performance',
            'app:prepare' => 'Prepare Laravel applications for different environments',
            'app:cleanup' => 'Clean up Laravel applications by removing temporary files',
            'app:serve' => 'Serve the application on the first available port',
            'db:init' => 'Initialize and configure the database',
            'db:backup' => 'Create and manage database backups',
            'app:status' => 'Check the status of Laravel Init packages and configurations',
            'app:help' => 'Get help and documentation for Laravel Init commands',
        ];
        
        $command = $this->choice(
            'Select a command to view help for:',
            array_merge(array_keys($commands), ['return' => 'Return to Main Menu']),
            'app:init'
        );
        
        if ($command === 'return') {
            return;
        }
        
        $this->newLine();
        $this->info("Help for: {$command}");
        $this->line($commands[$command]);
        $this->newLine();
        
        // Run the help command to show detailed help
        $this->line('Detailed help:');
        $this->newLine();
        Artisan::call('help', ['command_name' => $command]);
        $this->line(Artisan::output());
        
        $this->newLine();
        $this->confirm('Press Enter to continue', true);
    }

    /**
     * Check application status
     *
     * @return void
     */
    protected function checkStatus()
    {
        $this->newLine();
        $this->info('╔═══════════════════════════════════════════════════════════════╗');
        $this->info('║                    APPLICATION STATUS                          ║');
        $this->info('╚═══════════════════════════════════════════════════════════════╝');
        $this->newLine();
        
        $this->line('Running status check...');
        $this->newLine();
        
        Artisan::call('app:status', ['--verbose' => true]);
        $this->line(Artisan::output());
        
        $this->newLine();
        $this->confirm('Press Enter to continue', true);
    }

    /**
     * Configure application
     *
     * @return void
     */
    protected function configureApp()
    {
        $this->newLine();
        $this->info('╔═══════════════════════════════════════════════════════════════╗');
        $this->info('║                  APPLICATION CONFIGURATION                     ║');
        $this->info('╚═══════════════════════════════════════════════════════════════╝');
        $this->newLine();
        
        $options = [
            'app_name' => $this->ask('Application name', config('app.name', 'Laravel')),
            'app_env' => $this->choice('Environment', ['local', 'development', 'staging', 'production'], 'local'),
            'app_debug' => $this->confirm('Enable debug mode?', true),
            'app_url' => $this->ask('Application URL', config('app.url', 'http://localhost')),
        ];
        
        $this->newLine();
        $this->info('Application configuration:');
        
        $table = new Table($this->output);
        $table->setHeaders(['Setting', 'Value']);
        
        foreach ($options as $key => $value) {
            $table->addRow([$key, is_bool($value) ? ($value ? 'true' : 'false') : $value]);
        }
        
        $table->render();
        
        if ($this->confirm('Save these configuration options?', true)) {
            $this->configOptions['app'] = $options;
            $this->selectedFeatures[] = 'app_config';
            $this->info('Application configuration saved!');
        }
    }

    /**
     * Setup database
     *
     * @return void
     */
    protected function setupDatabase()
    {
        $this->newLine();
        $this->info('╔═══════════════════════════════════════════════════════════════╗');
        $this->info('║                    DATABASE SETUP                              ║');
        $this->info('╚═══════════════════════════════════════════════════════════════╝');
        $this->newLine();
        
        $options = [
            'connection' => $this->choice('Database connection', ['mysql', 'pgsql', 'sqlite', 'sqlsrv'], 'mysql'),
            'host' => $this->ask('Database host', '127.0.0.1'),
            'port' => $this->ask('Database port', '3306'),
            'database' => $this->ask('Database name', 'laravel'),
            'username' => $this->ask('Database username', 'root'),
            'password' => $this->secret('Database password'),
            'charset' => $this->ask('Character set', 'utf8mb4'),
            'collation' => $this->ask('Collation', 'utf8mb4_unicode_ci'),
            'run_migrations' => $this->confirm('Run migrations after setup?', false),
            'run_seeders' => $this->confirm('Run seeders after setup?', false),
        ];
        
        $this->newLine();
        $this->info('Database configuration:');
        
        $table = new Table($this->output);
        $table->setHeaders(['Setting', 'Value']);
        
        foreach ($options as $key => $value) {
            if ($key !== 'password') {
                $table->addRow([$key, is_bool($value) ? ($value ? 'true' : 'false') : $value]);
            } else {
                $table->addRow([$key, '********']);
            }
        }
        
        $table->render();
        
        if ($this->confirm('Save these database options?', true)) {
            $this->configOptions['database'] = $options;
            $this->selectedFeatures[] = 'database_config';
            
            if ($this->confirm('Initialize database now?', false)) {
                $this->call('db:init', [
                    '--force' => true,
                    '--migrate' => $options['run_migrations'],
                    '--seed' => $options['run_seeders'],
                ]);
            }
        }
    }

    /**
     * Setup frontend assets
     *
     * @return void
     */
    protected function setupFrontend()
    {
        $this->newLine();
        $this->info('╔═══════════════════════════════════════════════════════════════╗');
        $this->info('║                    FRONTEND SETUP                              ║');
        $this->info('╚═══════════════════════════════════════════════════════════════╝');
        $this->newLine();
        
        $options = [
            'stack' => $this->choice('Frontend stack', ['vite', 'mix'], 'vite'),
            'install_tailwind' => $this->confirm('Install TailwindCSS?', true),
            'with_plugins' => false,
            'with_dark_mode' => false,
            'package_manager' => $this->choice('Package manager', ['npm', 'yarn', 'pnpm'], 'npm'),
        ];
        
        if ($options['install_tailwind']) {
            $options['with_plugins'] = $this->confirm('Install TailwindCSS plugins (forms, typography)?', true);
            $options['with_dark_mode'] = $this->confirm('Enable dark mode support?', true);
        }
        
        $this->newLine();
        $this->info('Frontend configuration:');
        
        $table = new Table($this->output);
        $table->setHeaders(['Setting', 'Value']);
        
        foreach ($options as $key => $value) {
            $table->addRow([$key, is_bool($value) ? ($value ? 'true' : 'false') : $value]);
        }
        
        $table->render();
        
        if ($this->confirm('Save these frontend options?', true)) {
            $this->configOptions['frontend'] = $options;
            $this->selectedFeatures[] = 'frontend_config';
            
            if ($options['install_tailwind'] && $this->confirm('Install TailwindCSS now?', false)) {
                $this->call('app:install-tailwindcss', [
                    '--with-' . $options['stack'] => true,
                    '--with-plugins' => $options['with_plugins'],
                    '--dark-mode' => $options['with_dark_mode'],
                ]);
            }
        }
    }

    /**
     * Optimize application
     *
     * @return void
     */
    protected function optimizeApp()
    {
        $this->newLine();
        $this->info('╔═══════════════════════════════════════════════════════════════╗');
        $this->info('║                  APPLICATION OPTIMIZATION                      ║');
        $this->info('╚═══════════════════════════════════════════════════════════════╝');
        $this->newLine();
        
        $options = [
            'clear_cache' => $this->confirm('Clear application cache?', true),
            'optimize_config' => $this->confirm('Optimize configuration loading?', true),
            'optimize_routes' => $this->confirm('Cache routes?', true),
            'optimize_views' => $this->confirm('Cache views?', true),
            'optimize_autoloader' => $this->confirm('Optimize Composer autoloader?', true),
        ];
        
        $this->newLine();
        $this->info('Optimization options:');
        
        $table = new Table($this->output);
        $table->setHeaders(['Option', 'Value']);
        
        foreach ($options as $key => $value) {
            $table->addRow([$key, $value ? 'Yes' : 'No']);
        }
        
        $table->render();
        
        if ($this->confirm('Save these optimization options?', true)) {
            $this->configOptions['optimization'] = $options;
            $this->selectedFeatures[] = 'optimization';
            
            if ($this->confirm('Run optimization now?', false)) {
                $this->call('app:optimize', [
                    '--skip-config' => !$options['optimize_config'],
                    '--skip-routes' => !$options['optimize_routes'],
                    '--skip-views' => !$options['optimize_views'],
                ]);
            }
        }
    }

    /**
     * Backup database
     *
     * @return void
     */
    protected function backupDatabase()
    {
        $this->newLine();
        $this->info('╔═══════════════════════════════════════════════════════════════╗');
        $this->info('║                    DATABASE BACKUP                             ║');
        $this->info('╚═══════════════════════════════════════════════════════════════╝');
        $this->newLine();
        
        $options = [
            'format' => $this->choice('Backup format', ['sql', 'gz', 'zip'], 'gz'),
            'storage' => $this->choice('Storage disk', ['local', 's3', 'google'], 'local'),
            'path' => $this->ask('Backup path', config('database.ez-it-solutions.backup_dir', storage_path('app/backups'))),
            'retention_days' => $this->ask('Retention days', 7),
            'max_backups' => $this->ask('Maximum backups to keep', 10),
            'with_data' => $this->confirm('Include data in backup?', true),
            'notify' => $this->confirm('Send notification after backup?', false),
        ];
        
        $this->newLine();
        $this->info('Backup configuration:');
        
        $table = new Table($this->output);
        $table->setHeaders(['Setting', 'Value']);
        
        foreach ($options as $key => $value) {
            $table->addRow([$key, is_bool($value) ? ($value ? 'true' : 'false') : $value]);
        }
        
        $table->render();
        
        if ($this->confirm('Save these backup options?', true)) {
            $this->configOptions['backup'] = $options;
            $this->selectedFeatures[] = 'backup_config';
            
            if ($this->confirm('Run database backup now?', false)) {
                $this->call('db:backup', [
                    '--format' => $options['format'],
                    '--storage' => $options['storage'],
                    '--path' => $options['path'],
                    '--' . ($options['with_data'] ? 'with-data' : 'structure-only') => true,
                    '--notify' => $options['notify'],
                    '--force' => true,
                ]);
            }
        }
    }

    /**
     * Build configuration file
     *
     * @return void
     */
    protected function buildConfig()
    {
        $this->newLine();
        $this->info('╔═══════════════════════════════════════════════════════════════╗');
        $this->info('║                  CONFIGURATION BUILDER                         ║');
        $this->info('╚═══════════════════════════════════════════════════════════════╝');
        $this->newLine();
        
        if (empty($this->selectedFeatures)) {
            $this->warn('No features have been configured yet!');
            $this->line('Please configure some features before building a configuration file.');
            return;
        }
        
        $this->info('Selected features:');
        foreach ($this->selectedFeatures as $feature) {
            $this->line(" • {$feature}");
        }
        
        $this->newLine();
        $filename = $this->ask('Configuration filename', 'laravel-init.json');
        
        $configPath = base_path($filename);
        $configData = [
            'generated_at' => now()->toIso8601String(),
            'features' => $this->selectedFeatures,
            'options' => $this->configOptions,
        ];
        
        $jsonContent = json_encode($configData, JSON_PRETTY_PRINT);
        
        if (File::exists($configPath) && !$this->confirm("File {$filename} already exists. Overwrite?", false)) {
            $this->info('Configuration build cancelled.');
            return;
        }
        
        File::put($configPath, $jsonContent);
        
        $this->info("Configuration file saved to: {$filename}");
        $this->newLine();
        
        if ($this->confirm('Would you like to view the generated configuration?', true)) {
            $this->line($jsonContent);
        }
    }

    /**
     * Run in non-interactive mode
     *
     * @return int
     */
    protected function runNonInteractive()
    {
        $this->info('Running in non-interactive mode with default options...');
        
        // Load default options from config
        $this->configOptions = [
            'app' => config('init.commands.app_init'),
            'database' => config('init.database'),
            'frontend' => config('init.frontend'),
            'optimization' => config('init.commands.app_optimize'),
            'backup' => [
                'format' => config('database.ez-it-solutions.backup_compression', 'gz'),
                'storage' => config('database.ez-it-solutions.backup_cloud_storage', 'local'),
                'path' => config('database.ez-it-solutions.backup_dir'),
                'retention_days' => config('database.ez-it-solutions.backup_retention_days', 7),
                'max_backups' => config('database.ez-it-solutions.max_backups', 10),
                'with_data' => true,
                'notify' => false,
            ],
        ];
        
        $this->selectedFeatures = [
            'app_config',
            'database_config',
            'frontend_config',
            'optimization',
            'backup_config',
        ];
        
        // Build config file
        $configPath = base_path('laravel-init.json');
        $configData = [
            'generated_at' => now()->toIso8601String(),
            'features' => $this->selectedFeatures,
            'options' => $this->configOptions,
        ];
        
        File::put($configPath, json_encode($configData, JSON_PRETTY_PRINT));
        
        $this->info('Configuration file saved to: laravel-init.json');
        
        return Command::SUCCESS;
    }
}
