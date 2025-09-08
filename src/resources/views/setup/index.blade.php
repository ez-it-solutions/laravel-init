@extends('ez-it-solutions::setup.layout')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body">
                <h2 class="mb-4">Welcome to Laravel Initialization Utility</h2>
                <p class="lead">
                    This wizard will help you configure and use various features of the Laravel Initialization Utility.
                    Choose a task from the sidebar or follow the guided setup process below.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">Guided Setup Process</h3>
            </div>
            <div class="card-body">
                <div class="step-indicator mb-4">
                    <div class="step active">
                        <div class="step-number">1</div>
                        <div class="step-label">Start</div>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <div class="step-label">App Config</div>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-label">Database</div>
                    </div>
                    <div class="step">
                        <div class="step-number">4</div>
                        <div class="step-label">Frontend</div>
                    </div>
                    <div class="step">
                        <div class="step-number">5</div>
                        <div class="step-label">Optimize</div>
                    </div>
                    <div class="step">
                        <div class="step-number">6</div>
                        <div class="step-label">Finish</div>
                    </div>
                </div>
                
                <p>
                    Follow this guided setup process to configure your Laravel application step by step.
                    Each step will help you set up a different aspect of your application.
                </p>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('ez-it-solutions.setup.configure-app') }}" class="btn btn-primary">
                        Start Guided Setup <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="mb-0"><i class="bi bi-file-text me-2"></i> Documentation</h3>
            </div>
            <div class="card-body">
                <p>
                    View the README documentation to learn more about the Laravel Initialization Utility
                    and its features.
                </p>
                <div class="d-grid gap-2">
                    <a href="{{ route('ez-it-solutions.setup.readme') }}" class="btn btn-outline-primary">
                        View Documentation
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="mb-0"><i class="bi bi-question-circle me-2"></i> Help</h3>
            </div>
            <div class="card-body">
                <p>
                    Get help and documentation for all Laravel Initialization Utility commands
                    and features.
                </p>
                <div class="d-grid gap-2">
                    <a href="{{ route('ez-it-solutions.setup.help') }}" class="btn btn-outline-primary">
                        View Help
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="mb-0"><i class="bi bi-graph-up me-2"></i> Status</h3>
            </div>
            <div class="card-body">
                <p>
                    Check the status of your Laravel application, including configuration files,
                    environment variables, and installed packages.
                </p>
                <div class="d-grid gap-2">
                    <a href="{{ route('ez-it-solutions.setup.status') }}" class="btn btn-outline-primary">
                        Check Status
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="mb-0"><i class="bi bi-database me-2"></i> Database Management</h3>
            </div>
            <div class="card-body">
                <p>
                    Set up your database connection, create and manage database backups,
                    and run migrations and seeders.
                </p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                    <a href="{{ route('ez-it-solutions.setup.setup-database') }}" class="btn btn-outline-primary me-md-2">
                        Setup Database
                    </a>
                    <a href="{{ route('ez-it-solutions.setup.backup-database') }}" class="btn btn-outline-primary">
                        Backup Database
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="mb-0"><i class="bi bi-lightning me-2"></i> Application Optimization</h3>
            </div>
            <div class="card-body">
                <p>
                    Optimize your Laravel application for better performance by clearing caches,
                    compiling assets, and more.
                </p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                    <a href="{{ route('ez-it-solutions.setup.optimize-app') }}" class="btn btn-outline-primary me-md-2">
                        Optimize Application
                    </a>
                    <a href="{{ route('ez-it-solutions.setup.setup-frontend') }}" class="btn btn-outline-primary">
                        Setup Frontend
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0"><i class="bi bi-file-earmark-code me-2"></i> Configuration Builder</h3>
            </div>
            <div class="card-body">
                <p>
                    Build a configuration file based on your selected options. This file can be used
                    to quickly set up other Laravel applications with the same configuration.
                </p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('ez-it-solutions.setup.build-config') }}" class="btn btn-outline-primary">
                        Build Configuration
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
