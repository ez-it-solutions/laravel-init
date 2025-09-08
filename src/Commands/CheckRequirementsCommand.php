<?php

/**
 * CheckRequirementsCommand
 * 
 * Verifies that the server environment meets all requirements for
 * running Laravel applications.
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
use Illuminate\Support\Facades\Process;

/**
 * Validates system requirements for Laravel applications.
 * 
 * This command performs comprehensive checks to ensure the server environment
 * meets all necessary requirements, including:
 * - PHP version and extensions
 * - Required software versions
 * - PHP configuration settings
 * - File and directory permissions
 */
class CheckRequirementsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-requirements';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check system requirements for JC Portal';

    /**
     * Required PHP extensions and their purpose
     *
     * @var array
     */
    protected $requiredExtensions = [
        'pdo' => 'Required for database access',
        'pdo_mysql' => 'Required for MySQL database',
        'mbstring' => 'Required for string manipulation',
        'xml' => 'Required for XML processing',
        'tokenizer' => 'Required for Laravel',
        'openssl' => 'Required for secure connections',
        'json' => 'Required for JSON processing',
        'fileinfo' => 'Required for file uploads',
        'gd' => 'Required for image manipulation',
        'intl' => 'Required for internationalization',
        'zip' => 'Required for package management',
        'curl' => 'Required for HTTP requests',
        'dom' => 'Required for DOM processing',
        'simplexml' => 'Required for XML processing',
    ];

    /**
     * Required PHP settings
     *
     * @var array
     */
    protected $requiredSettings = [
        'memory_limit' => '128M',
        'upload_max_filesize' => '20M',
        'post_max_size' => '20M',
        'max_execution_time' => '300',
        'max_input_time' => '300',
    ];

    /**
     * Required software and commands
     *
     * @var array
     */
    protected $requiredSoftware = [
        'node' => ['--version', '>=16.0.0', 'Node.js'],
        'npm' => ['--version', '>=8.0.0', 'NPM'],
        'composer' => ['--version', '>=2.0.0', 'Composer'],
        'php' => ['-v', '>=8.1.0', 'PHP'],
        'mysql' => ['--version', '>=8.0.0', 'MySQL'],
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking JC Portal system requirements...');
        $this->newLine();

        $this->checkPhpVersion();
        $this->checkPhpExtensions();
        $this->checkPhpSettings();
        $this->checkRequiredSoftware();

        $this->newLine();
        $this->info('System check completed.');

        return Command::SUCCESS;
    }

    /**
     * Check PHP version
     */
    protected function checkPhpVersion(): void
    {
        $this->info('🔍 Checking PHP version...');
        
        $phpVersion = phpversion();
        $minVersion = '8.1.0';
        
        if (version_compare($phpVersion, $minVersion, '>=')) {
            $this->line("✅ PHP version: {$phpVersion} (>= {$minVersion} required)", 'info');
        } else {
            $this->error("❌ PHP version: {$phpVersion} (>= {$minVersion} required)");
            $this->warn('  Please upgrade your PHP version to at least ' . $minVersion);
        }
        
        $this->newLine();
    }

    /**
     * Check required PHP extensions
     */
    protected function checkPhpExtensions(): void
    {
        $this->info('🔍 Checking PHP extensions...');
        
        $rows = [];
        $allLoaded = true;
        
        foreach ($this->requiredExtensions as $extension => $description) {
            $isLoaded = extension_loaded($extension);
            $status = $isLoaded ? '✅' : '❌';
            $rows[] = [
                'extension' => $extension,
                'status' => $isLoaded ? 'Installed' : 'Missing',
                'icon' => $status,
                'description' => $description,
            ];
            
            if (!$isLoaded) {
                $allLoaded = false;
            }
        }
        
        $this->table(
            ['Extension', 'Status', '', 'Description'],
            $rows,
            'default'
        );
        
        if (!$allLoaded) {
            $this->warn('Some required PHP extensions are missing. Please install them.');
            $this->line('On Ubuntu/Debian, you can install them with:');
            $this->line('  sudo apt-get install php' . PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION . '-' . 
                implode(' php' . PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION . '-', 
                    array_keys($this->requiredExtensions)));
        } else {
            $this->info('✅ All required PHP extensions are installed.');
        }
        
        $this->newLine();
    }

    /**
     * Check PHP settings
     */
    protected function checkPhpSettings(): void
    {
        $this->info('🔍 Checking PHP settings...');
        
        $rows = [];
        $allValid = true;
        
        foreach ($this->requiredSettings as $setting => $requiredValue) {
            $currentValue = ini_get($setting);
            $isValid = $this->compareIniValues($currentValue, $requiredValue);
            $status = $isValid ? '✅' : '⚠️';
            
            if (!$isValid) {
                $allValid = false;
            }
            
            $rows[] = [
                'setting' => $setting,
                'current' => $currentValue,
                'required' => $requiredValue,
                'status' => $status,
            ];
        }
        
        $this->table(
            ['Setting', 'Current', 'Required', 'Status'],
            $rows,
            'default'
        );
        
        if (!$allValid) {
            $this->warn('Some PHP settings need adjustment.');
            $this->line('You can update these in your php.ini file or .htaccess:');
            foreach ($this->requiredSettings as $setting => $value) {
                $this->line("  {$setting} = {$value}");
            }
        } else {
            $this->info('✅ All PHP settings are properly configured.');
        }
        
        $this->newLine();
    }

    /**
     * Check required software
     */
    protected function checkRequiredSoftware(): void
    {
        $this->info('🔍 Checking required software...');
        
        $rows = [];
        $allInstalled = true;
        
        foreach ($this->requiredSoftware as $command => $data) {
            list($arg, $minVersion, $name) = $data;
            $version = $this->getCommandVersion($command, $arg);
            
            if ($version) {
                $version = trim($version);
                $isValid = version_compare($version, $minVersion, '>=');
                $status = $isValid ? '✅' : '⚠️';
                $rows[] = [
                    'software' => $name,
                    'version' => $version,
                    'required' => '>=' . $minVersion,
                    'status' => $status,
                ];
                
                if (!$isValid) {
                    $allInstalled = false;
                }
            } else {
                $allInstalled = false;
                $rows[] = [
                    'software' => $name,
                    'version' => 'Not found',
                    'required' => '>=' . $minVersion,
                    'status' => '❌',
                ];
            }
        }
        
        $this->table(
            ['Software', 'Version', 'Required', 'Status'],
            $rows,
            'default'
        );
        
        if (!$allInstalled) {
            $this->warn('Some required software is missing or needs updating.');
        } else {
            $this->info('✅ All required software is installed and up to date.');
        }
        
        $this->newLine();
    }

    /**
     * Get command version
     */
    protected function getCommandVersion(string $command, string $arg): ?string
    {
        try {
            $result = Process::run("$command $arg");
            if ($result->successful()) {
                return $result->output();
            }
        } catch (\Exception $e) {
            // Command not found
        }
        
        return null;
    }

    /**
     * Compare INI values
     */
    protected function compareIniValues(string $current, string $required): bool
    {
        // Convert both values to bytes for comparison
        $currentBytes = $this->convertToBytes($current);
        $requiredBytes = $this->convertToBytes($required);
        
        return $currentBytes >= $requiredBytes;
    }

    /**
     * Convert INI size values to bytes
     */
    protected function convertToBytes(string $value): int
    {
        $value = trim($value);
        $last = strtolower($value[strlen($value) - 1]);
        $value = (int) $value;
        
        switch ($last) {
            case 'g':
                $value *= 1024;
                // no break
            case 'm':
                $value *= 1024;
                // no break
            case 'k':
                $value *= 1024;
        }
        
        return $value;
    }
}
