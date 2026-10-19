@extends('admin.layout')

@section('title', 'Application #' . $application->id)
@section('page-title', 'Application Details')
@section('page-subtitle', 'Management and verification of applicant data')

@section('content')
    <div class="row fade-in">
        <!-- Left Column: Primary Information -->
        <div class="col-lg-8">
            <!-- Header Profile Card -->
            <div class="premium-card mb-4 overflow-hidden p-0">
                <div class="p-4 bg-primary bg-opacity-10 border-bottom d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-4">
                        <div class="position-relative">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-lg"
                                style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); font-size: 24px;">
                                @php
                                    $photoPath = $application->passport_photo_path ?? $application->student_photo_path;
                                    $photoUrl = $photoPath ? route('admin.file.serve', ['path' => base64_encode($photoPath)]) : null;
                                @endphp
                                @if($photoUrl)
                                    <img src="{{ $photoUrl }}" class="w-100 h-100 rounded-circle object-fit-cover shadow-sm">
                                @else
                                    {{ strtoupper(substr($application->first_name ?? 'A', 0, 1)) }}
                                @endif
                            </div>
                            <span
                                class="position-absolute bottom-0 end-0 p-2 bg-{{ $application->status == 'accepted' ? 'success' : ($application->status == 'rejected' ? 'danger' : 'warning') }} rounded-circle border border-4 border-white shadow-sm"
                                style="width: 20px; height: 20px;"></span>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h3 class="fw-bold mb-0 text-dark">{{ $application->first_name }}
                                    {{ $application->surname }}
                                </h3>
                                @if($application->payment_status == 'paid')
                                    <span class="badge bg-success rounded-pill px-3"><i
                                            class="fas fa-check-circle me-1"></i>PAID</span>
                                @elseif($application->payment_status == 'partial')
                                    <span class="badge bg-warning rounded-pill px-3"><i
                                            class="fas fa-hand-holding-usd me-1"></i>PARTIAL
                                        ({{ number_format($application->paid_amount) }})</span>
                                @elseif($application->payment_status == 'fee')
                                    <span class="badge bg-primary rounded-pill px-3"><i
                                            class="fas fa-file-invoice-dollar me-1"></i>FEE:
                                        {{ number_format($application->total_fee) }}</span>
                                @else
                                    <span class="badge bg-danger rounded-pill px-3"><i
                                            class="fas fa-times-circle me-1"></i>UNPAID</span>
                                @endif
                            </div>
                            <div class="d-flex align-items-center gap-3 text-muted">
                                <span class="small"><i class="fas fa-id-badge me-1"></i> ID #{{ $application->id }}</span>
                                <span class="small"><i class="fas fa-calendar-alt me-1"></i> Submitted
                                    {{ $application->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-white shadow-sm rounded-pill px-4" data-bs-toggle="modal"
                            data-bs-target="#editApplicationModal">
                            <i class="fas fa-edit me-2"></i> Edit
                        </button>
                        <a href="{{ route('admin.application.download', $application->id) }}"
                            class="btn btn-primary shadow-sm rounded-pill px-4">
                            <i class="fas fa-download me-2"></i> PDF
                        </a>
                        @if($application->downloaded_at)
                            <button type="button" class="btn btn-outline-warning shadow-sm rounded-pill px-4"
                                onclick="resetDownload({{ $application->id }})" title="Reset এক-কালীন download limit">
                                <i class="fas fa-history me-2"></i> Reset Download
                            </button>
                        @endif
                        <button type="button" class="btn btn-outline-danger shadow-sm rounded-pill px-4"
                            onclick="deleteApplication({{ $application->id }})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>

                <div class="p-4">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-4">
                                <small class="text-uppercase fw-bold text-muted d-block mb-1"
                                    style="font-size: 10px;">Applied For</small>
                                <div class="fw-bold text-dark">
                                    {{ $application->admission_sought_for_class ?? $application->admission_class ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-4">
                                <small class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 10px;">Form
                                    Type</small>
                                <div class="fw-bold text-dark">{{ strtoupper($application->form_type) }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-4">
                                <small class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 10px;">Email
                                    / Mobile</small>
                                <div class="fw-bold text-dark text-truncate" title="{{ $application->email ?? 'No email' }} | {{ $application->mobile_1 ?? $application->phone_no1 ?? 'No mobile' }}">
                                    {{ $application->email ?? 'No Email' }} &bull; {{ $application->mobile_1 ?? $application->phone_no1 ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="premium-card mb-4">
                <h5 class="fw-bold mb-4 d-flex align-items-center">
                    <span class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 me-3"><i
                            class="fas fa-user-check"></i></span>
                    Personal Information
                </h5>
                <div class="row g-4">
                    @php
                        $personalInfo = [
                            ['label' => 'Full Name', 'value' => $application->first_name . ' ' . $application->surname, 'icon' => 'fa-user'],
                            ['label' => 'Gender', 'value' => $application->gender, 'icon' => 'fa-venus-mars'],
                            ['label' => 'Date of Birth', 'value' => $application->date_of_birth ? $application->date_of_birth->format('M d, Y') : 'N/A', 'icon' => 'fa-birthday-cake'],
                            ['label' => 'Age', 'value' => $application->age . ' Years', 'icon' => 'fa-hourglass-half'],
                            ['label' => 'DOB in Words', 'value' => $application->date_of_birth_words ?? 'N/A', 'icon' => 'fa-font'],
                            ['label' => 'Place of Birth', 'value' => $application->place_of_birth ?? 'N/A', 'icon' => 'fa-map-marker-alt'],
                            ['label' => 'Nationality', 'value' => $application->nationality ?? 'N/A', 'icon' => 'fa-flag'],
                            ['label' => 'Religion', 'value' => $application->religion ?? 'N/A', 'icon' => 'fa-hands-praying'],
                            ['label' => 'Caste', 'value' => $application->caste ?? 'N/A', 'icon' => 'fa-users-cog'],
                            ['label' => 'Sub Caste', 'value' => $application->sub_caste ?? 'N/A', 'icon' => 'fa-users'],
                            ['label' => 'Mother Tongue', 'value' => $application->mother_tongue ?? 'N/A', 'icon' => 'fa-language'],
                            ['label' => 'Blood Group', 'value' => $application->blood_group ?? 'N/A', 'icon' => 'fa-tint'],
                            ['label' => 'Aadhar Number', 'value' => $application->aadhar_no ?? 'N/A', 'icon' => 'fa-id-card'],
                            ['label' => 'APAAR ID', 'value' => $application->apaar_id ?? 'N/A', 'icon' => 'fa-id-card-clip'],
                            ['label' => 'PEN Number', 'value' => $application->pen_no ?? 'N/A', 'icon' => 'fa-hashtag'],
                            ['label' => 'Adharshila Number', 'value' => $application->adharshila_number ?? 'N/A', 'icon' => 'fa-fingerprint']
                        ];
                    @endphp
                    @foreach($personalInfo as $info)
                        <div class="col-md-4 col-sm-6">
                            <label class="small text-muted mb-1">{{ $info['label'] }}</label>
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas {{ $info['icon'] }} text-primary opacity-50" style="font-size: 12px;"></i>
                                <span class="fw-semibold text-dark">{{ $info['value'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Parent & Guardian Information -->
            <div class="premium-card mb-4">
                <h5 class="fw-bold mb-4 d-flex align-items-center">
                    <span class="bg-info bg-opacity-10 text-info rounded-3 p-2 me-3"><i class="fas fa-users-cog"></i></span>
                    Parent & Guardian Information
                </h5>
                <div class="row g-4">
                    @php
                        $parentInfo = [
                            ['label' => 'Guardian Name', 'value' => $application->parents_guardian_full_name ?? 'N/A', 'icon' => 'fa-user-shield'],
                            ['label' => 'Father\'s Name', 'value' => $application->father_full_name ?? $application->father_name ?? 'N/A', 'icon' => 'fa-male'],
                            ['label' => 'Father\'s Qualification', 'value' => $application->father_qualification ?? 'N/A', 'icon' => 'fa-graduation-cap'],
                            ['label' => 'Father\'s Occupation', 'value' => $application->father_occupation ?? 'N/A', 'icon' => 'fa-briefcase'],
                            ['label' => 'Father\'s Monthly Income', 'value' => $application->father_designation ?? 'N/A', 'icon' => 'fa-money-bill-wave'],
                            ['label' => 'Mother\'s Name', 'value' => $application->mother_full_name ?? $application->mother_name ?? 'N/A', 'icon' => 'fa-female'],
                            ['label' => 'Mother\'s Qualification', 'value' => $application->mother_qualification ?? 'N/A', 'icon' => 'fa-graduation-cap'],
                            ['label' => 'Mother\'s Occupation', 'value' => $application->mother_occupation ?? 'N/A', 'icon' => 'fa-briefcase'],
                            ['label' => 'Mother\'s Monthly Income', 'value' => $application->mother_designation ?? 'N/A', 'icon' => 'fa-money-bill-wave']
                        ];
                    @endphp
                    @foreach($parentInfo as $info)
                        <div class="col-md-4 col-sm-6">
                            <label class="small text-muted mb-1">{{ $info['label'] }}</label>
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas {{ $info['icon'] }} text-info opacity-50" style="font-size: 12px;"></i>
                                <span class="fw-semibold text-dark">{{ $info['value'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Contact & Address -->
            <div class="Row g-4 mb-4">
                <div class="col-md-12">
                    <div class="premium-card h-100">
                        <h5 class="fw-bold mb-4 d-flex align-items-center">
                            <span class="bg-success bg-opacity-10 text-success rounded-3 p-2 me-3"><i
                                    class="fas fa-map-marked-alt"></i></span>
                            Contact & Communications
                        </h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="small text-muted mb-1">Local Address</label>
                                <p class="text-dark fw-medium mb-0">
                                    {{ $application->present_address ?? $application->local_address ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted mb-1">Permanent Address</label>
                                <p class="text-dark fw-medium mb-0">{{ $application->permanent_address ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="small text-muted mb-1">Primary Mobile</label>
                                <p class="text-dark fw-bold mb-0"><i
                                        class="fas fa-phone-alt me-2 text-success"></i>{{ $application->mobile_1 ?? $application->phone_no1 ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="small text-muted mb-1">Secondary Mobile</label>
                                <p class="text-dark fw-medium mb-0">{{ $application->mobile_2 ?? $application->phone_no2 ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="small text-muted mb-1">Email Address</label>
                                <p class="text-dark fw-medium mb-0">{{ $application->email ?? 'Not Provided' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Information -->
            <div class="premium-card mb-4">
                <h5 class="fw-bold mb-4 d-flex align-items-center">
                    <span class="bg-warning bg-opacity-10 text-warning rounded-3 p-2 me-3"><i
                            class="fas fa-graduation-cap"></i></span>
                    Academic Background
                </h5>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="small text-muted mb-1">Last School Attended</label>
                        <div class="fw-bold text-dark">{{ $application->last_school_attended ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted mb-1">Previous Class</label>
                        <div class="fw-bold text-dark">{{ $application->previous_class ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted mb-1">Academic Year</label>
                        <div class="fw-bold text-dark">{{ $application->academic_year ?? 'N/A' }}</div>
                    </div>

                    @if($application->form_type == 'form3')
                        <div class="col-12 mt-4 pt-3 border-top">
                            <h6 class="fw-bold text-primary mb-3">Higher Secondary Choice</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded-3">
                                        <small class="d-block text-muted mb-1">Stream</small>
                                        <span class="fw-bold">{{ $application->stream ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded-3">
                                        <small class="d-block text-muted mb-1">Subject Group</small>
                                        <span class="fw-bold">{{ $application->subject_group ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Documents Section -->
            <div class="premium-card">
                <h5 class="fw-bold mb-4 d-flex align-items-center">
                    <span class="bg-info bg-opacity-10 text-info rounded-3 p-2 me-3"><i class="fas fa-copy"></i></span>
                    Attached Photos
                </h5>
                <div class="row g-4">
                    @php
                        $docs = [
                            ['label' => 'Student\'s Photo', 'path' => $application->student_photo_path ?? $application->passport_photo_path, 'icon' => 'fa-user'],
                            ['label' => 'Father\'s Photo', 'path' => $application->father_photo_path, 'icon' => 'fa-user-tie'],
                            ['label' => 'Mother\'s Photo', 'path' => $application->mother_photo_path, 'icon' => 'fa-user-nurse'],
                        ];

                        // Only add legacy documents if they exist
                        if ($application->birth_certificate_path) {
                            $docs[] = ['label' => 'Birth Certificate', 'path' => $application->birth_certificate_path, 'icon' => 'fa-baby'];
                        }
                        if ($application->caste_certificate_path) {
                            $docs[] = ['label' => 'Caste Certificate', 'path' => $application->caste_certificate_path, 'icon' => 'fa-id-card'];
                        }
                        if ($application->leaving_certificate_path) {
                            $docs[] = ['label' => 'Leaving Certificate', 'path' => $application->leaving_certificate_path, 'icon' => 'fa-file-export'];
                        }
                        if ($application->marksheet_path) {
                            $docs[] = ['label' => 'Marksheet', 'path' => $application->marksheet_path, 'icon' => 'fa-file-invoice'];
                        }
                    @endphp
                    @foreach($docs as $doc)
                        <div class="col-md-4 col-sm-6">
                            <div class="doc-box text-center p-3 h-100 rounded-4 border-2 border-dashed bg-light">
                                <small class="fw-bold text-muted d-block mb-3">{{ $doc['label'] }}</small>

                                @if($doc['path'])
                                    @php
                                        $url = route('admin.file.serve', ['path' => base64_encode($doc['path'])]);
                                        $ext = strtolower(pathinfo($doc['path'], PATHINFO_EXTENSION));
                                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif']);
                                    @endphp

                                    <div class="position-relative mb-3 mx-auto" style="width: 80px; height: 80px;">
                                        @if($isImage)
                                            <img src="{{ $url }}" class="w-100 h-100 rounded-3 object-fit-cover shadow-sm border"
                                                onclick="showPreview('{{ $url }}', 'image')">
                                        @else
                                            <div class="w-100 h-100 rounded-3 bg-white d-flex align-items-center justify-content-center border"
                                                onclick="showPreview('{{ $url }}', 'pdf')">
                                                <i class="fas fa-file-pdf text-danger fa-2x"></i>
                                            </div>
                                        @endif
                                        <div class="position-absolute -top-2 -right-2">
                                            <span class="badge rounded-pill bg-success p-1"><i class="fas fa-check"></i></span>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="button" class="btn btn-sm btn-light rounded-pill px-3"
                                            onclick="showPreview('{{ $url }}', '{{ $isImage ? 'image' : 'pdf' }}')">
                                            <i class="fas fa-eye me-1"></i> View
                                        </button>
                                        <a href="{{ $url }}" download class="btn btn-sm btn-white rounded-circle shadow-sm"
                                            style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-download text-primary" style="font-size: 11px;"></i>
                                        </a>
                                    </div>
                                @else
                                    <div class="py-4 text-muted opacity-50">
                                        <i class="fas {{ $doc['icon'] }} fa-3x mb-2"></i>
                                        <p class="small mb-0">Missing</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Column: Verification & Timeline -->
        <div class="col-lg-4">
            <form id="statusForm">
                @csrf
                <!-- Management & Trail Combined Card -->
                <div class="premium-card mb-4 overflow-hidden p-0">
                    <!-- Dynamic Header showing current states -->
                    <div class="p-4 bg-dark text-white">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="mb-0 opacity-75 small text-uppercase fw-bold" style="letter-spacing: 1px;">
                                    Application Status</h6>
                                <h5 class="fw-bold mb-0">{{ $application->status_label }}</h5>
                            </div>
                            <div class="rounded-circle bg-white bg-opacity-20 d-flex align-items-center justify-content-center shadow-sm"
                                style="width: 45px; height: 45px;">
                                <i class="fas fa-shield-alt text-white"></i>
                            </div>
                        </div>
                        <div
                            class="d-flex align-items-center justify-content-between pt-3 border-top border-white border-opacity-10">
                            <div>
                                <h6 class="mb-0 opacity-75 small text-uppercase fw-bold" style="letter-spacing: 1px;">
                                    Payment Status</h6>
                                <h5 class="fw-bold mb-0">{{ strtoupper($application->payment_status ?? 'UNPAID') }}</h5>
                            </div>
                            <div class="text-end">
                                <h6 class="mb-0 opacity-75 small text-uppercase fw-bold" style="letter-spacing: 1px;">
                                    Balance</h6>
                                <h5 class="fw-bold mb-0">
                                    ₹{{ number_format($application->balance_amount ?? $application->total_fee ?? 0) }}</h5>
                            </div>
                        </div>
                    </div>

                    <div class="p-4">
                        <!-- Primary Changers -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Verification Result</label>
                                <select class="form-select bg-light border-0 py-2" name="status" id="statusSelect">
                                    <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="reviewed" {{ $application->status == 'reviewed' ? 'selected' : '' }}>
                                        Application Accepted</option>
                                    <option value="accepted" {{ $application->status == 'accepted' ? 'selected' : '' }}>
                                        Admitted</option>
                                    <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>
                                        Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Fee Status</label>
                                <select class="form-select bg-light border-0 py-2" name="payment_status"
                                    id="paymentStatusSelect">
                                    <option value="unpaid" {{ $application->payment_status == 'unpaid' ? 'selected' : '' }}>
                                        Unpaid</option>
                                    <option value="paid" {{ $application->payment_status == 'paid' ? 'selected' : '' }}>Paid
                                    </option>
                                    <option value="partial" {{ $application->payment_status == 'partial' ? 'selected' : '' }}>
                                        Partial</option>
                                    <option value="fee" {{ $application->payment_status == 'fee' ? 'selected' : '' }}>Fee
                                        Quoted</option>
                                </select>
                            </div>
                        </div>

                        <!-- Amount Changers (Conditional via JS) -->
                        <div class="mb-3 shadow-sm p-2 rounded-3 bg-opacity-10 bg-primary" id="paymentAmountFields" 
                                 style="{{ $application->payment_status == 'partial' ? '' : 'display: none;' }}">
                                <label class="form-label small fw-bold text-muted">Paid Amount</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-0">₹</span>
                                    <input type="number" class="form-control border-0" name="paid_amount" value="{{ $application->paid_amount ?? 0 }}">
                                </div>
                            </div>

                                @php
                                    $enclosures = [];
                                    if ($application->form_type == 'form1') {
                                        $enclosures = ['aadhar_copy' => 'Student Adhaar copy', 'birth_cert' => 'Birth Certificate', 'parent_aadhar' => 'Parent Aadhar', 'passport_photos' => 'Passport Photos', 'reservation_certificate' => 'Reservation Cert'];
                                    } elseif ($application->form_type == 'form2') {
                                        $enclosures = ['aadhar_copy' => "Student Aadhaar", 'parent_aadhar' => 'Parents Aadhaar', 'lc_last_school' => 'Previous LC', 'passport_photos' => 'Student Photos', 'progress_report' => "Progress Report", 'reservation_certificate' => 'Reservation Cert'];
                                    } elseif ($application->form_type == 'form3') {
                                        $enclosures = ['aadhar_copy' => 'Student Adhaar', 'lc_copy' => 'LC/TC', 'parent_aadhar' => 'Parent Aadhar', 'passport_photos' => 'Passport Photos', 'marklist' => 'Marklist', 'board_cert' => 'Board Certificate', 'reservation_certificate' => 'Reservation Cert'];
                                    }
                                    $savedEnclosures = $application->document_enclosure ?? [];
                                @endphp

                                @if(!empty($enclosures))
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-muted mb-2"><i class="fas fa-file-check me-2"></i>Document Verification</label>
                                        <div class="bg-light rounded-4 p-3 border border-dashed text-dark" style="max-height: 200px; overflow-y: auto;">
                                            @foreach($enclosures as $key => $label)
                                                <div class="row align-items-center mb-2 g-2">
                                                    <div class="col-7">
                                                        <div class="form-check m-0">
                                                            <input class="form-check-input" type="checkbox" name="document_enclosure[{{ $key }}][checked]" value="1" id="encl_{{ $key }}" {{ isset($savedEnclosures[$key]['checked']) ? 'checked' : '' }}>
                                                            <label class="form-check-label small fw-medium text-truncate d-block" for="encl_{{ $key }}">{{ $label }}</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-5">
                                                        <input type="text" class="form-control form-control-sm border-0 border-bottom bg-transparent p-0" name="document_enclosure[{{ $key }}][remark]" placeholder="Note..." value="{{ $savedEnclosures[$key]['remark'] ?? '' }}">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-muted">Internal Remarks</label>
                                    <textarea class="form-control bg-light border-0" name="remarks" rows="2" placeholder="Add private notes...">{{ $application->remarks ?? '' }}</textarea>
                                </div>

                                <!-- UPDATED: Buttons are now always clearly visible after the management section -->
                                <div class="d-grid gap-2 mb-4">
                                    <button type="button" class="btn btn-primary py-3 rounded-4 fw-bold shadow-sm" id="updateStatusBtn">
                                        <i class="fas fa-save me-2"></i> Update Record
                                    </button>
                                    <button type="button" class="btn btn-outline-primary py-2 rounded-4 small border-0" id="sendApplicationEmailBtn">
                                        <i class="fas fa-paper-plane me-1"></i> Email decision to student
                                    </button>
                                </div>

                                <hr class="my-4 opacity-10">

                                <!-- Timeline: Application Trail included in the same card -->
                                <h6 class="fw-bold mb-4 text-muted text-uppercase small" style="letter-spacing: 1px;">Application Trail</h6>
                                <div class="timeline-v2">
                                    <div class="trail-item active">
                                        <div class="trail-icon bg-success"><i class="fas fa-file-upload"></i></div>
                                        <div class="trail-info">
                                            <h6 class="fw-bold mb-0 small">Submission Received</h6>
                                            <small class="text-muted" style="font-size: 10px;">{{ $application->created_at->format('M d, Y | h:i A') }}</small>
                                        </div>
                                    </div>

                                    @foreach($application->activities->sortBy('created_at') as $activity)
                                        <div class="trail-item active">
                                            @php
                                                $icon = 'fa-history';
                                                $bg = 'info';
                                                if ($activity->activity == 'Status Updated') {
                                                    $icon = 'fa-sync';
                                                    $bg = str_contains($activity->description, 'accepted') ? 'success' : (str_contains($activity->description, 'rejected') ? 'danger' : 'info');
                                                } elseif ($activity->activity == 'Documents Updated') {
                                                    $icon = 'fa-file-check';
                                                    $bg = 'warning';
                                                } elseif ($activity->activity == 'Payment Updated') {
                                                    $icon = 'fa-money-bill-wave';
                                                    $bg = 'success';
                                                }
                                            @endphp
                                            <div class="trail-icon bg-{{ $bg }}"><i class="fas {{ $icon }}"></i></div>
                                            <div class="trail-info">
                                                <h6 class="fw-bold mb-0 small">{{ $activity->activity }}</h6>
                                                <p class="mb-0 small text-dark" style="font-size: 11px;">{{ $activity->description }}</p>
                                                <small class="text-muted" style="font-size: 10px;">{{ $activity->created_at->format('M d, Y | h:i A') }} @if($activity->user) • {{ $activity->user->name }} @endif</small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Parent Quick Details -->
                    <div class="premium-card">
                        <h5 class="fw-bold mb-4">Guardians</h5>
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center overflow-hidden"
                                style="width: 48px; height: 48px;">
                                @if($application->father_photo_path)
                                    <img src="{{ route('admin.file.serve', ['path' => base64_encode($application->father_photo_path)]) }}"
                                        class="w-100 h-100 object-fit-cover">
                                @else
                                    <i class="fas fa-male text-muted"></i>
                                @endif
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">
                                    {{ $application->father_full_name ?? $application->father_name ?? 'Father N/A' }}
                                </h6>
                                <div class="small text-muted">
                                    {{ $application->father_occupation }}{{ $application->father_designation ? ' (Income: ' . $application->father_designation . ')' : '' }}
                                </div>
                                @if($application->phone_no1)
                                    <div class="small text-success fw-medium"><i class="fas fa-phone-alt me-1"></i>
                                        {{ $application->phone_no1 }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center overflow-hidden"
                                style="width: 48px; height: 48px;">
                                @if($application->mother_photo_path)
                                    <img src="{{ route('admin.file.serve', ['path' => base64_encode($application->mother_photo_path)]) }}"
                                        class="w-100 h-100 object-fit-cover">
                                @else
                                    <i class="fas fa-female text-muted"></i>
                                @endif
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">
                                    {{ $application->mother_full_name ?? $application->mother_name ?? 'Mother N/A' }}
                                </h6>
                                <div class="small text-muted">
                                    {{ $application->mother_occupation }}{{ $application->mother_designation ? ' (Income: ' . $application->mother_designation . ')' : '' }}
                                </div>
                                @if($application->phone_no2)
                                    <div class="small text-success fw-medium"><i class="fas fa-phone-alt me-1"></i>
                                        {{ $application->phone_no2 }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Container (Reuse original logic) -->
            @include('admin.applications.modals')

            @push('scripts')
                <style>
                    .timeline-v2 {
                        position: relative;
                        padding-left: 20px;
                    }

                    .timeline-v2::before {
                        content: '';
                        position: absolute;
                        left: 0;
                        top: 0;
                        bottom: 0;
                        width: 2px;
                        background: #e9ecef;
                        border-radius: 2px;
                    }

                    .trail-item {
                        position: relative;
                        margin-bottom: 25px;
                        padding-left: 30px;
                    }

                    .trail-icon {
                        position: absolute;
                        left: -11px;
                        top: 0;
                        width: 20px;
                        height: 20px;
                        border-radius: 50%;
                        color: white;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 8px;
                        border: 3px solid white;
                        box-shadow: 0 0 0 1px #e9ecef;
                    }

                    .trail-item.active .trail-icon {
                        box-shadow: 0 0 0 1px currentColor;
                    }
                </style>
                <script>
                    $(document).ready(function () {
                        // Shared status update logic
                        $('#paymentStatusSelect').change(function() {
                            const status = $(this).val();
                            if (status === 'partial') {
                                $('#paymentAmountFields').slideDown();
                            } else {
                                $('#paymentAmountFields').slideUp();
                            }
                        });

                        $('#updateStatusBtn').click(function () {
                            const btn = $(this);
                            const original = btn.html();
                            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Saving...');

                            $.ajax({
                                url: '{{ route("admin.application.status", $application->id) }}',
                                method: 'POST',
                                data: $('#statusForm').serialize(),
                                success: function (res) {
                                    if (res.success) {
                                        location.reload();
                                    } else {
                                        alert(res.message || 'Update failed');
                                        btn.prop('disabled', false).html(original);
                                    }
                                },
                                error: function (xhr) {
                                    console.error(xhr);
                                    alert('Update failed: ' + (xhr.responseJSON?.message || 'Server error'));
                                    btn.prop('disabled', false).html(original);
                                }
                            });
                        });

                        // Modal triggers
                        $('#sendApplicationEmailBtn').click(function () {
                            $('#sendEmailModal').modal('show');
                        });
                    });

                    // Document Preview functionality
                    function showPreview(url, type) {
                        const modalEl = document.getElementById('documentPreviewModal');
                        const imgInfo = document.getElementById('previewImage');
                        const pdfInfo = document.getElementById('previewPdf');
                        const spinner = document.getElementById('previewSpinner');
                        const error = document.getElementById('previewError');
                        const downloadBtn = document.getElementById('downloadBtn');

                        // Reset State
                        imgInfo.style.display = 'none';
                        pdfInfo.style.display = 'none';
                        error.style.display = 'none';
                        spinner.style.display = 'block';
                        downloadBtn.href = url;

                        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                        modal.show();

                        if (type === 'image') {
                            imgInfo.src = url;
                            imgInfo.onload = () => { spinner.style.display = 'none'; imgInfo.style.display = 'block'; };
                        } else {
                            pdfInfo.src = url;
                            setTimeout(() => { spinner.style.display = 'none'; pdfInfo.style.display = 'block'; }, 1000);
                        }
                    }
                    function deleteApplication(id) {
                        if (!confirm('Are you absolutely sure you want to delete this application? This action cannot be undone.')) {
                            return;
                        }

                        $.ajax({
                            url: `/admin/application/${id}`,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (res) {
                                if (res.success) {
                                    window.location.href = '{{ route("admin.applications") }}';
                                } else {
                                    alert(res.message || 'Deletion failed');
                                }
                            },
                            error: function (xhr) {
                                alert('Something went wrong. Please try again.');
                            }
                        });
                    }
                    function resetDownload(id) {
                        if (!confirm('Are you sure you want to reset the download status? This will allow the applicant to download the form one more time.')) {
                            return;
                        }

                        $.ajax({
                            url: `/admin/application/${id}/reset-download`,
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (res) {
                                if (res.success) {
                                    alert(res.message);
                                    location.reload();
                                } else {
                                    alert(res.message || 'Reset failed');
                                }
                            },
                            error: function (xhr) {
                                alert('Something went wrong. Please try again.');
                            }
                        });
                    }
                </script>
            @endpush
@endsection