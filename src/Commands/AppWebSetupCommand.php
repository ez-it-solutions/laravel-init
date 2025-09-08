<?php

/**
 * AppWebSetupCommand
 * 
 * Launches a web-based setup wizard for configuring and using various features
 * of the Laravel Initialization Utility.
 * 
 * @category    Commands
 * @package     Ez_IT_Solutions\App_Init
 * @author      Chris Hultberg <chrishultberg@ez-it-solutions.com>
 * @see         https://www.Ez-IT-Solutions.com
 * @license     MIT
 * @link        https://github.com/ez-it-solutions/laravel-init
 * @copyright   Copyright (c) 2025, EZ IT Solutions
 * @version     1.0.0
 * @since       2025-09-07
 */

namespace Ez_IT_Solutions\AppInit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;

class AppWebSetupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:web-setup
                            {--port=8000 : The port to serve the application on}
                            {--host=127.0.0.1 : The host to serve the application on}
                            {--no-open : Do not open the browser automatically}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Launch a web-based setup wizard for Laravel Initialization Utility';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🚀 Launching Laravel Initialization Utility Web Setup Wizard');
        $this->newLine();
        
        $host = $this->option('host');
        $port = $this->option('port');
        $noOpen = $this->option('no-open');
        
        // Check if the port is available
        if (!$this->isPortAvailable($host, $port)) {
            $this->error("Port {$port} is already in use.");
            
            // Try to find an available port
            $newPort = $this->findAvailablePort($host, $port + 1, $port + 10);
            
            if ($newPort) {
                if ($this->confirm("Would you like to use port {$newPort} instead?", true)) {
                    $port = $newPort;
                } else {
                    $this->error('Setup wizard launch aborted.');
                    return Command::FAILURE;
                }
            } else {
                $this->error('Could not find an available port. Please specify a different port using the --port option.');
                return Command::FAILURE;
            }
        }
        
        // Build the URL
        $url = "http://{$host}:{$port}/setup";
        
        // Start the server
        $this->info("Starting server at {$url}");
        $this->newLine();
        
        // Create the server process
        $process = new Process([
            PHP_BINARY,
            '-S',
            "{$host}:{$port}",
            '-t',
            public_path(),
        ]);
        
        // Start the process
        $process->start();
        
        // Wait a moment for the server to start
        sleep(1);
        
        // Check if the process is running
        if (!$process->isRunning()) {
            $this->error('Failed to start the server.');
            $this->error($process->getErrorOutput());
            return Command::FAILURE;
        }
        
        // Open the browser if requested
        if (!$noOpen) {
            $this->info('Opening browser...');
            $this->openBrowser($url);
        }
        
        $this->info("Setup wizard is now available at {$url}");
        $this->info('Press Ctrl+C to stop the server');
        
        // Keep the process running until the user stops it
        while ($process->isRunning()) {
            sleep(1);
        }
        
        return Command::SUCCESS;
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
        
        if ($socket) {
            fclose($socket);
            return false;
        }
        
        return true;
    }
    
    /**
     * Find an available port
     *
     * @param string $host
     * @param int $startPort
     * @param int $endPort
     * @return int|null
     */
    protected function findAvailablePort($host, $startPort, $endPort)
    {
        for ($port = $startPort; $port <= $endPort; $port++) {
            if ($this->isPortAvailable($host, $port)) {
                return $port;
            }
        }
        
        return null;
    }
    
    /**
     * Open the browser
     *
     * @param string $url
     * @return void
     */
    protected function openBrowser($url)
    {
        if (PHP_OS_FAMILY === 'Windows') {
            exec('start ' . escapeshellarg($url));
        } elseif (PHP_OS_FAMILY === 'Darwin') { // macOS
            exec('open ' . escapeshellarg($url));
        } elseif (PHP_OS_FAMILY === 'Linux') {
            exec('xdg-open ' . escapeshellarg($url));
        } else {
            $this->warn('Could not automatically open the browser.');
            $this->line("Please manually open the following URL in your browser:");
            $this->line($url);
        }
    }
}
