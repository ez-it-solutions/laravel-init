<?php

/**
 * File: AppServeCommand.php
 * 
 * Purpose: Serves the Laravel application on the first available port in a given range.
 * This command automatically finds an open port and launches the application server,
 * optionally opening the browser to the application URL.
 * 
 * Usage Examples:
 * - Basic usage: php artisan app:serve
 * - Custom host: php artisan app:serve --host=192.168.1.100
 * - Custom port range: php artisan app:serve --start-port=3000 --max-attempts=5
 * - Without browser: php artisan app:serve --no-open
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

namespace Ez_IT_Solutions\AppServe\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class AppServeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:serve 
        {--host=127.0.0.1 : The host address to serve the application on}
        {--start-port=8001 : The starting port number to try}
        {--max-attempts=10 : Maximum number of ports to try}
        {--no-open : Do not open the browser automatically}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Serve the application on the first available port';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $host = $this->option('host');
        $startPort = (int)$this->option('start-port');
        $maxAttempts = (int)$this->option('max-attempts');
        $noOpen = $this->option('no-open');
        
        $port = $this->findAvailablePort($host, $startPort, $maxAttempts);
        
        if ($port === null) {
            $this->error("Could not find an available port after {$maxAttempts} attempts.");
            return Command::FAILURE;
        }
        
        $url = "http://{$host}:{$port}";
        
        $this->info("Serving application on {$url}");
        $this->info("Press Ctrl+C to stop the server");
        
        if (!$noOpen) {
            $this->openBrowser($url);
        }
        
        // Start the server
        $process = new Process([
            PHP_BINARY,
            'artisan',
            'serve',
            '--host=' . $host,
            '--port=' . $port,
        ], base_path());
        
        // Allow the process to run indefinitely
        $process->setTimeout(null);
        
        try {
            // Display server output
            $process->run(function ($type, $buffer) {
                $this->output->write($buffer);
            });
            
            return Command::SUCCESS;
        } catch (ProcessFailedException $e) {
            $this->error('The server process failed to start.');
            $this->error($e->getMessage());
            return Command::FAILURE;
        }
    }
    
    /**
     * Find an available port starting from the given port
     *
     * @param string $host
     * @param int $startPort
     * @param int $maxAttempts
     * @return int|null
     */
    protected function findAvailablePort($host, $startPort, $maxAttempts)
    {
        $port = $startPort;
        $attempt = 0;
        
        while ($attempt < $maxAttempts) {
            $this->info("Checking if port {$port} is available...");
            
            if ($this->isPortAvailable($host, $port)) {
                return $port;
            }
            
            $port++;
            $attempt++;
        }
        
        return null;
    }
    
    /**
     * Check if a port is available
     *
     * @param string $host
     * @param int $port
     * @return bool
     */
    protected function isPortAvailable($host, $port)
    {
        $socket = @fsockopen($host, $port, $errno, $errstr, 1);
        
        if ($socket === false) {
            return true; // Port is available
        }
        
        fclose($socket);
        return false; // Port is in use
    }
    
    /**
     * Open the given URL in the default browser
     *
     * @param string $url
     * @return void
     */
    protected function openBrowser($url)
    {
        if (PHP_OS_FAMILY === 'Windows') {
            // Use 'start /B' to start the process in the background without a new window
            shell_exec('start "" /B ' . escapeshellarg($url));
        } elseif (PHP_OS_FAMILY === 'Darwin') {
            // On macOS, use 'open' with the URL
            shell_exec('open ' . escapeshellarg($url));
        } else {
            // On Linux, use 'xdg-open' with the URL
            shell_exec('xdg-open ' . escapeshellarg($url) . ' > /dev/null 2>&1 &');
        }
    }
}
