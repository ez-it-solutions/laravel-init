<?php

/**
 * File: HelpController.php
 * 
 * Purpose: Controller for serving the HTML help documentation.
 * 
 * @category    Controllers
 * @package     Ez_IT_Solutions\App_Init
 * @author      Chris Hultberg <chrishultberg@ez-it-solutions.com>
 * @website     https://www.Ez-IT-Solutions.com
 * @license     MIT
 * @link        https://github.com/ez-it-solutions/laravel-init
 * @copyright   Copyright (c) 2025 EZ IT Solutions
 * @version     1.0.0
 * @since       2025-09-07
 */

namespace Ez_IT_Solutions\AppInit\Http\Controllers;

use Illuminate\Routing\Controller;

class HelpController extends Controller
{
    /**
     * Display the HTML help documentation.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('ez-it-solutions::help');
    }
    
    /**
     * Display documentation for a specific command.
     *
     * @param string $command
     * @return \Illuminate\View\View
     */
    public function command($command)
    {
        return view('ez-it-solutions::help', [
            'activeCommand' => $command
        ]);
    }
}
