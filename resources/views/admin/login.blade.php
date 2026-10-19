<x-guest-layout>
    <div class="mb-4">
        <span class="brand-accent">ADMINISTRATIVE PORTAL</span>
        <h2 class="auth-title">Welcome Back</h2>
        <p class="auth-subtitle">Unlock your administrative dashboard to manage excellence.</p>
    </div>

    @if(session('status'))
        <div
            class="alert alert-success border-0 rounded-4 bg-success bg-opacity-10 text-success p-3 mb-4 animate-on-scroll">
            <i class="fas fa-check-circle me-2"></i> {{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 rounded-4 bg-danger bg-opacity-10 text-danger p-3 mb-4 animate-on-scroll">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="fas fa-exclamation-triangle"></i>
                <h6 class="mb-0 fw-bold">Login Failed</h6>
            </div>
            <ul class="mb-0 ps-3 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.post') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label fw-bold">Email Address</label>
            <div class="position-relative">
                <input type="email" class="form-control form-control-lg border-0 bg-light rounded-4 px-4 py-3 w-full"
                    id="email" name="email" value="{{ old('email') }}" required autofocus
                    placeholder="admin@example.com">
            </div>
        </div>

        <div class="mb-3 mt-4">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label fw-bold mb-0">Password</label>
                <a href="{{ route('password.request') }}" class="auth-link small font-bold">Forgot Password?</a>
            </div>
            <div class="position-relative">
                <input type="password" class="form-control form-control-lg border-0 bg-light rounded-4 px-4 py-3 w-full"
                    id="password" name="password" required placeholder="••••••••">
                <i class="fas fa-eye position-absolute top-50 end-0 translate-middle-y me-3 text-muted cursor-pointer"
                    id="togglePassword"></i>
            </div>
        </div>

        <div class="mb-4">
            <div class="form-check custom-checkbox">
                <input type="checkbox" class="form-check-input rounded border-gray-300" id="remember" name="remember">
                <label class="form-check-label ms-2 text-sm text-muted" for="remember">Keep me logged in</label>
            </div>
        </div>

        <div class="captcha-container mb-4 text-center">
            <div class="g-recaptcha d-inline-block" data-sitekey="{{ config('captcha.site_key') }}"></div>
            @error('g-recaptcha-response')
                <div class="text-danger small mt-2 text-center">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="auth-btn-primary shadow-sm">
            SIGN IN <i class="fas fa-arrow-right ms-2"></i>
        </button>
    </form>

    <div class="text-center mt-5 border-top pt-4">
        <p class="text-muted small">&copy; {{ date('Y') }} YES INDIA FOUNDATION. All rights reserved.</p>
    </div>

    @push('scripts')
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <script>
            document.getElementById('togglePassword').addEventListener('click', function () {
                const input = document.getElementById('password');
                const type = input.type === 'password' ? 'text' : 'password';
                input.type = type;
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        </script>
    @endpush
</x-guest-layout>