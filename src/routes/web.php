<?php

use Illuminate\Support\Facades\Route;
use Ez_IT_Solutions\AppInit\Http\Controllers\HelpController;

Route::group(['prefix' => 'laravel-init', 'as' => 'laravel-init.'], function () {
    Route::get('/help', [HelpController::class, 'index'])->name('help');
    Route::get('/help/{command}', [HelpController::class, 'command'])->name('help.command');
});
