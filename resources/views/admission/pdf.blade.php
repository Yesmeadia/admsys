@php $admission = $admission ?? $admissionQuery ?? null; @endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Application - {{ $admission->application_number ?? 'N/A' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700&display=swap" rel="stylesheet">
    <style>
        @page {
            margin: 20px;
            size: A4 portrait;
        }

        body {
            font-family: 'Helvetica', Arial, sans-serif;
            line-height: 1.3;
            color: #333;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }

        /* Header Styles - New Premium Style */
        .header {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #172B75;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-logo-td {
            width: 100px;
            vertical-align: middle;
        }

        .header-text-td {
            padding: 0;
            vertical-align: middle;
            text-align: center;
        }

        .header-logo-td img {
            height: 65px;
            margin: 0;
        }

        .society-name {
            font-size: 10px;
            margin: 0;
            color: #333;
            font-weight: 400;
            text-transform: none;
            letter-spacing: 0.5px;
        }

        .school-name {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 28px;
            margin: 0;
            color: #1a237e;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.1;
            text-align: left;
            letter-spacing: -0.5px;
        }

        .school-info {
            font-size: 10px;
            color: #333;
            margin-top: 2px;
            font-weight: 400;
        }

        .app-title {
            font-size: 14px;
            margin: 8px 0;
            color: #dc3545;
            font-weight: bold;
            text-decoration: underline;
        }

        .header-meta {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }

        /* Student Photo Section */
        .student-photo-section {
            float: left;
            width: 120px;
            margin-left: 15px;
            margin-bottom: 10px;
            text-align: center;
        }

        .student-photo {
            width: 100px;
            height: 120px;
            object-fit: cover;
            border: 2px solid #333;
            padding: 2px;
            background: white;
        }

        .photo-label {
            font-size: 9px;
            margin-top: 3px;
            font-weight: bold;
            color: #333;
        }

        .no-photo-box {
            width: 100px;
            height: 120px;
            border: 2px dashed #ccc;
            text-align: center;
            padding-top: 35px;
            color: #999;
            font-size: 9px;
        }

        /* Application Info */
        .application-info {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 3px;
            margin-bottom: 10px;
            overflow: hidden;
            border: 1px solid #dee2e6;
        }

        .info-row {
            display: flex;
            margin-bottom: 3px;
        }

        .info-label {
            font-weight: bold;
            color: #495057;
            min-width: 110px;
        }

        .info-value {
            flex: 1;
            border-bottom: 1px #ccc;
            padding-bottom: 2px;
        }

        /* Section Styles */
        .section {
            margin-bottom: 12px;
            page-break-inside: avoid;
        }

        .section-title {
            background: #172B75;
            color: white;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: bold;
            border-radius: 2px;
            margin-bottom: 6px;
        }

        /* Table Styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .data-table td {
            padding: 4px 6px;
            border: 1px solid #dee2e6;
            vertical-align: top;
        }

        .data-table .label {
            font-weight: bold;
            background: #f8f9fa;
            width: 25%;
            color: #495057;
        }

        .data-table .value {
            width: 25%;
            text-transform: uppercase;
        }

        /* Documents Grid */
        .documents-grid {
            margin-top: 10px;
        }

        .document-item {
            display: inline-block;
            width: 32%;
            border: 1px solid #dee2e6;
            padding: 8px;
            margin-bottom: 5px;
            border-radius: 3px;
            font-size: 10px;
        }

        .document-check {
            font-weight: bold;
            margin-right: 5px;
            font-size: 12px;
        }

        .submitted {
            color: #198754;
        }

        .missing {
            color: #dc3545;
        }

        /* Signature Section */
        .signature-section {
            margin-top: 25px;
            page-break-inside: avoid;
        }

        .signature-line {
            width: 200px;
            border-top: 1px solid #333;
            margin: 20px auto 5px;
            padding-top: 3px;
        }

        .signature-container {
            width: 100%;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8px;
            color: #6c757d;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 60px;
            color: rgba(0, 0, 0, 0.03);
            z-index: -1;
            white-space: nowrap;
            font-weight: bold;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            color: white;
            text-transform: uppercase;
        }

        .status-pending {
            background: #ffc107;
            color: #000;
        }

        .status-accepted {
            background: #198754;
        }

        .status-rejected {
            background: #dc3545;
        }

        .status-reviewed {
            background: #0dcaf0;
            color: #000;
        }

        .text-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="watermark">{{ config('app.school_name') }}</div>

    <div class="header" style="border-bottom: 3px solid #1a237e; padding-bottom: 12px; margin-bottom: 20px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 90px; vertical-align: middle; text-align: left;">
                    @if(isset($logo_base64) && $logo_base64)
                        <img src="{{ $logo_base64 }}" style="height: 80px; width: auto;">
                    @else
                        @php 
                            $logoPaths = [
                                public_path('images/logo1.png'),
                                public_path('images/logow.png'),
                                storage_path('app/public/logo1.png'),
                                storage_path('app/public/logow.png'),
                            ];
                            $foundPath = null;
                            foreach($logoPaths as $p) { if(file_exists($p)) { $foundPath = $p; break; } }
                        @endphp
                        @if($foundPath)
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents($foundPath)) }}"
                                style="height: 80px; width: auto;">
                        @endif
                    @endif
                </td>
                <td style="vertical-align: middle; text-align: left; padding-left: 18px;">
                    <h1 style="
                        font-family: 'Helvetica Neue', 'Arial Black', 'Helvetica', Arial, sans-serif;
                        font-size: 32px;
                        margin: 0 0 4px 0;
                        color: #1a237e;
                        font-weight: 900;
                        text-transform: uppercase;
                        line-height: 1.0;
                        letter-spacing: 0px;
                    ">{{ config('app.school_name') }}</h1>
                    <div style="font-size: 13px; font-weight: 500; color: #333; margin-top: 4px;">
                        Managed by: YES INDIA FOUNDATION (A KERALA BASED ACADEMIC COUNCIL)
                    </div>
                    <div style="font-size: 12px; color: #444; margin-top: 2px;">
                        {{ config('app.school_location') }} &nbsp; MOB: {{ config('app.school_phone') }}, &nbsp; Email: {{ config('app.school_email') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div style="text-align: center; margin-top: 15px;">
        <div class="app-title" style="margin-bottom: 5px;">
            {{ $admission->status == 'accepted' ? 'ADMISSION FORM' : 'ADMISSION APPLICATION FORM' }}
        </div>
        <div class="header-meta">
            Application No: <b>{{ $admission->application_number }}</b> |
            Date: {{ $admission->created_at->format('d/m/Y') }} |
            Form: <b>{{ $admission->form_type_name }}</b>
        </div>
    </div>
    </div>

    <!-- Application Info with Photo -->
    <div style="margin-bottom: 25px; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
        <div style="background: #f8fafc; padding: 12px 20px; border-bottom: 1px solid #e2e8f0;">
            <span style="font-size: 14px; font-weight: bold; color: #172B75;">APPLICANT PROFILE</span>
        </div>
        <table style="width: 100%; border-collapse: collapse; background: #fff;">
            <tr>
                <td style="padding: 20px; vertical-align: top;">
                    <h2 style="font-size: 18px; color: #1e293b; margin: 0 0 15px 0;">{{ $admission->first_name }}
                        {{ $admission->surname }}
                    </h2>

                    <table style="width: 100%; font-size: 11px;">
                        <tr>
                            <td style="padding-bottom: 8px; color: #64748b; width: 130px;">Application Number</td>
                            <td style="padding-bottom: 8px; font-weight: bold;">: {{ $admission->application_number }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 8px; color: #64748b;">Applied For Class</td>
                            <td style="padding-bottom: 8px; font-weight: bold;">:
                                {{ $admission->admission_sought_for_class }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 8px; color: #64748b;">Current Status</td>
                            <td style="padding-bottom: 8px;">
                                : <span class="status-badge status-{{ strtolower($admission->status) }}"
                                    style="padding: 2px 10px; border-radius: 12px; font-size: 9px;">
                                    {{ strtoupper($admission->status_label) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="color: #64748b;">Submission Date</td>
                            <td>: {{ $admission->created_at->format('d M, Y') }} at
                                {{ $admission->created_at->format('h:i A') }}
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width: 130px; padding: 20px; vertical-align: top; text-align: center;">
                    <div style="width: 110px; height: 130px; border: 1px solid #cbd5e1; background: #f1f5f9;">
                        @if(isset($student_photo_base64) && $student_photo_base64)
                            <img src="{{ $student_photo_base64 }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="line-height: 130px; font-size: 10px; color: #94a3b8;">NO PHOTO</div>
                        @endif
                    </div>
                    <div style="font-size: 8px; font-weight: bold; margin-top: 5px; color: #64748b;">STUDENT PHOTO</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Section 1: Personal Information -->
    <div class="section">
        <div class="section-title">PERSONAL INFORMATION</div>
        <table class="data-table">
            <tr>
                <td class="label">First Name</td>
                <td class="value">{{ $admission->first_name }}</td>
                <td class="label">Surname</td>
                <td class="value">{{ $admission->surname }}</td>
            </tr>
            <tr>
                <td class="label">Father's Full Name</td>
                <td class="value">{{ $admission->father_full_name ?? $admission->father_name }}</td>
                <td class="label">Mother's Full Name</td>
                <td class="value">{{ $admission->mother_full_name ?? $admission->mother_name }}</td>
            </tr>
            <tr>
                <td class="label">Father's Occupation</td>
                <td class="value">{{ $admission->father_occupation ?? 'N/A' }}</td>
                <td class="label">Mother's Occupation</td>
                <td class="value">{{ $admission->mother_occupation ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Date of Birth</td>
                <td class="value">{{ \Carbon\Carbon::parse($admission->date_of_birth)->format('d/m/Y') }}</td>
                <td class="label">Date of Birth (Words)</td>
                <td class="value">{{ $admission->date_of_birth_words }}</td>
            </tr>
            <tr>
                <td class="label">Religion</td>
                <td class="value">{{ $admission->religion }}</td>
                <td class="label">Caste</td>
                <td class="value">{{ $admission->caste ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Sub Caste</td>
                <td class="value">{{ $admission->sub_caste ?? 'N/A' }}</td>
                <td class="label">Place of Birth</td>
                <td class="value">{{ $admission->place_of_birth }}</td>
            </tr>
            <tr>
                <td class="label">Nationality</td>
                <td class="value">{{ $admission->nationality }}</td>
                <td class="label">Mother Tongue</td>
                <td class="value">{{ $admission->mother_tongue }}</td>
            </tr>
            <tr>
                <td class="label">Gender</td>
                <td class="value">{{ $admission->gender }}</td>
                <td class="label">Blood Group</td>
                <td class="value">{{ $admission->blood_group ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Aadhar Number</td>
                <td class="value">{{ $admission->aadhar_no }}</td>
                <td class="label">Guardian Name</td>
                <td class="value">{{ $admission->parents_guardian_full_name ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <!-- Section 2: Contact Information -->
    <div class="section">
        <div class="section-title">CONTACT INFORMATION</div>
        <table class="data-table">
            <tr>
                <td class="label">Local Address</td>
                <td class="value" colspan="3">{{ $admission->local_address }}</td>
            </tr>
            <tr>
                <td class="label">Permanent Address</td>
                <td class="value" colspan="3">{{ $admission->permanent_address }}</td>
            </tr>
            <tr>
                <td class="label">Mobile Number 1</td>
                <td class="value">{{ $admission->mobile_1 }}</td>
                <td class="label">Mobile Number 2</td>
                <td class="value">{{ $admission->mobile_2 ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Email Address</td>
                <td class="value" colspan="3" style="text-transform: none;">{{ $admission->email }}</td>
            </tr>
        </table>
    </div>

    <!-- Section 3: Educational Background -->
    <div class="section">
        <div class="section-title">EDUCATIONAL BACKGROUND</div>
        <table class="data-table">
            <tr>
                <td class="label">Last School Attended</td>
                <td class="value" colspan="3">{{ $admission->last_school_attended ?? 'N/A' }}</td>
            </tr>
            @if(in_array($admission->form_type, ['form2', 'form3']))
                <tr>
                    <td class="label">APAAR ID</td>
                    <td class="value">{{ $admission->apaar_id ?? 'N/A' }}</td>
                    <td class="label">PEN Number</td>
                    <td class="value">{{ $admission->pen_no ?? 'N/A' }}</td>
                </tr>
            @endif
        </table>
    </div>
    <!-- Section 4: Admission Details -->
    <div class="section">
        <div class="section-title">ADMISSION DETAILS</div>
        <table class="data-table">
            <tr>
                <td class="label">Admission Sought for</td>
                <td class="value">{{ $admission->admission_sought_for_class }}</td>
            </tr>
            @if($admission->form_type == 'form3')
                <tr>
                    <td class="label">Stream</td>
                    <td class="value">{{ $admission->stream ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Subject Group</td>
                    <td class="value">{{ $admission->subject_group ?? 'N/A' }}</td>
                </tr>
            @endif
        </table>
    </div>

    <!-- Section 5: Parent Photos -->
    <div class="section">
        <div class="section-title">PARENT PHOTOS</div>
        <table style="width: 100%; border-collapse: collapse; background: #fff; border: 1px solid #dee2e6;">
            <tr>
                <td style="width: 50%; padding: 15px; text-align: center; border-right: 1px solid #dee2e6;">
                    <div
                        style="width: 110px; height: 130px; border: 1px solid #cbd5e1; background: #f1f5f9; margin: 0 auto;">
                        @if(isset($father_photo_base64) && $father_photo_base64)
                            <img src="{{ $father_photo_base64 }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="line-height: 130px; font-size: 10px; color: #94a3b8;">NO PHOTO</div>
                        @endif
                    </div>
                    <div style="font-size: 10px; font-weight: bold; margin-top: 8px; color: #172B75;">FATHER'S PHOTO
                    </div>
                </td>
                <td style="width: 50%; padding: 15px; text-align: center;">
                    <div
                        style="width: 110px; height: 130px; border: 1px solid #cbd5e1; background: #f1f5f9; margin: 0 auto;">
                        @if(isset($mother_photo_base64) && $mother_photo_base64)
                            <img src="{{ $mother_photo_base64 }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="line-height: 130px; font-size: 10px; color: #94a3b8;">NO PHOTO</div>
                        @endif
                    </div>
                    <div style="font-size: 10px; font-weight: bold; margin-top: 8px; color: #172B75;">MOTHER'S PHOTO
                    </div>
                </td>
            </tr>
        </table>
    </div>

    </div>

    <!-- Section 6: Documents Enclosure -->
    @php
        $enclMap = [
            'form1' => [
                'aadhar_copy' => 'Student Adhaar card copy',
                'birth_cert' => 'Original Birth Certificate',
                'parent_aadhar' => 'Aadhar Card of Parent/Guardian',
                'passport_photos' => 'Two Passport Size Photograph of Student',
                'reservation_certificate' => 'Reservation Certificate (if any)'
            ],
            'form2' => [
                'aadhar_copy' => "Student's Aadhaar Card",
                'lc_last_school' => 'Previous School Leaving Certificate',
                'parent_aadhar' => 'Aadhaar cards of Father and Mother/Guardian',
                'passport_photos' => 'Two passport-sized photographs of the student',
                'progress_report' => "Last year's academic progress report",
                'reservation_certificate' => 'Reservation Certificate (if any)'
            ],
            'form3' => [
                'aadhar_copy' => 'Student Adhaar card copy',
                'lc_copy' => 'Transfer Certificate (LC)',
                'parent_aadhar' => 'Parent Aadhar Card',
                'passport_photos' => 'Passport Size Photographs',
                'marklist' => 'Marklist',
                'board_cert' => 'Original Certificate of Board Exam',
                'reservation_certificate' => 'Reservation Certificate (if any)'
            ]
        ];

        $currentFormEncl = $enclMap[$admission->form_type] ?? [];
        $savedSettings = $admission->document_enclosure ?? [];
        $checkedItems = [];

        foreach ($currentFormEncl as $key => $label) {
            if (isset($savedSettings[$key]['checked'])) {
                $checkedItems[] = [
                    'label' => $label,
                    'remark' => $savedSettings[$key]['remark'] ?? ''
                ];
            }
        }
    @endphp

    <div class="section">
        @if(!empty($checkedItems))
            <div class="section-title">DOCUMENTS ENCLOSED (Verified by Office)</div>
            <table class="data-table">
                <tr>
                    <td style="padding: 8px 12px;">
                        <ul style="margin: 0; padding-left: 15px; list-style-type: square;">
                            @foreach($checkedItems as $item)
                                <li style="margin-bottom: 1px;">
                                    <span style="font-weight: bold;">{{ $item['label'] }}</span>
                                    @if(!empty($item['remark']))
                                        <span style="color: #555; font-size: 9px; font-style: italic;"> — Remark:
                                            {{ $item['remark'] }}</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            </table>
        @else
            <div class="section-title">REQUIRED DOCUMENTS (To be submitted at Admission Confirmation)</div>
            <table class="data-table">
                <tr>
                    <td style="padding: 8px 12px;">
                        <ul style="margin: 0; padding-left: 15px; list-style-type: square;">
                            @if($admission->form_type == 'form1')
                                <li style="margin-bottom: 1px;">Student Adhaar card copy</li>
                                <li style="margin-bottom: 1px;">Original Birth Certificate</li>
                                <li style="margin-bottom: 1px;">Aadhar Card of Parent/Guardian</li>
                                <li style="margin-bottom: 1px;">Two Passport Size Photograph of Student</li>
                                <li style="margin-bottom: 1px;">Reservation Certificate (if any)</li>
                            @elseif($admission->form_type == 'form2')
                                <li style="margin-bottom: 1px;">Student's Aadhaar Card</li>
                                <li style="margin-bottom: 1px;">Aadhaar cards of Father and Mother/Guardian</li>
                                <li style="margin-bottom: 1px;">Previous School Leaving Certificate</li>
                                <li style="margin-bottom: 1px;">Two passport-sized photographs of the student</li>
                                <li style="margin-bottom: 1px;">Last year's academic progress report</li>
                                <li style="margin-bottom: 1px;">Reservation Certificate (if any)</li>
                            @elseif($admission->form_type == 'form3')
                                <li style="margin-bottom: 1px;">Student Adhaar card copy</li>
                                <li style="margin-bottom: 1px;">Transfer Certificate (LC)</li>
                                <li style="margin-bottom: 1px;">Parent Aadhar Card</li>
                                <li style="margin-bottom: 1px;">Passport Size Photographs</li>
                                <li style="margin-bottom: 1px;">Marklist</li>
                                <li style="margin-bottom: 1px;">Original Certificate of Board Exam</li>
                                <li style="margin-bottom: 1px;">Reservation Certificate (if any)</li>
                            @endif
                        </ul>
                    </td>
                </tr>
            </table>
        @endif
    </div>

    <!-- Section 7: Declaration & Signatures -->
    <div class="signature-section">
        <div class="section-title">ADMISSION RULES & REGULATIONS</div>
        <div style="font-size: 9.5px; line-height: 1.1; color: #333; margin-bottom: 6px;">
            <p style="margin-bottom: 1px;"><b>1.</b> The Head of the Institution has the final discretion to admit or
                refuse any child. Management reserves the right to amend these rules at any time.</p>
            <p style="margin-bottom: 1px;"><b>2.</b> There are no fee concessions. Fees are non-refundable, even if
                admission is cancelled in the middle of the academic year.</p>
            <p style="margin-bottom: 1px;"><b>3.</b> Students should not bring money or valuables to school. The school
                is not responsible for any loss.</p>
            <p style="margin-bottom: 1px;"><b>4.</b> The minimum age for admission to each class shall be in accordance
                with the National Education Policy (NEP) 2020 norms and applicable government regulations.</p>
            <p style="margin-bottom: 1px;"><b>5.</b> All required documents must be submitted at the time of admission.
                Admission cannot be finalized without the complete set of documents listed in the form.</p>
            <p style="margin-bottom: 1px;"><b>6.</b> For Classes II –X, a School Leaving Certificate and Mark Sheet are
                mandatory. Out-of-state certificates must be countersigned by the Education Officer.</p>
            <p style="margin-bottom: 1px;"><b>7.</b> Parents must disclose any physical or mental health issues before
                admission. Hiding this information may lead to admission cancellation.</p>
            <p style="margin-bottom: 1px;"><b>8.</b> Prior leave applications are mandatory. Medical absences exceeding
                three days require a doctor's certificate. Unexplained absence for over a month will result in the
                student's name being struck off the register.</p>
            <p style="margin-bottom: 1px;"><b>9.</b> The school enforces a zero-tolerance policy for misconduct.
                Fighting, abusive language, bullying, or disrespect toward staff or peers will result in immediate
                disciplinary action, including the possibility of expulsion.</p>
            <p style="margin-bottom: 1px;"><b>10.</b> The school uniform is mandatory; students not in compliance will
                be sent home. Hair must be kept simple and neat. Fashionable styles, coloring, or long hair (for boys)
                are strictly prohibited.</p>
            <p style="margin-bottom: 1px;"><b>11.</b> No student shall bring a vehicle to the school premises without a
                legal driving license.</p>
            <p
                style="margin-top: 5px; color: #172B75; font-style: italic; font-weight: bold; border-top: 1px solid #eee; padding-top: 3px;">
                We the
                following undersigned hereby certify that the above information provided is correct and if it is found
                incorrect or false, my ward shall be automatically debared from
                the school without any correspondence in this regard. We accept the process of admission under taken by
                the school authority.</p>
        </div>
        <table style="width: 100%;">
            <tr>
                <td style="width: 55%;"></td>
                <td style="text-align: center; width: 45%;">
                    <div class="signature-line"></div>
                    <div style="font-weight: bold;">Signature of Parent/Guardian</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- For Office Use Only Section -->
    <div
        style="border: 2px solid #172B75; padding: 15px; margin-top: 30px; page-break-inside: avoid; background-color: #f8f9fa;">
        <div style="text-align: center; margin-bottom: 20px; margin-top: -27px;">
            <span
                style="font-size: 12px; font-weight: bold; background: #172B75; color: white; padding: 5px 15px; text-transform: uppercase;">For
                Office Use Only</span>
        </div>

        <table style="width: 100%; border-collapse: separate; border-spacing: 0 10px;">
            <tr>
                <td style="width: 33%; padding-right: 15px; vertical-align: top;">
                    <div style="font-size: 9px; font-weight: bold; color: #172B75; margin-bottom: 4px;">Admitted Class
                    </div>
                    <div style="border: 1px solid #999; height: 30px; background: white;"></div>
                </td>
                <td style="width: 33%; padding-right: 15px; vertical-align: top;">
                    <div style="font-size: 9px; font-weight: bold; color: #172B75; margin-bottom: 4px;">Admission No
                    </div>
                    <div style="border: 1px solid #999; height: 30px; background: white;"></div>
                </td>
                <td style="width: 34%; vertical-align: top;">
                    <div style="font-size: 9px; font-weight: bold; color: #172B75; margin-bottom: 4px;">Date of
                        Admission
                    </div>
                    <div style="border: 1px solid #999; height: 30px; background: white;"></div>
                </td>
            </tr>
        </table>

        <table style="width: 100%; margin-top: 20px;">
            <tr>
                <td style="width: 50%; padding-right: 20px; text-align: center;">
                    <div style="border-bottom: 1px solid #333; margin-bottom: 5px; height: 30px;"></div>
                    <div style="font-size: 10px; font-weight: bold; color: #333;">Signature of Clerk</div>
                </td>
                <td style="width: 50%; padding-left: 20px; text-align: center;">
                    <div style="border-bottom: 1px solid #333; margin-bottom: 5px; height: 30px;"></div>
                    <div style="font-size: 10px; font-weight: bold; color: #333;">Signature of Principal</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p style="margin-bottom: 3px;">Generated on: {{ now()->format('d/m/Y h:i:s A') }} | Ref:
            {{ $admission->application_number }}
        </p>
        <p style="margin-bottom: 3px;">© {{ config('app.school_name') }} - All Rights Reserved</p>
    </div>
</body>

</html>