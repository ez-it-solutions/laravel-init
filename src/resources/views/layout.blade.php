<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Laravel Initialization Utility' }}</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Prism.js for syntax highlighting -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.25.0/themes/prism-tomorrow.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .code-block {
            border-radius: 0.5rem;
            background-color: #1e293b;
            color: #e2e8f0;
            padding: 1rem;
            margin: 1rem 0;
            overflow-x: auto;
        }
        .command-option {
            border-left: 3px solid #3b82f6;
            padding-left: 1rem;
            margin: 0.5rem 0;
        }
        .sidebar-link {
            transition: all 0.2s ease;
        }
        .sidebar-link:hover {
            background-color: rgba(59, 130, 246, 0.1);
            border-left: 3px solid #3b82f6;
        }
        .sidebar-link.active {
            background-color: rgba(59, 130, 246, 0.1);
            border-left: 3px solid #3b82f6;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="flex flex-col md:flex-row min-h-screen">
        <!-- Sidebar -->
        <div class="bg-white shadow-md w-full md:w-64 md:min-h-screen p-4">
            <div class="flex items-center justify-center mb-8 pt-4">
                <img src="https://github.com/ez-it-solutions.png" alt="Ez IT Solutions" class="w-16 h-16 rounded-full">
                <div class="ml-4">
                    <h1 class="text-xl font-bold text-gray-800">Laravel Init</h1>
                    <p class="text-sm text-gray-600">Documentation</p>
                </div>
            </div>
            
            <nav>
                <div class="mb-4">
                    <h2 class="text-xs uppercase font-semibold text-gray-500 tracking-wide mb-2">Getting Started</h2>
                    <ul>
                        <li><a href="#overview" class="sidebar-link block px-4 py-2 rounded-md">Overview</a></li>
                        <li><a href="#installation" class="sidebar-link block px-4 py-2 rounded-md">Installation</a></li>
                    </ul>
                </div>
                
                <div class="mb-4">
                    <h2 class="text-xs uppercase font-semibold text-gray-500 tracking-wide mb-2">Commands</h2>
                    <ul>
                        <li><a href="#app-init" class="sidebar-link block px-4 py-2 rounded-md">app:init</a></li>
                        <li><a href="#app-deploy" class="sidebar-link block px-4 py-2 rounded-md">app:deploy</a></li>
                        <li><a href="#app-optimize" class="sidebar-link block px-4 py-2 rounded-md">app:optimize</a></li>
                        <li><a href="#app-prepare" class="sidebar-link block px-4 py-2 rounded-md">app:prepare</a></li>
                        <li><a href="#app-cleanup" class="sidebar-link block px-4 py-2 rounded-md">app:cleanup</a></li>
                        <li><a href="#app-serve" class="sidebar-link block px-4 py-2 rounded-md">app:serve</a></li>
                        <li><a href="#db-init" class="sidebar-link block px-4 py-2 rounded-md">db:init</a></li>
                        <li><a href="#mysql-exec" class="sidebar-link block px-4 py-2 rounded-md">mysql:exec</a></li>
                    </ul>
                </div>
                
                <div class="mb-4">
                    <h2 class="text-xs uppercase font-semibold text-gray-500 tracking-wide mb-2">Resources</h2>
                    <ul>
                        <li><a href="https://github.com/ez-it-solutions/laravel-init" target="_blank" class="sidebar-link block px-4 py-2 rounded-md">
                            <i class="fab fa-github mr-2"></i> GitHub
                        </a></li>
                        <li><a href="https://packagist.org/packages/ez-it-solutions/laravel-init" target="_blank" class="sidebar-link block px-4 py-2 rounded-md">
                            <i class="fas fa-box mr-2"></i> Packagist
                        </a></li>
                        <li><a href="https://ez-it-solutions.com" target="_blank" class="sidebar-link block px-4 py-2 rounded-md">
                            <i class="fas fa-globe mr-2"></i> Website
                        </a></li>
                    </ul>
                </div>
            </nav>
            
            <div class="mt-auto pt-8 text-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} Ez IT Solutions</p>
                <p class="mt-1">v1.0.0</p>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 p-4 md:p-8 overflow-y-auto">
            @yield('content')
        </div>
    </div>
    
    <!-- JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.25.0/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.25.0/plugins/autoloader/prism-autoloader.min.js"></script>
    <script>
        // Highlight active sidebar link based on hash
        function setActiveLink() {
            const hash = window.location.hash || '#overview';
            document.querySelectorAll('.sidebar-link').forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === hash) {
                    link.classList.add('active');
                }
            });
            
            // Scroll to section
            if (hash) {
                const element = document.querySelector(hash);
                if (element) {
                    element.scrollIntoView({ behavior: 'smooth' });
                }
            }
        }
        
        // Initialize
        window.addEventListener('load', setActiveLink);
        window.addEventListener('hashchange', setActiveLink);
        
        // Make code blocks copyable
        document.querySelectorAll('.code-block').forEach(block => {
            const copyButton = document.createElement('button');
            copyButton.innerHTML = '<i class="far fa-copy"></i>';
            copyButton.className = 'absolute top-2 right-2 bg-gray-700 hover:bg-gray-600 text-white rounded p-1';
            copyButton.addEventListener('click', () => {
                const code = block.querySelector('code').innerText;
                navigator.clipboard.writeText(code);
                copyButton.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(() => {
                    copyButton.innerHTML = '<i class="far fa-copy"></i>';
                }, 2000);
            });
            
            // Make the block position relative for absolute positioning of the button
            block.style.position = 'relative';
            block.appendChild(copyButton);
        });
    </script>
</body>
</html>
