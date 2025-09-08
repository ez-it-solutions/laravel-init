<div align="center">

# Laravel Initialization Utility

<img src="https://github.com/ez-it-solutions.png" alt="Ez IT Solutions" width="150" height="150">

### A comprehensive Laravel Initialization Utility

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-7.3%2B-blue.svg)](https://www.php.net/)
[![Laravel Version](https://img.shields.io/badge/Laravel-6.0%2B-red.svg)](https://laravel.com/)
[![Packagist Version](https://img.shields.io/badge/Packagist-v1.0.0-orange.svg)](https://packagist.org/packages/ez-it-solutions/laravel-init)

A powerful toolkit that combines several functions and features specific to Laravel projects, including application initialization, deployment, optimization, database management, and more.

</div>

## 📋 Overview

The Laravel Initialization Utility is a comprehensive toolkit designed to streamline the entire lifecycle of Laravel application development, deployment, and maintenance. It provides a collection of powerful commands that handle everything from initial setup to production optimization.

<div align="center">
<img src="https://via.placeholder.com/600x300?text=Laravel+Initialization+Utility" alt="Command Output Example" width="600">
</div>

This utility combines multiple essential functions into a single, cohesive package, eliminating the need for various separate tools and scripts. Whether you're setting up a new project, preparing for deployment, or optimizing performance, this toolkit has you covered.

## 🛠️ Custom Commands

<div align="center">

[![Command Reference](https://img.shields.io/badge/Command-Reference-blue.svg)](#custom-commands)

</div>

### 🧙‍♂️ Interactive Setup Wizard

```bash
# Start the interactive CLI setup wizard
php artisan app:setup

# Options:
#   --non-interactive  Run in non-interactive mode with default options
#   --skip-intro      Skip the introduction screen

# Start the web-based setup wizard
php artisan app:setup --web

# Or directly launch the web wizard
php artisan app:web-setup

# Web wizard options:
#   --port=8000       The port to use for the web server
#   --host=127.0.0.1  The host to use for the web server
#   --no-open         Do not open the browser automatically
```

The setup wizard provides a user-friendly interface for configuring and using various features of the Laravel Initialization Utility, including:

- Viewing README and help documentation
- Checking application status
- Configuring application settings
- Setting up database connections
- Installing frontend assets
- Optimizing the application
- Creating database backups
- Building configuration files

<div align="center">
<img src="https://via.placeholder.com/800x450?text=CLI+Setup+Wizard+Screenshot" alt="CLI Setup Wizard Screenshot" width="800">
<p><em>Interactive CLI setup wizard with easy-to-use menus</em></p>
</div>

#### Web-Based Setup Wizard

The web-based setup wizard provides a modern, responsive interface for configuring your Laravel application through your browser. It offers all the same functionality as the CLI wizard but with a more visual and intuitive interface.

<div align="center">
<img src="https://via.placeholder.com/800x450?text=Web+Setup+Wizard+Screenshot" alt="Web Setup Wizard Screenshot" width="800">
<p><em>Web-based setup wizard with a modern, responsive design</em></p>
</div>

Features of the web-based wizard:

- **Dashboard**: Overview of all available features
- **Documentation Viewer**: View README and help documentation directly in the browser
- **Status Checker**: Check the status of your application's configuration and packages
- **Configuration Forms**: Easy-to-use forms for configuring your application
- **Step-by-Step Process**: Guided setup process with progress indicators
- **Responsive Design**: Works on all devices, from desktop to mobile

### 📊 Application Status

```bash
# Check the status of Laravel Init packages and configurations
php artisan app:status

# Options:
#   --verbose         Display detailed information
#   --config-only     Only check configuration files
#   --packages-only   Only check installed packages
```

The status command provides a comprehensive report of your Laravel application's initialization status, including:

- Configuration files
- Environment variables
- Installed Node packages
- Installed Composer packages
- System information

### 💾 Database Backup

```bash
# Create and manage database backups
php artisan db:backup

# Options:
#   --tables=         Comma-separated list of tables to backup (default: all tables)
#   --exclude=        Comma-separated list of tables to exclude from backup
#   --filename=       Custom filename for the backup
#   --format=sql      Output format (sql, gz, zip)
#   --storage=        Storage disk to use (local, s3, etc.)
#   --path=           Custom path within the storage disk
#   --no-compress     Do not compress the backup file
#   --with-data       Include data in the backup (default)
#   --structure-only  Only backup the database structure, not the data
#   --notify          Send notification when backup is complete
#   --force           Force the operation to run when in production
```

The database backup command provides powerful features for creating and managing database backups, including:

- Selective table backup
- Multiple compression formats
- Cloud storage integration
- Backup retention policies
- Notification options

### 📚 Help & Documentation

```bash
# Get help and documentation for all commands
php artisan app:help

# Get help for a specific command
php artisan app:help app:init

# List all available commands
php artisan app:help --list

# Show usage examples
php artisan app:help app:deploy --examples

# Start interactive help mode
php artisan app:help --interactive

# Open beautiful HTML documentation in your browser
php artisan app:help --html
```

The help command provides comprehensive documentation, examples, and interactive guidance for all Laravel Initialization Utility commands.

<div align="center">
<img src="https://via.placeholder.com/800x450?text=HTML+Documentation+Screenshot" alt="HTML Documentation Screenshot" width="800">
<p><em>Beautiful HTML documentation with a modern, responsive design</em></p>
</div>

The HTML documentation provides a more visually appealing way to access the package's documentation, with features like:

- Modern, responsive design that works on all devices
- Syntax highlighting for code examples
- Easy navigation with a sidebar menu
- Detailed command documentation with examples
- Copy-to-clipboard functionality for code snippets

### 🔧 Application Initialization

```bash
# Initialize the application
php artisan app:init

# Options:
#   --skip-requirements  Skip system requirements check
#   --skip-db           Skip database initialization
#   --skip-prepare      Skip application preparation
#   --skip-optimize     Skip application optimization
#   --force             Force the operation to run when in production
#   --show-config       Show database configuration and exit
#   --migrate           Run database migrations
#   --seed              Seed the database with records
```

### 💾 Database Management

```bash
# Initialize and configure the database
php artisan db:init

# Options:
#   --show-config       Show database configuration and exit
#   --migrate           Run database migrations
#   --seed              Seed the database with records
#   --force             Force the operation to run when in production

# Execute MySQL commands directly
php artisan mysql:exec --command="SHOW TABLES;"

# List available predefined commands
php artisan mysql:exec --list-commands
```

### 🖥️ Development Server

```bash
# Start the development server
php artisan serve

# Or use the custom serve command with auto port selection
php artisan app:serve

# Options:
#   --host=127.0.0.1    The host address to serve the application on
#   --start-port=8001   The starting port number to try
#   --max-attempts=10   Maximum number of ports to try
#   --no-open           Do not open the browser automatically
```

### 🚀 Application Deployment

```bash
# Prepare the application for deployment
php artisan app:deploy --prepare

# Set up the application after deployment
php artisan app:deploy --setup

# Options:
#   --exclude-vendor    Exclude vendor directory from deployment package
#   --exclude-node      Exclude node_modules directory from deployment package
#   --output=PATH       Specify output path for deployment package
```

### ⚡ Application Optimization

```bash
# Optimize the application
php artisan app:optimize

# Options:
#   --no-vite           Skip Vite asset compilation
#   --no-cache          Skip cache rebuilding
#   --no-storage-link   Skip storage link creation
#   --production        Optimize for production environment
#   --dev               Optimize for development environment (default)

# Clean up the application
php artisan app:cleanup

# Options:
#   --force             Skip confirmation prompt
```

### 🔍 System Requirements Check

```bash
# Check if your system meets all requirements
php artisan app:check-requirements
```

### 📝 Environment Preparation

```bash
# Prepare for development environment
php artisan app:prepare development

# Prepare for production environment
php artisan app:prepare production

# Options:
#   --clean             Remove unnecessary files
#   --permissions       Set appropriate file permissions
#   --optimize          Run optimization commands
```

## ✨ Features

### Interactive Setup Wizard
- **User-Friendly Interface**: Configure your application with an interactive wizard
- **Feature Selection**: Pick and choose which features to use and configure
- **Documentation Viewer**: View README and help documentation directly in the console
- **Configuration Builder**: Generate configuration files for your selected options
- **Cross-Platform Support**: Works on Windows, macOS, and Linux

### Application Status
- **Comprehensive Status Check**: Get a complete overview of your application's status
- **Configuration Files**: Check for required configuration files
- **Environment Variables**: Verify environment variables are properly set
- **Package Detection**: Automatically detect installed Node and Composer packages
- **System Information**: View PHP, Laravel, Node.js, and server information

### Application Initialization
- **Complete Setup**: Initialize your Laravel application with a single command
- **System Requirements Check**: Verify your environment meets all requirements
- **Database Initialization**: Set up your database with proper character set and collation
- **Application Preparation**: Configure environment-specific settings
- **Permissions Management**: Set appropriate file and directory permissions

### Application Deployment
- **Deployment Package Creation**: Create optimized deployment packages
- **Post-Deployment Setup**: Configure your application after deployment
- **Customizable Exclusions**: Control which files are included in deployment packages

### Application Optimization
- **Cache Management**: Clear and rebuild various caches
- **Asset Compilation**: Compile frontend assets for production
- **Environment-Specific Optimization**: Different optimization strategies for development and production

### Application Cleanup
- **Comprehensive Cleanup**: Clear all caches and optimize your application
- **Visual Progress Feedback**: See detailed progress of each cleanup step
- **Performance Metrics**: Track execution time for each operation

### Database Tools
- **Database Initialization**: Create and configure databases with proper settings
- **Database Backup**: Create and manage database backups with various options
- **MySQL Command Execution**: Run MySQL commands directly from Artisan
- **Migration and Seeding**: Easily run migrations and seeders
- **Backup Compression**: Compress backups in various formats (SQL, GZ, ZIP)
- **Cloud Storage Integration**: Store backups in cloud storage services
- **Retention Policies**: Automatically manage backup retention

### Frontend Asset Management
- **TailwindCSS Installation**: Easily install and configure TailwindCSS
- **Dark Mode Support**: Add dark mode support to your application
- **Plugin Integration**: Install and configure TailwindCSS plugins
- **Build Tool Detection**: Automatically detect and configure Vite or Laravel Mix

### Development Server
- **Port Auto-Detection**: Automatically finds the first available port
- **Browser Auto-Launch**: Opens your default browser to the application URL
- **Customizable Host**: Specify a custom host address
- **Cross-Platform Support**: Works on Windows, macOS, and Linux
## 🚀 Installation

<div align="center">

[![Latest Stable Version](https://img.shields.io/packagist/v/ez-it-solutions/laravel-init.svg)](https://packagist.org/packages/ez-it-solutions/laravel-init)
[![Total Downloads](https://img.shields.io/packagist/dt/ez-it-solutions/laravel-init.svg)](https://packagist.org/packages/ez-it-solutions/laravel-init)
[![License](https://img.shields.io/packagist/l/ez-it-solutions/laravel-init.svg)](https://packagist.org/packages/ez-it-solutions/laravel-init)

</div>

### Requirements

- PHP 7.3 or higher
- Laravel 6.0 or higher
- Composer

### Option 1: Install via Composer (Recommended)

Simply run the following command in your Laravel project:

```bash
composer require ez-it-solutions/laravel-init
```

That's it! The package will automatically register all commands with Laravel through auto-discovery.

### Option 2: Manual Installation

1. Clone the repository from our [GitHub repository](https://github.com/ez-it-solutions/laravel-init)
   ```bash
   git clone https://github.com/ez-it-solutions/laravel-init.git
   ```

2. Copy the `src` directory to your Laravel project

3. Register the service providers in your `config/app.php` file:

   ```php
   'providers' => [
       // ...
       Ez_IT_Solutions\AppServe\AppServeServiceProvider::class,
       Ez_IT_Solutions\AppCleanup\AppCleanupServiceProvider::class,
       Ez_IT_Solutions\AppInit\AppInitServiceProvider::class,
       Ez_IT_Solutions\DatabaseTools\DatabaseToolsServiceProvider::class,
   ],
   ```

4. Publish the configuration files (optional):

   ```bash
   php artisan vendor:publish --tag=ez-it-solutions-config
   ```

   This will publish the following configuration files:
   - `config/init.php`: Configuration for initialization settings
   - `config/database.php`: Enhanced database configuration

### Compatibility

This package is thoroughly tested and compatible with:

| Laravel Version | PHP Version |
|-----------------|-------------|
| 6.x             | 7.3 - 8.2   |
| 7.x             | 7.3 - 8.2   |
| 8.x             | 7.3 - 8.2   |
| 9.x             | 8.0 - 8.2   |
| 10.x            | 8.1 - 8.2   |

## 🎮 Command Showcase

<div align="center">

### App Cleanup Command

```
╔═════════════════════════════════════════════════╗
║                                                 ║
║              INSTALLATION CLEANUP               ║
║                                                 ║
╚═════════════════════════════════════════════════╝

[1/10] Running: Clear Route Cache (route:clear)
 100% [===========================================] ✓ Done
Time: 0.23s

[2/10] Running: Clear Configuration Cache (config:clear)
 100% [===========================================] ✓ Done
Time: 0.18s

[3/10] Running: Clear Application Cache (cache:clear)
 100% [===========================================] ✓ Done
Time: 0.25s
```

### App Init Command

```
🚀 JC Portal Initialization
========================

🔍 Checking system requirements...
✅ PHP version: 8.2.0 (>= 8.1.0 required)

💾 Initializing database...
✅ Database 'laravel_db' initialized successfully

🔧 Preparing application...
📂 Ensuring required directories exist...
🔒 Setting file and directory permissions...
🔗 Creating storage symlink...
  ✓ Storage symlink created successfully
✅ Application preparation completed successfully

⚡ Optimizing application...
✅ Application has been initialized successfully!
```

</div>

## 📦 Creating Your Own Package

If you want to create your own version of this package, follow these steps:

### 1. Set Up Package Structure

Create the following directory structure:

```
laravel-init/
├── config/
│   ├── database.php        # Enhanced database configuration
│   └── init.php           # Application initialization settings
├── src/
│   ├── Commands/
│   │   ├── AppCleanup.php              # Clean up application
│   │   ├── AppDeploy.php               # Deploy application
│   │   ├── AppHelpCommand.php          # Help documentation
│   │   ├── AppInit.php                 # Initialize application
│   │   ├── AppInstallTailwindCSS.php   # Install TailwindCSS
│   │   ├── AppOptimize.php             # Optimize application
│   │   ├── AppPrepare.php              # Prepare application
│   │   ├── AppServeCommand.php         # Serve application
│   │   ├── AppSetupCommand.php         # Interactive setup wizard
│   │   ├── AppWebSetupCommand.php      # Web-based setup wizard
│   │   ├── CheckRequirementsCommand.php # Check system requirements
│   │   ├── DatabaseBackupCommand.php    # Backup database
│   │   ├── DatabaseInitCommand.php      # Initialize database
│   │   ├── MysqlExecCommand.php         # Execute MySQL commands
│   │   └── StatusCommand.php            # Check application status
│   ├── Http/
│   │   └── Controllers/
│   │       ├── HelpController.php          # Help documentation controller
│   │       └── SetupWizardController.php   # Web setup wizard controller
│   ├── resources/
│   │   └── views/
│   │       ├── help/                      # Help documentation views
│   │       │   ├── index.blade.php
│   │       │   └── command.blade.php
│   │       ├── setup/                     # Web setup wizard views
│   │       │   ├── index.blade.php
│   │       │   ├── documentation.blade.php
│   │       │   ├── help.blade.php
│   │       │   ├── status.blade.php
│   │       │   ├── configure-app.blade.php
│   │       │   ├── setup-database.blade.php
│   │       │   ├── wizard-navigation.js
│   │       │   └── layout.blade.php
│   │       └── layout.blade.php           # Main layout template
│   ├── routes/
│   │   └── web.php                     # Web routes
│   ├── AppCleanupServiceProvider.php     # Cleanup service provider
│   ├── AppInitServiceProvider.php        # Init service provider
│   ├── AppServeServiceProvider.php       # Serve service provider
│   └── DatabaseToolsServiceProvider.php  # Database tools provider
├── composer.json
├── LICENSE
└── README.md
```

### 2. Create composer.json

```json
{
    "name": "ez-it-solutions/laravel-init",
    "description": "A comprehensive Laravel Initialization Utility that combines several functions and features specific to Laravel projects",
    "type": "library",
    "license": "MIT",
    "authors": [
        {
            "name": "Ez IT Solutions",
            "email": "info@ez-it-solutions.com"
        }
    ],
    "require": {
        "php": "^7.3|^8.0"
    },
    "require-dev": {
        "laravel/framework": "^6.0|^7.0|^8.0|^9.0|^10.0"
    },
    "autoload": {
        "psr-4": {
            "Ez_IT_Solutions\\AppServe\\": "src/",
            "Ez_IT_Solutions\\AppCleanup\\": "src/",
            "Ez_IT_Solutions\\AppInit\\": "src/",
            "Ez_IT_Solutions\\DatabaseTools\\": "src/"
        }
    },
    "extra": {
        "laravel": {
            "providers": [
                "Ez_IT_Solutions\\AppServe\\AppServeServiceProvider",
                "Ez_IT_Solutions\\AppCleanup\\AppCleanupServiceProvider",
                "Ez_IT_Solutions\\AppInit\\AppInitServiceProvider",
                "Ez_IT_Solutions\\DatabaseTools\\DatabaseToolsServiceProvider"
            ]
        }
    },
    "minimum-stability": "dev",
    "prefer-stable": true
}
```

### 3. Create Service Providers

Create the following service provider files:

#### src/AppInitServiceProvider.php

```php
<?php

namespace Ez_IT_Solutions\AppInit;

use Illuminate\Support\ServiceProvider;
use Ez_IT_Solutions\AppInit\Commands\AppInit;
use Ez_IT_Solutions\AppInit\Commands\AppDeploy;
use Ez_IT_Solutions\AppInit\Commands\AppOptimize;
use Ez_IT_Solutions\AppInit\Commands\AppPrepare;
use Ez_IT_Solutions\AppInit\Commands\CheckRequirementsCommand;

class AppInitServiceProvider extends ServiceProvider
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
                AppInit::class,
                AppDeploy::class,
                AppOptimize::class,
                AppPrepare::class,
                CheckRequirementsCommand::class,
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
```

#### src/AppCleanupServiceProvider.php

```php
<?php

namespace Ez_IT_Solutions\AppCleanup;

use Illuminate\Support\ServiceProvider;
use Ez_IT_Solutions\AppCleanup\Commands\AppCleanup;

class AppCleanupServiceProvider extends ServiceProvider
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
                AppCleanup::class,
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
```

#### src/DatabaseToolsServiceProvider.php

```php
<?php

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
```

#### src/AppServeServiceProvider.php

```php
<?php

namespace Ez_IT_Solutions\AppServe;

use Illuminate\Support\ServiceProvider;
use Ez_IT_Solutions\AppServe\Commands\AppServeCommand;

class AppServeServiceProvider extends ServiceProvider
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
                AppServeCommand::class,
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
```

### 4. Update Namespace in Command

Update the namespace in your `AppServeCommand.php` file to match your package:

```php
namespace Ez_IT_Solutions\AppServe\Commands;
```

### 5. Publish to GitHub

```bash
# Initialize Git repository
git init

# Add all files
git add .

# Commit the files
git commit -m "Initial commit"

# Create a new repository on GitHub at https://github.com/ez-it-solutions/laravel-init
# Then push to GitHub
git remote add origin https://github.com/ez-it-solutions/laravel-init.git
git branch -M main
git push -u origin main

# Tag a release
git tag -a v1.0.0 -m "Initial release"
git push origin v1.0.0
```

### 6. Register with Packagist

1. Visit [Packagist](https://packagist.org/packages/submit)
2. Submit your GitHub repository URL: `https://github.com/ez-it-solutions/laravel-init`
3. Once approved, your package will be available via Composer

### 7. Set Up GitHub Webhooks for Packagist

To automatically update your package on Packagist when you push to GitHub:

1. Go to your GitHub repository settings
2. Click on "Webhooks" > "Add webhook"
3. Set Payload URL to: `https://packagist.org/api/github?username=your-packagist-username`
4. Set Content type to: `application/json`
5. Select "Just the push event"
6. Click "Add webhook"

### 8. Add GitHub Actions for Testing (Optional)

Create a file at `.github/workflows/tests.yml`:

```yaml
name: Tests

on:
  push:
    branches: [ main ]
  pull_request:
    branches: [ main ]

jobs:
  tests:
    runs-on: ubuntu-latest
    strategy:
      matrix:
        php: [7.4, 8.0, 8.1, 8.2]
        laravel: [8.*, 9.*, 10.*]
        exclude:
          - php: 7.4
            laravel: 10.*

    name: PHP ${{ matrix.php }} - Laravel ${{ matrix.laravel }}

    steps:
      - name: Checkout code
        uses: actions/checkout@v3

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: ${{ matrix.php }}
          extensions: dom, curl, libxml, mbstring, zip
          coverage: none

      - name: Install dependencies
        run: |
          composer require "laravel/framework:${{ matrix.laravel }}" --no-interaction --no-update
          composer update --prefer-dist --no-interaction --no-progress
```

## 📝 Usage Guide

<div align="center">

[![Command Documentation](https://img.shields.io/badge/Command-Documentation-blue.svg)](#usage-guide)

</div>

### Application Initialization

<table>
<tr>
<td width="60%">

```bash
php artisan app:init
```

This command runs the complete initialization process for your Laravel application, including:

- System requirements check
- Database initialization
- Application preparation
- Performance optimization

</td>
<td>

#### Options

| Option | Description |
|--------|-------------|
| `--skip-requirements` | Skip system requirements check |
| `--skip-db` | Skip database initialization |
| `--skip-prepare` | Skip application preparation |
| `--skip-optimize` | Skip application optimization |
| `--force` | Force the operation in production |
| `--show-config` | Show database configuration |
| `--migrate` | Run database migrations |
| `--seed` | Seed the database with records |

</td>
</tr>
</table>

### Application Deployment

<table>
<tr>
<td width="60%">

```bash
# Create deployment package
php artisan app:deploy --prepare

# Set up after deployment
php artisan app:deploy --setup
```

This command handles the deployment process of your Laravel application, including:

- Creating optimized deployment packages
- Setting up the application after deployment
- Installing dependencies and running migrations
- Optimizing for production environment

</td>
<td>

#### Options

| Option | Description |
|--------|-------------|
| `--prepare` | Create deployment package |
| `--setup` | Set up after deployment |
| `--exclude-vendor` | Exclude vendor directory |
| `--exclude-node` | Exclude node_modules directory |
| `--output=PATH` | Specify output path for package |

</td>
</tr>
</table>

### Frontend Asset Management

<table>
<tr>
<td width="60%">

```bash
php artisan app:install-tailwindcss
```

This command installs and configures TailwindCSS in your Laravel application with:

- Support for both Vite and Laravel Mix
- Optional plugins (forms, typography)
- Dark mode support
- Automatic detection of package manager
- Configuration file generation

</td>
<td>

#### Options

| Option | Description |
|--------|-------------|
| `--with-vite` | Configure for Vite (default) |
| `--with-mix` | Configure for Laravel Mix |
| `--with-plugins` | Install TailwindCSS plugins |
| `--dark-mode` | Enable dark mode support |
| `--force` | Overwrite existing files |

</td>
</tr>
</table>

### Application Optimization

<table>
<tr>
<td width="60%">

```bash
php artisan app:optimize
```

This command optimizes your Laravel application for better performance by:

- Clearing and rebuilding various caches
- Compiling frontend assets
- Creating storage links
- Optimizing for specific environments

</td>
<td>

#### Options

| Option | Description |
|--------|-------------|
| `--no-vite` | Skip Vite asset compilation |
| `--no-cache` | Skip cache rebuilding |
| `--no-storage-link` | Skip storage link creation |
| `--production` | Optimize for production |
| `--dev` | Optimize for development (default) |

</td>
</tr>
</table>

### Application Preparation

<table>
<tr>
<td width="60%">

```bash
# For development environment
php artisan app:prepare development

# For production environment
php artisan app:prepare production
```

This command prepares your Laravel application for specific environments by:

- Setting up directory structures
- Configuring environment-specific settings
- Managing file permissions
- Cleaning up unnecessary files

</td>
<td>

#### Options

| Option | Description |
|--------|-------------|
| `development` | Prepare for development |
| `production` | Prepare for production |
| `--clean` | Remove unnecessary files |
| `--permissions` | Set file permissions |
| `--optimize` | Run optimization commands |

</td>
</tr>
</table>

### Application Cleanup

<table>
<tr>
<td width="60%">

```bash
php artisan app:cleanup
```

This command provides a comprehensive cleanup utility for your Laravel application by:

- Clearing route, config, application, view, and event caches
- Clearing compiled classes
- Optimizing the autoloader
- Rebuilding configuration and route caches

</td>
<td>

#### Options

| Option | Description |
|--------|-------------|
| `--force` | Skip confirmation prompt |

</td>
</tr>
</table>

### Application Status

<table>
<tr>
<td width="60%">

```bash
php artisan app:status
```

This command provides a comprehensive report of your Laravel application's initialization status, including:

- Configuration files
- Environment variables
- Installed Node packages
- Installed Composer packages
- System information

</td>
<td>

#### Options

| Option | Description |
|--------|-------------|
| `--verbose` | Display detailed information |
| `--config-only` | Only check configuration files |
| `--packages-only` | Only check installed packages |

</td>
</tr>
</table>

### System Requirements Check

<table>
<tr>
<td width="60%">

```bash
php artisan app:check-requirements
```

This command performs comprehensive checks to ensure your server environment meets all necessary requirements, including:

- PHP version and extensions
- Required software versions
- PHP configuration settings
- File and directory permissions

</td>
<td>

#### What It Checks

- PHP 7.3+ or 8.0+
- Required PHP extensions
- MySQL 5.7+ or 8.0+
- Composer
- Node.js and NPM
- Server configuration
- Directory permissions

</td>
</tr>
</table>

### Database Initialization

<table>
<tr>
<td width="60%">

```bash
php artisan db:init
```

This command initializes and configures the database for your Laravel application with:

- Proper character set and collation
- Optional migrations and seeding
- Detailed error reporting for connection issues

</td>
<td>

#### Options

| Option | Description |
|--------|-------------|
| `--migrate` | Run migrations after initialization |
| `--seed` | Seed the database after migrations |
| `--force` | Force operation in production |
| `--show-config` | Show database configuration |

</td>
</tr>
</table>

### Database Backup

<table>
<tr>
<td width="60%">

```bash
php artisan db:backup
```

This command creates and manages database backups with powerful features:

- Support for MySQL, PostgreSQL, and SQLite
- Selective table backup
- Multiple compression formats (SQL, GZ, ZIP)
- Cloud storage integration
- Backup retention policies
- Notification options

</td>
<td>

#### Options

| Option | Description |
|--------|-------------|
| `--tables=` | Tables to backup (comma-separated) |
| `--exclude=` | Tables to exclude (comma-separated) |
| `--format=sql` | Output format (sql, gz, zip) |
| `--storage=` | Storage disk to use |
| `--path=` | Custom backup path |
| `--structure-only` | Only backup structure, not data |
| `--with-data` | Include data in backup (default) |
| `--notify` | Send notification when complete |
| `--force` | Force operation in production |

</td>
</tr>
</table>

### MySQL Command Execution

<table>
<tr>
<td width="60%">

```bash
php artisan mysql:exec --command="SHOW TABLES;"
```

This command executes MySQL commands directly using the MySQL command-line client, allowing you to:

- Run custom SQL queries
- Use predefined commands
- Format output in different ways
- Connect to different MySQL servers

</td>
<td>

#### Options

| Option | Description |
|--------|-------------|
| `--command=SQL` | MySQL command to execute |
| `--list-commands` | List predefined commands |
| `--vertical` | Print output vertically |
| `--mysql-path=PATH` | Path to MySQL executable |
| `--db=NAME` | Database name |

</td>
</tr>
</table>

### Development Server

<table>
<tr>
<td width="60%">

```bash
php artisan app:serve
```

This command provides an intelligent solution for serving your Laravel application with:

- Automatic port detection
- Browser auto-launch capability
- Customizable host and port settings
- Cross-platform support

</td>
<td>

#### Options

| Option | Description |
|--------|-------------|
| `--host=ADDRESS` | Custom host address |
| `--start-port=PORT` | Starting port number |
| `--max-attempts=NUM` | Max ports to try |
| `--no-open` | Don't open browser |

</td>
</tr>
</table>

## 🔧 Customization

### Modifying Port Range

You can easily customize the default port range by modifying the `$signature` property in the command:

```php
protected $signature = 'app:serve 
    {--host=127.0.0.1 : The host address to serve the application on}
    {--start-port=8001 : The starting port number to try}
    {--max-attempts=10 : Maximum number of ports to try}
    {--no-open : Do not open the browser automatically}';
```

### Enhancing Browser Opening Logic

You can customize the browser opening logic by modifying the `openBrowser()` method:

```php
protected function openBrowser($url)
{
    if (PHP_OS_FAMILY === 'Windows') {
        // Use 'start /B' to start the process in the background without a new window
        shell_exec('start "" /B ' . escapeshellarg($url));
    } elseif (PHP_OS_FAMILY === 'Darwin') {
        // On macOS, use 'open' with the URL
        shell_exec('open ' . escapeshellarg($url));
    } else {
        // On Linux, use 'xdg-open' with the URL
        shell_exec('xdg-open ' . escapeshellarg($url) . ' > /dev/null 2>&1 &');
    }
}
```

### Changing the Command Name

If you want to change the command name, modify the `$signature` property:

```php
protected $signature = 'your:serve {--host=127.0.0.1 : The host address to serve the application on}';
```

## 📊 Output Example

```
Checking if port 8001 is available...
Serving application on http://127.0.0.1:8001
Press Ctrl+C to stop the server

Starting Laravel development server: http://127.0.0.1:8001
[Wed Sep 7 15:45:23 2025] PHP 8.2.0 Development Server (http://127.0.0.1:8001) started
[Wed Sep 7 15:45:24 2025] [200]: /favicon.ico
[Wed Sep 7 15:45:25 2025] [200]: /css/app.css
[Wed Sep 7 15:45:25 2025] [200]: /js/app.js
```

The command will automatically open your default web browser to `http://127.0.0.1:8001` (or whichever port was available).

## 💡 Upcoming Features

We're planning to add the following features in future releases:

### HTTPS Support
Enable HTTPS with self-signed certificates for local development:
```bash
php artisan app:serve --https
```

### Environment-Specific Configuration
Specify which environment file to load:
```bash
php artisan app:serve --env=testing
```

### Custom Document Root
Specify a custom document root directory:
```bash
php artisan app:serve --docroot=public_html
```

### Specific Browser Selection
Open a specific browser instead of the default one:
```bash
php artisan app:serve --browser=chrome
```

### QR Code Display
Generate and display a QR code in the terminal for easy mobile testing:
```bash
php artisan app:serve --qr
```

### Multiple Host Binding
Bind to multiple interfaces simultaneously:
```bash
php artisan app:serve --hosts=127.0.0.1,192.168.1.100
```

### Auto-Reload on File Changes
Watch for file changes and reload the browser automatically:
```bash
php artisan app:serve --watch
php artisan app:serve --watch-dir=resources/views
```

### Performance Metrics
Display basic performance metrics for requests:
```bash
php artisan app:serve --metrics
```

### Request Logging
Enhanced request logging with filtering options:
```bash
php artisan app:serve --log-requests --log-level=debug
```

### API Testing Mode
Optimize output for API development:
```bash
php artisan app:serve --api-mode
```

### Custom Server Headers
Add custom headers to all responses:
```bash
php artisan app:serve --header="X-Custom: Value"
```

## 🤝 Contributing

Contributions are welcome! Feel free to submit a pull request or open an issue.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👨‍💻 Author

**Ez IT Solutions**  
[https://github.com/ez-it-solutions](https://github.com/ez-it-solutions)

---

<div align="center">

## 🤝 Support & Community

[![GitHub Issues](https://img.shields.io/github/issues/ez-it-solutions/laravel-init.svg)](https://github.com/ez-it-solutions/laravel-init/issues)
[![GitHub Stars](https://img.shields.io/github/stars/ez-it-solutions/laravel-init.svg)](https://github.com/ez-it-solutions/laravel-init/stargazers)
[![GitHub Forks](https://img.shields.io/github/forks/ez-it-solutions/laravel-init.svg)](https://github.com/ez-it-solutions/laravel-init/network)

We welcome contributions, bug reports, and feature requests! Feel free to open an issue or submit a pull request.

### Support Options

- **GitHub Issues**: For bug reports and feature requests
- **Email Support**: [support@ez-it-solutions.com](mailto:support@ez-it-solutions.com)
- **Documentation**: [https://ez-it-solutions.com/docs/laravel-init](https://ez-it-solutions.com/docs/laravel-init)

</div>

---

<div align="center">

### Made with ❤️ by Ez IT Solutions

© 2025 Ez IT Solutions. All rights reserved.

[Website](https://ez-it-solutions.com) • [GitHub](https://github.com/ez-it-solutions) • [Twitter](https://twitter.com/ezitsolutions)

</div>
