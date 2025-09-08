<?php

/**
 * AppInstallTailwindCSS
 * 
 * Installs and configures TailwindCSS for Laravel applications with best practices
 * and optimized configurations.
 * 
 * @category    Commands
 * @package     Ez_IT_Solutions\AppInit
 * @author      Chris Hultberg <chrishultberg@ez-it-solutions.com>
 * @see         https://www.Ez-IT-Solutions.com
 * @license     MIT
 * @link        https://github.com/ez-it-solutions/laravel-init
 * @copyright   Copyright (c) 2025 EZ IT Solutions
 * @version     1.0.0
 * @since       2025-09-07
 */

namespace Ez_IT_Solutions\AppInit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class AppInstallTailwindCSS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install-tailwindcss
                            {--force : Overwrite any existing files}
                            {--with-plugins : Install recommended plugins (forms, typography, etc.)}
                            {--with-vite : Configure for Vite (default)}
                            {--with-mix : Configure for Laravel Mix instead of Vite}
                            {--dark-mode : Add dark mode support}
                            {--no-interaction : Do not ask any interactive questions}'; 

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install and configure TailwindCSS with best practices for Laravel projects';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->components->info('Installing TailwindCSS for Laravel...');

        // Check if Node.js and NPM are installed
        if (!$this->checkNodeAndNpm()) {
            return Command::FAILURE;
        }

        // Detect package manager (npm or yarn)
        $packageManager = $this->detectPackageManager();
        
        // Check if project is using Vite or Mix
        $buildTool = $this->detectBuildTool();
        
        // Create necessary directories
        $this->createDirectories();
        
        // Install TailwindCSS and its dependencies
        if (!$this->installTailwindPackages($packageManager)) {
            return Command::FAILURE;
        }
        
        // Create configuration files
        $this->createConfigFiles($buildTool);
        
        // Update CSS file
        $this->updateCssFile();
        
        // Update build configuration
        $this->updateBuildConfig($buildTool);
        
        // Add dark mode support if requested
        if ($this->option('dark-mode')) {
            $this->addDarkModeSupport();
        }
        
        // Final instructions
        $this->showSuccessMessage($packageManager, $buildTool);
        
        return Command::SUCCESS;
    }

    /**
     * Check if Node.js and NPM are installed
     *
     * @return bool
     */
    private function checkNodeAndNpm(): bool
    {
        $this->components->task('Checking Node.js and NPM installation', function () {
            $nodeVersion = $this->runProcess(['node', '-v']);
            $npmVersion = $this->runProcess(['npm', '-v']);
            
            if (empty($nodeVersion) || empty($npmVersion)) {
                $this->components->error('Node.js and NPM are required but not found.');
                $this->components->info('Please install Node.js from https://nodejs.org/');
                return false;
            }
            
            return true;
        });
        
        return true;
    }
    
    /**
     * Detect which package manager is being used (npm or yarn)
     *
     * @return string
     */
    private function detectPackageManager(): string
    {
        $hasYarnLock = File::exists(base_path('yarn.lock'));
        $hasNpmLock = File::exists(base_path('package-lock.json'));
        
        if ($hasYarnLock && !$hasNpmLock) {
            $this->components->info('Yarn detected as package manager');
            return 'yarn';
        }
        
        $this->components->info('NPM detected as package manager');
        return 'npm';
    }
    
    /**
     * Detect if project is using Vite or Laravel Mix
     *
     * @return string
     */
    private function detectBuildTool(): string
    {
        if ($this->option('with-mix')) {
            return 'mix';
        }
        
        if ($this->option('with-vite') || File::exists(base_path('vite.config.js'))) {
            return 'vite';
        }
        
        if (File::exists(base_path('webpack.mix.js'))) {
            return 'mix';
        }
        
        // Default to Vite for newer Laravel projects
        return 'vite';
    }
    
    /**
     * Create necessary directories
     */
    private function createDirectories(): void
    {
        $this->components->task('Creating necessary directories', function () {
            $directories = [
                resource_path('css'),
            ];
            
            foreach ($directories as $directory) {
                if (!File::isDirectory($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }
            }
            
            return true;
        });
    }
    
    /**
     * Install TailwindCSS and its dependencies
     *
     * @param string $packageManager
     * @return bool
     */
    private function installTailwindPackages(string $packageManager): bool
    {
        $packages = [
            'tailwindcss@latest',
            'postcss@latest',
            'autoprefixer@latest',
        ];
        
        if ($this->option('with-plugins')) {
            $packages = array_merge($packages, [
                '@tailwindcss/forms@latest',
                '@tailwindcss/typography@latest',
            ]);
        }
        
        $installCommand = $packageManager === 'yarn' 
            ? ['yarn', 'add', '-D'] 
            : ['npm', 'install', '--save-dev'];
        
        $installCommand = array_merge($installCommand, $packages);
        
        return $this->components->task('Installing TailwindCSS packages', function () use ($installCommand) {
            $process = $this->runProcess($installCommand);
            return !empty($process);
        });
    }
    
    /**
     * Create TailwindCSS configuration files
     *
     * @param string $buildTool
     */
    private function createConfigFiles(string $buildTool): void
    {
        $this->components->task('Creating TailwindCSS configuration files', function () use ($buildTool) {
            // Create tailwind.config.js
            $tailwindConfig = $this->getTailwindConfig($buildTool);
            File::put(base_path('tailwind.config.js'), $tailwindConfig);
            
            // Create postcss.config.js
            $postcssConfig = $this->getPostcssConfig();
            File::put(base_path('postcss.config.js'), $postcssConfig);
            
            return true;
        });
    }
    
    /**
     * Update or create the main CSS file
     */
    private function updateCssFile(): void
    {
        $this->components->task('Updating CSS file with Tailwind directives', function () {
            $cssContent = $this->getTailwindCssContent();
            File::put(resource_path('css/app.css'), $cssContent);
            return true;
        });
    }
    
    /**
     * Update build configuration based on the build tool
     *
     * @param string $buildTool
     */
    private function updateBuildConfig(string $buildTool): void
    {
        if ($buildTool === 'vite') {
            $this->updateViteConfig();
        } else {
            $this->updateMixConfig();
        }
    }
    
    /**
     * Add dark mode support
     */
    private function addDarkModeSupport(): void
    {
        $this->components->task('Adding dark mode support', function () {
            $tailwindConfigPath = base_path('tailwind.config.js');
            $content = File::get($tailwindConfigPath);
            
            // Add darkMode: 'class' to the configuration
            $content = str_replace(
                "module.exports = {",
                "module.exports = {\n  darkMode: 'class',",
                $content
            );
            
            File::put($tailwindConfigPath, $content);
            
            // Create a dark mode toggle helper
            $this->createDarkModeToggle();
            
            return true;
        });
    }
    
    /**
     * Show success message with next steps
     *
     * @param string $packageManager
     * @param string $buildTool
     */
    private function showSuccessMessage(string $packageManager, string $buildTool): void
    {
        $this->components->info('✅ TailwindCSS has been successfully installed!');
        $this->newLine();
        
        $this->components->info('Next steps:');
        
        $devCommand = $packageManager === 'yarn' ? 'yarn dev' : 'npm run dev';
        $this->components->bulletList([
            "Run '$devCommand' to compile your assets",
            "Start using Tailwind classes in your blade templates",
            "Check out the Tailwind documentation at https://tailwindcss.com/docs"
        ]);
        
        if ($this->option('with-plugins')) {
            $this->newLine();
            $this->components->info('Installed plugins:');
            $this->components->bulletList([
                "Forms: https://github.com/tailwindlabs/tailwindcss-forms",
                "Typography: https://github.com/tailwindlabs/tailwindcss-typography"
            ]);
        }
    }
    
    /**
     * Run a process and return its output
     *
     * @param array $command
     * @return string|null
     */
    private function runProcess(array $command): ?string
    {
        try {
            $process = new Process($command);
            $process->setTimeout(60);
            $process->run();
            
            if ($process->isSuccessful()) {
                return trim($process->getOutput());
            }
            
            return null;
        } catch (\Exception $e) {
            $this->components->error($e->getMessage());
            return null;
        }
    }
    
    /**
     * Get the Tailwind configuration content
     *
     * @param string $buildTool
     * @return string
     */
    private function getTailwindConfig(string $buildTool): string
    {
        $contentPaths = $buildTool === 'vite'
            ? "['./resources/**/*.blade.php', './resources/**/*.js', './resources/**/*.vue']"
            : "['./resources/**/*.blade.php', './resources/**/*.js', './resources/**/*.vue']"; 
        
        $plugins = $this->option('with-plugins')
            ? "[\n    require('@tailwindcss/forms'),\n    require('@tailwindcss/typography'),\n  ]"
            : "[]"; 
        
        return <<<EOT
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: {$contentPaths},
  theme: {
    extend: {},
  },
  plugins: {$plugins},
}
EOT;
    }
    
    /**
     * Get the PostCSS configuration content
     *
     * @return string
     */
    private function getPostcssConfig(): string
    {
        return <<<'EOT'
module.exports = {
  plugins: {
    tailwindcss: {},
    autoprefixer: {},
  },
}
EOT;
    }
    
    /**
     * Get the Tailwind CSS content with directives
     *
     * @return string
     */
    private function getTailwindCssContent(): string
    {
        return <<<'EOT'
@tailwind base;
@tailwind components;
@tailwind utilities;

/* Custom styles below this line */
EOT;
    }
    
    /**
     * Update Vite configuration to include Tailwind
     */
    private function updateViteConfig(): void
    {
        $this->components->task('Updating Vite configuration', function () {
            $viteConfigPath = base_path('vite.config.js');
            
            if (!File::exists($viteConfigPath)) {
                // Create a new vite.config.js file if it doesn't exist
                $viteConfig = $this->getDefaultViteConfig();
                File::put($viteConfigPath, $viteConfig);
                return true;
            }
            
            // Update existing vite.config.js
            $content = File::get($viteConfigPath);
            
            // Check if tailwindcss is already in the plugins
            if (strpos($content, 'tailwindcss') !== false) {
                $this->components->info('Tailwind CSS is already configured in Vite');
                return true;
            }
            
            // Add tailwindcss to plugins
            $content = preg_replace(
                '/plugins:\s*\[/',
                "plugins: [\n    require('tailwindcss'),\n    require('autoprefixer'),",
                $content
            );
            
            File::put($viteConfigPath, $content);
            return true;
        });
    }
    
    /**
     * Get default Vite configuration
     *
     * @return string
     */
    private function getDefaultViteConfig(): string
    {
        return <<<'EOT'
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        require('tailwindcss'),
        require('autoprefixer'),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
EOT;
    }
    
    /**
     * Update Laravel Mix configuration to include Tailwind
     */
    private function updateMixConfig(): void
    {
        $this->components->task('Updating Laravel Mix configuration', function () {
            $mixConfigPath = base_path('webpack.mix.js');
            
            if (!File::exists($mixConfigPath)) {
                // Create a new webpack.mix.js file if it doesn't exist
                $mixConfig = $this->getDefaultMixConfig();
                File::put($mixConfigPath, $mixConfig);
                return true;
            }
            
            // Update existing webpack.mix.js
            $content = File::get($mixConfigPath);
            
            // Check if tailwindcss is already configured
            if (strpos($content, 'tailwindcss') !== false) {
                $this->components->info('Tailwind CSS is already configured in Laravel Mix');
                return true;
            }
            
            // Add tailwindcss configuration
            $content = str_replace(
                "mix.js('resources/js/app.js', 'public/js')",
                "mix.js('resources/js/app.js', 'public/js')\n    .postCss('resources/css/app.css', 'public/css', [
        require('tailwindcss'),
        require('autoprefixer'),
    ])",
                $content
            );
            
            File::put($mixConfigPath, $content);
            return true;
        });
    }
    
    /**
     * Get default Laravel Mix configuration
     *
     * @return string
     */
    private function getDefaultMixConfig(): string
    {
        return <<<'EOT'
const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require('tailwindcss'),
        require('autoprefixer'),
    ]);

