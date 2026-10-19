<x-guest-layout>
    <div class="mb-5">
        <span class="brand-accent">SECURE ACCOUNT RECOVERY</span>
        <h2 class="auth-title">Forgot Password?</h2>
        <p class="auth-subtitle text-muted">Don't worry! Enter your email address and we'll send you instructions to
            reset your password securely.</p>
    </div>

    @if(session('status'))
        <div
            class="alert alert-success border-0 rounded-4 bg-success bg-opacity-10 text-success p-3 mb-4 animate-on-scroll">
            <i class="fas fa-check-circle me-2"></i> {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label fw-bold">Email Address</label>
            <div class="position-relative">
                <input type="email" class="form-control form-control-lg border-0 bg-light rounded-4 px-4 py-3 w-full"
                    id="email" name="email" value="{{ old('email') }}" required autofocus
                    placeholder="admin@example.com">
            </div>
            @error('email')
                <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="auth-btn-primary shadow-sm mb-4">
            SEND RESET LINK <i class="fas fa-paper-plane ms-2"></i>
        </button>

        <div class="text-center mt-4 pt-4 border-top">
            <a href="{{ route('admin.login') }}" class="auth-link font-bold">
                <i class="fas fa-arrow-left me-1"></i> Back to Login
            </a>
        </div>
    </form>

    <div class="text-center mt-5 border-top pt-4">
        <p class="text-muted small">&copy; {{ date('Y') }} YES INDIA FOUNDATION. All rights reserved.</p>
    </div>
</x-guest-layout>