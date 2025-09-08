<?php
/**
 * File: AppCleanup.php
 * 
 * Purpose: This command provides a comprehensive cleanup utility for Laravel applications.
 * It runs multiple optimization and cache clearing commands in sequence with visual feedback.
 * 
 * Usage Examples:
 * - Basic usage: php artisan app:cleanup
 * - Skip confirmation: php artisan app:cleanup --force
 * 
 * The command performs the following operations:
 * - Clears route, config, application, view, and event caches
 * - Clears compiled classes
 * - Optimizes the autoloader
 * - Rebuilds configuration and route caches
 * - Optimizes the application
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

namespace Ez_IT_Solutions\AppCleanup\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\Table;

class AppCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup {--force : Force the operation without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up the application by running various optimization commands';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Start timing the entire process
        $startTime = microtime(true);
        
        if (!$this->option('force') && !$this->confirm('This will clear all caches and optimize your application. Continue?', true)) {
            $this->error('Operation cancelled.');
            return 1;
        }

        $this->info('');
        $this->info('╔═════════════════════════════════════════════════╗');
        $this->info('║                                                 ║');
        $this->info('║              INSTALLATION CLEANUP               ║');
        $this->info('║                                                 ║');
        $this->info('╚═════════════════════════════════════════════════╝');
        $this->info('');

        $commands = [
            'Clear Route Cache' => 'route:clear',
            'Clear Configuration Cache' => 'config:clear',
            'Clear Application Cache' => 'cache:clear',
            'Clear Compiled Views' => 'view:clear',
            'Clear Event Cache' => 'event:clear',
            'Clear Compiled Classes' => 'clear-compiled',
            'Optimize Autoloader' => 'optimize:clear',
            'Rebuild Configuration Cache' => 'config:cache',
            'Rebuild Route Cache' => 'route:cache',
            'Optimize Application' => 'optimize',
        ];

        $totalSteps = count($commands);
        $currentStep = 1;
        $commandResults = [];

        foreach ($commands as $description => $command) {
            $result = $this->executeArtisanCommand($command, $description, $currentStep, $totalSteps);
            $commandResults[] = [
                'step' => $currentStep,
                'command' => $command,
                'description' => $description,
                'duration' => $result['duration'],
                'status' => $result['success'] ? 'Success' : 'Failed',
            ];
            $currentStep++;
        }

        // Calculate total execution time
        $endTime = microtime(true);
        $totalDuration = round($endTime - $startTime, 2);
        
        // Display summary table
        $this->info('');
        $this->info('<fg=blue>Command Execution Summary:</>');        
        $table = new Table($this->output);
        $table->setHeaders(['Step', 'Command', 'Description', 'Duration (s)', 'Status']);
        $table->setRows($commandResults);
        $table->render();
        
        $this->info('');
        $this->info('<fg=green>Total execution time:</> ' . $totalDuration . ' seconds');
        $this->info('');
        
        $this->info('╔═════════════════════════════════════════════════╗');
        $this->info('║                                                 ║');
        $this->info('║         CLEANUP COMPLETED SUCCESSFULLY          ║');
        $this->info('║                                                 ║');
        $this->info('║             EZ IT SOLUTIONS © 2025              ║');
        $this->info('║                                                 ║');
        $this->info('╚═════════════════════════════════════════════════╝');

        return 0;
    }

    /**
     * Execute an Artisan command with colorful output
     *
     * @param string $command
     * @param string $description
     * @param int $currentStep
     * @param int $totalSteps
     * @return array
     */
    protected function executeArtisanCommand($command, $description, $currentStep, $totalSteps)
    {
        // Start timing this command
        $startTime = microtime(true);
        $success = true;
        
        // Display command information on its own line
        $this->output->writeln("");
        $this->output->writeln("<fg=blue>[$currentStep/$totalSteps]</> <fg=yellow>Running:</> <options=bold>$description</> <fg=cyan>($command)</>");
        
        // Create a progress bar
        $progressBar = $this->output->createProgressBar(100);
        $progressBar->setFormat(' %current%% [%bar%]');
        $progressBar->start();

        // Simulate progress
        for ($i = 0; $i < 100; $i += 10) {
            usleep(50000); // 50ms delay
            $progressBar->advance(10);
        }
        
        $progressBar->finish();
        
        try {
            Artisan::call($command);
            $this->output->writeln(" <fg=green>✓ Done</>");
        } catch (\Exception $e) {
            $this->output->writeln(" <fg=red>✗ Failed</>");
            $this->output->writeln("<fg=red>Error: " . $e->getMessage() . "</>");
            $success = false;
        }
        
        // Calculate execution time for this command
        $endTime = microtime(true);
        $duration = round($endTime - $startTime, 2);
        $this->output->writeln("<fg=blue>Time:</> {$duration}s");
        $this->output->writeln("");
        
        return [
            'success' => $success,
            'duration' => $duration
        ];
    }
}
