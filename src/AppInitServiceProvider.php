<?php
/**
 * File: AppInitServiceProvider.php
 * 
 * Purpose: Service provider for the Laravel Initialization Utility.
 * Registers the initialization-related commands with Laravel.
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

namespace Ez_IT_Solutions\AppInit;

use Illuminate\Support\ServiceProvider;
use Ez_IT_Solutions\AppInit\Commands\AppInit;
use Ez_IT_Solutions\AppInit\Commands\AppDeploy;
use Ez_IT_Solutions\AppInit\Commands\AppOptimize;
use Ez_IT_Solutions\AppInit\Commands\AppPrepare;
use Ez_IT_Solutions\AppInit\Commands\AppHelpCommand;
use Ez_IT_Solutions\AppInit\Commands\CheckRequirementsCommand;
use Ez_IT_Solutions\AppInit\Commands\DatabaseInitCommand;

class AppInitServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register commands when running in console
        if ($this->app->runningInConsole()) {
            $this->commands([
                AppInit::class,
                AppDeploy::class,
                AppOptimize::class,
                AppPrepare::class,
                AppHelpCommand::class,
                CheckRequirementsCommand::class,
                DatabaseInitCommand::class,
            ]);
        }
        
        // Register views
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'ez-it-solutions');
        
        // Register routes
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
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
