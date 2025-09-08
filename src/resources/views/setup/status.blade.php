@extends('ez-it-solutions::setup.layout')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><i class="bi bi-graph-up me-2"></i> Application Status</h3>
                <div>
                    <button class="btn btn-outline-primary btn-sm me-2" id="refreshStatus">
                        <i class="bi bi-arrow-clockwise"></i> Refresh
                    </button>
                    <a href="{{ route('ez-it-solutions.setup.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs mb-4" id="statusTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="config-tab" data-bs-toggle="tab" data-bs-target="#config" type="button" role="tab" aria-controls="config" aria-selected="true">
                            <i class="bi bi-gear-fill me-1"></i> Configuration
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="env-tab" data-bs-toggle="tab" data-bs-target="#env" type="button" role="tab" aria-controls="env" aria-selected="false">
                            <i class="bi bi-file-earmark-text me-1"></i> Environment
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="node-tab" data-bs-toggle="tab" data-bs-target="#node" type="button" role="tab" aria-controls="node" aria-selected="false">
                            <i class="bi bi-box me-1"></i> Node Packages
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="composer-tab" data-bs-toggle="tab" data-bs-target="#composer" type="button" role="tab" aria-controls="composer" aria-selected="false">
                            <i class="bi bi-box-seam me-1"></i> Composer Packages
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="system-tab" data-bs-toggle="tab" data-bs-target="#system" type="button" role="tab" aria-controls="system" aria-selected="false">
                            <i class="bi bi-cpu me-1"></i> System Info
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="raw-tab" data-bs-toggle="tab" data-bs-target="#raw" type="button" role="tab" aria-controls="raw" aria-selected="false">
                            <i class="bi bi-terminal me-1"></i> Raw Output
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content" id="statusTabsContent">
                    <!-- Configuration Files Tab -->
                    <div class="tab-pane fade show active" id="config" role="tabpanel" aria-labelledby="config-tab">
                        <h4 class="mb-3">Configuration Files</h4>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>File</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($configFiles as $file)
                                        <tr>
                                            <td>
                                                @if($file['status'] === true)
                                                    <span class="badge bg-success"><i class="bi bi-check-lg"></i></span>
                                                @elseif($file['status'] === false)
                                                    <span class="badge bg-danger"><i class="bi bi-x-lg"></i></span>
                                                @else
                                                    <span class="badge bg-secondary"><i class="bi bi-dash"></i></span>
                                                @endif
                                            </td>
                                            <td>{{ $file['name'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Environment Variables Tab -->
                    <div class="tab-pane fade" id="env" role="tabpanel" aria-labelledby="env-tab">
                        <h4 class="mb-3">Environment Variables</h4>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Variable</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($envVars as $var)
                                        <tr>
                                            <td>
                                                @if($var['status'] === true)
                                                    <span class="badge bg-success"><i class="bi bi-check-lg"></i></span>
                                                @elseif($var['status'] === false)
                                                    <span class="badge bg-danger"><i class="bi bi-x-lg"></i></span>
                                                @else
                                                    <span class="badge bg-secondary"><i class="bi bi-dash"></i></span>
                                                @endif
                                            </td>
                                            <td>{{ $var['name'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Node Packages Tab -->
                    <div class="tab-pane fade" id="node" role="tabpanel" aria-labelledby="node-tab">
                        <h4 class="mb-3">Node Packages</h4>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Package</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($nodePackages as $package)
                                        <tr>
                                            <td>
                                                @if($package['status'] === true)
                                                    <span class="badge bg-success"><i class="bi bi-check-lg"></i></span>
                                                @elseif($package['status'] === false)
                                                    <span class="badge bg-danger"><i class="bi bi-x-lg"></i></span>
                                                @else
                                                    <span class="badge bg-secondary"><i class="bi bi-dash"></i></span>
                                                @endif
                                            </td>
                                            <td>{{ $package['name'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Composer Packages Tab -->
                    <div class="tab-pane fade" id="composer" role="tabpanel" aria-labelledby="composer-tab">
                        <h4 class="mb-3">Composer Packages</h4>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Package</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($composerPackages as $package)
                                        <tr>
                                            <td>
                                                @if($package['status'] === true)
                                                    <span class="badge bg-success"><i class="bi bi-check-lg"></i></span>
                                                @elseif($package['status'] === false)
                                                    <span class="badge bg-danger"><i class="bi bi-x-lg"></i></span>
                                                @else
                                                    <span class="badge bg-secondary"><i class="bi bi-dash"></i></span>
                                                @endif
                                            </td>
                                            <td>{{ $package['name'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- System Info Tab -->
                    <div class="tab-pane fade" id="system" role="tabpanel" aria-labelledby="system-tab">
                        <h4 class="mb-3">System Information</h4>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Component</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($systemInfo as $info)
                                        <tr>
                                            <td>{{ $info['name'] }}</td>
                                            <td>{{ str_replace(['PHP Version:', 'Laravel Version:', 'Node.js Version:', 'NPM Version:', 'Operating System:', 'Server Software:'], '', $info['name']) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Raw Output Tab -->
                    <div class="tab-pane fade" id="raw" role="tabpanel" aria-labelledby="raw-tab">
                        <h4 class="mb-3">Raw Command Output</h4>
                        <pre class="console-output">{{ $rawOutput }}</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
document.getElementById('refreshStatus').addEventListener('click', function() {
    window.location.reload();
});
@endsection
