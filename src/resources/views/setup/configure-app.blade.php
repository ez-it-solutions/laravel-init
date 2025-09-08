@extends('ez-it-solutions::setup.layout')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><i class="bi bi-gear me-2"></i> Configure Application</h3>
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
                    <div class="step active">
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
                
                <form action="{{ route('ez-it-solutions.setup.store-app-config') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="mb-0">Basic Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="app_name" class="form-label">Application Name</label>
                                        <input type="text" class="form-control" id="app_name" name="app_name" value="{{ $appName }}" required>
                                        <div class="form-text">The name of your application, used in various places.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="app_url" class="form-label">Application URL</label>
                                        <input type="url" class="form-control" id="app_url" name="app_url" value="{{ $appUrl }}" required>
                                        <div class="form-text">The URL where your application will be accessible.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="mb-0">Environment Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="app_env" class="form-label">Environment</label>
                                        <select class="form-select" id="app_env" name="app_env" required>
                                            <option value="local" {{ $appEnv === 'local' ? 'selected' : '' }}>Local</option>
                                            <option value="development" {{ $appEnv === 'development' ? 'selected' : '' }}>Development</option>
                                            <option value="staging" {{ $appEnv === 'staging' ? 'selected' : '' }}>Staging</option>
                                            <option value="production" {{ $appEnv === 'production' ? 'selected' : '' }}>Production</option>
                                        </select>
                                        <div class="form-text">The environment your application is running in.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="app_debug" name="app_debug" value="1" {{ $appDebug ? 'checked' : '' }}>
                                            <label class="form-check-label" for="app_debug">Enable Debug Mode</label>
                                        </div>
                                        <div class="form-text">When enabled, detailed error messages will be shown. Disable in production.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Additional Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="force_https" name="force_https" value="1">
                                            <label class="form-check-label" for="force_https">Force HTTPS</label>
                                        </div>
                                        <div class="form-text">Force all URLs to use HTTPS instead of HTTP.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="cache_routes" name="cache_routes" value="1">
                                            <label class="form-check-label" for="cache_routes">Cache Routes</label>
                                        </div>
                                        <div class="form-text">Cache routes for better performance.</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="cache_config" name="cache_config" value="1">
                                            <label class="form-check-label" for="cache_config">Cache Configuration</label>
                                        </div>
                                        <div class="form-text">Cache configuration files for better performance.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="cache_views" name="cache_views" value="1">
                                            <label class="form-check-label" for="cache_views">Cache Views</label>
                                        </div>
                                        <div class="form-text">Cache views for better performance.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('ez-it-solutions.setup.index') }}" class="btn btn-outline-secondary">
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
