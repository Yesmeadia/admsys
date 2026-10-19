<x-guest-layout>
    <div class="mb-5">
        <span class="brand-accent">IDENTITY VERIFICATION</span>
        <h2 class="auth-title">Verify Access</h2>
        <p class="auth-subtitle text-muted">For your protection, please confirm your password before continuing to this secure section.</p>
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

    <form method="POST" action="{{ route('password.confirm.post') }}">
        @csrf

        <div class="mb-4">
            <label for="password" class="form-label fw-bold">Administrative Password</label>
            <div class="position-relative">
                <input type="password" class="form-control form-control-lg border-0 bg-light rounded-4 px-4 py-3 w-full" id="password" name="password" required placeholder="••••••••">
                <i class="fas fa-eye position-absolute top-50 end-0 translate-middle-y me-3 text-muted cursor-pointer" id="togglePassword"></i>
            </div>
        </div>

        <button type="submit" class="auth-btn-primary shadow-sm mb-4">
            CONFIRM ACCESS <i class="fas fa-shield-halved ms-2"></i>
        </button>

        <div class="text-center">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-link small font-bold">
                    Forgot your password?
                </a>
            @endif
        </div>
    </form>

    <div class="text-center mt-5 border-top pt-4">
        <p class="text-muted small">&copy; {{ date('Y') }} YES INDIA FOUNDATION. All rights reserved.</p>
    </div>

    @push('scripts')
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