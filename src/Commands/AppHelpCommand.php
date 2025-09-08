<?php

/**
 * AppHelpCommand
 * 
 * Provides comprehensive help and documentation for all Laravel Initialization Utility commands.
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
use Illuminate\Support\Str;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Helper\Table;

/**
 * Provides comprehensive help and documentation for all Laravel Initialization Utility commands.
 * 
 * This command serves as a central hub for accessing documentation, examples, and usage
 * information for all commands provided by the Laravel Initialization Utility.
 */
class AppHelpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:help
                            {command? : Specific command to get help for}
                            {--list : List all available commands}
                            {--examples : Show usage examples}
                            {--interactive : Start interactive help mode}
                            {--html : Open documentation in browser}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get help and documentation for Laravel Initialization Utility commands';

    /**
     * Command information repository.
     *
     * @var array
     */
    protected $commandInfo = [
        'app:init' => [
            'description' => 'Initialize Laravel applications with all required setup steps',
            'summary' => 'Main command that orchestrates the entire initialization process including system checks, database setup, and application preparation.',
            'examples' => [
                'Basic usage' => 'php artisan app:init',
                'Skip requirements check' => 'php artisan app:init --skip-requirements',
                'Skip database initialization' => 'php artisan app:init --skip-database',
                'Show database configuration' => 'php artisan app:init --show-config',
                'Run with migrations' => 'php artisan app:init --migrate',
                'Run with seeding' => 'php artisan app:init --seed',
            ],
            'related' => ['app:prepare', 'app:deploy', 'app:optimize'],
        ],
        'app:deploy' => [
            'description' => 'Deploy Laravel applications to various environments',
            'summary' => 'Handles the deployment process including database migrations, asset compilation, and environment setup.',
            'examples' => [
                'Basic usage' => 'php artisan app:deploy',
                'Deploy to production' => 'php artisan app:deploy --env=production',
                'Skip migrations' => 'php artisan app:deploy --skip-migrations',
                'Skip asset compilation' => 'php artisan app:deploy --skip-assets',
            ],
            'related' => ['app:init', 'app:optimize'],
        ],
        'app:optimize' => [
            'description' => 'Optimize Laravel applications for better performance',
            'summary' => 'Performs various optimization tasks including cache clearing, configuration caching, and route optimization.',
            'examples' => [
                'Basic usage' => 'php artisan app:optimize',
                'Skip config caching' => 'php artisan app:optimize --skip-config',
                'Skip route caching' => 'php artisan app:optimize --skip-routes',
                'Skip view caching' => 'php artisan app:optimize --skip-views',
            ],
            'related' => ['app:cleanup', 'app:deploy'],
        ],
        'app:prepare' => [
            'description' => 'Prepare Laravel applications for different environments',
            'summary' => 'Sets up directory structures, configures environment-specific settings, and manages file permissions.',
            'examples' => [
                'Basic usage' => 'php artisan app:prepare',
                'Prepare for production' => 'php artisan app:prepare --env=production',
                'Prepare for development' => 'php artisan app:prepare --env=development',
                'Skip directory setup' => 'php artisan app:prepare --skip-directories',
            ],
            'related' => ['app:init', 'app:deploy'],
        ],
        'app:cleanup' => [
            'description' => 'Clean up Laravel applications by removing temporary files and clearing caches',
            'summary' => 'Comprehensive cleanup utility that clears various caches, removes temporary files, and optimizes the application.',
            'examples' => [
                'Basic usage' => 'php artisan app:cleanup',
                'Skip confirmation' => 'php artisan app:cleanup --force',
                'Skip cache clearing' => 'php artisan app:cleanup --skip-cache',
                'Skip compiled views' => 'php artisan app:cleanup --skip-views',
            ],
            'related' => ['app:optimize'],
        ],
        'app:serve' => [
            'description' => 'Serve the application on the first available port in a given range',
            'summary' => 'Enhanced development server that automatically finds an open port and launches the application server.',
            'examples' => [
                'Basic usage' => 'php artisan app:serve',
                'Custom host' => 'php artisan app:serve --host=192.168.1.100',
                'Custom port range' => 'php artisan app:serve --start-port=3000 --max-attempts=5',
                'Without browser' => 'php artisan app:serve --no-open',
            ],
            'related' => ['app:init'],
        ],
        'db:init' => [
            'description' => 'Initialize and configure the database with proper character set and collation',
            'summary' => 'Creates the database if it doesn\'t exist, sets proper character set and collation, and optionally runs migrations and seeding.',
            'examples' => [
                'Basic usage' => 'php artisan db:init',
                'Show configuration' => 'php artisan db:init --show-config',
                'With migrations' => 'php artisan db:init --migrate',
                'With seeding' => 'php artisan db:init --seed',
                'Force on production' => 'php artisan db:init --force',
            ],
            'related' => ['app:init', 'mysql:exec'],
        ],
        'mysql:exec' => [
            'description' => 'Execute MySQL commands directly using the MySQL command-line client',
            'summary' => 'Allows executing raw SQL commands or scripts directly using the MySQL command-line client.',
            'examples' => [
                'Execute SQL command' => 'php artisan mysql:exec "SELECT VERSION()"',
                'Execute SQL file' => 'php artisan mysql:exec --file=path/to/script.sql',
                'With specific database' => 'php artisan mysql:exec "SHOW TABLES" --database=custom_db',
            ],
            'related' => ['db:init'],
        ],
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $command = $this->argument('command');
        
        // Check if HTML documentation should be opened
        if ($this->option('html')) {
            return $this->openHtmlDocumentation($command);
        }
        
        if ($this->option('list')) {
            return $this->listAllCommands();
        }
        
        if ($this->option('interactive')) {
            return $this->startInteractiveMode();
        }
        
        if ($command) {
            return $this->showCommandHelp($command);
        }
        
        $this->showWelcomeScreen();
        return 0;
    }
    
    /**
     * Show the welcome screen with general information.
     *
     * @return void
     */
    protected function showWelcomeScreen()
    {
        $this->newLine();
        $this->info('📚 Laravel Initialization Utility - Help Center');
        $this->info('================================================');
        $this->newLine();
        
        $this->line('The Laravel Initialization Utility is a comprehensive toolkit designed to streamline');
        $this->line('the entire lifecycle of Laravel application development, deployment, and maintenance.');
        $this->newLine();
        
        $this->comment('Available Command Categories:');
        $this->newLine();
        
        $categories = [
            'Initialization' => ['app:init', 'app:prepare'],
            'Deployment' => ['app:deploy', 'app:optimize'],
            'Maintenance' => ['app:cleanup', 'app:serve'],
            'Database' => ['db:init', 'mysql:exec'],
        ];
        
        foreach ($categories as $category => $commands) {
            $this->line("<fg=yellow;options=bold>$category:</>");
            foreach ($commands as $cmd) {
                $this->line("  • <fg=green>$cmd</> - " . $this->commandInfo[$cmd]['description']);
            }
            $this->newLine();
        }
        
        $this->line('To get detailed help for a specific command:');
        $this->line('  <fg=cyan>php artisan app:help {command}</> - e.g., <fg=cyan>php artisan app:help app:init</>');
        $this->newLine();
        
        $this->line('To list all available commands:');
        $this->line('  <fg=cyan>php artisan app:help --list</>');
        $this->newLine();
        
        $this->line('To see usage examples:');
        $this->line('  <fg=cyan>php artisan app:help {command} --examples</>');
        $this->newLine();
        
        $this->line('To start interactive help mode:');
        $this->line('  <fg=cyan>php artisan app:help --interactive</>');
        $this->newLine();
        
        $this->line('To open beautiful HTML documentation in your browser:');
        $this->line('  <fg=cyan>php artisan app:help --html</>');
        $this->newLine();
        
        $this->info('For more information, visit: https://ez-it-solutions.com/docs/laravel-init');
    }
    
    /**
     * List all available commands.
     *
     * @return int
     */
    protected function listAllCommands()
    {
        $this->newLine();
        $this->info('📋 Available Commands');
        $this->info('===================');
        $this->newLine();
        
        $table = new Table($this->output);
        $table->setHeaders(['Command', 'Description']);
        
        foreach ($this->commandInfo as $command => $info) {
            $table->addRow([$command, $info['description']]);
        }
        
        $table->render();
        $this->newLine();
        
        return 0;
    }
    
    /**
     * Show help for a specific command.
     *
     * @param string $command
     * @return int
     */
    protected function showCommandHelp($command)
    {
        if (!isset($this->commandInfo[$command])) {
            $this->error("Command '$command' not found.");
            $this->line("Run <fg=cyan>php artisan app:help --list</> to see all available commands.");
            return 1;
        }
        
        $info = $this->commandInfo[$command];
        
        $this->newLine();
        $this->info("📖 Help: $command");
        $this->info(str_repeat('=', 9 + strlen($command)));
        $this->newLine();
        
        $this->line("<fg=yellow;options=bold>Description:</>");
        $this->line("  " . $info['description']);
        $this->newLine();
        
        $this->line("<fg=yellow;options=bold>Summary:</>");
        $this->line("  " . $info['summary']);
        $this->newLine();
        
        if ($this->option('examples')) {
            $this->line("<fg=yellow;options=bold>Examples:</>");
            foreach ($info['examples'] as $title => $example) {
                $this->line("  • <fg=green>$title:</> $example");
            }
            $this->newLine();
        } else {
            $this->line("Use <fg=cyan>php artisan app:help $command --examples</> to see usage examples.");
            $this->newLine();
        }
        
        if (!empty($info['related'])) {
            $this->line("<fg=yellow;options=bold>Related Commands:</>");
            foreach ($info['related'] as $relatedCmd) {
                $this->line("  • <fg=green>$relatedCmd</> - " . $this->commandInfo[$relatedCmd]['description']);
            }
            $this->newLine();
        }
        
        $this->line("For more details, run: <fg=cyan>php artisan help $command</>");
        $this->newLine();
        
        return 0;
    }
    
    /**
     * Open HTML documentation in a browser.
     *
     * @param string|null $command Specific command to show documentation for
     * @return int
     */
    protected function openHtmlDocumentation($command = null)
    {
        $baseUrl = config('app.url', 'http://localhost:8000');
        
        // Build the documentation URL
        $url = $baseUrl . '/laravel-init/help';
        if ($command) {
            $url .= '/' . $command;
        }
        
        $this->info('Opening HTML documentation in your browser...');
        $this->newLine();
        $this->line("URL: <fg=blue>$url</>");
        $this->newLine();
        
        // Open the URL in the default browser based on the operating system
        if (PHP_OS_FAMILY === 'Windows') {
            exec('start ' . escapeshellarg($url));
        } elseif (PHP_OS_FAMILY === 'Darwin') { // macOS
            exec('open ' . escapeshellarg($url));
        } elseif (PHP_OS_FAMILY === 'Linux') {
            exec('xdg-open ' . escapeshellarg($url));
        } else {
            $this->warn('Could not automatically open the browser.');
            $this->line("Please manually open the following URL in your browser:");
            $this->line("<fg=blue>$url</>");
            return 1;
        }
        
        $this->info('Documentation opened in your browser.');
        $this->newLine();
        $this->line('If the browser did not open automatically, please manually navigate to:');
        $this->line("<fg=blue>$url</>");
        
        return 0;
    }
    
    protected function startInteractiveMode()
    {
        $this->newLine();
        $this->info('🔍 Interactive Help Mode');
        $this->info('======================');
        $this->newLine();
        
        $this->line("Welcome to interactive help mode. I'll guide you through the Laravel Initialization Utility.");
        $this->newLine();
        
        $categories = [
            '1' => ['name' => 'Getting Started', 'commands' => ['app:init', 'app:prepare']],
            '2' => ['name' => 'Deployment & Optimization', 'commands' => ['app:deploy', 'app:optimize']],
            '3' => ['name' => 'Maintenance & Development', 'commands' => ['app:cleanup', 'app:serve']],
            '4' => ['name' => 'Database Management', 'commands' => ['db:init', 'mysql:exec']],
            '5' => ['name' => 'Exit Interactive Mode', 'commands' => []],
        };
        
        while (true) {
            $this->line("<fg=yellow;options=bold>What would you like help with?</>");
            
            foreach ($categories as $key => $category) {
                $this->line("  $key. " . $category['name']);
            }
            
            $choice = $this->ask('Enter your choice (1-5)');
            
            if ($choice == '5') {
                $this->line('Exiting interactive help mode. Goodbye!');
                break;
            }
            
            if (!isset($categories[$choice])) {
                $this->error('Invalid choice. Please try again.');
                continue;
            }
            
            $category = $categories[$choice];
            $this->newLine();
            $this->info('📚 ' . $category['name']);
            $this->info(str_repeat('=', 4 + strlen($category['name'])));
            $this->newLine();
            
            $commands = [];
            foreach ($category['commands'] as $index => $cmd) {
                $num = $index + 1;
                $commands[$num] = $cmd;
                $this->line("  $num. <fg=green>$cmd</> - " . $this->commandInfo[$cmd]['description']);
            }
            $this->line("  " . (count($commands) + 1) . ". Back to main menu");
            
            $cmdChoice = $this->ask('Enter your choice (1-' . (count($commands) + 1) . ')');
            
            if ($cmdChoice == (count($commands) + 1)) {
                $this->newLine();
                continue;
            }
            
            if (!isset($commands[$cmdChoice])) {
                $this->error('Invalid choice. Please try again.');
                $this->newLine();
                continue;
            }
            
            $this->showCommandHelp($commands[$cmdChoice]);
            
            if ($this->confirm('Would you like to see examples for this command?')) {
                $this->line("<fg=yellow;options=bold>Examples:</>");
                foreach ($this->commandInfo[$commands[$cmdChoice]]['examples'] as $title => $example) {
                    $this->line("  • <fg=green>$title:</> $example");
                }
                $this->newLine();
            }
            
            $this->confirm('Press Enter to continue', true);
            $this->newLine();
        }
        
        return 0;
    }
}
