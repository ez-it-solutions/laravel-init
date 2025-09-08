<?php

/**
 * SetupWizardController
 * 
 * Provides a web-based wizard interface for configuring and using various features
 * of the Laravel Initialization Utility.
 * 
 * @category    Controllers
 * @package     Ez_IT_Solutions\App_Init
 * @author      Chris Hultberg <chrishultberg@ez-it-solutions.com>
 * @see         https://www.Ez-IT-Solutions.com
 * @license     MIT
 * @link        https://github.com/ez-it-solutions/laravel-init
 * @copyright   Copyright (c) 2025, EZ IT Solutions
 * @version     1.0.0
 * @since       2025-09-07
 */

namespace Ez_IT_Solutions\AppInit\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Process\Process;

class SetupWizardController extends Controller
{
    /**
     * Show the setup wizard welcome page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('ez-it-solutions::setup.index', [
            'title' => 'Laravel Initialization Utility - Setup Wizard'
        ]);
    }

    /**
     * Show the README documentation
     *
     * @return \Illuminate\View\View
     */
    public function readme()
    {
        $readmePath = base_path('README.md');
        $content = '';
        
        if (File::exists($readmePath)) {
            $content = File::get($readmePath);
            // Convert markdown to HTML (requires league/commonmark package)
            if (class_exists('\League\CommonMark\CommonMarkConverter')) {
                $converter = new \League\CommonMark\CommonMarkConverter();
                $content = $converter->convertToHtml($content);
            }
        } else {
            $content = '<div class="alert alert-warning">README.md file not found!</div>';
        }
        
        return view('ez-it-solutions::setup.documentation', [
            'title' => 'README Documentation',
            'content' => $content
        ]);
    }

    /**
     * Show the help documentation
     *
     * @param string|null $command
     * @return \Illuminate\View\View
     */
    public function help($command = null)
    {
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
            'app:setup' => 'Interactive setup wizard for Laravel Initialization Utility',
        ];
        
        $helpContent = '';
        
        if ($command) {
            Artisan::call('help', ['command_name' => $command]);
            $helpContent = Artisan::output();
            // Convert console output to HTML
            $helpContent = '<pre class="console-output">' . htmlspecialchars($helpContent) . '</pre>';
        }
        
