@extends('emails.layout')

@php
    $application = $application ?? $admission ?? $query ?? null;
@endphp

@section('subject', 'Application Status Update - ' . config('app.name'))

@section('content')
    <h1 class="greeting">Hello {{ $application->full_name }},</h1>
    <p class="text">
        We have updated the status of your admission application. Please find the details of the update below.
    </p>

    <div class="info-card" style="border-left-color: {{ 
                        $application->status == 'accepted' ? '#10b981' :
        ($application->status == 'rejected' ? '#ef4444' :
            ($application->status == 'reviewed' ? '#f59e0b' : '#3b82f6')) 
                    }};">
        <div class="info-card-title">Application Status Detail</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Current Status</div>
                <div class="info-value">
                    <span class="badge {{ 
                                        $application->status == 'accepted' ? 'badge-success' :
        ($application->status == 'rejected' ? 'badge-danger' :
            ($application->status == 'reviewed' ? 'badge-warning' : 'badge-primary')) 
                                    }}">
                        {{ ucfirst($application->status) }}
                    </span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Application ID</div>
                <div class="info-value">{{ $application->application_number }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Desired Class</div>
                <div class="info-value">{{ $application->desired_class }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Last Updated</div>
                <div class="info-value">{{ now()->format('F d, Y') }}</div>
            </div>
        </div>
    </div>

    @if(!empty($emailData['additional_message']))
        <div class="info-card" style="border-left-color: #6366f1; background-color: #f5f3ff;">
            <div class="info-card-title" style="color: #4f46e5;">Message from Administrator</div>
            <p class="text" style="color: #4338ca; font-style: italic; margin: 0; font-size: 15px;">
                "{{ $emailData['additional_message'] }}"
            </p>
        </div>
    @endif

    @if($application->status == 'accepted')
        <div class="info-card" style="border-left-color: #10b981; background-color: #f0fdf4;">
            <div class="info-card-title" style="color: #166534;">Congratulations!</div>
            <p class="text" style="color: #166534; font-size: 14px; margin: 0;">
                Your application has been approved. Please visit the portal to complete your enrollment and secure your seat.
            </p>
        </div>
        <div class="button-wrapper">
            <a href="{{ config('app.url') }}" class="button" style="background-color: #10b981;">Access Student Portal</a>
        </div>
    @elseif($application->status == 'reviewed')
        <p class="text">
            Your application is currently under review by our admissions committee. We will notify you as soon as a final
            decision is reached.
        </p>
    @elseif($application->status == 'rejected')
        <p class="text">
            We regret to inform you that your application was not successful at this time. We wish you the best in your academic
            pursuits.
        </p>
    @endif

    <div style="background-color: #f1f5f9; padding: 20px; border-radius: 8px; margin-top: 30px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #64748b;">
            Questions? Contact us at <a href="mailto:{{ config('app.school_email') }}"
                style="color: #4361ee; text-decoration: none; font-weight: 600;">{{ config('app.school_email') }}</a>
        </p>
    </div>
@endsection