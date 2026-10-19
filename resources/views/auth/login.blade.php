<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-4">
        <span class="brand-accent">{{ config('app.school_name') }}</span>
        <h2 class="auth-title">Welcome Back</h2>
        <p class="auth-subtitle">Please enter your details to sign in.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="mt-4">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-bold">Email Address</label>
            <input id="email" class="form-control form-control-lg border-0 bg-light rounded-4 px-4 py-3" type="email"
                name="email" :value="old('email')" required autofocus autocomplete="username"
                placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label fw-bold mb-0">Password</label>
                @if (Route::has('password.request'))
                    <a class="auth-link small" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>
            <input id="password" class="form-control form-control-lg border-0 bg-light rounded-4 px-4 py-3"
                type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="mb-4">
            <div class="form-check custom-checkbox">
                <input id="remember_me" type="checkbox" class="form-check-input rounded border-gray-300"
                    name="remember">
                <label for="remember_me" class="form-check-label ms-2 text-sm text-muted">Keep me signed in</label>
            </div>
        </div>

        <div class="mb-4">
            <button type="submit" class="auth-btn-primary shadow-sm">
                Sign In <x-fas-sign-in-alt class="ms-2" />
            </button>
        </div>

        @if (Route::has('register'))
            <div class="text-center mt-4 pt-2 border-top">
                <p class="text-muted small">Don't have an account?
                    <a href="{{ route('register') }}" class="auth-link">Contact Admin</a>
                </p>
            </div>
        @endif
    </form>
</x-guest-layout>