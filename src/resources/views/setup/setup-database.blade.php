@extends('ez-it-solutions::setup.layout')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><i class="bi bi-database me-2"></i> Setup Database</h3>
                <a href="{{ route('ez-it-solutions.setup.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
            <div class="card-body">
                <div class="step-indicator mb-4">
                    <div class="step completed">
                        <div class="step-number">1</div>
                        <div class="step-label">Start</div>
                    </div>
                    <div class="step completed">
                        <div class="step-number">2</div>
                        <div class="step-label">App Config</div>
                    </div>
                    <div class="step active">
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
                
                <form action="{{ route('ez-it-solutions.setup.store-database-config') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="mb-0">Database Connection</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="connection" class="form-label">Connection Type</label>
                                        <select class="form-select" id="connection" name="connection" required>
                                            <option value="mysql" {{ $connection === 'mysql' ? 'selected' : '' }}>MySQL</option>
                                            <option value="pgsql" {{ $connection === 'pgsql' ? 'selected' : '' }}>PostgreSQL</option>
                                            <option value="sqlite" {{ $connection === 'sqlite' ? 'selected' : '' }}>SQLite</option>
                                            <option value="sqlsrv" {{ $connection === 'sqlsrv' ? 'selected' : '' }}>SQL Server</option>
                                        </select>
                                        <div class="form-text">The database connection type to use.</div>
                                    </div>
                                    
                                    <div class="mb-3 connection-field" data-connection="mysql,pgsql,sqlsrv">
                                        <label for="host" class="form-label">Host</label>
                                        <input type="text" class="form-control" id="host" name="host" value="{{ $host }}">
                                        <div class="form-text">The hostname of your database server.</div>
                                    </div>
                                    
                                    <div class="mb-3 connection-field" data-connection="mysql,pgsql,sqlsrv">
                                        <label for="port" class="form-label">Port</label>
                                        <input type="text" class="form-control" id="port" name="port" value="{{ $port }}">
                                        <div class="form-text">The port of your database server.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="database" class="form-label">Database Name</label>
                                        <input type="text" class="form-control" id="database" name="database" value="{{ $database }}" required>
                                        <div class="form-text">The name of the database to use.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="mb-0">Authentication & Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3 connection-field" data-connection="mysql,pgsql,sqlsrv">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" value="{{ $username }}">
                                        <div class="form-text">The username to connect to your database.</div>
                                    </div>
                                    
                                    <div class="mb-3 connection-field" data-connection="mysql,pgsql,sqlsrv">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password">
                                        <div class="form-text">The password to connect to your database.</div>
                                    </div>
                                    
                                    <div class="mb-3 connection-field" data-connection="mysql,pgsql">
                                        <label for="charset" class="form-label">Character Set</label>
                                        <input type="text" class="form-control" id="charset" name="charset" value="{{ $charset }}">
                                        <div class="form-text">The character set to use for the database.</div>
                                    </div>
                                    
                                    <div class="mb-3 connection-field" data-connection="mysql,pgsql">
                                        <label for="collation" class="form-label">Collation</label>
                                        <input type="text" class="form-control" id="collation" name="collation" value="{{ $collation }}">
                                        <div class="form-text">The collation to use for the database.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Database Operations</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="run_migrations" name="run_migrations" value="1">
                                            <label class="form-check-label" for="run_migrations">Run Migrations</label>
                                        </div>
                                        <div class="form-text">Run database migrations after initialization.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="run_seeders" name="run_seeders" value="1">
                                            <label class="form-check-label" for="run_seeders">Run Seeders</label>
                                        </div>
                                        <div class="form-text">Run database seeders after migrations.</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="initialize" name="initialize" value="1">
                                            <label class="form-check-label" for="initialize">Initialize Database</label>
                                        </div>
                                        <div class="form-text">Create the database if it doesn't exist and set proper character set and collation.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="force" name="force" value="1">
                                            <label class="form-check-label" for="force">Force Operation</label>
                                        </div>
                                        <div class="form-text">Force the operation to run when in production.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('ez-it-solutions.setup.configure-app') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Previous
                        </a>
                        <div>
                            <button type="submit" class="btn btn-primary me-2">
                                Save Configuration
                            </button>
                            <button type="submit" name="next" value="1" class="btn btn-success">
                                Save and Continue <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </form>
                
                @if(session('output'))
                    <div class="mt-4">
                        <h5>Command Output:</h5>
                        <pre class="console-output">{{ session('output') }}</pre>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
// Show/hide fields based on connection type
document.addEventListener('DOMContentLoaded', function() {
    const connectionSelect = document.getElementById('connection');
    const connectionFields = document.querySelectorAll('.connection-field');
    
    function toggleConnectionFields() {
        const selectedConnection = connectionSelect.value;
        
        connectionFields.forEach(field => {
            const connections = field.getAttribute('data-connection').split(',');
            if (connections.includes(selectedConnection)) {
                field.style.display = 'block';
            } else {
                field.style.display = 'none';
            }
        });
    }
    
    connectionSelect.addEventListener('change', toggleConnectionFields);
    toggleConnectionFields();
});
@endsection
