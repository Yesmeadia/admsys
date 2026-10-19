@extends('layouts.app')

@section('title', 'Session Expired')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0"><x-fas-exclamation-triangle class="me-2" />Session Expired</h4>
                </div>
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <x-fas-clock class="fa-5x text-danger mb-4" />
                        <h3 class="text-danger">Your session has expired</h3>
                        <p class="text-muted">{{ $message ?? 'The page has been inactive for too long. Please refresh and try again.' }}</p>
                    </div>

                    <div class="d-grid gap-2 col-md-6 mx-auto">
                        <button onclick="window.location.reload()" class="btn btn-primary">
                            <x-fas-redo class="me-2" />Refresh Page
                        </button>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                            <x-fas-arrow-left class="me-2" />Go Back
                        </a>
                    </div>

                    <div class="mt-4 text-muted small">
                        <p>If the problem persists, try:</p>
                        <ul class="list-unstyled">
                            <li><x-fas-check-circle class="text-success me-2" />Clear browser cache</li>
                            <li><x-fas-check-circle class="text-success me-2" />Enable cookies</li>
                            <li><x-fas-check-circle class="text-success me-2" />Disable browser extensions</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Update CSRF token after page loads
    document.addEventListener('DOMContentLoaded', function() {
        const newToken = '{{ $refresh_token ?? csrf_token() }}';
        if (newToken) {
            // Update meta tag
            const metaTag = document.querySelector('meta[name="csrf-token"]');
            if (metaTag) {
                metaTag.content = newToken;
            }

            // Update global variable
            if (window.CSRF_TOKEN) {
                window.CSRF_TOKEN = newToken;
            }

            // Update all hidden _token inputs
            document.querySelectorAll('input[name="_token"]').forEach(input => {
                input.value = newToken;
            });
        }
    });
</script>
@endsection
