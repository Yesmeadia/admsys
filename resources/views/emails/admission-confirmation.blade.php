@extends('emails.layout')

@php
    $admission = $admission ?? $query ?? null;
@endphp

@section('subject', 'Admission Application Confirmation - ' . config('app.school_name'))

@section('content')
    <div style="text-align: center; margin-bottom: 30px;">
        <span class="badge badge-success" style="padding: 8px 20px; font-size: 14px;">Submission Successful</span>
    </div>

    <h1 class="greeting">Dear {{ $admission->full_name }}</h1>
    <p class="text">
        Thank you for submitting your admission application to <strong>{{ config('app.school_name') }}</strong>. We
        have successfully received your form and it is currently being processed by our admissions department.
    </p>

    <div class="info-card">
        <div class="info-card-title">Application Reference</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Application ID</div>
                <div class="info-value">#{{ $admission->application_number }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Student Name</div>
                <div class="info-value">{{ $admission->full_name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Desired Class</div>
                <div class="info-value">{{ $admission->desired_class }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Submitted On</div>
                <div class="info-value">{{ $admission->submitted_at->format('F d, Y H:i') }}</div>
            </div>
        </div>
    </div>

    <div class="info-card" style="border-left-color: #4361ee; background-color: #f0f7ff;">
        <div class="info-card-title">What Happens Next?</div>
        <ul style="padding-left: 15px; margin: 0; color: #4b5563; font-size: 14px; line-height: 1.8;">
            <li>Our admissions team will thoroughly review your application.</li>
            <li>You will receive another email notification once your application state changes.</li>
            <li>Please keep your <strong>Application ID (#{{ $admission->application_number }})</strong> for all future
                correspondence.</li>
            <li>We have attached a copy of your completed application form for your records.</li>
        </ul>
    </div>

    <p class="text" style="font-size: 14px; text-align: center;">
        If you have any urgent questions, please feel free to reach out to our helpdesk.
    </p>
@endsection