        return view('ez-it-solutions::setup.help', [
            'title' => 'Help Documentation',
            'commands' => $commands,
            'selectedCommand' => $command,
            'helpContent' => $helpContent
        ]);
    }

    /**
     * Show the application status
     *
     * @return \Illuminate\View\View
     */
    public function status()
    {
        Artisan::call('app:status', ['--verbose' => true]);
        $statusOutput = Artisan::output();
        
        // Parse the status output to get structured data
        $configFiles = $this->parseStatusSection($statusOutput, 'Configuration Files:');
        $envVars = $this->parseStatusSection($statusOutput, 'Environment Variables:');
        $nodePackages = $this->parseStatusSection($statusOutput, 'Node Packages:');
        $composerPackages = $this->parseStatusSection($statusOutput, 'Composer Packages:');
        $systemInfo = $this->parseStatusSection($statusOutput, 'System Information:');
        
        return view('ez-it-solutions::setup.status', [
            'title' => 'Application Status',
            'configFiles' => $configFiles,
            'envVars' => $envVars,
            'nodePackages' => $nodePackages,
            'composerPackages' => $composerPackages,
            'systemInfo' => $systemInfo,
            'rawOutput' => $statusOutput
        ]);
    }

    /**
     * Show the application configuration form
     *
     * @return \Illuminate\View\View
     */
    public function configureApp()
    {
        return view('ez-it-solutions::setup.configure-app', [
            'title' => 'Configure Application',
            'appName' => config('app.name', 'Laravel'),
            'appEnv' => config('app.env', 'local'),
            'appDebug' => config('app.debug', true),
            'appUrl' => config('app.url', 'http://localhost')
        ]);
    }

    /**
     * Process the application configuration form
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeAppConfig(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_env' => 'required|string|in:local,development,staging,production',
            'app_debug' => 'boolean',
            'app_url' => 'required|url'
        ]);
        
        // Update .env file
        $this->updateEnvFile([
            'APP_NAME' => $validated['app_name'],
            'APP_ENV' => $validated['app_env'],
            'APP_DEBUG' => $validated['app_debug'] ? 'true' : 'false',
            'APP_URL' => $validated['app_url']
        ]);
        
        return redirect()->route('ez-it-solutions.setup.configure-app')
            ->with('success', 'Application configuration saved successfully!');
    }

    /**
     * Show the database configuration form
     *
     * @return \Illuminate\View\View
     */
    public function setupDatabase()
    {
        return view('ez-it-solutions::setup.setup-database', [
            'title' => 'Database Setup',
            'connection' => config('database.default', 'mysql'),
            'host' => config('database.connections.mysql.host', '127.0.0.1'),
            'port' => config('database.connections.mysql.port', '3306'),
            'database' => config('database.connections.mysql.database', 'laravel'),
            'username' => config('database.connections.mysql.username', 'root'),
            'charset' => config('database.connections.mysql.charset', 'utf8mb4'),
            'collation' => config('database.connections.mysql.collation', 'utf8mb4_unicode_ci')
        ]);
    }

    /**
     * Process the database configuration form
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeDatabaseConfig(Request $request)
    {
        $validated = $request->validate([
            'connection' => 'required|string|in:mysql,pgsql,sqlite,sqlsrv',
            'host' => 'required_unless:connection,sqlite|string|max:255',
            'port' => 'required_unless:connection,sqlite|string|max:10',
            'database' => 'required|string|max:255',
            'username' => 'required_unless:connection,sqlite|string|max:255',
            'password' => 'nullable|string|max:255',
            'charset' => 'required|string|max:20',
            'collation' => 'required_unless:connection,sqlite|string|max:40',
            'run_migrations' => 'boolean',
            'run_seeders' => 'boolean'
        ]);
        
        // Update .env file
        $this->updateEnvFile([
            'DB_CONNECTION' => $validated['connection'],
            'DB_HOST' => $validated['host'] ?? '127.0.0.1',
            'DB_PORT' => $validated['port'] ?? '3306',
            'DB_DATABASE' => $validated['database'],
            'DB_USERNAME' => $validated['username'] ?? '',
            'DB_PASSWORD' => $validated['password'] ?? '',
        ]);
        
        // Initialize database if requested
        if ($request->has('initialize')) {
            $options = [
                '--force' => true
            ];
            
            if ($validated['run_migrations'] ?? false) {
                $options['--migrate'] = true;
            }
            
            if ($validated['run_seeders'] ?? false) {
                $options['--seed'] = true;
            }
            
            Artisan::call('db:init', $options);
            $output = Artisan::output();
            
            return redirect()->route('ez-it-solutions.setup.setup-database')
                ->with('success', 'Database configuration saved and initialized successfully!')
                ->with('output', $output);
        }
        
        return redirect()->route('ez-it-solutions.setup.setup-database')
            ->with('success', 'Database configuration saved successfully!');
    }

    /**
     * Show the frontend setup form
     *
     * @return \Illuminate\View\View
     */
    public function setupFrontend()
    {
        return view('ez-it-solutions::setup.setup-frontend', [
            'title' => 'Frontend Setup',
            'stack' => config('init.frontend.default_stack', 'vite'),
            'installTailwind' => config('init.frontend.tailwind.install_by_default', true),
            'withPlugins' => config('init.frontend.tailwind.with_plugins', true),
            'withDarkMode' => config('init.frontend.tailwind.with_dark_mode', true),
            'packageManager' => config('init.frontend.package_manager', 'npm')
        ]);
    }

    /**
     * Process the frontend setup form
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeFrontendConfig(Request $request)
    {
        $validated = $request->validate([
            'stack' => 'required|string|in:vite,mix',
            'install_tailwind' => 'boolean',
            'with_plugins' => 'boolean',
            'with_dark_mode' => 'boolean',
            'package_manager' => 'required|string|in:npm,yarn,pnpm'
        ]);
        
        // Install TailwindCSS if requested
        if ($validated['install_tailwind'] && $request->has('install')) {
            $options = [
                '--with-' . $validated['stack'] => true
            ];
            
            if ($validated['with_plugins']) {
                $options['--with-plugins'] = true;
            }
            
            if ($validated['with_dark_mode']) {
                $options['--dark-mode'] = true;
            }
            
            Artisan::call('app:install-tailwindcss', $options);
            $output = Artisan::output();
            
            return redirect()->route('ez-it-solutions.setup.setup-frontend')
                ->with('success', 'Frontend configuration saved and TailwindCSS installed successfully!')
                ->with('output', $output);
        }
        
        return redirect()->route('ez-it-solutions.setup.setup-frontend')
            ->with('success', 'Frontend configuration saved successfully!');
    }

    /**
     * Show the optimization form
     *
     * @return \Illuminate\View\View
     */
    public function optimizeApp()
    {
        return view('ez-it-solutions::setup.optimize-app', [
            'title' => 'Optimize Application',
            'clearCache' => true,
            'optimizeConfig' => true,
            'optimizeRoutes' => true,
            'optimizeViews' => true,
            'optimizeAutoloader' => true
        ]);
    }

    /**
     * Process the optimization form
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function runOptimization(Request $request)
    {
        $validated = $request->validate([
            'clear_cache' => 'boolean',
            'optimize_config' => 'boolean',
            'optimize_routes' => 'boolean',
            'optimize_views' => 'boolean',
            'optimize_autoloader' => 'boolean'
        ]);
        
        $options = [
            '--skip-config' => !($validated['optimize_config'] ?? true),
            '--skip-routes' => !($validated['optimize_routes'] ?? true),
            '--skip-views' => !($validated['optimize_views'] ?? true)
        ];
        
        Artisan::call('app:optimize', $options);
        $output = Artisan::output();
        
        if ($validated['optimize_autoloader'] ?? true) {
            $process = new Process(['composer', 'dump-autoload', '--optimize']);
            $process->run();
            $output .= "\n" . $process->getOutput();
        }
        
        return redirect()->route('ez-it-solutions.setup.optimize-app')
            ->with('success', 'Application optimized successfully!')
            ->with('output', $output);
    }

    /**
     * Show the database backup form
     *
     * @return \Illuminate\View\View
     */
    public function backupDatabase()
    {
        return view('ez-it-solutions::setup.backup-database', [
            'title' => 'Database Backup',
            'format' => config('database.ez-it-solutions.backup_compression', 'gz'),
            'storage' => config('database.ez-it-solutions.backup_cloud_storage', 'local'),
            'path' => config('database.ez-it-solutions.backup_dir', storage_path('app/backups')),
            'retentionDays' => config('database.ez-it-solutions.backup_retention_days', 7),
            'maxBackups' => config('database.ez-it-solutions.max_backups', 10),
            'withData' => true,
            'notify' => false
        ]);
    }

    /**
     * Process the database backup form
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function runDatabaseBackup(Request $request)
    {
        $validated = $request->validate([
            'format' => 'required|string|in:sql,gz,zip',
            'storage' => 'required|string',
            'path' => 'required|string',
            'tables' => 'nullable|string',
            'exclude' => 'nullable|string',
            'with_data' => 'boolean',
            'notify' => 'boolean'
        ]);
        
        $options = [
            '--format' => $validated['format'],
            '--storage' => $validated['storage'],
            '--path' => $validated['path'],
            '--force' => true
        ];
        
        if (!empty($validated['tables'])) {
            $options['--tables'] = $validated['tables'];
        }
        
        if (!empty($validated['exclude'])) {
            $options['--exclude'] = $validated['exclude'];
        }
        
        if ($validated['with_data'] ?? true) {
            $options['--with-data'] = true;
        } else {
            $options['--structure-only'] = true;
        }
        
        if ($validated['notify'] ?? false) {
            $options['--notify'] = true;
        }
        
        Artisan::call('db:backup', $options);
        $output = Artisan::output();
        
        return redirect()->route('ez-it-solutions.setup.backup-database')
            ->with('success', 'Database backup created successfully!')
            ->with('output', $output);
    }

    /**
     * Show the configuration builder form
     *
     * @return \Illuminate\View\View
     */
    public function buildConfig()
    {
        return view('ez-it-solutions::setup.build-config', [
            'title' => 'Configuration Builder',
            'features' => [
                'app_config' => 'Application Configuration',
                'database_config' => 'Database Configuration',
                'frontend_config' => 'Frontend Configuration',
                'optimization' => 'Application Optimization',
                'backup_config' => 'Database Backup Configuration'
            ]
        ]);
    }

    /**
     * Process the configuration builder form
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeConfig(Request $request)
    {
        $validated = $request->validate([
            'filename' => 'required|string',
            'features' => 'required|array',
            'features.*' => 'string|in:app_config,database_config,frontend_config,optimization,backup_config'
        ]);
        
        $configData = [
            'generated_at' => now()->toIso8601String(),
            'features' => $validated['features'],
            'options' => []
        ];
        
        // Add options based on selected features
        if (in_array('app_config', $validated['features'])) {
            $configData['options']['app'] = [
                'app_name' => config('app.name'),
                'app_env' => config('app.env'),
                'app_debug' => config('app.debug'),
                'app_url' => config('app.url')
            ];
        }
        
        if (in_array('database_config', $validated['features'])) {
            $connection = config('database.default');
            $configData['options']['database'] = [
                'connection' => $connection,
                'host' => config("database.connections.{$connection}.host"),
                'port' => config("database.connections.{$connection}.port"),
                'database' => config("database.connections.{$connection}.database"),
                'username' => config("database.connections.{$connection}.username"),
                'charset' => config("database.connections.{$connection}.charset"),
                'collation' => config("database.connections.{$connection}.collation")
            ];
        }
        
        if (in_array('frontend_config', $validated['features'])) {
            $configData['options']['frontend'] = [
                'stack' => config('init.frontend.default_stack'),
                'install_tailwind' => config('init.frontend.tailwind.install_by_default'),
                'with_plugins' => config('init.frontend.tailwind.with_plugins'),
                'with_dark_mode' => config('init.frontend.tailwind.with_dark_mode'),
                'package_manager' => config('init.frontend.package_manager')
            ];
        }
        
        if (in_array('optimization', $validated['features'])) {
            $configData['options']['optimization'] = [
                'clear_cache' => true,
                'optimize_config' => !config('init.commands.app_optimize.skip_config_cache'),
                'optimize_routes' => !config('init.commands.app_optimize.skip_route_cache'),
                'optimize_views' => !config('init.commands.app_optimize.skip_view_cache'),
                'optimize_autoloader' => true
            ];
        }
        
        if (in_array('backup_config', $validated['features'])) {
            $configData['options']['backup'] = [
                'format' => config('database.ez-it-solutions.backup_compression'),
                'storage' => config('database.ez-it-solutions.backup_cloud_storage'),
                'path' => config('database.ez-it-solutions.backup_dir'),
                'retention_days' => config('database.ez-it-solutions.backup_retention_days'),
                'max_backups' => config('database.ez-it-solutions.max_backups'),
                'with_data' => true,
                'notify' => false
            ];
        }
        
        $filename = $validated['filename'];
        if (!str_ends_with($filename, '.json')) {
            $filename .= '.json';
        }
        
        $configPath = base_path($filename);
        $jsonContent = json_encode($configData, JSON_PRETTY_PRINT);
        
        File::put($configPath, $jsonContent);
        
        return redirect()->route('ez-it-solutions.setup.build-config')
            ->with('success', "Configuration file saved to: {$filename}")
            ->with('config', $jsonContent);
    }

    /**
     * Parse a section from the status output
     *
     * @param string $output
     * @param string $sectionTitle
     * @return array
     */
    protected function parseStatusSection($output, $sectionTitle)
    {
        $result = [];
        $inSection = false;
        $lines = explode("\n", $output);
        
        foreach ($lines as $line) {
            if (str_contains($line, $sectionTitle)) {
                $inSection = true;
                continue;
            }
            
            if ($inSection) {
                // Check if we've reached the next section
                if (preg_match('/^[A-Z]/', trim($line)) && !str_starts_with(trim($line), '  ')) {
                    break;
                }
                
                // Parse the line
                $line = trim($line);
                if (!empty($line)) {
                    if (preg_match('/\[([✓✗])\]\s+(.+)/', $line, $matches)) {
                        $status = $matches[1] === '✓';
                        $name = $matches[2];
                        $result[] = [
                            'name' => $name,
                            'status' => $status
                        ];
                    } elseif (!str_starts_with($line, '  ')) {
                        $result[] = [
                            'name' => $line,
                            'status' => null
                        ];
                    }
                }
            }
        }
        
        return $result;
    }

    /**
     * Update the .env file with new values
     *
     * @param array $values
     * @return void
     */
    protected function updateEnvFile(array $values)
    {
        $envPath = base_path('.env');
        
        if (File::exists($envPath)) {
            $envContent = File::get($envPath);
            
            foreach ($values as $key => $value) {
                // Escape any quotes
                $value = is_string($value) ? '"' . addslashes($value) . '"' : $value;
                
                // Check if the key exists
                if (preg_match("/^{$key}=/m", $envContent)) {
                    // Replace the value
                    $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $envContent);
                } else {
                    // Add the key-value pair
                    $envContent .= "\n{$key}={$value}";
                }
            }
            
            File::put($envPath, $envContent);
        }
    }
}
