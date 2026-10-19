<!-- Document Preview Modal -->
<div class="modal fade" id="documentPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 bg-light p-4">
                <h5 class="modal-title fw-bold">Document Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center"
                style="min-height: 500px;">
                <div id="previewSpinner" class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <img id="previewImage" src="" class="img-fluid shadow-sm" style="display: none; max-height: 80vh;">
                <embed id="previewPdf" src="" type="application/pdf"
                    style="display: none; width: 100%; height: 80vh; border: none;"></embed>
                <div id="previewError" style="display: none;" class="text-center p-5">
                    <i class="fas fa-exclamation-circle text-danger fa-3x mb-3"></i>
                    <h5>Cannot preview this file</h5>
                    <p class="text-muted">Try downloading it instead.</p>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 bg-light">
                <button type="button" class="btn btn-white rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                <a href="" id="downloadBtn" download class="btn btn-primary rounded-pill px-4 shadow-sm">
                    <i class="fas fa-download me-2"></i>Download File
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Email Decision Modal -->
<div class="modal fade" id="sendEmailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 p-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-paper-plane me-2 text-primary"></i>Notify Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                @if($application->email)
                    <div class="alert alert-info border-0 rounded-4 mb-4"
                        style="background: rgba(67, 97, 238, 0.05); color: #4361ee;">
                        <small><i class="fas fa-info-circle me-2"></i>This will send an email to
                            <strong>{{ $application->email }}</strong> regarding the current status
                            ({{ $application->status }}).</small>
                    </div>
                @else
                    <div class="alert alert-warning border-0 rounded-4 mb-4"
                        style="background: rgba(245, 158, 11, 0.1); color: #b45309;">
                        <small><i class="fas fa-exclamation-triangle me-2"></i><strong>No email address on file.</strong> This applicant was registered without an email address. You can add an email using the Comprehensive Student Edit modal if you wish to send notifications.</small>
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Email Subject</label>
                    <input type="text" class="form-control" id="emailSubject"
                        value="Update on your application - {{ config('app.name') }}">
                </div>

                <div class="mb-0">
                    <label class="form-label small fw-bold text-muted text-uppercase">Custom Message (Optional)</label>
                    <textarea class="form-control" id="additionalMessage" rows="5"
                        placeholder="Add any personal message or instructions for the student..."></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" id="sendEmailBtn" {{ empty($application->email) ? 'disabled' : '' }}>
                    <i class="fas fa-paper-plane me-2"></i>Send Notification
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Application Modal (Comprehensive) -->
<div class="modal fade" id="editApplicationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form action="{{ route('admin.application.update', $application->id) }}" method="POST"
                enctype="multipart/form-data" class="h-100">
                @csrf
                <div class="modal-header border-0 bg-white p-4 pb-2">
                    <div>
                        <h4 class="modal-title fw-bold text-dark mb-1">
                            <i class="fas fa-user-edit me-2 text-primary"></i>Comprehensive Student Edit
                        </h4>
                        <p class="text-muted small mb-0">Managing record for: <span
                                class="fw-bold text-primary">{{ $application->first_name }}
                                {{ $application->surname }}</span> ({{ $application->application_number }})</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <style>
                    #editApplicationModal .nav-link-custom {
                        border-radius: 12px;
                        padding: 12px 20px;
                        color: #64748b;
                        font-weight: 500;
                        transition: all 0.3s ease;
                        border: 1px solid transparent;
                        margin-bottom: 8px;
                        display: flex;
                        align-items: center;
                    }

                    #editApplicationModal .nav-link-custom i {
                        width: 24px;
                        font-size: 1.1rem;
                        margin-right: 12px;
                    }

                    #editApplicationModal .nav-link-custom:hover {
                        background: #f8fafc;
                        color: #4361ee;
                    }

                    #editApplicationModal .nav-link-custom.active {
                        background: rgba(67, 97, 238, 0.1);
                        color: #4361ee;
                        border-color: rgba(67, 97, 238, 0.2);
                    }

                    #editApplicationModal .form-section-card {
                        background: #fff;
                        border: 1px solid #e2e8f0;
                        border-radius: 16px;
                        padding: 24px;
                        margin-bottom: 24px;
                        transition: all 0.3s ease;
                    }

                    #editApplicationModal .form-section-card:hover {
                        border-color: #cbd5e1;
                    }

                    #editApplicationModal .section-title {
                        font-size: 1.1rem;
                        font-weight: 700;
                        color: #1e293b;
                        margin-bottom: 20px;
                        display: flex;
                        align-items: center;
                    }

                    #editApplicationModal .section-title i {
                        margin-right: 10px;
                        color: #4361ee;
                    }

                    #editApplicationModal .form-label-premium {
                        font-size: 12px;
                        font-weight: 700;
                        color: #64748b;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                        margin-bottom: 8px;
                    }

                    #editApplicationModal .form-control-premium {
                        border-radius: 10px;
                        padding: 10px 14px;
                        border: 1.5px solid #e2e8f0;
                        background-color: #f8fafc;
                        transition: all 0.2s ease;
                    }

                    #editApplicationModal .form-control-premium:focus {
                        border-color: #4361ee;
                        background-color: #fff;
                        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
                    }
                </style>

                <div class="modal-body p-0">
                    <div class="d-flex flex-column flex-md-row h-100" style="min-height: 70vh;">
                        <!-- Sidebar Navigation -->
                        <div class="col-md-3 border-end bg-light bg-opacity-50 p-4"
                            style="overflow-y: auto; max-height: 75vh;">
                            <div class="nav flex-column nav-pills" id="edit-tabs" role="tablist"
                                aria-orientation="vertical">
                                <a class="nav-link-custom active" id="tab-basic-link" data-bs-toggle="pill"
                                    href="#tab-basic" role="tab">
                                    <i class="fas fa-user-circle"></i>Basic Info
                                </a>
                                <a class="nav-link-custom" id="tab-personal-link" data-bs-toggle="pill"
                                    href="#tab-personal" role="tab">
                                    <i class="fas fa-fingerprint"></i>Personal Details
                                </a>
                                <a class="nav-link-custom" id="tab-parents-link" data-bs-toggle="pill"
                                    href="#tab-parents" role="tab">
                                    <i class="fas fa-users-cog"></i>Family Info
                                </a>
                                <a class="nav-link-custom" id="tab-address-link" data-bs-toggle="pill"
                                    href="#tab-address" role="tab">
                                    <i class="fas fa-map-marked-alt"></i>Addresses
                                </a>
                                <a class="nav-link-custom" id="tab-academic-link" data-bs-toggle="pill"
                                    href="#tab-academic" role="tab">
                                    <i class="fas fa-graduation-cap"></i>Education Docs
                                </a>
                                <a class="nav-link-custom" id="tab-id-link" data-bs-toggle="pill" href="#tab-id"
                                    role="tab">
                                    <i class="fas fa-id-card"></i>IDs & Admission
                                </a>
                                <a class="nav-link-custom" id="tab-files-link" data-bs-toggle="pill" href="#tab-files"
                                    role="tab">
                                    <i class="fas fa-cloud-upload-alt"></i>Photos & Files
                                </a>
                                <a class="nav-link-custom" id="tab-admin-link" data-bs-toggle="pill" href="#tab-admin"
                                    role="tab">
                                    <i class="fas fa-user-shield"></i>Admin Controls
                                </a>
                            </div>
                        </div>

                        <!-- Content Area -->
                        <div class="col-md-9 p-4 bg-white" style="overflow-y: auto; max-height: 75vh;">
                            <div class="tab-content" id="edit-tabs-content">
                                <!-- TAB 1: Basic Information -->
                                <div class="tab-pane fade show active" id="tab-basic" role="tabpanel">
                                    <div class="form-section-card shadow-sm border-0 bg-light bg-opacity-10">
                                        <h5 class="section-title"><i class="fas fa-user"></i>General Information</h5>
                                        <div class="row g-4">
                                            <div class="col-md-4">
                                                <label class="form-label-premium">First Name / Student Name</label>
                                                <input type="text" name="first_name"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->first_name ?? $application->student_name }}"
                                                    placeholder="Student's name">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label-premium">Surname</label>
                                                <input type="text" name="surname"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->surname }}"
                                                    placeholder="Surname (optional)">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label-premium">Email Address <span class="text-muted fw-normal text-lowercase">(optional)</span></label>
                                                <input type="email" name="email"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->email }}"
                                                    placeholder="e.g. name@example.com (optional)">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label-premium">Primary Mobile</label>
                                                <input type="text" name="mobile_1"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->mobile_1 ?? $application->phone_no1 }}"
                                                    placeholder="Primary mobile">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label-premium">Secondary Mobile</label>
                                                <input type="text" name="mobile_2"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->mobile_2 ?? $application->phone_no2 }}"
                                                    placeholder="Secondary mobile">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label-premium">Academic Year</label>
                                                <input type="text" name="academic_year"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->academic_year }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label-premium">Application No.</label>
                                                <input type="text" name="application_number"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->application_number }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- TAB 2: Personal Details -->
                                <div class="tab-pane fade" id="tab-personal" role="tabpanel">
                                    <div class="form-section-card shadow-sm border-0 bg-light bg-opacity-10">
                                        <h5 class="section-title"><i class="fas fa-fingerprint"></i>Identity Details
                                        </h5>
                                        <div class="row g-4">
                                            <div class="col-md-4">
                                                <label class="form-label-premium">Gender</label>
                                                <select name="gender" class="form-select form-control-premium">
                                                    <option value="Male" {{ strtolower($application->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                                    <option value="Female" {{ strtolower($application->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                                    <option value="Other" {{ strtolower($application->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label-premium">Date of Birth</label>
                                                <input type="date" name="date_of_birth"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->date_of_birth ? $application->date_of_birth->format('Y-m-d') : '' }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label-premium">Blood Group</label>
                                                <select name="blood_group" class="form-select form-control-premium">
                                                    <option value="">Select</option>
                                                    @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg)
                                                        <option value="{{ $bg }}" {{ $application->blood_group == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label-premium">Nationality</label>
                                                <input type="text" name="nationality"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->nationality }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label-premium">Religion</label>
                                                <input type="text" name="religion"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->religion }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label-premium">Caste</label>
                                                <input type="text" name="caste"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->caste }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-premium">Sub Caste</label>
                                                <input type="text" name="sub_caste"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->sub_caste }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-premium">Place of Birth</label>
                                                <input type="text" name="place_of_birth"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->place_of_birth }}">
                                            </div>
                                            <div class="col-md-6 d-flex align-items-center">
                                                <div class="form-check form-switch mt-3">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="is_physically_unfit" id="is_physically_unfit_p" {{ $application->is_physically_unfit ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold text-muted ms-2"
                                                        for="is_physically_unfit_p">Special Needs / Physically
                                                        Unfit</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- TAB 3: Family Information -->
                                <div class="tab-pane fade" id="tab-parents" role="tabpanel">
                                    <div class="form-section-card shadow-sm border-0 bg-light bg-opacity-10 mb-4">
                                        <h5 class="section-title"><i class="fas fa-male"></i>Father's Details</h5>
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <label class="form-label-premium">Full Name</label>
                                                <input type="text" name="father_full_name"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->father_full_name ?? $application->father_name }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-premium">Qualification</label>
                                                <input type="text" name="father_qualification"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->father_qualification }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-premium">Occupation</label>
                                                <input type="text" name="father_occupation"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->father_occupation }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-premium">Monthly Income</label>
                                                <input type="text" name="father_designation"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->father_designation }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-section-card shadow-sm border-0 bg-light bg-opacity-10 mb-4">
                                        <h5 class="section-title"><i class="fas fa-female"></i>Mother's Details</h5>
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <label class="form-label-premium">Full Name</label>
                                                <input type="text" name="mother_full_name"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->mother_full_name ?? $application->mother_name }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-premium">Qualification</label>
                                                <input type="text" name="mother_qualification"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->mother_qualification }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-premium">Occupation</label>
                                                <input type="text" name="mother_occupation"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->mother_occupation }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-premium">Monthly Income</label>
                                                <input type="text" name="mother_designation"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->mother_designation }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-section-card shadow-sm border-0 bg-light bg-opacity-10">
                                        <h5 class="section-title"><i class="fas fa-user-friends"></i>Guardian & Contact
                                        </h5>
                                        <div class="row g-4">
                                            <div class="col-12">
                                                <label class="form-label-premium">Guardian Name (If any)</label>
                                                <input type="text" name="parents_guardian_full_name"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->parents_guardian_full_name }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-premium">Parent Mobile 1</label>
                                                <input type="text" name="phone_no1"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->phone_no1 }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-premium">Parent Mobile 2</label>
                                                <input type="text" name="phone_no2"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->phone_no2 }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- TAB 4: Addresses -->
                                <div class="tab-pane fade" id="tab-address" role="tabpanel">
                                    <div class="form-section-card shadow-sm border-0 bg-light bg-opacity-10">
                                        <h5 class="section-title"><i class="fas fa-map-marker-alt"></i>Mailing
                                            Information</h5>
                                        <div class="row g-4">
                                            <div class="col-12">
                                                <label class="form-label-premium">Correspondence / Local Address</label>
                                                <textarea name="local_address" class="form-control form-control-premium"
                                                    rows="3">{{ $application->local_address }}</textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-premium">Present Address</label>
                                                <textarea name="present_address"
                                                    class="form-control form-control-premium"
                                                    rows="3">{{ $application->present_address }}</textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-premium">Permanent Address</label>
                                                <textarea name="permanent_address"
                                                    class="form-control form-control-premium"
                                                    rows="3">{{ $application->permanent_address }}</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label-premium">Pin Code</label>
                                                <input type="text" name="pin_code"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->pin_code }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- TAB 5: Academic History -->
                                <div class="tab-pane fade" id="tab-academic" role="tabpanel">
                                    <div class="form-section-card shadow-sm border-0 bg-light bg-opacity-10">
                                        <h5 class="section-title"><i class="fas fa-university"></i>Previous Education
                                        </h5>
                                        <div class="row g-4">
                                            <div class="col-12">
                                                <label class="form-label-premium">Last School Attended</label>
                                                <input type="text" name="last_school_attended"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->last_school_attended ?? $application->previous_school_name }}">
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label-premium">Last School Address</label>
                                                <input type="text" name="last_school_address"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->last_school_address }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-premium">Last Class / Std</label>
                                                <input type="text" name="studying_in_std"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->studying_in_std ?? $application->previous_class }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-premium">Medium of Instruction</label>
                                                <input type="text" name="medium_of_instruction"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->medium_of_instruction }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- TAB 6: ID & Admission Details -->
                                <div class="tab-pane fade" id="tab-id" role="tabpanel">
                                    <div class="form-section-card shadow-sm border-0 bg-light bg-opacity-10">
                                        <h5 class="section-title"><i class="fas fa-id-card"></i>Registration & Govt. IDs
                                        </h5>
                                        <div class="row g-4">
                                            <div class="col-md-4">
                                                <label class="form-label-premium">Admission Sought For Class</label>
                                                <input type="text" name="admission_sought_for_class"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->admission_sought_for_class ?? $application->admission_class }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label-premium">Stream (H.S.)</label>
                                                <input type="text" name="stream"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->stream }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label-premium">Subject Group</label>
                                                <input type="text" name="subject_group"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->subject_group }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-premium">Aadhar Number</label>
                                                <input type="text" name="aadhar_no"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->aadhar_no }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-premium">APAAR ID</label>
                                                <input type="text" name="apaar_id"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->apaar_id }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-premium">PEN Number</label>
                                                <input type="text" name="pen_no"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->pen_no }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-premium">Adharshila Number</label>
                                                <input type="text" name="adharshila_number"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->adharshila_number }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- TAB 7: Photos & Files -->
                                <div class="tab-pane fade" id="tab-files" role="tabpanel">
                                    <div class="form-section-card shadow-sm border-0 bg-light bg-opacity-10 mb-4">
                                        <h5 class="section-title"><i class="fas fa-images"></i>Profile Photos</h5>
                                        <div class="row g-4">
                                            <div class="col-md-4">
                                                <label class="form-label-premium">Student Photo</label>
                                                <input type="file" name="student_photo"
                                                    class="form-control form-control-premium">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label-premium">Father's Photo</label>
                                                <input type="file" name="father_photo"
                                                    class="form-control form-control-premium">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label-premium">Mother's Photo</label>
                                                <input type="file" name="mother_photo"
                                                    class="form-control form-control-premium">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- TAB 8: Admin Controls -->
                                <div class="tab-pane fade" id="tab-admin" role="tabpanel">
                                    <div
                                        class="form-section-card shadow-sm border-0 border-start border-4  bg-white bg-opacity-5">
                                        <h5 class="section-title"><i class="fas fa-user-shield"></i>Management Controls
                                        </h5>
                                        <div class="row g-4">
                                            <div class="col-md-4">
                                                <label class="form-label-premium">Update Status</label>
                                                <select name="status"
                                                    class="form-select form-control-premium fw-bold text-primary">
                                                    <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="reviewed" {{ $application->status == 'reviewed' ? 'selected' : '' }}>Application Accepted</option>
                                                    <option value="accepted" {{ $application->status == 'accepted' ? 'selected' : '' }}>Admitted</option>
                                                    <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label-premium">Admission Fee Status</label>
                                                <select name="payment_status"
                                                    class="form-select form-control-premium fw-bold 
                                                    {{ $application->payment_status == 'paid' ? 'text-success' : 'text-danger' }}">
                                                    <option value="unpaid" {{ $application->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                                    <option value="paid" {{ $application->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label-premium">Public Remarks (Visible to
                                                    Parent)</label>
                                                <input type="text" name="remarks"
                                                    class="form-control form-control-premium"
                                                    value="{{ $application->remarks }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-premium">Internal Admin Notes</label>
                                                <textarea name="admin_notes" class="form-control form-control-premium"
                                                    rows="4">{{ $application->admin_notes }}</textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-premium">Extra Submitted Information</label>
                                                <textarea name="additional_info"
                                                    class="form-control form-control-premium"
                                                    rows="4">{{ $application->additional_info }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 bg-light bg-opacity-50">
                    <button type="button" class="btn btn-white rounded-pill px-4 shadow-sm"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm fw-bold">
                        <i class="fas fa-save me-2"></i>Save Comprehensive Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sendEmailBtn = document.getElementById('sendEmailBtn');
            if (sendEmailBtn) {
                sendEmailBtn.addEventListener('click', function () {
                    const btn = $(sendEmailBtn);
                    const originalText = btn.html();
                    const subject = document.getElementById('emailSubject').value;
                    const message = document.getElementById('additionalMessage').value;

                    console.log('Sending notification for application #{{ $application->id }}');

                    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Sending...');

                    $.ajax({
                        url: '{{ route("admin.application.send-email", $application->id) }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            message: message,
                            subject: subject,
                            include_remarks: '1'
                        },
                        success: function (res) {
                            if (res.success) {
                                $('#sendEmailModal').modal('hide');
                                alert('Notification sent successfully!');
                            } else {
                                alert('Error: ' + (res.message || 'Unknown error'));
                            }
                        },
                        error: function (xhr) {
                            console.error('Email failed:', xhr);
                            let errorMsg = 'Email failed to send.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg += ' Detail: ' + xhr.responseJSON.message;
                            }
                            alert(errorMsg);
                        },
                        complete: function () {
                            btn.prop('disabled', false).html(originalText);
                        }
                    });
                });
            }
        });
    </script>
@endpush