@extends('emails.layout')

@section('subject', 'Security Alert: New Login Detected')

@section('content')
    <h1 class="greeting">Security Alert: New Login</h1>
    <p class="text">
        Hello <strong>{{ $user->name }}</strong>,
    </p>
    <p class="text">
        This is a security notification to inform you that your account was just accessed from a new device or location.
    </p>

    <div class="info-card" style="border-left-color: #ef476f;">
        <div class="info-card-title">Login Details</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Date & Time</div>
                <div class="info-value">{{ $loginTime }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">IP Address</div>
                <div class="info-value">{{ $ipAddress }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Device/Browser</div>
                <div class="info-value" style="font-size: 12px;">{{ $userAgent }}</div>
            </div>
        </div>
    </div>

    <p class="text">
        If this was you, you can safely ignore this email. No further action is required.
    </p>

    <div class="info-card" style="background-color: #fff1f2; border-left-color: #e11d48;">
        <p class="text" style="color: #9f1239; font-weight: 600; margin-bottom: 10px;">
            <i class="fas fa-exclamation-triangle"></i> Was this not you?
        </p>
        <p class="text" style="font-size: 14px; margin-bottom: 0;">
            If you did not authorize this login, please change your password immediately and contact the IT department to
            secure your account.
        </p>
    </div>

    <div class="button-wrapper">
        <a href="{{ route('password.request') }}" class="button" style="background-color: #e11d48;">
            Change Password
        </a>
    </div>
@endsection