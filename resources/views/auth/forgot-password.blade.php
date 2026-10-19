<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        <span class="brand-accent">{{ config('app.school_name') }}</span>
        <h2 class="auth-title">Reset Password</h2>
        <p class="auth-subtitle text-muted">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email')" class="mb-2 fw-bold" />
            <x-text-input id="email" class="form-control form-control-lg border-0 bg-light rounded-4 px-4 py-3 w-full"
                type="email" name="email" :value="old('email')" required autofocus placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger small" />
        </div>

        <div class="mb-4">
            <button type="submit" class="auth-btn-primary shadow-sm w-full">
                {{ __('Email Password Reset Link') }} <x-fas-paper-plane class="ms-2" />
            </button>
        </div>

        <div class="text-center mt-4">
            <p class="text-muted small">Remembered your password?
                <a href="{{ route('login') }}" class="auth-link">Back to Sign In</a>
            </p>
        </div>
    </form>
</x-guest-layout>