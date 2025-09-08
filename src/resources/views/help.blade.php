@extends('ez-it-solutions::layout')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-12">
            <h1 class="text-4xl font-bold text-blue-600 mb-4" id="overview">Laravel Initialization Utility</h1>
            <p class="text-xl text-gray-600 mb-6">A comprehensive toolkit for Laravel application lifecycle management</p>
            
            <div class="flex flex-wrap gap-2 mb-6">
                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">MIT License</span>
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">PHP 7.3+</span>
                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Laravel 6.0+</span>
                <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded-full">v1.0.0</span>
            </div>
            
            <p class="text-gray-700 mb-4">
                The Laravel Initialization Utility is a comprehensive toolkit designed to streamline the entire lifecycle of Laravel application development, deployment, and maintenance. It provides a collection of powerful commands that handle everything from initial setup to production optimization.
            </p>
            
            <p class="text-gray-700 mb-4">
                This utility combines multiple essential functions into a single, cohesive package, eliminating the need for various separate tools and scripts. Whether you're setting up a new project, preparing for deployment, or optimizing performance, this toolkit has you covered.
            </p>
        </div>
        
        <div class="mb-12" id="installation">
            <h2 class="text-2xl font-bold text-blue-600 mb-4">Installation</h2>
            
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Requirements</h3>
            <ul class="list-disc list-inside mb-4 text-gray-700">
                <li>PHP 7.3 or higher</li>
                <li>Laravel 6.0 or higher</li>
                <li>Composer</li>
            </ul>
            
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Via Composer</h3>
            <div class="relative">
                <pre class="code-block"><code class="language-bash">composer require ez-it-solutions/laravel-init</code></pre>
            </div>
            
            <p class="text-gray-700 mb-4">
                That's it! The package will automatically register all commands with Laravel through auto-discovery.
            </p>
        </div>
        
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-blue-600 mb-4">Available Commands</h2>
            
            <div class="mb-8" id="app-init">
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">app:init</h3>
                    <p class="text-gray-600 mb-4">Initialize Laravel applications with all required setup steps</p>
                    
                    <div class="mb-4">
                        <h4 class="text-lg font-medium text-gray-800 mb-2">Description</h4>
                        <p class="text-gray-700">
                            Main command that orchestrates the entire initialization process including system checks, database setup, and application preparation.
                        </p>
                    </div>
                    
                    <div class="mb-4">
                        <h4 class="text-lg font-medium text-gray-800 mb-2">Usage</h4>
                        <div class="relative">
                            <pre class="code-block"><code class="language-bash">php artisan app:init</code></pre>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h4 class="text-lg font-medium text-gray-800 mb-2">Options</h4>
                        <div class="pl-4">
                            <div class="command-option">
                                <p class="font-medium">--skip-requirements</p>
                                <p class="text-gray-600">Skip system requirements check</p>
                            </div>
                            <div class="command-option">
                                <p class="font-medium">--skip-db</p>
                                <p class="text-gray-600">Skip database initialization</p>
                            </div>
                            <div class="command-option">
                                <p class="font-medium">--skip-prepare</p>
                                <p class="text-gray-600">Skip application preparation</p>
                            </div>
                            <div class="command-option">
                                <p class="font-medium">--skip-optimize</p>
                                <p class="text-gray-600">Skip application optimization</p>
                            </div>
                            <div class="command-option">
                                <p class="font-medium">--force</p>
                                <p class="text-gray-600">Force the operation to run when in production</p>
                            </div>
                            <div class="command-option">
                                <p class="font-medium">--show-config</p>
                                <p class="text-gray-600">Show database configuration and exit</p>
                            </div>
                            <div class="command-option">
                                <p class="font-medium">--migrate</p>
                                <p class="text-gray-600">Run database migrations</p>
                            </div>
                            <div class="command-option">
                                <p class="font-medium">--seed</p>
                                <p class="text-gray-600">Seed the database with records</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h4 class="text-lg font-medium text-gray-800 mb-2">Examples</h4>
                        <div class="pl-4">
                            <div class="mb-2">
                                <p class="font-medium">Basic usage:</p>
                                <div class="relative">
                                    <pre class="code-block"><code class="language-bash">php artisan app:init</code></pre>
                                </div>
                            </div>
                            <div class="mb-2">
                                <p class="font-medium">Skip requirements check:</p>
                                <div class="relative">
                                    <pre class="code-block"><code class="language-bash">php artisan app:init --skip-requirements</code></pre>
                                </div>
                            </div>
                            <div class="mb-2">
                                <p class="font-medium">Show database configuration:</p>
                                <div class="relative">
                                    <pre class="code-block"><code class="language-bash">php artisan app:init --show-config</code></pre>
                                </div>
                            </div>
                            <div class="mb-2">
                                <p class="font-medium">Run with migrations and seeding:</p>
                                <div class="relative">
                                    <pre class="code-block"><code class="language-bash">php artisan app:init --migrate --seed</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-lg font-medium text-gray-800 mb-2">Related Commands</h4>
                        <div class="flex flex-wrap gap-2">
                            <a href="#app-prepare" class="bg-blue-100 text-blue-800 hover:bg-blue-200 px-3 py-1 rounded-full text-sm">app:prepare</a>
                            <a href="#app-deploy" class="bg-blue-100 text-blue-800 hover:bg-blue-200 px-3 py-1 rounded-full text-sm">app:deploy</a>
                            <a href="#app-optimize" class="bg-blue-100 text-blue-800 hover:bg-blue-200 px-3 py-1 rounded-full text-sm">app:optimize</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mb-8" id="app-deploy">
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">app:deploy</h3>
                    <p class="text-gray-600 mb-4">Deploy Laravel applications to various environments</p>
                    
                    <div class="mb-4">
                        <h4 class="text-lg font-medium text-gray-800 mb-2">Description</h4>
                        <p class="text-gray-700">
                            Handles the deployment process including database migrations, asset compilation, and environment setup.
                        </p>
                    </div>
                    
                    <div class="mb-4">
                        <h4 class="text-lg font-medium text-gray-800 mb-2">Usage</h4>
                        <div class="relative">
                            <pre class="code-block"><code class="language-bash">php artisan app:deploy</code></pre>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h4 class="text-lg font-medium text-gray-800 mb-2">Options</h4>
                        <div class="pl-4">
                            <div class="command-option">
                                <p class="font-medium">--env=</p>
                                <p class="text-gray-600">Specify the environment to deploy to</p>
                            </div>
                            <div class="command-option">
                                <p class="font-medium">--skip-migrations</p>
                                <p class="text-gray-600">Skip database migrations</p>
                            </div>
                            <div class="command-option">
                                <p class="font-medium">--skip-assets</p>
                                <p class="text-gray-600">Skip asset compilation</p>
                            </div>
                            <div class="command-option">
                                <p class="font-medium">--force</p>
                                <p class="text-gray-600">Force the operation to run when in production</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h4 class="text-lg font-medium text-gray-800 mb-2">Examples</h4>
                        <div class="pl-4">
                            <div class="mb-2">
                                <p class="font-medium">Basic usage:</p>
                                <div class="relative">
                                    <pre class="code-block"><code class="language-bash">php artisan app:deploy</code></pre>
                                </div>
                            </div>
                            <div class="mb-2">
                                <p class="font-medium">Deploy to production:</p>
                                <div class="relative">
                                    <pre class="code-block"><code class="language-bash">php artisan app:deploy --env=production</code></pre>
                                </div>
                            </div>
                            <div class="mb-2">
                                <p class="font-medium">Skip migrations:</p>
                                <div class="relative">
                                    <pre class="code-block"><code class="language-bash">php artisan app:deploy --skip-migrations</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-lg font-medium text-gray-800 mb-2">Related Commands</h4>
                        <div class="flex flex-wrap gap-2">
                            <a href="#app-init" class="bg-blue-100 text-blue-800 hover:bg-blue-200 px-3 py-1 rounded-full text-sm">app:init</a>
                            <a href="#app-optimize" class="bg-blue-100 text-blue-800 hover:bg-blue-200 px-3 py-1 rounded-full text-sm">app:optimize</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional command documentation sections would follow the same pattern -->
            <!-- For brevity, I'm only including two full command sections in this example -->
            
            <div class="text-center mt-8">
                <p class="text-gray-600">
                    For more detailed information about other commands, use the command line help:
                </p>
                <div class="relative max-w-lg mx-auto mt-4">
                    <pre class="code-block"><code class="language-bash">php artisan app:help</code></pre>
                </div>
            </div>
        </div>
    </div>
@endsection
