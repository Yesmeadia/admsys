@extends('layouts.app')

@section('title', 'Application Submitted Successfully - ' . config('app.school_name'))

@php
    $admission = $admission ?? $admissionQuery ?? null;
    $studentName = 'N/A';
    $appliedClass = 'N/A';
    $appNumber = '----';
    $email = 'Not provided';
    $phone = 'Not provided';
    $academicYear = \App\Models\SiteSetting::get('academic_year');
    $submittedAt = now()->format('d M Y, h:i A');

    if ($admission) {
        if (!empty($admission->first_name)) {
            $studentName = trim($admission->first_name . ' ' . ($admission->surname ?? ''));
        } elseif (!empty($admission->student_name)) {
            $studentName = $admission->student_name;
        } elseif (!empty($admission->full_name)) {
            $studentName = $admission->full_name;
        }

        $appliedClass = $admission->admission_sought_for_class ?? $admission->admission_class ?? 'N/A';
        $appNumber = !empty($admission->application_number) ? str_pad($admission->application_number, 4, '0', STR_PAD_LEFT) : '----';
        $email = !empty($admission->email) ? $admission->email : 'Not provided';
        $phone = !empty($admission->mobile_1) ? $admission->mobile_1 : (!empty($admission->phone_no1) ? $admission->phone_no1 : 'Not provided');
        $academicYear = !empty($admission->academic_year) ? $admission->academic_year : $academicYear;
        if (!empty($admission->created_at)) {
            $submittedAt = $admission->created_at->format('d M Y, h:i A');
        }
    }
@endphp

