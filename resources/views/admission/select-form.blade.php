@extends('layouts.app')

@section('title', 'Select Admission Program - ' . config('app.school_name'))

@section('content')
    <div class="school-select-wrapper py-4 py-md-5">
        <div class="container">
            <!-- Breadcrumbs -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"
                            class="text-decoration-none text-muted"><x-fas-home class="me-1"
                                style="width: 12px; height: 12px;" /> Home</a></li>
                    <li class="breadcrumb-item text-muted">Online Admissions</li>
                    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Select Program</li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="text-center mb-5">
                <div class="select-badge-pill mb-2">
                    <span class="badge-dot-green"></span>
                    <span>ONLINE ADMISSION PORTAL &bull; SESSION {{ $academicYear }}</span>
                </div>
                <h1 class="select-page-title mb-2">Choose Your Child's Academic Stage</h1>
                <p class="select-page-subtitle text-muted mx-auto">
                    Select the program corresponding to the class your child is applying for to begin the fast, paperless
                    registration.
                </p>

                <!-- Application Stepper Indicator -->
                <div class="stepper-track-wrapper mx-auto mt-4">
                    <div class="d-flex align-items-center justify-content-center gap-2 gap-sm-4">
                        <div class="stepper-node active">
                            <span class="node-circle">1</span>
                            <span class="node-label">Select Program</span>
                        </div>
                        <div class="stepper-line active"></div>
                        <div class="stepper-node">
                            <span class="node-circle">2</span>
                            <span class="node-label">Student Details</span>
                        </div>
                        <div class="stepper-line"></div>
                        <div class="stepper-node">
                            <span class="node-circle">3</span>
                            <span class="node-label">PDF Receipt</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Program Selection Cards -->
            @if($anyFormEnabled)
                <div class="row g-4 justify-content-center mb-5">
                    @if($form1Enabled)
                        <!-- Form 1: Lower Academy -->
                        <div class="col-lg-4 col-md-6">
                            <div
                                class="selection-card h-100 p-4 rounded-4 border bg-white shadow-sm position-relative d-flex flex-column justify-content-between">
                                <div class="card-accent-bar bg-primary"></div>
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 fw-bold"
                                            style="font-size: 11px;">
                                            PRE-PRIMARY
                                        </span>
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 small fw-semibold">
                                            <x-fas-circle class="me-1 text-success" style="width: 7px; height: 7px;" /> Open
                                        </span>
                                    </div>

                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div>
                                            <h3 class="fw-bold text-dark mb-0" style="font-size: 1.35rem;">Lower Academy</h3>
                                            <small class="text-primary fw-semibold">Nursery to Class 1st</small>
                                        </div>
                                    </div>

                                    <p class="text-muted small mb-3" style="line-height: 1.6;">
                                        Designed for our youngest learners. Focus on sensory play, foundational phonetics, social
                                        development, and joyful learning.
                                    </p>

                                    <div class="feature-bullets mb-4 p-3 bg-light bg-opacity-50 rounded-3 border">
                                        <div class="bullet-item d-flex align-items-center gap-2 small text-dark mb-2">
                                            <x-fas-check-circle class="text-primary flex-shrink-0"
                                                style="width: 13px; height: 13px;" />
                                            <span>Classes: <strong>Nursery, LKG, UKG, 1st</strong></span>
                                        </div>
                                        <div class="bullet-item d-flex align-items-center gap-2 small text-dark mb-2">
                                            <x-fas-check-circle class="text-primary flex-shrink-0"
                                                style="width: 13px; height: 13px;" />
                                            <span>Play-way & activity-based methodology</span>
                                        </div>
                                        <div class="bullet-item d-flex align-items-center gap-2 small text-dark">
                                            <x-fas-check-circle class="text-primary flex-shrink-0"
                                                style="width: 13px; height: 13px;" />
                                            <span>Dedicated child-safe facilities</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <a href="{{ route('admission.form1') }}"
                                        class="btn btn-outline-primary w-100 rounded-pill py-2.5 fw-bold d-flex align-items-center justify-content-center gap-2 shadow-xs transition-btn">
                                        <span>Proceed to Lower Academy Form</span>
                                        <x-fas-arrow-right style="width: 13px; height: 13px;" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($form2Enabled)
                        <!-- Form 2: General Education -->
                        <div class="col-lg-4 col-md-6">
                            <div
                                class="selection-card h-100 p-4 rounded-4 border bg-white shadow-sm position-relative d-flex flex-column justify-content-between selected-stage-card">
                                <div class="card-accent-bar bg-info"></div>
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-1 fw-bold"
                                            style="font-size: 11px;">
                                            PRIMARY & SECONDARY
                                        </span>
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 small fw-semibold">
                                            <x-fas-circle class="me-1 text-success" style="width: 7px; height: 7px;" /> Open
                                        </span>
                                    </div>

                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div>
                                            <h3 class="fw-bold text-dark mb-0" style="font-size: 1.35rem;">General Education</h3>
                                            <small class="text-info fw-semibold">Class 2nd to Class 10th</small>
                                        </div>
                                    </div>

                                    <p class="text-muted small mb-3" style="line-height: 1.6;">
                                        Comprehensive schooling with rigorous academics, interactive science & IT labs, English
                                        fluency, and Board exam preparedness.
                                    </p>

                                    <div class="feature-bullets mb-4 p-3 bg-light bg-opacity-50 rounded-3 border">
                                        <div class="bullet-item d-flex align-items-center gap-2 small text-dark mb-2">
                                            <x-fas-check-circle class="text-info flex-shrink-0"
                                                style="width: 13px; height: 13px;" />
                                            <span>Classes: <strong>2nd to 10th Grade</strong></span>
                                        </div>
                                        <div class="bullet-item d-flex align-items-center gap-2 small text-dark mb-2">
                                            <x-fas-check-circle class="text-info flex-shrink-0"
                                                style="width: 13px; height: 13px;" />
                                            <span>Experiential STEM & computer education</span>
                                        </div>
                                        <div class="bullet-item d-flex align-items-center gap-2 small text-dark">
                                            <x-fas-check-circle class="text-info flex-shrink-0"
                                                style="width: 13px; height: 13px;" />
                                            <span>Holistic sports, arts & leadership programs</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <a href="{{ route('admission.form2') }}"
                                        class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold d-flex align-items-center justify-content-center gap-2 shadow-sm transition-btn">
                                        <span>Proceed to General Education Form</span>
                                        <x-fas-arrow-right style="width: 13px; height: 13px;" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($form3Enabled)
                        <!-- Form 3: Higher Secondary -->
                        <div class="col-lg-4 col-md-6">
                            <div
                                class="selection-card h-100 p-4 rounded-4 border bg-white shadow-sm position-relative d-flex flex-column justify-content-between">
                                <div class="card-accent-bar bg-dark"></div>
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="badge bg-dark bg-opacity-10 text-dark rounded-pill px-3 py-1 fw-bold"
                                            style="font-size: 11px;">
                                            HIGHER SECONDARY (10+2)
                                        </span>
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 small fw-semibold">
                                            <x-fas-circle class="me-1 text-success" style="width: 7px; height: 7px;" /> Open
                                        </span>
                                    </div>

                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div>
                                            <h3 class="fw-bold text-dark mb-0" style="font-size: 1.35rem;">Higher Secondary</h3>
                                            <small class="text-dark fw-semibold">Class 11th & 12th Streams</small>
                                        </div>
                                    </div>

                                    <p class="text-muted small mb-3" style="line-height: 1.6;">
                                        Specialized secondary education across Medical, Non-Medical, and Arts streams with advanced
                                        laboratory training and entrance mentoring.
                                    </p>

                                    <div class="feature-bullets mb-4 p-3 bg-light bg-opacity-50 rounded-3 border">
                                        <div class="bullet-item d-flex align-items-center gap-2 small text-dark mb-2">
                                            <x-fas-check-circle class="text-dark flex-shrink-0"
                                                style="width: 13px; height: 13px;" />
                                            <span>Streams: <strong>Medical, Non-Med, Arts</strong></span>
                                        </div>
                                        <div class="bullet-item d-flex align-items-center gap-2 small text-dark mb-2">
                                            <x-fas-check-circle class="text-dark flex-shrink-0"
                                                style="width: 13px; height: 13px;" />
                                            <span>Equipped physics, chemistry & bio labs</span>
                                        </div>
                                        <div class="bullet-item d-flex align-items-center gap-2 small text-dark">
                                            <x-fas-check-circle class="text-dark flex-shrink-0"
                                                style="width: 13px; height: 13px;" />
                                            <span>University preparation & career counseling</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <a href="{{ route('admission.form3') }}"
                                        class="btn btn-outline-dark w-100 rounded-pill py-2.5 fw-bold d-flex align-items-center justify-content-center gap-2 shadow-xs transition-btn">
                                        <span>Proceed to Higher Secondary Form</span>
                                        <x-fas-arrow-right style="width: 13px; height: 13px;" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <!-- All Forms Closed Message -->
                <div class="row justify-content-center mb-5">
                    <div class="col-lg-6">
                        <div class="p-5 text-center bg-white rounded-4 shadow-sm border animate-on-scroll">
                            <div class="rounded-circle bg-warning bg-opacity-10 text-warning mx-auto mb-3 d-flex align-items-center justify-content-center shadow-xs"
                                style="width: 64px; height: 64px;">
                                <x-fas-lock style="width: 26px; height: 26px;" />
                            </div>
                            <h4 class="fw-bold text-dark mb-2">Admissions Are Currently Closed</h4>
                            <p class="text-muted small mb-4">
                                Online admission applications for academic session {{ $academicYear }} are currently closed.
                                Please contact our administrative office for assistance and notifications on subsequent
                                registration windows.
                            </p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('home') }}" class="btn btn-light rounded-pill px-4 btn-sm fw-semibold">
                                    <x-fas-arrow-left class="me-1" style="width: 12px; height: 12px;" /> Back to Home
                                </a>
                                <a href="tel:{{ config('app.school_phone') }}"
                                    class="btn btn-primary rounded-pill px-4 btn-sm fw-semibold">
                                    <x-fas-phone class="me-1" style="width: 12px; height: 12px;" /> Call Admissions Desk
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Pre-Registration Checklist & Helpdesk Support -->
            <div class="row g-4 mt-2">
                <div class="col-lg-8">
                    <div class="p-4 rounded-4 bg-white border shadow-xs h-100">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="rounded-3 bg-primary bg-opacity-10 text-primary p-2 d-flex align-items-center justify-content-center"
                                style="width: 36px; height: 36px;">
                                <x-fas-clipboard-list style="width: 16px; height: 16px;" />
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">What You'll Need For Registration</h6>
                                <small class="text-muted">Keep these records handy to complete the application in under 5
                                    minutes</small>
                            </div>
                        </div>

                        <div class="row g-3 pt-2">
                            <div class="col-sm-6">
                                <div class="d-flex align-items-start gap-2">
                                    <x-fas-check class="text-success mt-1 flex-shrink-0"
                                        style="width: 12px; height: 12px;" />
                                    <div>
                                        <div class="fw-semibold small text-dark">Passport Photograph</div>
                                        <small class="text-muted" style="font-size: 11px;">Recent digital photo of the
                                            student (JPG/PNG)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-start gap-2">
                                    <x-fas-check class="text-success mt-1 flex-shrink-0"
                                        style="width: 12px; height: 12px;" />
                                    <div>
                                        <div class="fw-semibold small text-dark">Birth Certificate or Aadhar</div>
                                        <small class="text-muted" style="font-size: 11px;">Date of birth and identity
                                            verification</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-start gap-2">
                                    <x-fas-check class="text-success mt-1 flex-shrink-0"
                                        style="width: 12px; height: 12px;" />
                                    <div>
                                        <div class="fw-semibold small text-dark">Parent / Guardian Contacts</div>
                                        <small class="text-muted" style="font-size: 11px;">Active phone number & email
                                            address</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-start gap-2">
                                    <x-fas-check class="text-success mt-1 flex-shrink-0"
                                        style="width: 12px; height: 12px;" />
                                    <div>
                                        <div class="fw-semibold small text-dark">Previous Report Card</div>
                                        <small class="text-muted" style="font-size: 11px;">Transfer certificate/marks (For
                                            Class 2+)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div
                        class="p-4 rounded-4 bg-primary bg-opacity-10 border border-primary border-opacity-25 shadow-xs h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <x-fas-headset class="text-primary" style="width: 18px; height: 18px;" />
                                <h6 class="fw-bold text-dark mb-0">Need Assistance?</h6>
                            </div>
                            <p class="small text-muted mb-3" style="font-size: 12px; line-height: 1.6;">
                                Our admissions counselors are available Monday to Saturday (9:00 AM – 4:00 PM) to help you
                                choose the right grade.
                            </p>
                            <div class="mb-2">
                                <small class="text-muted d-block" style="font-size: 11px;">OFFICIAL HELPLINE</small>
                                <a href="tel:{{ config('app.school_phone') }}"
                                    class="fw-bold text-primary text-decoration-none fs-6">
                                    <x-fas-phone class="me-1" style="width: 13px; height: 13px;" />
                                    {{ config('app.school_phone') }}
                                </a>
                            </div>
                            <div>
                                <small class="text-muted d-block" style="font-size: 11px;">EMAIL SUPPORT</small>
                                <a href="mailto:{{ config('app.school_email') }}"
                                    class="fw-semibold text-dark text-decoration-none small">
                                    <x-fas-envelope class="me-1 text-primary" style="width: 12px; height: 12px;" />
                                    {{ config('app.school_email') }}
                                </a>
                            </div>
                        </div>

                        <div class="pt-3 mt-3 border-top border-primary border-opacity-20 text-center">
                            <a href="{{ route('home') }}"
                                class="btn btn-light rounded-pill px-3 py-1.5 small fw-semibold text-muted d-inline-flex align-items-center gap-1 shadow-xs">
                                <x-fas-arrow-left style="width: 10px; height: 10px;" /> Return to Homepage
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Inter', sans-serif;
        }

        .select-badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(12, 33, 58, 0.06);
            color: #0c213a;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .badge-dot-green {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #10b981;
        }

        .select-page-title {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(2rem, 3.5vw, 2.75rem);
            font-weight: 800;
            color: #0c213a;
            letter-spacing: -0.5px;
        }

        .select-page-subtitle {
            font-size: 1.05rem;
            max-width: 620px;
            line-height: 1.6;
        }

        /* Stepper Track */
        .stepper-track-wrapper {
            max-width: 540px;
        }

        .stepper-node {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stepper-node .node-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e2e8f0;
            color: #64748b;
            font-weight: 700;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .stepper-node.active .node-circle {
            background: #0c213a;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(12, 33, 58, 0.25);
        }

        .stepper-node .node-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #64748b;
        }

        .stepper-node.active .node-label {
            color: #0c213a;
            font-weight: 700;
        }

        .stepper-line {
            width: 40px;
            height: 2px;
            background: #e2e8f0;
        }

        .stepper-line.active {
            background: #0c213a;
        }

        /* Selection Card */
        .selection-card {
            transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
            overflow: hidden;
        }

        .card-accent-bar {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .selection-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 36px rgba(12, 33, 58, 0.1) !important;
            border-color: rgba(59, 130, 246, 0.35) !important;
        }

        .selected-stage-card {
            background: linear-gradient(180deg, #ffffff 0%, #f9fbff 100%) !important;
            border-color: rgba(59, 130, 246, 0.25) !important;
        }

        .transition-btn {
            transition: all 0.25s ease;
        }

        .transition-btn:hover {
            transform: translateY(-2px);
        }

        @media (max-width: 575px) {
            .stepper-node .node-label {
                display: none;
            }

            .stepper-line {
                width: 25px;
            }
        }
    </style>
@endsection