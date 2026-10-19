<x-guest-layout>
    <div class="mb-5 text-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mb-4"
            style="width: 80px; height: 80px; font-size: 2rem;">
            <i class="fas fa-shield-check"></i>
        </div>
        <span class="brand-accent d-block">TWO-FACTOR AUTHENTICATION</span>
        <h2 class="auth-title">Verify Your Identity</h2>
        <p class="auth-subtitle text-muted">Enter the 6-digit verification code from your authenticator app, or an 8-character backup recovery code.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 rounded-4 bg-danger bg-opacity-10 text-danger p-3 mb-4 animate-on-scroll">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="fas fa-exclamation-triangle"></i>
                <h6 class="mb-0 fw-bold">Verification Failed</h6>
            </div>
            <ul class="mb-0 ps-3 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.two-factor.post') }}">
        @csrf

        <div class="mb-5">
            <label for="code" class="form-label fw-bold d-block text-center mb-3">Authentication or Backup Code</label>
            <div class="position-relative">
                <input type="text"
                    class="form-control form-control-lg border-0 bg-light rounded-4 px-4 py-4 text-center w-full"
                    style="letter-spacing: 0.5rem; font-weight: 800; font-size: 1.5rem; text-transform: uppercase;" id="code" name="code" required
                    autofocus maxlength="12" placeholder="000000 / CODE">
            </div>
        </div>

        <button type="submit" class="auth-btn-primary shadow-sm mb-4">
            VERIFY & LOGIN <i class="fas fa-circle-check ms-2"></i>
        </button>

        <div class="text-center mt-4">
            <a href="{{ route('admin.login') }}" class="auth-link font-bold">
                <i class="fas fa-arrow-left me-1"></i> Back to Login
            </a>
        </div>
    </form>

    <div class="text-center mt-5 border-top pt-4">
        <p class="text-muted small">&copy; {{ date('Y') }} YES INDIA FOUNDATION. All rights reserved.</p>
    </div>
</x-guest-layout>