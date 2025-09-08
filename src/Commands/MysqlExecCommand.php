<?php

/**
 * MysqlExecCommand
 * 
 * Executes MySQL commands directly using the MySQL command-line client.
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

namespace Ez_IT_Solutions\DatabaseTools\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class MysqlExecCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mysql:exec
                            {--command= : MySQL command to execute}
                            {--list-commands : List available predefined commands}
                            {--mysql-path= : Path to MySQL executable (default: C:\\xampp\\mysql\\bin\\mysql.exe)}
                            {--db= : Database name (default: from .env)}
                            {--user= : MySQL username (default: from .env)}
                            {--password= : MySQL password (default: from .env)}
                            {--host= : MySQL host (default: from .env)}
                            {--port= : MySQL port (default: from .env)}
                            {--vertical : Print output in vertical format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute MySQL commands directly using the MySQL command-line client';

    /**
     * Available predefined commands
     * 
     * @var array
     */
    protected $predefinedCommands = [
        'show-databases' => 'SHOW DATABASES;',
        'show-tables' => 'SHOW TABLES;',
        'show-users' => 'SELECT user, host FROM mysql.user;',
        'show-status' => 'SHOW STATUS;',
        'show-variables' => 'SHOW VARIABLES;',
        'show-processlist' => 'SHOW PROCESSLIST;',
        'version' => 'SELECT VERSION();',
        'status' => 'STATUS;',
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->option('list-commands')) {
            return $this->listPredefinedCommands();
        }

        $command = $this->option('command');
        
        if (empty($command)) {
            $this->error('No command specified. Use --command="YOUR_SQL" or --list-commands');
            return Command::FAILURE;
        }

        // Check if it's a predefined command
        if (isset($this->predefinedCommands[$command])) {
            $command = $this->predefinedCommands[$command];
        }

        return $this->executeCommand($command);
    }

    /**
     * Execute the MySQL command
     *
     * @param string $sql
     * @return int
     */
    protected function executeCommand(string $sql): int
    {
        $mysqlPath = $this->option('mysql-path') ?: 'C:\\xampp\\mysql\\bin\\mysql.exe';
        
        // Build the command arguments
        $command = [
            $mysqlPath,
            '--user=' . $this->getOptionOrConfig('user', 'DB_USERNAME', 'root'),
            '--host=' . $this->getOptionOrConfig('host', 'DB_HOST', '127.0.0.1'),
            '--port=' . $this->getOptionOrConfig('port', 'DB_PORT', '3306'),
        ];

        // Add password if provided
        if ($password = $this->option('password') ?: env('DB_PASSWORD')) {
            $command[] = '--password=' . $password;
        }

        // Add database if specified
        if ($db = $this->option('db') ?: env('DB_DATABASE')) {
            $command[] = $db;
        }

        // Add vertical format if requested
        if ($this->option('vertical')) {
            $command[] = '--vertical';
        }

        // Add the SQL command
        $command[] = '--execute=' . $sql;

        // Execute the command
        $process = new Process($command);
        $process->setTimeout(60);
        $process->setIdleTimeout(30);

        try {
            $process->mustRun(function ($type, $buffer) {
                if (Process::ERR === $type) {
                    $this->error($buffer);
                } else {
                    $this->line($buffer);
                }
            });
            
            return Command::SUCCESS;
        } catch (ProcessFailedException $exception) {
            $this->error('Command failed: ' . $exception->getMessage());
            $this->line('Full command: ' . $process->getCommandLine());
            return Command::FAILURE;
        }
    }

    /**
     * Get option from command line or fall back to .env config
     *
     * @param string $option
     * @param string $envKey
     * @param mixed $default
     * @return mixed
     */
    protected function getOptionOrConfig(string $option, string $envKey, $default = null)
    {
        return $this->option($option) ?: env($envKey, $default);
    }

    /**
     * List all available predefined commands
     * 
     * @return int
     */
    protected function listPredefinedCommands(): int
    {
        $this->info('Available predefined commands:');
        $this->newLine();
        
        $headers = ['Command', 'Description'];
        $rows = [];
        
        foreach ($this->predefinedCommands as $cmd => $sql) {
            $rows[] = [
                $cmd,
                'Executes: ' . (strlen($sql) > 50 ? substr($sql, 0, 47) . '...' : $sql)
            ];
        }
        
        $this->table($headers, $rows);
        $this->newLine();
        $this->line('Example usage:');
        $this->line('  php artisan mysql:exec --command=show-databases');
        $this->line('  php artisan mysql:exec --command="SHOW TABLES;"');
        $this->line('  php artisan mysql:exec --command="SELECT * FROM users LIMIT 5;" --vertical');
        
        return Command::SUCCESS;
    }
}
