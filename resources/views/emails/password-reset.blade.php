@extends('emails.layout')

@section('subject', 'Reset Password - ' . config('app.name'))

@section('content')
    <h1 class="greeting">Hello {{ $user->name ?? 'Valued User' }},</h1>
    <p class="text">
        You are receiving this email because we received a password reset request for your account. If you did not request
        this, no further action is required.
    </p>

    <div class="button-wrapper">
        <a href="{{ $resetUrl }}" class="button">Reset My Password</a>
    </div>

    <div class="info-card" style="border-left-color: #f59e0b; background-color: #fffbeb;">
        <div class="info-card-title" style="color: #92400e;">Security Notice</div>
        <p class="text" style="color: #92400e; font-size: 14px; margin: 0;">
            This password reset link will expire in <strong>{{ $expireMinutes }} minutes</strong>. For security reasons, do
            not share this link with anyone.
        </p>
    </div>

    <p class="text"
        style="font-size: 14px; color: #94a3b8; margin-top: 40px; border-top: 1px solid #f1f5f9; padding-top: 20px;">
        If you're having trouble clicking the "Reset My Password" button, copy and paste the URL below into your web
        browser:
    </p>
    <div
        style="background-color: #f8fafc; padding: 12px; border: 1px solid #e2e8f0; border-radius: 6px; font-family: monospace; font-size: 11px; word-break: break-all; color: #4361ee;">
        {{ $resetUrl }}
    </div>
@endsection