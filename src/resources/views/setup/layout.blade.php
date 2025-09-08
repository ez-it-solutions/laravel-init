<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Laravel Initialization Utility' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #818cf8;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
            --light-color: #f3f4f6;
            --dark-color: #1f2937;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f9fafb;
            color: #1f2937;
            line-height: 1.6;
        }
        
        .sidebar {
            background-color: #fff;
            border-right: 1px solid #e5e7eb;
            height: 100vh;
            position: fixed;
            width: 280px;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .sidebar-logo {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .sidebar-nav-item {
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            color: #4b5563;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .sidebar-nav-item:hover {
            background-color: #f3f4f6;
            color: var(--primary-color);
        }
        
        .sidebar-nav-item.active {
            background-color: #f3f4f6;
            color: var(--primary-color);
            border-right: 3px solid var(--primary-color);
            font-weight: 500;
        }
        
        .sidebar-nav-item i {
            margin-right: 0.75rem;
            font-size: 1.25rem;
        }
        
        .main-content {
            margin-left: 280px;
            padding: 2rem;
        }
        
        .page-header {
            margin-bottom: 2rem;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 1rem;
        }
        
        .page-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 600;
            padding: 1rem 1.5rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #4338ca;
            border-color: #4338ca;
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .alert-success {
            background-color: #ecfdf5;
            border-color: #d1fae5;
            color: #065f46;
        }
        
        .alert-danger {
            background-color: #fef2f2;
            border-color: #fee2e2;
            color: #991b1b;
        }
        
        .alert-warning {
            background-color: #fffbeb;
            border-color: #fef3c7;
            color: #92400e;
        }
        
        .alert-info {
            background-color: #eff6ff;
            border-color: #dbeafe;
            color: #1e40af;
        }
        
        .console-output {
            background-color: #1f2937;
            color: #f3f4f6;
            padding: 1rem;
            border-radius: 0.5rem;
            font-family: 'Fira Code', 'Courier New', Courier, monospace;
            font-size: 0.875rem;
            line-height: 1.5;
            overflow-x: auto;
            white-space: pre-wrap;
        }
        
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
        }
        
        .step {
            flex: 1;
            text-align: center;
            position: relative;
        }
        
        .step:not(:last-child):after {
            content: '';
            position: absolute;
            top: 1rem;
            left: 50%;
            width: 100%;
            height: 2px;
            background-color: #e5e7eb;
            z-index: 1;
        }
        
        .step-number {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            background-color: #e5e7eb;
            color: #6b7280;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            position: relative;
            z-index: 2;
        }
        
        .step.active .step-number {
            background-color: var(--primary-color);
            color: #fff;
        }
        
        .step.completed .step-number {
            background-color: var(--success-color);
            color: #fff;
        }
        
        .step-label {
            font-size: 0.875rem;
            color: #6b7280;
        }
        
        .step.active .step-label {
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .step.completed .step-label {
            color: var(--success-color);
            font-weight: 500;
        }
        
        .markdown-content img {
            max-width: 100%;
            height: auto;
        }
        
        .markdown-content h1,
        .markdown-content h2,
        .markdown-content h3 {
            margin-top: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .markdown-content pre {
            background-color: #f3f4f6;
            padding: 1rem;
            border-radius: 0.5rem;
            overflow-x: auto;
        }
        
        .markdown-content table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        
        .markdown-content table th,
        .markdown-content table td {
            padding: 0.5rem;
            border: 1px solid #e5e7eb;
        }
        
        .markdown-content table th {
            background-color: #f3f4f6;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="bi bi-rocket"></i> Laravel Init
                </div>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('ez-it-solutions.setup.index') }}" class="sidebar-nav-item {{ request()->routeIs('ez-it-solutions.setup.index') ? 'active' : '' }}">
                    <i class="bi bi-house"></i> Dashboard
                </a>
                <a href="{{ route('ez-it-solutions.setup.readme') }}" class="sidebar-nav-item {{ request()->routeIs('ez-it-solutions.setup.readme') ? 'active' : '' }}">
                    <i class="bi bi-file-text"></i> README
                </a>
                <a href="{{ route('ez-it-solutions.setup.help') }}" class="sidebar-nav-item {{ request()->routeIs('ez-it-solutions.setup.help') ? 'active' : '' }}">
                    <i class="bi bi-question-circle"></i> Help
                </a>
                <a href="{{ route('ez-it-solutions.setup.status') }}" class="sidebar-nav-item {{ request()->routeIs('ez-it-solutions.setup.status') ? 'active' : '' }}">
                    <i class="bi bi-graph-up"></i> Status
                </a>
                <a href="{{ route('ez-it-solutions.setup.configure-app') }}" class="sidebar-nav-item {{ request()->routeIs('ez-it-solutions.setup.configure-app') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i> Configure App
                </a>
                <a href="{{ route('ez-it-solutions.setup.setup-database') }}" class="sidebar-nav-item {{ request()->routeIs('ez-it-solutions.setup.setup-database') ? 'active' : '' }}">
                    <i class="bi bi-database"></i> Setup Database
                </a>
                <a href="{{ route('ez-it-solutions.setup.setup-frontend') }}" class="sidebar-nav-item {{ request()->routeIs('ez-it-solutions.setup.setup-frontend') ? 'active' : '' }}">
                    <i class="bi bi-palette"></i> Setup Frontend
                </a>
                <a href="{{ route('ez-it-solutions.setup.optimize-app') }}" class="sidebar-nav-item {{ request()->routeIs('ez-it-solutions.setup.optimize-app') ? 'active' : '' }}">
                    <i class="bi bi-lightning"></i> Optimize App
                </a>
                <a href="{{ route('ez-it-solutions.setup.backup-database') }}" class="sidebar-nav-item {{ request()->routeIs('ez-it-solutions.setup.backup-database') ? 'active' : '' }}">
                    <i class="bi bi-cloud-arrow-up"></i> Backup Database
                </a>
                <a href="{{ route('ez-it-solutions.setup.build-config') }}" class="sidebar-nav-item {{ request()->routeIs('ez-it-solutions.setup.build-config') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-code"></i> Build Config
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="page-header">
                <h1 class="page-title">{{ $title ?? 'Laravel Initialization Utility' }}</h1>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Enable Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        @yield('scripts')
    </script>
</body>
</html>
