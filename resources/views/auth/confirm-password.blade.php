<x-guest-layout>
    <div class="mb-4">
        <span class="brand-accent">{{ config('app.school_name') }}</span>
        <h2 class="auth-title">Verify Access</h2>
        <p class="auth-subtitle text-muted">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('Password')" class="mb-2 fw-bold" />
            <x-text-input id="password"
                class="form-control form-control-lg border-0 bg-light rounded-4 px-4 py-3 w-full" type="password"
                name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mb-4">
            <button type="submit" class="auth-btn-primary shadow-sm w-full">
                {{ __('Confirm') }} <x-fas-lock class="ms-2" />
            </button>
        </div>
    </form>
</x-guest-layout>