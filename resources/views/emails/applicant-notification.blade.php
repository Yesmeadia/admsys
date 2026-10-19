@extends('emails.layout')

@php
    $admission = $admission ?? $query ?? null;
@endphp

@section('subject', 'Admission Application Received - ' . config('app.school_name'))

@section('content')
    <h1 class="greeting">Hello {{ $admission->full_name }},</h1>
    <p class="text">
        Thank you for your interest in <strong>{{ config('app.school_name') }}</strong>. We have received your admission
        application and our team is currently reviewing it.
    </p>

    <div class="info-card">
        <div class="info-card-title">Application Reference</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Student Name</div>
                <div class="info-value">{{ $admission->full_name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Applied Class</div>
                <div class="info-value">{{ $admission->desired_class }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Submission Date</div>
                <div class="info-value">{{ now()->format('F d, Y') }}</div>
            </div>
        </div>
    </div>

    <p class="text">
        You can check your application status by logging into our portal using your registered email address. We will keep
        you updated on any progress regarding your application.
    </p>

    <div class="button-wrapper">
        <a href="{{ config('app.url') }}" class="button">Visit Portal</a>
    </div>

    <div style="background-color: #f1f5f9; padding: 20px; border-radius: 8px; margin-top: 30px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #64748b;">
            This is an automated confirmation of receipt. No further action is required from your side at this moment.
        </p>
    </div>
@endsection