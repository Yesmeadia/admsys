<x-guest-layout>
    <div class="mb-5">
        <span class="brand-accent">{{ config('app.school_name') }}</span>
        <h2 class="auth-title">Verify Email</h2>
        <p class="auth-subtitle text-muted">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div
            class="mb-4 font-medium text-sm text-success p-3 rounded-4 bg-success bg-opacity-10 border border-success border-opacity-20 animate-on-scroll">
            <x-fas-check-circle class="me-2" />
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 d-flex align-items-center justify-content-between gap-3">
        <form method="POST" action="{{ route('verification.send') }}" class="flex-grow-1">
            @csrf
            <div>
                <button type="submit" class="auth-btn-primary shadow-sm w-full mt-0">
                    {{ __('Resend Verification Email') }} <x-fas-redo class="ms-2" />
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-0">
            @csrf
            <button type="submit" class="btn btn-outline-danger border-0 rounded-4 px-3 py-2 fw-bold small">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>