@extends('emails.layout')

@php
    $admission = $admission ?? $query;
@endphp

@section('subject', 'New Admission Application - ' . config('app.name'))

@section('content')
    <h1 class="greeting">New Student Application,</h1>
    <p class="text">
        A new admission application has been submitted through the portal and is awaiting your review.
    </p>

    <div class="info-card">
        <div class="info-card-title">Student Information Summary</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Student Name</div>
                <div class="info-value">{{ $admission->full_name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Desired Class</div>
                <div class="info-value">{{ $admission->desired_class }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email Address</div>
                <div class="info-value">{{ $admission->email }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Contact Number</div>
                <div class="info-value">{{ $admission->mobile_1 }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Submission Date</div>
                <div class="info-value">{{ $admission->submitted_at->format('M d, Y H:i A') }}</div>
            </div>
        </div>
    </div>

    <div class="button-wrapper">
        <a href="{{ url('/admin/applications/' . $admission->id) }}" class="button" style="background-color: #059669;">View
            Full Application</a>
    </div>

    <div style="background-color: #fefce8; padding: 20px; border-radius: 8px; border: 1px solid #fef08a; margin-top: 20px;">
        <p style="margin: 0; font-size: 13px; color: #854d0e; text-align: center;">
            <strong>Admin Action Required:</strong> Please process this application by visiting the admin dashboard to
            ensure timely response to the applicant.
        </p>
    </div>
@endsection