@section('content')
    <div class="success-page-canvas py-2 py-md-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-9 col-lg-10">

                    <!-- Compact Celebration & Hero Header -->
                    <div class="text-center mb-3 animate-fade-up">
                        <div class="d-inline-flex align-items-center justify-content-center gap-2 mb-2">
                            <div class="celebration-seal-wrapper">
                                <div class="seal-pulsar-ring"></div>
                                <div class="seal-icon-circle">
                                    <x-fas-check />
                                </div>
                            </div>
                            <div class="session-badge-pill">
                                <span class="pulsing-live-dot"></span>
                                <span>SUBMISSION CONFIRMED</span>
                            </div>
                        </div>

                        <h2 class="success-hero-title mb-1">Application Submitted Successfully!</h2>
                        <p class="success-hero-subtitle mb-0">
                            Welcome to <strong>{{ config('app.school_name') }}</strong>. Your admission details have been
                            safely registered.
                        </p>
                    </div>

                    <!-- Main Official Admission Certificate / Receipt Card -->
                    <div class="official-receipt-card shadow-premium animate-fade-up" id="admissionReceiptCard">
                        <!-- Top Multi-Tone Accent Bar -->
                        <div class="receipt-accent-bar"></div>

                        <!-- Receipt Header -->
                        <div class="receipt-header p-4 p-md-5 pb-md-4">
                            <div
                                class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 text-center text-md-start">
                                <div class="d-flex align-items-center gap-3">
                                    <a href="{{ route('home') }}" class="d-inline-block">
                                        <img src="{{ asset('images/logo1.png') }}" alt="{{ config('app.school_name') }}"
                                            class="receipt-logo hover-scale">
                                    </a>
                                    <div>
                                        <h4 class="receipt-institution-name mb-0">{{ config('app.school_name') }}</h4>
                                        <span class="receipt-institution-sub">Central Admissions Office &bull; Verification
                                            Wing</span>
                                    </div>
                                </div>
                                <div class="receipt-status-chip">
                                    <span class="chip-status-dot"></span>
                                    <span class="chip-text">Application Under Review</span>
                                </div>
                            </div>
                        </div>

                        <div class="px-4 px-md-5">
                            <!-- Reference Number Spotlight Box -->
                            <div class="reference-spotlight-box p-3 p-md-4 mb-4">
                                <div class="row align-items-center g-3">
                                    <div class="col-md-7 text-center text-md-start">
                                        <span class="reference-box-label">APPLICATION REFERENCE ID</span>
                                        <div class="reference-code-wrapper">
                                            <span class="reference-code" id="appRefCode">#{{ $appNumber }}</span>
                                        </div>
                                        <p class="reference-note mb-0">Quote this unique reference ID for all queries,
                                            interviews, and tracking.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- 6-Point Structured Key Details Grid -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-4 col-sm-6">
                                    <div class="detail-tile h-100">
                                        <div class="detail-icon-box text-primary-navy">
                                            <x-fas-user-graduate />
                                        </div>
                                        <div class="detail-content">
                                            <span class="detail-label">Student Name</span>
                                            <h6 class="detail-value text-truncate" title="{{ $studentName }}">
                                                {{ $studentName }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6">
                                    <div class="detail-tile h-100">
                                        <div class="detail-icon-box text-accent-gold">
                                            <x-fas-graduation-cap />
                                        </div>
                                        <div class="detail-content">
                                            <span class="detail-label">Applied Class</span>
                                            <h6 class="detail-value text-truncate" title="{{ $appliedClass }}">
                                                {{ $appliedClass }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6">
                                    <div class="detail-tile h-100">
                                        <div class="detail-icon-box text-emerald">
                                            <x-fas-calendar-check />
                                        </div>
                                        <div class="detail-content">
                                            <span class="detail-label">Submission Date</span>
                                            <h6 class="detail-value text-truncate">{{ $submittedAt }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Action Command Center -->
                            <div class="action-command-bar p-3 p-md-4 mb-4">
                                <div
                                    class="d-flex flex-column flex-md-row gap-3 justify-content-center align-items-stretch">
                                    @if($admission && !$admission->downloaded_at)
                                        <a href="{{ route('admission.download', $admission->id) }}"
                                            class="btn-action-primary px-4 py-3">
                                            <x-fas-file-pdf class="btn-icon" />
                                            <span class="btn-text">Download Application</span>
                                        </a>
                                    @elseif($admission)
                                        <button class="btn-action-disabled px-4 py-3" disabled
                                            title="Application copy has already been downloaded.">
                                            <x-fas-check-circle class="btn-icon text-success" />
                                            <span class="btn-text">Application PDF Downloaded</span>
                                        </button>
                                    @endif
                                    <a href="{{ route('home') }}" class="btn-action-ghost px-4 py-3">
                                        <x-fas-home class="btn-icon" />
                                        <span class="btn-text">Return Home</span>
                                    </a>
                                </div>

                                <div class="text-center mt-3">
                                    <span class="email-dispatch-pill">
                                        <span>A PDF confirmation copy has also been sent to registered email address.</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Receipt Footer & Admissions Helpdesk -->
                        <div class="receipt-footer p-4 p-md-4 bg-slate-subtle border-top">
                            <div class="row align-items-center g-3 text-center text-md-start">
                                <div class="col-md-7">
                                    <h6 class="footer-help-title mb-1">
                                        <x-fas-info-circle class="me-1 text-primary-navy" />
                                        Have Questions or Need to Update Details?
                                    </h6>
                                    <p class="footer-help-desc mb-0">
                                        Our admissions team is available Mon &ndash; Sat (9:00 AM &ndash; 4:00 PM) to assist
                                        you.
                                    </p>
                                </div>
                                <div class="col-md-5 text-center text-md-end">
                                    <div
                                        class="d-flex flex-column flex-sm-row justify-content-center justify-content-md-end gap-2">
                                        @if(config('app.school_phone'))
                                            <a href="tel:{{ config('app.school_phone') }}" class="btn-help-contact">
                                                <x-fas-phone-alt class="me-1" /> {{ config('app.school_phone') }}
                                            </a>
                                        @endif
                                        @if(config('app.school_email'))
                                            <a href="mailto:{{ config('app.school_email') }}" class="btn-help-contact">
                                                <x-fas-envelope class="me-1" /> Helpdesk
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function copyApplicationId() {
            const text = '{{ $appNumber }}';
            const button = document.getElementById('btnCopyId');
            const label = document.getElementById('copyButtonLabel');

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(showCopiedState).catch(fallbackCopy);
            } else {
                fallbackCopy();
            }

            function fallbackCopy() {
                const tempInput = document.createElement('input');
                tempInput.value = text;
                document.body.appendChild(tempInput);
                tempInput.select();
                try {
                    document.execCommand('copy');
                    showCopiedState();
                } catch (err) {
                    console.error('Failed to copy', err);
                }
                document.body.removeChild(tempInput);
            }

            function showCopiedState() {
                button.classList.add('btn-copied');
                label.textContent = 'Copied to Clipboard!';
                setTimeout(() => {
                    button.classList.remove('btn-copied');
                    label.textContent = 'Copy Reference ID';
                }, 2500);
            }
        }
    </script>

    <style>
        /* ==========================================================
                                                       SUCCESS PAGE - INSTITUTIONAL RE-DESIGN
                                                       Color Tokens: Oxford Blue (#0c213a), Gold (#b08d57), Emerald (#10b981)
                                                       ========================================================== */

        .success-page-canvas {
            background: radial-gradient(circle at 50% 0%, rgba(12, 33, 58, 0.05) 0%, rgba(248, 250, 252, 1) 70%);
            min-height: calc(100vh - var(--nav-height, 80px));
        }

        /* Compact Celebration Seal & Animation */
        .celebration-seal-wrapper {
            position: relative;
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .seal-icon-circle {
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: 2px solid #ffffff;
            border-radius: 50%;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.35);
            z-index: 2;
            animation: scalePopIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .seal-icon-circle svg {
            width: 13px;
            height: 13px;
            fill: currentColor;
        }

        .seal-pulsar-ring {
            position: absolute;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(16, 185, 129, 0.22);
            animation: ringPulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            z-index: 1;
        }

        @keyframes scalePopIn {
            0% {
                transform: scale(0.4);
                opacity: 0;
            }

            70% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes ringPulse {
            0% {
                transform: scale(0.85);
                opacity: 0.8;
            }

            50% {
                transform: scale(1.25);
                opacity: 0.25;
            }

            100% {
                transform: scale(1.45);
                opacity: 0;
            }
        }

        /* Session Badge */
        .session-badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 0.25rem 0.85rem;
            border-radius: 50px;
            font-size: 0.725rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            color: #0c213a;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .pulsing-live-dot {
            width: 6px;
            height: 6px;
            background: #10b981;
            border-radius: 50%;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: dotGlow 1.8s infinite;
        }

        @keyframes dotGlow {
            0% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }

            70% {
                box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        /* Hero Typography */
        .success-hero-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            color: #0c213a !important;
            font-size: 1.45rem;
            letter-spacing: -0.02em;
            line-height: 1.25;
        }

        @media (min-width: 768px) {
            .success-hero-title {
                font-size: 1.65rem;
            }
        }

        .success-hero-subtitle {
            color: #64748b !important;
            font-size: 0.875rem;
            max-width: 580px;
            margin: 0 auto;
            line-height: 1.4;
        }

        /* The Main Receipt Card */
        .official-receipt-card {
            background: #ffffff;
            border-radius: 1.5rem;
            border: 1px solid rgba(226, 232, 240, 0.85);
            overflow: hidden;
            position: relative;
            background-clip: padding-box;
        }

        .receipt-accent-bar {
            height: 5px;
            width: 100%;
            background: linear-gradient(90deg, #0c213a 0%, #b08d57 50%, #10b981 100%);
        }

        .receipt-logo {
            height: 60px;
            width: auto;
            object-fit: contain;
        }

        .receipt-institution-name {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            color: #0c213a !important;
            font-size: 1.35rem;
            letter-spacing: -0.02em;
        }

        .receipt-institution-sub {
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
        }

        /* Status Chip */
        .receipt-status-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            padding: 0.45rem 1rem;
            border-radius: 50px;
            font-size: 0.825rem;
            font-weight: 700;
            color: #065f46;
        }

        .chip-status-dot {
            width: 7px;
            height: 7px;
            background: #10b981;
            border-radius: 50%;
        }

        /* Reference Spotlight Box */
        .reference-spotlight-box {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1.5px dashed #cbd5e1;
            border-radius: 1rem;
        }

        .reference-box-label {
            display: block;
            font-size: 0.725rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            color: #64748b;
            margin-bottom: 0.2rem;
        }

        .reference-code-wrapper {
            display: inline-block;
        }

        .reference-code {
            font-family: 'Outfit', 'Inter', monospace;
            font-size: 2rem;
            font-weight: 800;
            color: #0c213a !important;
            letter-spacing: 0.05em;
            line-height: 1.1;
        }

        .reference-note {
            font-size: 0.825rem;
            color: #64748b;
            margin-top: 0.25rem;
        }

        /* Copy Button */
        .btn-copy-reference {
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            color: #0c213a;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.875rem;
            padding: 0.6rem 1.25rem;
            border-radius: 50px;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.04);
        }

        .btn-copy-reference:hover {
            background: #0c213a;
            color: #ffffff;
            border-color: #0c213a;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(12, 33, 58, 0.15);
        }

        .btn-copy-reference.btn-copied {
            background: #10b981 !important;
            color: #ffffff !important;
            border-color: #10b981 !important;
        }

        /* 6-Point Detail Tiles */
        .detail-tile {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0.875rem;
            padding: 0.95rem 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            transition: all 0.2s ease;
        }

        .detail-tile:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(12, 33, 58, 0.04);
        }

        .detail-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .detail-icon-box svg {
            width: 1.15em;
            height: 1.15em;
        }

        .text-primary-navy {
            color: #0c213a !important;
        }

        .text-accent-gold {
            color: #b08d57 !important;
        }

        .text-emerald {
            color: #10b981 !important;
        }

        .text-royal-blue {
            color: #2563eb !important;
        }

        .text-purple {
            color: #7c3aed !important;
        }

        .text-slate {
            color: #64748b !important;
        }

        .detail-label {
            display: block;
            font-size: 0.725rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            margin-bottom: 0.15rem;
        }

        .detail-value {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            color: #0c213a !important;
            font-size: 0.975rem;
            margin-bottom: 0;
            letter-spacing: -0.01em;
        }

        /* Admissions Pathway / Stepper */
        .pathway-container {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
        }

        .pathway-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.05rem;
            color: #0c213a !important;
        }

        .pathway-title-icon {
            color: #b08d57;
            font-size: 1.1rem;
        }

        .pathway-status-badge {
            font-size: 0.75rem;
            font-weight: 700;
            background: #ecfdf5;
            color: #059669;
            padding: 0.25rem 0.65rem;
            border-radius: 50px;
            border: 1px solid #a7f3d0;
        }

        .stepper-horizontal {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            position: relative;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
        }

        .step-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #ffffff;
            border: 2px solid #cbd5e1;
            color: #64748b;
            font-weight: 800;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.6rem;
            position: relative;
            z-index: 2;
        }

        .step-circle svg {
            width: 14px;
            height: 14px;
        }

        .step-completed .step-circle {
            background: #10b981;
            border-color: #10b981;
            color: #ffffff;
        }

        .step-active .step-circle {
            background: #0c213a;
            border-color: #0c213a;
            color: #ffffff;
        }

        .step-pulse-ring {
            position: absolute;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 2px solid rgba(12, 33, 58, 0.4);
            animation: pulseRing 1.8s infinite;
        }

        @keyframes pulseRing {
            0% {
                transform: scale(0.8);
                opacity: 0.8;
            }

            100% {
                transform: scale(1.3);
                opacity: 0;
            }
        }

        .step-label {
            display: block;
            font-size: 0.685rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .step-active .step-label {
            color: #0c213a;
            font-weight: 800;
        }

        .step-completed .step-label {
            color: #059669;
        }

        .step-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.875rem;
            color: #0c213a !important;
            margin-bottom: 0.15rem;
        }

        .step-desc {
            font-size: 0.725rem;
            color: #64748b;
            margin-bottom: 0;
            line-height: 1.3;
        }

        /* Action Command Center */
        .action-command-bar {
            background: #ffffff;
            border-radius: 1rem;
        }

        .btn-action-primary {
            background: linear-gradient(135deg, #0c213a 0%, #1e3a5f 100%);
            color: #ffffff !important;
            border: 2px solid #0c213a;
            padding: 0.875rem 2rem;
            border-radius: 50px;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 0.02em;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 8px 20px rgba(12, 33, 58, 0.2);
        }

        .btn-action-primary:hover {
            background: linear-gradient(135deg, #1e3a5f 0%, #0c213a 100%);
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(12, 33, 58, 0.28);
        }

        .btn-action-outline {
            background: #ffffff;
            color: #0c213a !important;
            border: 2px solid #0c213a;
            padding: 0.875rem 1.85rem;
            border-radius: 50px;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 0.02em;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
        }

        .btn-action-outline:hover {
            background: #0c213a;
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(12, 33, 58, 0.18);
        }

        .btn-action-ghost {
            background: #f8fafc;
            color: #475569 !important;
            border: 2px solid #e2e8f0;
            padding: 0.875rem 1.75rem;
            border-radius: 50px;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 0.02em;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-action-ghost:hover {
            background: #ffffff;
            color: #0c213a !important;
            border-color: #cbd5e1;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .btn-action-disabled {
            background: #f1f5f9;
            color: #94a3b8 !important;
            border: 2px solid #e2e8f0;
            padding: 0.875rem 2rem;
            border-radius: 50px;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            cursor: not-allowed;
        }

        .btn-icon {
            width: 1.15em;
            height: 1.15em;
            flex-shrink: 0;
        }

        .email-dispatch-pill {
            display: inline-flex;
            align-items: center;
            font-size: 0.875rem;
            color: #475569;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 0.4rem 1.1rem;
            border-radius: 50px;
        }

        /* Receipt Footer */
        .bg-slate-subtle {
            background: #f8fafc;
        }

        .footer-help-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            color: #0c213a !important;
        }

        .footer-help-desc {
            font-size: 0.825rem;
            color: #64748b;
        }

        .btn-help-contact {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.45rem 1rem;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 50px;
            font-size: 0.825rem;
            font-weight: 700;
            color: #0c213a !important;
            text-decoration: none !important;
            transition: all 0.2s ease;
        }

        .btn-help-contact:hover {
            background: #0c213a;
            color: #ffffff !important;
            border-color: #0c213a;
        }

        .btn-help-contact svg {
            width: 0.9em;
            height: 0.9em;
        }

        /* Animations */
        .animate-fade-up {
            animation: fadeUpIn 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes fadeUpIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hover-scale {
            transition: transform 0.3s ease;
        }

        .hover-scale:hover {
            transform: scale(1.05);
        }

        /* ==========================================================
                                                       PRINT STYLESHEET: Pristine Official A4 Print Receipt
                                                       ========================================================== */
        @media print {

            /* Hide navbar, footer, action buttons, browser chrome */
            nav,
            footer,
            .navbar,
            .premium-footer,
            .action-command-bar,
            .celebration-seal-wrapper,
            .btn-copy-reference,
            .session-badge-pill {
                display: none !important;
            }

            body {
                background: #ffffff !important;
                color: #000000 !important;
                padding: 0 !important;
            }

            .success-page-canvas {
                background: #ffffff !important;
                padding: 0 !important;
            }

            .official-receipt-card {
                border: 1px solid #000000 !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }

            .receipt-accent-bar {
                height: 3px !important;
                background: #000000 !important;
            }

            .reference-spotlight-box {
                border: 1px dashed #000000 !important;
                background: #f8f8f8 !important;
            }

            .detail-tile {
                border: 1px solid #e0e0e0 !important;
                page-break-inside: avoid;
            }

            .pathway-container {
                border: 1px solid #e0e0e0 !important;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .stepper-horizontal {
                grid-template-columns: 1fr 1fr;
                row-gap: 1.5rem;
            }

            .success-hero-title {
                font-size: 1.75rem;
            }

            .reference-code {
                font-size: 1.6rem;
            }

            .btn-action-primary,
            .btn-action-outline,
            .btn-action-ghost,
            .btn-action-disabled {
                width: 100%;
            }
        }
    </style>
@endsection