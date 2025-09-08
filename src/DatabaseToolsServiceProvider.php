<?php
/**
 * File: DatabaseToolsServiceProvider.php
 * 
 * Purpose: Service provider for the Database Tools package.
 * Registers database-related commands with Laravel.
 * 
 * @category    ServiceProviders
 * @package     Ez_IT_Solutions\App_Init
 * @author      Chris Hultberg <chrishultberg@ez-it-solutions.com>
 * @website     https://www.Ez-IT-Solutions.com
 * @license     MIT
 * @link        https://github.com/ez-it-solutions/laravel-init
 * @copyright   Copyright (c) 2025 EZ IT Solutions
 * @version     1.0.0
 * @since       2025-09-07
 */

namespace Ez_IT_Solutions\DatabaseTools;

use Illuminate\Support\ServiceProvider;
use Ez_IT_Solutions\DatabaseTools\Commands\DatabaseInitCommand;
use Ez_IT_Solutions\DatabaseTools\Commands\MysqlExecCommand;

class DatabaseToolsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                DatabaseInitCommand::class,
                MysqlExecCommand::class,
            ]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // No additional services to register
    }
}
