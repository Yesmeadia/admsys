<x-guest-layout>
    <div class="mb-5">
        <span class="brand-accent">{{ config('app.school_name') }}</span>
        <h2 class="auth-title">New Password</h2>
        <p class="auth-subtitle text-muted">
            {{ __('Please create a strong new password for your account.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" class="mb-2 fw-bold" />
            <x-text-input id="email" class="form-control form-control-lg border-0 bg-light rounded-4 px-4 py-3 w-full"
                type="email" name="email" :value="old('email', $request->email)" required autofocus
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger small" />
        </div>

        <!-- Password -->
        <div class="mb-3 mt-4">
            <x-input-label for="password" :value="__('Password')" class="mb-2 fw-bold" />
            <x-text-input id="password"
                class="form-control form-control-lg border-0 bg-light rounded-4 px-4 py-3 w-full" type="password"
                name="password" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger small" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-4 mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="mb-2 fw-bold" />
            <x-text-input id="password_confirmation"
                class="form-control form-control-lg border-0 bg-light rounded-4 px-4 py-3 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger small" />
        </div>

        <div class="mb-4">
            <button type="submit" class="auth-btn-primary shadow-sm w-full">
                {{ __('Reset Password') }} <x-fas-check-circle class="ms-2" />
            </button>
        </div>
    </form>
</x-guest-layout>