if (mix.inProduction()) {
    mix.version();
}
EOT;
    }
    
    /**
     * Create a dark mode toggle helper
     */
    private function createDarkModeToggle(): void
    {
        $jsDirectory = resource_path('js');
        
        if (!File::isDirectory($jsDirectory)) {
            File::makeDirectory($jsDirectory, 0755, true);
        }
        
        $darkModeTogglePath = resource_path('js/dark-mode-toggle.js');
        $darkModeToggleContent = $this->getDarkModeToggleContent();
        
        File::put($darkModeTogglePath, $darkModeToggleContent);
        
        $this->components->info('Created dark mode toggle helper at resources/js/dark-mode-toggle.js');
        $this->components->info('Add <script src="{{ asset(\'js/dark-mode-toggle.js\') }}"></script> to your layout');
    }
    
    /**
     * Get dark mode toggle JavaScript content
     *
     * @return string
     */
    private function getDarkModeToggleContent(): string
    {
        return <<<'EOT'
// Dark mode toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    // Check for saved theme preference or respect OS preference
    const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    const savedTheme = localStorage.getItem('theme');
    
    // Apply the right theme based on saved preference or OS preference
    if (savedTheme === 'dark' || (!savedTheme && darkModeMediaQuery.matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
    
    // Function to toggle dark mode
    window.toggleDarkMode = function() {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.theme = 'light';
        } else {
            document.documentElement.classList.add('dark');
            localStorage.theme = 'dark';
        }
    }
    
    // Example usage in HTML:
    // <button onclick="toggleDarkMode()">Toggle Dark Mode</button>
});
EOT;
    }
}