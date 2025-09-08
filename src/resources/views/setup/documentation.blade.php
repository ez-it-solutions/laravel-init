@extends('ez-it-solutions::setup.layout')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><i class="bi bi-file-text me-2"></i> README Documentation</h3>
                <a href="{{ route('ez-it-solutions.setup.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
            <div class="card-body">
                <div class="markdown-content">
                    {!! $content !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
// Add syntax highlighting for code blocks
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('pre code').forEach(function(block) {
        if (window.hljs) {
            hljs.highlightBlock(block);
        }
    });
});
@endsection
