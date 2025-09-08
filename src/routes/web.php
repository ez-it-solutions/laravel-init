<?php

use Illuminate\Support\Facades\Route;
use Ez_IT_Solutions\AppInit\Http\Controllers\HelpController;
use Ez_IT_Solutions\AppInit\Http\Controllers\SetupWizardController;

// Help documentation routes
Route::group(['prefix' => 'laravel-init', 'as' => 'laravel-init.'], function () {
    Route::get('/help', [HelpController::class, 'index'])->name('help');
    Route::get('/help/{command}', [HelpController::class, 'command'])->name('help.command');
});

// Setup wizard routes
Route::group(['prefix' => 'setup', 'as' => 'ez-it-solutions.setup.'], function () {
    // Dashboard
    Route::get('/', [SetupWizardController::class, 'index'])->name('index');
    
    // Documentation
    Route::get('/readme', [SetupWizardController::class, 'readme'])->name('readme');
    Route::get('/help/{command?}', [SetupWizardController::class, 'help'])->name('help');
    
    // Status
    Route::get('/status', [SetupWizardController::class, 'status'])->name('status');
    
    // Application configuration
    Route::get('/configure-app', [SetupWizardController::class, 'configureApp'])->name('configure-app');
    Route::post('/configure-app', [SetupWizardController::class, 'storeAppConfig'])->name('store-app-config');
    
    // Database setup
    Route::get('/setup-database', [SetupWizardController::class, 'setupDatabase'])->name('setup-database');
    Route::post('/setup-database', [SetupWizardController::class, 'storeDatabaseConfig'])->name('store-database-config');
    
    // Frontend setup
    Route::get('/setup-frontend', [SetupWizardController::class, 'setupFrontend'])->name('setup-frontend');
    Route::post('/setup-frontend', [SetupWizardController::class, 'storeFrontendConfig'])->name('store-frontend-config');
    
    // Optimization
    Route::get('/optimize-app', [SetupWizardController::class, 'optimizeApp'])->name('optimize-app');
    Route::post('/optimize-app', [SetupWizardController::class, 'runOptimization'])->name('run-optimization');
    
    // Database backup
    Route::get('/backup-database', [SetupWizardController::class, 'backupDatabase'])->name('backup-database');
    Route::post('/backup-database', [SetupWizardController::class, 'runDatabaseBackup'])->name('run-database-backup');
    
    // Configuration builder
    Route::get('/build-config', [SetupWizardController::class, 'buildConfig'])->name('build-config');
    Route::post('/build-config', [SetupWizardController::class, 'storeConfig'])->name('store-config');
});
