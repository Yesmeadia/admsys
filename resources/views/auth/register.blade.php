<x-guest-layout>
    <div class="mb-4">
        <span class="brand-accent">{{ config('app.school_name') }}</span>
        <h2 class="auth-title">Create Account</h2>
        <p class="auth-subtitle text-muted">Join our academic community and start your journey.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="mt-4">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <x-input-label for="name" :value="__('Full Name')" class="mb-2 fw-bold" />
            <x-text-input id="name" class="form-control form-control-lg border-0 bg-light rounded-4 px-4 py-3 w-full"
                type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger small" />
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <x-input-label for="email" :value="__('Email Address')" class="mb-2 fw-bold" />
            <x-text-input id="email" class="form-control form-control-lg border-0 bg-light rounded-4 px-4 py-3 w-full"
                type="email" name="email" :value="old('email')" required autocomplete="username"
                placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger small" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <x-input-label for="password" :value="__('Password')" class="mb-2 fw-bold" />
            <x-text-input id="password"
                class="form-control form-control-lg border-0 bg-light rounded-4 px-4 py-3 w-full" type="password"
                name="password" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger small" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="mb-2 fw-bold" />
            <x-text-input id="password_confirmation"
                class="form-control form-control-lg border-0 bg-light rounded-4 px-4 py-3 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger small" />
        </div>

        <div class="mb-4">
            <button type="submit" class="auth-btn-primary shadow-sm w-full">
                {{ __('Register Account') }} <x-fas-user-plus class="ms-2" />
            </button>
        </div>

        <div class="text-center mt-4">
            <p class="text-muted small">Already registered?
                <a href="{{ route('login') }}" class="auth-link font-bold">Sign In Instead</a>
            </p>
        </div>
    </form>
</x-guest-layout>