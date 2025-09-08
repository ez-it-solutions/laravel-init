@extends('ez-it-solutions::setup.layout')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><i class="bi bi-question-circle me-2"></i> Help Documentation</h3>
                <a href="{{ route('ez-it-solutions.setup.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="list-group mb-4">
                            <div class="list-group-item bg-light">
                                <h5 class="mb-0">Available Commands</h5>
                            </div>
                            @foreach($commands as $cmd => $description)
                                <a href="{{ route('ez-it-solutions.setup.help', ['command' => $cmd]) }}" 
                                   class="list-group-item list-group-item-action {{ $selectedCommand === $cmd ? 'active' : '' }}">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $cmd }}</h6>
                                    </div>
                                    <small>{{ $description }}</small>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-8">
                        @if($selectedCommand)
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ $selectedCommand }}</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Description:</strong> {{ $commands[$selectedCommand] }}</p>
                                    
                                    <div class="mt-4">
                                        <h6>Command Details:</h6>
                                        {!! $helpContent !!}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <h5><i class="bi bi-info-circle me-2"></i> Help Center</h5>
                                <p>Select a command from the list to view detailed help information.</p>
                            </div>
                            
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Getting Started</h5>
                                </div>
                                <div class="card-body">
                                    <p>The Laravel Initialization Utility provides a comprehensive set of commands to help you initialize, configure, and optimize your Laravel application.</p>
                                    
                                    <h6 class="mt-4">Command Categories:</h6>
                                    <ul>
                                        <li><strong>Initialization Commands:</strong> app:init, app:prepare</li>
                                        <li><strong>Deployment Commands:</strong> app:deploy, app:optimize</li>
                                        <li><strong>Maintenance Commands:</strong> app:cleanup, app:serve</li>
                                        <li><strong>Database Commands:</strong> db:init, db:backup, mysql:exec</li>
                                        <li><strong>Utility Commands:</strong> app:status, app:help, app:setup</li>
                                    </ul>
                                    
                                    <h6 class="mt-4">Common Options:</h6>
                                    <ul>
                                        <li><code>--force</code>: Force the operation to run when in production</li>
                                        <li><code>--verbose</code>: Display detailed information</li>
                                        <li><code>--help</code>: Display help for the command</li>
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
