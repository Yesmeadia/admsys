@extends('layouts.app')

@section('title', 'Official Admission Form - Secondary')

@section('content')
    <div class="premium-container container py-5">
        <!-- Official Form Header -->
        <div class="text-center mb-5 animate-fade-up">
            <a href="{{ route('home') }}" class="d-inline-block mb-4">
                <img src="{{ asset('images/logo1.png') }}" alt="School Logo" style="height: 70px;" class="hover-scale">
            </a>
            <h1 class="institutional-section-title h3 mb-2" style="color: #b08d57; font-weight: 700;">Secondary Registration
            </h1>
            <p class="text-muted small fw-bold text-uppercase letter-spacing-1">Admission Path: Class 2 to Class 8 •
                Session {{ $academicYear }}</p>
        </div>

        <div class="form-layout-wrapper mt-5">
            <!-- Institutional Navigation Sidebar -->
            <aside class="form-navigation-sidebar d-none d-xl-block animate-fade-up delay-1">
                <nav id="formNav">
                    <a href="#section-identity" class="nav-item-link active">
                        <i class="fas fa-user-graduate"></i> Candidate Identity
                    </a>
                    <a href="#section-official" class="nav-item-link">
                        <i class="fas fa-fingerprint"></i> Government IDs
                    </a>
                    <a href="#section-parents" class="nav-item-link">
                        <i class="fas fa-user-group"></i> Parental Data
                    </a>
                    <a href="#section-contact" class="nav-item-link">
                        <i class="fas fa-location-dot"></i> Communications
                    </a>
                    <a href="#section-academic" class="nav-item-link">
                        <i class="fas fa-graduation-cap"></i> Academic History
                    </a>
                    <a href="#section-rules" class="nav-item-link">
                        <i class="fas fa-scale-balanced"></i> Rules & Regulations
                    </a>
                    <a href="#section-declaration" class="nav-item-link">
                        <i class="fas fa-folder-tree"></i> Documents Enclosure
                    </a>
                </nav>
                <div class="mt-4 p-3 info-surface rounded-3 border-0 bg-light">
                    <p class="text-xs text-muted mb-0 d-flex gap-2">
                        <i class="fas fa-shield-halved mt-1"></i>
                        <span>End-to-end encrypted secure institutional transmission.</span>
                    </p>
                </div>
            </aside>

            <!-- Main Form Area -->
            <main class="animate-fade-up delay-2">
                @if($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-4 p-4 mb-4">
                        <div class="d-flex gap-3">
                            <i class="fas fa-exclamation-triangle fs-4 text-danger"></i>
                            <div>
                                <h6 class="fw-bold mb-2">Correction Required</h6>
                                <ul class="mb-0 small ps-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admission.form2.submit') }}" method="POST" enctype="multipart/form-data"
                    id="admissionForm2" class="official-form">
                    @csrf

                    <!-- 2. Candidate Identity / Student Details -->
                    <section id="section-identity" class="official-form-section">
                        <div class="row g-4 align-items-center mb-2">
                            <!-- Left: Upload Photo Dotted Card -->
                            <div class="col-lg-3 col-md-4 text-center">
                                <div class="wireframe-photo-upload-box mx-auto"
                                    onclick="document.getElementById('student_photo').click()"
                                    title="Click to upload candidate photo">
                                    <i class="fas fa-camera camera-icon"></i>
                                    <p class="upload-text">Upload Photo</p>
                                    <img id="preview_student_photo" class="preview-img" alt="Student Preview" />
                                </div>
                                <input type="file" name="student_photo" id="student_photo" class="d-none" accept="image/*"
                                    required onchange="validateFile(this, 'preview_student_photo')">
                            </div>

                            <!-- Right: Name & Surname, DOB & DOB Words -->
                            <div class="col-lg-9 col-md-8">
                                <div class="row g-3">
                                    <!-- Name -->
                                    <div class="col-md-7">
                                        <label for="first_name" class="wireframe-label required">Name</label>
                                        <input type="text" class="wireframe-input" name="first_name" id="first_name"
                                            value="{{ old('first_name') }}" required
                                            placeholder="Enter candidate's first name">
                                    </div>
                                    <!-- Surname Name -->
                                    <div class="col-md-5">
                                        <label for="surname" class="wireframe-label">Surname Name</label>
                                        <input type="text" class="wireframe-input" name="surname" id="surname"
                                            value="{{ old('surname') }}" placeholder="Enter surname">
                                    </div>
                                    <!-- Date of Birth -->
                                    <div class="col-md-5">
                                        <label for="date_of_birth" class="wireframe-label required">Date of Birth</label>
                                        <input type="date" class="wireframe-input" name="date_of_birth" id="date_of_birth"
                                            value="{{ old('date_of_birth') }}" required>
                                    </div>
                                    <!-- Date of Birth (Words) -->
                                    <div class="col-md-7">
                                        <label for="date_of_birth_words" class="wireframe-label required">Date of Birth
                                            (Words)</label>
                                        <input type="text" class="wireframe-input bg-light" name="date_of_birth_words"
                                            id="date_of_birth_words" value="{{ old('date_of_birth_words') }}" required
                                            readonly placeholder="Auto-generated in formal words">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Row: Place of Birth, Mother Tongue, Gender -->
                        <div class="row g-3 mt-1">
                            <div class="col-md-4">
                                <label for="place_of_birth" class="wireframe-label required">Place of Birth</label>
                                <input type="text" class="wireframe-input" name="place_of_birth" id="place_of_birth"
                                    value="{{ old('place_of_birth') }}" required placeholder="Enter place of birth">
                            </div>
                            <div class="col-md-4">
                                <label for="mother_tongue" class="wireframe-label required">Mother Tongue</label>
                                <input type="text" class="wireframe-input" name="mother_tongue" id="mother_tongue"
                                    value="{{ old('mother_tongue') }}" required placeholder="Enter mother tongue">
                            </div>
                            <div class="col-md-4">
                                <label class="wireframe-label required">Gender</label>
                                <div class="d-flex align-items-center gap-4 pt-2">
                                    <label class="wireframe-radio-item" for="gender_male">
                                        <input type="radio" name="gender" id="gender_male" value="Male"
                                            class="wireframe-radio-hidden" {{ old('gender') == 'Male' ? 'checked' : '' }}
                                            required>
                                        <span class="wireframe-radio-circle"></span>
                                        <span>Male</span>
                                    </label>
                                    <label class="wireframe-radio-item" for="gender_female">
                                        <input type="radio" name="gender" id="gender_female" value="Female"
                                            class="wireframe-radio-hidden" {{ old('gender') == 'Female' ? 'checked' : '' }}>
                                        <span class="wireframe-radio-circle"></span>
                                        <span>Female</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Row: Religion, Caste / Category, Sub Caste -->
                        <div class="row g-3 mt-1">
                            <div class="col-md-4">
                                <label for="religion" class="wireframe-label required">Relegion</label>
                                <div class="position-relative">
                                    <select name="religion" id="religion" class="form-select wireframe-input" required
                                        style="appearance: none;">
                                        <option value="">Select Religion</option>
                                        @foreach(['Islam', 'Hinduism', 'Christianity', 'Sikhism', 'Buddhism', 'Jainism', 'Other'] as $rel)
                                            <option value="{{ $rel }}" {{ old('religion') == $rel ? 'selected' : '' }}>{{ $rel }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <i class="fas fa-chevron-down position-absolute top-50 end-0 translate-middle-y me-3 text-muted"
                                        style="pointer-events: none;"></i>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="caste" class="wireframe-label required">Caste / Catogory</label>
                                <div class="position-relative">
                                    <select name="caste" id="caste" class="form-select wireframe-input" required
                                        style="appearance: none;">
                                        <option value="">Select Category</option>
                                        @foreach(['General', 'OBC', 'OEC', 'SC', 'ST', 'EWS', 'Other'] as $cat)
                                            <option value="{{ $cat }}" {{ old('caste') == $cat ? 'selected' : '' }}>{{ $cat }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <i class="fas fa-chevron-down position-absolute top-50 end-0 translate-middle-y me-3 text-muted"
                                        style="pointer-events: none;"></i>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="sub_caste" class="wireframe-label">Sub Caste</label>
                                <input type="text" class="wireframe-input" name="sub_caste" id="sub_caste"
                                    value="{{ old('sub_caste') }}" placeholder="Enter sub-caste (optional)">
                            </div>
                        </div>

                        <!-- Row: Blood Group, Aadhaar Number, Nationality -->
                        <div class="row g-3 mt-1">
                            <div class="col-md-4">
                                <label for="blood_group" class="wireframe-label">Blood Gruop</label>
                                <div class="position-relative">
                                    <select name="blood_group" id="blood_group" class="form-select wireframe-input"
                                        style="appearance: none;">
                                        <option value="">Select Blood Group</option>
                                        @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg)
                                            <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <i class="fas fa-chevron-down position-absolute top-50 end-0 translate-middle-y me-3 text-muted"
                                        style="pointer-events: none;"></i>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="aadhar_no" class="wireframe-label required">Aadhaar Number</label>
                                <input type="text" class="wireframe-input" name="aadhar_no" id="aadhar_no"
                                    value="{{ old('aadhar_no') }}" maxlength="12" inputmode="numeric" pattern="\d{12}"
                                    required placeholder="12-digit UIDAI number">
                            </div>
                            <div class="col-md-4">
                                <label for="nationality" class="wireframe-label required">Nationality</label>
                                <input type="text" class="wireframe-input" name="nationality" id="nationality"
                                    value="{{ old('nationality', 'INDIAN') }}" required placeholder="e.g. INDIAN">
                            </div>
                        </div>
                    </section>

                    <!-- 3. Official IDs -->
                    <section id="section-official" class="official-form-section">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="apaar_id" class="wireframe-label">APAAR ID (If available)</label>
                                <input type="text" class="wireframe-input" name="apaar_id" id="apaar_id"
                                    value="{{ old('apaar_id') }}" placeholder="APAAR Number">
                            </div>
                            <div class="col-md-6">
                                <label for="pen_no" class="wireframe-label">PEN Number</label>
                                <input type="text" class="wireframe-input" name="pen_no" id="pen_no"
                                    value="{{ old('pen_no') }}" placeholder="Permanent Education Number">
                            </div>
                        </div>
                    </section>

                    <!-- 4. Parental Details -->
                    <section id="section-parents" class="official-form-section">
                        <div class="row g-4 g-lg-5">
                            <!-- Father's Column -->
                            <div class="col-md-6">
                                <div class="wireframe-parent-col">
                                    <!-- Photo Upload Box -->
                                    <div class="wireframe-photo-upload-box"
                                        onclick="document.getElementById('father_photo').click()"
                                        title="Click to upload father's photograph">
                                        <i class="fas fa-camera camera-icon"></i>
                                        <p class="upload-text">Upload Photo</p>
                                        <img id="preview_father_photo" class="preview-img" alt="Father Preview" />
                                    </div>
                                    <input type="file" name="father_photo" id="father_photo" class="d-none" accept="image/*"
                                        onchange="validateFile(this, 'preview_father_photo')">

                                    <!-- Name of Father -->
                                    <div>
                                        <label for="father_name" class="wireframe-label required">Name of Father</label>
                                        <input type="text" class="wireframe-input" name="father_name" id="father_name"
                                            value="{{ old('father_name') }}" required placeholder="Father's Full Name">
                                    </div>

                                    <!-- Qualification -->
                                    <div>
                                        <label for="father_qualification" class="wireframe-label">Qualification</label>
                                        <input type="text" class="wireframe-input" name="father_qualification"
                                            id="father_qualification" value="{{ old('father_qualification') }}"
                                            placeholder="Qualification">
                                    </div>

                                    <!-- Occupation -->
                                    <div>
                                        <label for="father_occupation" class="wireframe-label">Occupation</label>
                                        <input type="text" class="wireframe-input" name="father_occupation"
                                            id="father_occupation" value="{{ old('father_occupation') }}"
                                            placeholder="Occupation">
                                    </div>

                                    <!-- Income -->
                                    <div>
                                        <label for="father_designation" class="wireframe-label">Income</label>
                                        <input type="text" class="wireframe-input" name="father_designation"
                                            id="father_designation" value="{{ old('father_designation') }}"
                                            placeholder="Income">
                                    </div>
                                </div>
                            </div>

                            <!-- Mother's Column -->
                            <div class="col-md-6">
                                <div class="wireframe-parent-col">
                                    <!-- Photo Upload Box -->
                                    <div class="wireframe-photo-upload-box"
                                        onclick="document.getElementById('mother_photo').click()"
                                        title="Click to upload mother's photograph">
                                        <i class="fas fa-camera camera-icon"></i>
                                        <p class="upload-text">Upload Photo</p>
                                        <img id="preview_mother_photo" class="preview-img" alt="Mother Preview" />
                                    </div>
                                    <input type="file" name="mother_photo" id="mother_photo" class="d-none" accept="image/*"
                                        onchange="validateFile(this, 'preview_mother_photo')">

                                    <!-- Name of Mother -->
                                    <div>
                                        <label for="mother_name" class="wireframe-label required">Name of Mother</label>
                                        <input type="text" class="wireframe-input" name="mother_name" id="mother_name"
                                            value="{{ old('mother_name') }}" required placeholder="Mother's Full Name">
                                    </div>

                                    <!-- Qualification -->
                                    <div>
                                        <label for="mother_qualification" class="wireframe-label">Qualification</label>
                                        <input type="text" class="wireframe-input" name="mother_qualification"
                                            id="mother_qualification" value="{{ old('mother_qualification') }}"
                                            placeholder="Qualification">
                                    </div>

                                    <!-- Occupation -->
                                    <div>
                                        <label for="mother_occupation" class="wireframe-label">Occupation</label>
                                        <input type="text" class="wireframe-input" name="mother_occupation"
                                            id="mother_occupation" value="{{ old('mother_occupation') }}"
                                            placeholder="Occupation">
                                    </div>

                                    <!-- Income -->
                                    <div>
                                        <label for="mother_designation" class="wireframe-label">Income</label>
                                        <input type="text" class="wireframe-input" name="mother_designation"
                                            id="mother_designation" value="{{ old('mother_designation') }}"
                                            placeholder="Income">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- 5. Contact & Address Details -->
                    <section id="section-contact" class="official-form-section">
                        <!-- Guardian Option -->
                        <div class="mb-4">
                            <label class="wireframe-label mb-2">Guardian</label>
                            <div class="d-flex align-items-center gap-4 flex-wrap">
                                <label class="wireframe-radio-item" for="guardian_father">
                                    <input type="radio" name="guardian_type" id="guardian_father" value="Father"
                                        class="wireframe-radio-hidden" onchange="handleGuardianOption('Father')">
                                    <span class="wireframe-radio-circle"></span>
                                    <span>Father</span>
                                </label>
                                <label class="wireframe-radio-item" for="guardian_mother">
                                    <input type="radio" name="guardian_type" id="guardian_mother" value="Mother"
                                        class="wireframe-radio-hidden" onchange="handleGuardianOption('Mother')">
                                    <span class="wireframe-radio-circle"></span>
                                    <span>Mother</span>
                                </label>
                                <label class="wireframe-radio-item" for="guardian_others">
                                    <input type="radio" name="guardian_type" id="guardian_others" value="Others"
                                        class="wireframe-radio-hidden" onchange="handleGuardianOption('Others')">
                                    <span class="wireframe-radio-circle"></span>
                                    <span>Others</span>
                                </label>
                            </div>
                            <div id="other_guardian_box" class="mt-2" style="display: none; max-width: 450px;">
                                <input type="text" class="wireframe-input" name="parents_guardian_full_name"
                                    id="parents_guardian_full_name" value="{{ old('parents_guardian_full_name') }}"
                                    placeholder="Enter Guardian's Name">
                            </div>
                        </div>

                        <!-- Current Address -->
                        <div class="mb-4">
                            <label for="present_address" class="wireframe-label required">Current Address</label>
                            <input type="text" class="wireframe-input" name="present_address" id="present_address"
                                value="{{ old('present_address') }}" required placeholder="Current Address">
                        </div>

                        <!-- Permanent Address & Same as -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="permanent_address" class="wireframe-label required mb-0">Permanent
                                    Address</label>
                                <label class="same-as-toggle" for="same_as_present">
                                    <input type="checkbox" id="same_as_present" class="same-as-checkbox">
                                    <span class="same-as-circle"></span>
                                    <span>Same as</span>
                                </label>
                            </div>
                            <input type="text" class="wireframe-input" name="permanent_address" id="permanent_address"
                                value="{{ old('permanent_address') }}" required placeholder="Permanent Address">
                        </div>

                        <!-- Phone & Email Row -->
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="phone_no1" class="wireframe-label required">Mobile Number</label>
                                <input type="tel" class="wireframe-input" name="phone_no1" id="phone_no1"
                                    value="{{ old('phone_no1') }}" required maxlength="10" inputmode="tel" pattern="\d{10}"
                                    placeholder="Mobile Number">
                            </div>
                            <div class="col-md-4">
                                <label for="phone_no2" class="wireframe-label">Alternative Number</label>
                                <input type="tel" class="wireframe-input" name="phone_no2" id="phone_no2"
                                    value="{{ old('phone_no2') }}" maxlength="10" inputmode="tel" pattern="\d{10}"
                                    placeholder="Alternative Number">
                            </div>
                            <div class="col-md-4">
                                <label for="email" class="wireframe-label">Email Address</label>
                                <input type="email" class="wireframe-input" name="email" id="email"
                                    value="{{ old('email') }}" placeholder="Email Address">
                            </div>
                        </div>
                    </section>

                    <!-- 6. Academic -->
                    <section id="section-academic" class="official-form-section">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="admission_class" class="wireframe-label required">Admission Sought for</label>
                                <div class="position-relative">
                                    <select class="form-select wireframe-input" name="admission_class" id="admission_class"
                                        required style="appearance: none;">
                                        <option value="">Select Target Class</option>
                                        @foreach((array) $classes as $class)
                                            <option value="{{ $class }}" {{ old('admission_class') == $class ? 'selected' : '' }}>
                                                Class {{ $class }}</option>
                                        @endforeach
                                    </select>
                                    <i class="fas fa-chevron-down position-absolute top-50 end-0 translate-middle-y me-3 text-muted"
                                        style="pointer-events: none;"></i>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="academic_year" class="wireframe-label required">Academic session</label>
                                <input type="text" class="wireframe-input bg-light" name="academic_year" id="academic_year"
                                    value="{{ $academicYear }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="last_school_attended" class="wireframe-label required">Previous School
                                    Name</label>
                                <input type="text" class="wireframe-input" name="last_school_attended"
                                    id="last_school_attended" value="{{ old('last_school_attended') }}" required
                                    placeholder="">
                            </div>
                        </div>
                    </section>

                    @include('admission.partials.rules')

                    <!-- 7. Declaration -->
                    <section id="section-declaration" class="official-form-section">
                        <div class="official-section-header">
                            <h4>Documents Enclosure</h4>
                        </div>

                        <div class="info-surface-indigo p-4 rounded-4 mb-4">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <h6 class="fw-bold mb-0 text-dark">Required Documents Checklist</h6>
                            </div>
                            <p class="small text-muted mb-3">Original items to be presented during campus verification:</p>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2 p-2 bg-white rounded-3 border">
                                        <i class="fas fa-circle-check text-success"></i>
                                        <span class="small fw-semibold text-dark">Student's Aadhaar Card Copy</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2 p-2 bg-white rounded-3 border">
                                        <i class="fas fa-circle-check text-success"></i>
                                        <span class="small fw-semibold text-dark">Aadhaar of Father & Mother /
                                            Guardian</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2 p-2 bg-white rounded-3 border">
                                        <i class="fas fa-circle-check text-success"></i>
                                        <span class="small fw-semibold text-dark">Previous School Leaving Certificate</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2 p-2 bg-white rounded-3 border">
                                        <i class="fas fa-circle-check text-success"></i>
                                        <span class="small fw-semibold text-dark">2 Passport-sized Photographs</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2 p-2 bg-white rounded-3 border">
                                        <i class="fas fa-circle-check text-success"></i>
                                        <span class="small fw-semibold text-dark">Last Year's Academic Progress
                                            Report</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2 p-2 bg-white rounded-3 border">
                                        <i class="fas fa-circle-check text-success"></i>
                                        <span class="small fw-semibold text-dark">Reservation Certificate (if
                                            applicable)</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mb-5">
                            <div class="g-recaptcha d-inline-block" data-sitekey="{{ config('captcha.site_key') }}"></div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn-royal w-100 py-3 shadow-premium justify-content-center"
                                id="btnSubmit">
                                <span>SUBMIT APPLICATION</span>
                                <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </section>
                </form>
            </main>
        </div>
    </div>

    <div id="loadingOverlay">
        <div class="text-center">
            <div class="spinner-border text-primary mb-4" style="width: 4rem; height: 4rem;"></div>
            <h3 class="fw-bold text-dark mb-1">DATA SYNCHRONIZATION</h3>
            <p class="text-muted">BUILDING SECURE PROFILE ON CLOUD ARCHIVE</p>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        // Section Highlighter
        window.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('.official-form-section');
            const navLinks = document.querySelectorAll('.nav-item-link');
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (window.scrollY >= sectionTop - 180) current = section.getAttribute('id');
            });
            if (current) {
                navLinks.forEach(link => {
                    link.classList.toggle('active', link.getAttribute('href') === '#' + current);
                });
            }
        });

        // Form Progress Calculator
        function updateFormProgress() {
            const form = document.getElementById('admissionForm2');
            if (!form) return;
            const requiredFields = form.querySelectorAll('input[required], select[required], textarea[required]');
            let filledCount = 0;

            requiredFields.forEach(field => {
                if (field.type === 'checkbox') {
                    if (field.checked) filledCount++;
                } else if (field.type === 'file') {
                    if (field.files && field.files.length > 0) filledCount++;
                } else if (field.value.trim() !== '') {
                    filledCount++;
                }
            });

            const percent = requiredFields.length > 0 ? Math.round((filledCount / requiredFields.length) * 100) : 0;
            const bar = document.getElementById('formProgressBar');
            const txt = document.getElementById('formProgressText');
            if (bar) bar.style.width = percent + '%';
            if (txt) txt.textContent = percent + '%';
        }

        document.getElementById('admissionForm2')?.addEventListener('input', updateFormProgress);
        document.getElementById('admissionForm2')?.addEventListener('change', updateFormProgress);
        document.addEventListener('DOMContentLoaded', updateFormProgress);

        document.getElementById('date_of_birth').addEventListener('change', function () {
            const date = new Date(this.value);
            if (isNaN(date)) return;
            const day = date.getDate();
            const month = date.toLocaleString('default', { month: 'long' });
            const year = date.getFullYear();
            const dayWords = ['', 'First', 'Second', 'Third', 'Fourth', 'Fifth', 'Sixth', 'Seventh', 'Eighth', 'Ninth', 'Tenth', 'Eleventh', 'Twelfth', 'Thirteenth', 'Fourteenth', 'Fifteenth', 'Sixteenth', 'Seventeenth', 'Eighteenth', 'Nineteenth', 'Twentieth', 'Twenty-First', 'Twenty-Second', 'Twenty-Third', 'Twenty-Fourth', 'Twenty-Fifth', 'Twenty-Sixth', 'Twenty-Seventh', 'Twenty-Eighth', 'Twenty-Ninth', 'Thirtieth', 'Thirty-First'];

            const convertToWords = (num) => {
                const ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
                const teens = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
                const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
                if (num < 10) return ones[num];
                if (num < 20) return teens[num - 10];
                return tens[Math.floor(num / 10)] + (num % 10 !== 0 ? ' ' + ones[num % 10] : '');
            };

            let yearWords = year >= 2000 ? 'Two Thousand ' + convertToWords(year % 100) : 'One Thousand Nine Hundred ' + convertToWords(year % 100);
            document.getElementById('date_of_birth_words').value = `${dayWords[day]} ${month} ${yearWords}`.toUpperCase();
        });

        function handleGuardianOption(type) {
            const box = document.getElementById('other_guardian_box');
            const input = document.getElementById('parents_guardian_full_name');
            const fatherInput = document.getElementById('father_name');
            const motherInput = document.getElementById('mother_name');

            if (type === 'Father') {
                box.style.display = 'none';
                input.value = fatherInput ? fatherInput.value : '';
            } else if (type === 'Mother') {
                box.style.display = 'none';
                input.value = motherInput ? motherInput.value : '';
            } else {
                box.style.display = 'block';
                input.value = '';
                input.focus();
            }
        }

        const fNameInput = document.getElementById('father_name');
        const mNameInput = document.getElementById('mother_name');
        if (fNameInput) {
            fNameInput.addEventListener('input', function () {
                const gFather = document.getElementById('guardian_father');
                if (gFather && gFather.checked) {
                    document.getElementById('parents_guardian_full_name').value = this.value;
                }
            });
        }
        if (mNameInput) {
            mNameInput.addEventListener('input', function () {
                const gMother = document.getElementById('guardian_mother');
                if (gMother && gMother.checked) {
                    document.getElementById('parents_guardian_full_name').value = this.value;
                }
            });
        }

        const checkSame = document.getElementById('same_as_present');
        const presentAddr = document.getElementById('present_address');
        const permAddr = document.getElementById('permanent_address');

        if (checkSame && presentAddr && permAddr) {
            checkSame.addEventListener('change', function () {
                if (this.checked) {
                    permAddr.value = presentAddr.value;
                    permAddr.setAttribute('readonly', true);
                } else {
                    permAddr.removeAttribute('readonly');
                }
            });

            presentAddr.addEventListener('input', function () {
                if (checkSame.checked) {
                    permAddr.value = this.value;
                }
            });
        }

        // Form Validation & Submission Handler
        const admissionForm = document.getElementById('admissionForm2');
        admissionForm.addEventListener('submit', function (event) {
            const fatherPhoto = document.getElementById('father_photo');
            const motherPhoto = document.getElementById('mother_photo');
            let customValid = true;

            // Check photo requirement
            if (!fatherPhoto.value && !motherPhoto.value) {
                customValid = false;
                alert('MANDATORY: Please upload either Father\'s Photo or Mother\'s Photo to proceed.');
                const parentSection = document.getElementById('section-parents');
                if (parentSection) parentSection.scrollIntoView({ behavior: 'smooth' });
            }

            // Check overall validity
            if (!admissionForm.checkValidity() || !customValid) {
                event.preventDefault();
                event.stopPropagation();

                // Add Bootstrap validation styling
                admissionForm.classList.add('was-validated');

                // If native validation failed, scroll to first error
                if (!admissionForm.checkValidity()) {
                    alert('CORRECTION REQUIRED: Please fill in all mandatory fields highlighted in red.');
                    const firstError = admissionForm.querySelector(':invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        // Trigger browser's native bubble for the first error
                        firstError.reportValidity();
                    }
                }
            } else {
                // Success: Show loading overlay
                document.getElementById('btnSubmit').disabled = true;
                document.getElementById('loadingOverlay').style.display = 'flex';
            }
        });

        function validateFile(input, previewId) {
            const file = input.files[0];
            if (!file || file.size > 2 * 1024 * 1024) {
                if (file) alert('FILE SIZE EXCEEDS 2MB');
                input.value = ''; return;
            }
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.getElementById(previewId);
                img.src = e.target.result;
                img.style.display = 'block';
                const box = img.closest('.wireframe-photo-upload-box') || img.closest('.photo-upload-box') || img.closest('.ey-photo-box');
                if (box) {
                    const iconWrap = box.querySelector('.camera-icon') || box.querySelector('.ey-photo-icon-wrap') || box.querySelector('i');
                    if (iconWrap) iconWrap.style.display = 'none';
                    const textWrap = box.querySelector('.upload-text') || box.querySelector('.ey-photo-text') || box.querySelector('p');
                    if (textWrap) textWrap.style.display = 'none';
                }
            };
            reader.readAsDataURL(file);
        }
    </script>
@endsection