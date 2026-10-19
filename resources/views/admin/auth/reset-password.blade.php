<x-guest-layout>
    <div class="mb-5">
        <span class="brand-accent">SECURE ACCOUNT RECOVERY</span>
        <h2 class="auth-title">Reset Password</h2>
        <p class="auth-subtitle text-muted">Please create a strong new password for your account to ensure continued
            security.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 rounded-4 bg-danger bg-opacity-10 text-danger p-3 mb-4 animate-on-scroll">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="fas fa-exclamation-triangle"></i>
                <h6 class="mb-0 fw-bold">Reset Failed</h6>
            </div>
            <ul class="mb-0 ps-3 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <!-- Email Address -->
        <div class="mb-3">
            <label class="form-label fw-bold">Email Address</label>
            <div class="position-relative">
                <input type="email" class="form-control form-control-lg border-0 bg-light rounded-4 px-4 py-3 w-full"
                    name="email" value="{{ $email ?? old('email') }}" required readonly>
            </div>
        </div>

        <!-- New Password -->
        <div class="mb-3">
            <label for="password" class="form-label fw-bold">New Password</label>
            <div class="position-relative">
                <input type="password" class="form-control form-control-lg border-0 bg-light rounded-4 px-4 py-3 w-full"
                    id="password" name="password" required placeholder="••••••••" onkeyup="checkStrength(this.value)">
                <i class="fas fa-eye position-absolute top-50 end-0 translate-middle-y me-3 text-muted cursor-pointer"
                    id="togglePassword"></i>
            </div>
            <div class="password-strength-container mt-2">
                <div class="password-strength-bar" id="strengthBar"></div>
            </div>
            <small class="text-muted" style="font-size: 11px;">Min 8 chars with letters, numbers & symbols</small>
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-bold">Confirm New Password</label>
            <div class="position-relative">
                <input type="password" class="form-control form-control-lg border-0 bg-light rounded-4 px-4 py-3 w-full"
                    id="password_confirmation" name="password_confirmation" required placeholder="••••••••">
                <i class="fas fa-eye position-absolute top-50 end-0 translate-middle-y me-3 text-muted cursor-pointer"
                    id="toggleConfirmPassword"></i>
            </div>
        </div>

        <button type="submit" class="auth-btn-primary shadow-sm mb-4">
            RESET PASSWORD <i class="fas fa-sync-alt ms-2"></i>
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

    @push('styles')
        <style>
            .password-strength-container {
                height: 6px;
                background: #e2e8f0;
                border-radius: 3px;
                overflow: hidden;
            }

            .password-strength-bar {
                height: 100%;
                width: 0;
                transition: all 0.3s ease;
            }

            .strength-1 {
                width: 25%;
                background: #ef4444;
            }

            .strength-2 {
                width: 50%;
                background: #f59e0b;
            }

            .strength-3 {
                width: 75%;
                background: #10b981;
            }

            .strength-4 {
                width: 100%;
                background: #059669;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            function setupToggle(buttonId, inputId) {
                const btn = document.getElementById(buttonId);
                const input = document.getElementById(inputId);
                btn.addEventListener('click', () => {
                    const type = input.type === 'password' ? 'text' : 'password';
                    input.type = type;
                    btn.classList.toggle('fa-eye');
                    btn.classList.toggle('fa-eye-slash');
                });
            }
            setupToggle('togglePassword', 'password');
            setupToggle('toggleConfirmPassword', 'password_confirmation');

            function checkStrength(p) {
                const bar = document.getElementById('strengthBar');
                let s = 0;
                if (p.length >= 8) s++;
                if (/[0-9]/.test(p)) s++;
                if (/[A-Z]/.test(p)) s++;
                if (/[^A-Za-z0-9]/.test(p)) s++;
                bar.className = 'password-strength-bar strength-' + s;
            }
        </script>
    @endpush
</x-guest-layout>