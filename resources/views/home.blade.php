@extends('layouts.app')

@section('title', 'Admissions ' . $academicYear . ' - ' . config('app.school_name'))

@section('content')
    <div class="school-home-wrapper">
        <main class="school-main-content">
            <!-- Hero Section (Balanced, Modern, High-Impact) -->
            <section class="hero-section-compact">
                <div class="container">
                    <div class="hero-banner-container animate-on-scroll">
                        <div class="row align-items-center g-0">
                            <!-- Left: Hero Text Content -->
                            <div class="col-lg-7 p-4 p-md-5 hero-content-pane">
                                <div class="hero-badge-pill mb-3">
                                    <span class="pulse-dot"></span>
                                    <span>Admissions Open &bull; Session {{ $academicYear }}</span>
                                </div>

                                <h1 class="hero-title-refined mb-3">
                                    Nurturing Bright Minds & <br class="d-none d-sm-inline">
                                    <span class="hero-gradient-text">Future Leaders</span>
                                </h1>

                                <p class="hero-subtitle mb-4">
                                    Welcome to {{ config('app.school_name') }}. We offer a progressive, value-driven K-12
                                    educational journey fostering critical thinking, creative innovation, and character
                                    development.
                                </p>

                                <div class="hero-actions-row d-flex flex-wrap align-items-center gap-3">
                                    @if($anyFormEnabled)
                                        <a href="{{ route('admission.select') }}" class="btn btn-hero-primary shadow-sm">
                                            <span>Apply for Admission</span>
                                            <x-fas-arrow-right class="ms-2" style="width: 14px; height: 14px;" />
                                        </a>
                                    @else
                                        <a href="#admissions-portal" class="btn btn-hero-primary shadow-sm">
                                            <span>Admissions Notice</span>
                                            <x-fas-arrow-right class="ms-2" style="width: 14px; height: 14px;" />
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <!-- Right: Hero Visual Showcase -->
                            <div class="col-lg-5 hero-visual-pane position-relative d-none d-lg-block">
                                <div class="hero-image-wrapper h-100">
                                    <img src="{{ asset('images/hero.png') }}" alt="Student in Uniform"
                                        class="hero-student-img">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Logo Marquee Strip (Flagship Programs) -->
            <section class="logo-marquee-section py-3">
                <div class="container-fluid px-0">
                    <div class="marquee-wrapper">
                        <div class="marquee-track">
                            <!-- Group 1 -->
                            <div class="marquee-item">
                                <img src="{{ asset('images/gpp/anglorium.png') }}" alt="Anglorium" class="marquee-logo">
                            </div>
                            <div class="marquee-item">
                                <img src="{{ asset('images/gpp/mathrubhasha.png') }}" alt="Mathrubhasha" class="marquee-logo">
                            </div>
                            <div class="marquee-item">
                                <img src="{{ asset('images/gpp/sensorium.png') }}" alt="Sensorium" class="marquee-logo">
                            </div>
                            <div class="marquee-item">
                                <img src="{{ asset('images/gpp/technorium.png') }}" alt="Technorium" class="marquee-logo">
                            </div>
                            <div class="marquee-item">
                                <img src="{{ asset('images/gpp/victorium.png') }}" alt="Victorium" class="marquee-logo">
                            </div>

                            <div class="marquee-item">
                                <img src="{{ asset('images/gpp/anglorium.png') }}" alt="Anglorium" class="marquee-logo">
                            </div>
                            <div class="marquee-item">
                                <img src="{{ asset('images/gpp/mathrubhasha.png') }}" alt="Mathrubhasha" class="marquee-logo">
                            </div>
                            <div class="marquee-item">
                                <img src="{{ asset('images/gpp/sensorium.png') }}" alt="Sensorium" class="marquee-logo">
                            </div>
                            <div class="marquee-item">
                                <img src="{{ asset('images/gpp/technorium.png') }}" alt="Technorium" class="marquee-logo">
                            </div>
                            <div class="marquee-item">
                                <img src="{{ asset('images/gpp/victorium.png') }}" alt="Victorium" class="marquee-logo">
                            </div>

                            <!-- Group 2 (Exact duplicate for seamless infinite loop) -->
                            <div class="marquee-item">
                                <img src="{{ asset('images/gpp/anglorium.png') }}" alt="Anglorium" class="marquee-logo">
                            </div>
                            <div class="marquee-item">
                                <img src="{{ asset('images/gpp/mathrubhasha.png') }}" alt="Mathrubhasha" class="marquee-logo">
                            </div>
                            <div class="marquee-item">
                                <img src="{{ asset('images/gpp/sensorium.png') }}" alt="Sensorium" class="marquee-logo">
                            </div>
                            <div class="marquee-item">
                                <img src="{{ asset('images/gpp/technorium.png') }}" alt="Technorium" class="marquee-logo">
                            </div>
                            <div class="marquee-item">
                                <img src="{{ asset('images/gpp/victorium.png') }}" alt="Victorium" class="marquee-logo">
                            </div>

                            <div class="marquee-item">
                                <img src="{{ asset('images/gpp/anglorium.png') }}" alt="Anglorium" class="marquee-logo">
                            </div>
                            <div class="marquee-item">
                                <img src="{{ asset('images/gpp/mathrubhasha.png') }}" alt="Mathrubhasha" class="marquee-logo">
                            </div>
                            <div class="marquee-item">
                                <img src="{{ asset('images/gpp/sensorium.png') }}" alt="Sensorium" class="marquee-logo">
                            </div>
                            <div class="marquee-item">
                                <img src="{{ asset('images/gpp/technorium.png') }}" alt="Technorium" class="marquee-logo">
                            </div>
                            <div class="marquee-item">
                                <img src="{{ asset('images/gpp/victorium.png') }}" alt="Victorium" class="marquee-logo">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Admission Forms Portal Section -->
            <section id="admissions-portal" class="admissions-portal-section py-5">
                <div class="container">
                    <div class="text-center mb-5">
                        <div class="section-tag-pill mb-2">ADMISSIONS PORTAL &bull; {{ $academicYear }}</div>
                        <h2 class="section-title-modern">Select An Admission Program</h2>
                        <p class="section-desc-modern mx-auto">
                            Choose the designated stage for your child's age and class to begin the fast, paperless
                            application.
                        </p>
                    </div>

                    @if($anyFormEnabled)
                        <div class="row g-4 justify-content-center">
                            @if($form1Enabled)
                                <!-- Form 1: Lower Academy -->
                                <div class="col-lg-4 col-md-6">
                                    <div
                                        class="program-card h-100 p-4 rounded-4 border bg-white shadow-sm position-relative d-flex flex-column justify-content-between">
                                        <div class="card-accent-strip bg-primary"></div>
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span
                                                    class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 fw-bold"
                                                    style="font-size: 11px;">
                                                    PRE-PRIMARY
                                                </span>
                                                <span
                                                    class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 small fw-semibold">
                                                    <x-fas-circle class="me-1 text-success" style="width: 7px; height: 7px;" /> Live
                                                </span>
                                            </div>

                                            <div class="d-flex align-items-center gap-3 mb-3">
                                                <div>
                                                    <h4 class="fw-bold text-dark mb-0" style="font-size: 1.25rem;">Lower Academy
                                                    </h4>
                                                    <small class="text-muted">Nursery to Class 1st</small>
                                                </div>
                                            </div>

                                            <p class="text-muted small mb-3" style="line-height: 1.6;">
                                                Play-way curriculum, cognitive development, foundational literacy, and
                                                child-centered interactive activities.
                                            </p>

                                            <div class="program-features-list mb-4">
                                                <div class="feature-item d-flex align-items-center gap-2 small text-muted mb-1.5">
                                                    <x-fas-check class="text-primary" style="width: 12px; height: 12px;" />
                                                    <span>Nursery, LKG, UKG, 1st Grade</span>
                                                </div>
                                                <div class="feature-item d-flex align-items-center gap-2 small text-muted mb-1.5">
                                                    <x-fas-check class="text-primary" style="width: 12px; height: 12px;" />
                                                    <span>Early sensory & motor skill workshops</span>
                                                </div>
                                                <div class="feature-item d-flex align-items-center gap-2 small text-muted">
                                                    <x-fas-check class="text-primary" style="width: 12px; height: 12px;" />
                                                    <span>Safe, engaging play-school setup</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pt-3 border-top">
                                            <a href="{{ route('admission.form1') }}"
                                                class="btn btn-outline-primary w-100 rounded-pill py-2.5 fw-semibold d-flex align-items-center justify-content-center gap-2 shadow-xs">
                                                <span>Apply for Lower Academy</span>
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
                                        class="program-card h-100 p-4 rounded-4 border bg-white shadow-sm position-relative d-flex flex-column justify-content-between highlighted-program">
                                        <div class="card-accent-strip bg-info"></div>
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-1 fw-bold"
                                                    style="font-size: 11px;">
                                                    PRIMARY & SECONDARY
                                                </span>
                                                <span
                                                    class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 small fw-semibold">
                                                    <x-fas-circle class="me-1 text-success" style="width: 7px; height: 7px;" /> Live
                                                </span>
                                            </div>

                                            <div class="d-flex align-items-center gap-3 mb-3">
                                                <div>
                                                    <h4 class="fw-bold text-dark mb-0" style="font-size: 1.25rem;">General Education
                                                    </h4>
                                                    <small class="text-muted">Class 2nd to Class 10th</small>
                                                </div>
                                            </div>

                                            <p class="text-muted small mb-3" style="line-height: 1.6;">
                                                Holistic academic rigor, digital labs, sports training, leadership opportunities,
                                                and secondary board prep.
                                            </p>

                                            <div class="program-features-list mb-4">
                                                <div class="feature-item d-flex align-items-center gap-2 small text-muted mb-1.5">
                                                    <x-fas-check class="text-info" style="width: 12px; height: 12px;" />
                                                    <span>Class 2nd through 10th Grade</span>
                                                </div>
                                                <div class="feature-item d-flex align-items-center gap-2 small text-muted mb-1.5">
                                                    <x-fas-check class="text-info" style="width: 12px; height: 12px;" />
                                                    <span>STEM, languages, and IT curriculum</span>
                                                </div>
                                                <div class="feature-item d-flex align-items-center gap-2 small text-muted">
                                                    <x-fas-check class="text-info" style="width: 12px; height: 12px;" />
                                                    <span>Board exam mentorship & career guidance</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pt-3 border-top">
                                            <a href="{{ route('admission.form2') }}"
                                                class="btn btn-primary w-100 rounded-pill py-2.5 fw-semibold d-flex align-items-center justify-content-center gap-2 shadow-sm">
                                                <span>Apply for General Education</span>
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
                                        class="program-card h-100 p-4 rounded-4 border bg-white shadow-sm position-relative d-flex flex-column justify-content-between">
                                        <div class="card-accent-strip bg-dark"></div>
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="badge bg-dark bg-opacity-10 text-dark rounded-pill px-3 py-1 fw-bold"
                                                    style="font-size: 11px;">
                                                    HIGHER SECONDARY
                                                </span>
                                                <span
                                                    class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 small fw-semibold">
                                                    <x-fas-circle class="me-1 text-success" style="width: 7px; height: 7px;" /> Live
                                                </span>
                                            </div>

                                            <div class="d-flex align-items-center gap-3 mb-3">
                                                <div>
                                                    <h4 class="fw-bold text-dark mb-0" style="font-size: 1.25rem;">Higher Secondary
                                                    </h4>
                                                    <small class="text-muted">Class 11th & 12th Streams</small>
                                                </div>
                                            </div>

                                            <p class="text-muted small mb-3" style="line-height: 1.6;">
                                                Specialized academic tracks across Science, Medical, Non-Medical, and Arts streams
                                                with university prep.
                                            </p>

                                            <div class="program-features-list mb-4">
                                                <div class="feature-item d-flex align-items-center gap-2 small text-muted mb-1.5">
                                                    <x-fas-check class="text-dark" style="width: 12px; height: 12px;" />
                                                    <span>Medical, Non-Med, Arts & Humanities</span>
                                                </div>
                                                <div class="feature-item d-flex align-items-center gap-2 small text-muted mb-1.5">
                                                    <x-fas-check class="text-dark" style="width: 12px; height: 12px;" />
                                                    <span>Advanced science laboratories & workshops</span>
                                                </div>
                                                <div class="feature-item d-flex align-items-center gap-2 small text-muted">
                                                    <x-fas-check class="text-dark" style="width: 12px; height: 12px;" />
                                                    <span>Competitive exam readiness support</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pt-3 border-top">
                                            <a href="{{ route('admission.form3') }}"
                                                class="btn btn-outline-dark w-100 rounded-pill py-2.5 fw-semibold d-flex align-items-center justify-content-center gap-2 shadow-xs">
                                                <span>Apply for Higher Secondary</span>
                                                <x-fas-arrow-right style="width: 13px; height: 13px;" />
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- All Forms Disabled Notice -->
                        <div class="row justify-content-center">
                            <div class="col-lg-6">
                                <div class="p-5 text-center bg-white rounded-4 shadow-sm border animate-on-scroll">
                                    <div class="rounded-circle bg-warning bg-opacity-10 text-warning mx-auto mb-3 d-flex align-items-center justify-content-center shadow-xs"
                                        style="width: 60px; height: 60px;">
                                        <x-fas-lock style="width: 24px; height: 24px;" />
                                    </div>
                                    <h4 class="fw-bold text-dark mb-2">Admissions Currently Closed</h4>
                                    <p class="text-muted small mb-3">
                                        Online admission registrations for session {{ $academicYear }} are currently closed.
                                        Please contact the administrative office for prospective vacancy announcements.
                                    </p>
                                    <a href="tel:{{ config('app.school_phone') }}"
                                        class="btn btn-outline-primary rounded-pill px-4 btn-sm fw-semibold">
                                        <x-fas-phone class="me-1" style="width: 12px; height: 12px;" /> Contact Admissions
                                        Office
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </section>

            <!-- 3-Step Simple Admission Process Banner -->
            <section class="process-section py-5 bg-light bg-opacity-60 border-top border-bottom">
                <div class="container">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold text-dark mb-1">Simple 3-Step Online Admission</h4>
                        <p class="text-muted small mb-0">Apply in under 5 minutes from your mobile phone or computer</p>
                    </div>

                    <div class="row g-4 justify-content-center">
                        <div class="col-md-4">
                            <div class="step-card text-center p-3">
                                <div class="step-number mb-2">1</div>
                                <h6 class="fw-bold text-dark mb-1">Select Program</h6>
                                <p class="text-muted small mb-0">Choose between Lower Academy, General Education, or Higher
                                    Secondary.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="step-card text-center p-3">
                                <div class="step-number mb-2">2</div>
                                <h6 class="fw-bold text-dark mb-1">Fill Student Details</h6>
                                <p class="text-muted small mb-0">Provide basic demographic, guardian, and previous academic
                                    records.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="step-card text-center p-3">
                                <div class="step-number mb-2">3</div>
                                <h6 class="fw-bold text-dark mb-1">Instant Confirmation</h6>
                                <p class="text-muted small mb-0">Receive instant application number and download the
                                    official PDF copy.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- About Institution & Campus Section -->
            <section id="about-institution" class="about-section py-5">
                <div class="container py-3">
                    <div class="row align-items-center g-4 g-lg-5">
                        <div class="col-lg-6">
                            <div class="section-tag-pill mb-2">INSTITUTIONAL HERITAGE</div>
                            <h2 class="section-title-modern mb-3">"YES India Makes an Excellent India"</h2>
                            <p class="text-muted mb-4" style="line-height: 1.8;">
                                Established in 2007 under the visionary leadership of the YES India Foundation, our
                                institution is committed to providing holistic, values-based education. Through a modern
                                campus environment, expert faculty, and child-centric teaching practices, we equip young
                                minds with the confidence, discipline, and academic excellence required to excel in
                                tomorrow's world.
                            </p>

                            <div class="campus-perks-grid row g-3">
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-start gap-2.5">
                                        <div class="perk-check text-success"><x-fas-check-circle
                                                style="width: 16px; height: 16px;" /></div>
                                        <div>
                                            <h6 class="fw-bold text-dark small mb-0">Interactive Classrooms</h6>
                                            <small class="text-muted" style="font-size: 11px;">Audio-visual smart
                                                learning</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-start gap-2.5">
                                        <div class="perk-check text-success"><x-fas-check-circle
                                                style="width: 16px; height: 16px;" /></div>
                                        <div>
                                            <h6 class="fw-bold text-dark small mb-0">Play-Way Preschool</h6>
                                            <small class="text-muted" style="font-size: 11px;">Safe, vibrant
                                                kindergarten</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-start gap-2.5">
                                        <div class="perk-check text-success"><x-fas-check-circle
                                                style="width: 16px; height: 16px;" /></div>
                                        <div>
                                            <h6 class="fw-bold text-dark small mb-0">Recreational Grounds</h6>
                                            <small class="text-muted" style="font-size: 11px;">Athletics and outdoor
                                                sports</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-start gap-2.5">
                                        <div class="perk-check text-success"><x-fas-check-circle
                                                style="width: 16px; height: 16px;" /></div>
                                        <div>
                                            <h6 class="fw-bold text-dark small mb-0">English-Speaking Culture</h6>
                                            <small class="text-muted" style="font-size: 11px;">Communication fluency
                                                focus</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="campus-showcase-box position-relative rounded-5 overflow-hidden shadow-lg">
                                <img src="{{ asset('images/school.jpeg') }}" alt="{{ config('app.school_name') }} Campus"
                                    class="w-100 h-100 object-fit-cover campus-main-img" style="min-height: 380px;">
                                <div
                                    class="campus-badge-glass position-absolute bottom-0 start-0 m-4 p-3 rounded-4 shadow-sm">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-white text-primary p-2 d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            <img src="{{ asset('images/logo1.png') }}" alt="{{ config('app.school_name') }}"
                                                style="width: 18px; height: 18px;" />
                                        </div>
                                        <div>
                                            <div class="fw-bold text-white small">{{ config('app.school_name') }}</div>
                                            <small class="text-white-50"
                                                style="font-size: 11px;">{{ config('app.school_location', 'Surankote, Poonch') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Life At Campus Gallery Section -->
            <section class="gallery-section py-5 bg-white border-top">
                <div class="container">
                    <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
                        <div>
                            <div class="section-tag-pill mb-1">CAMPUS EXPERIENCE</div>
                            <h3 class="section-title-modern mb-0">Life At Our School</h3>
                        </div>
                        <p class="text-muted small mb-0">Curating multi-dimensional student talent through arts, sciences,
                            and athletics.</p>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <div class="gallery-tile position-relative rounded-4 overflow-hidden shadow-xs">
                                <img src="{{ asset('images/c.jpeg') }}" alt="Sports & Physical Education"
                                    class="w-100 h-100 object-fit-cover">
                                <div class="gallery-overlay p-3 d-flex align-items-end">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="gallery-tile position-relative rounded-4 overflow-hidden shadow-xs">
                                <img src="{{ asset('images/b.jpeg') }}" alt="Science & STEM"
                                    class="w-100 h-100 object-fit-cover">
                                <div class="gallery-overlay p-3 d-flex align-items-end">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="gallery-tile position-relative rounded-4 overflow-hidden shadow-xs">
                                <img src="{{ asset('images/a.jpeg') }}" alt="Arts & Expression"
                                    class="w-100 h-100 object-fit-cover">
                                <div class="gallery-overlay p-3 d-flex align-items-end">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="gallery-tile position-relative rounded-4 overflow-hidden shadow-xs">
                                <img src="{{ asset('images/d.jpeg') }}" alt="Student Life"
                                    class="w-100 h-100 object-fit-cover">
                                <div class="gallery-overlay p-3 d-flex align-items-end">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
@endsection

@section('styles')
    <style>
        :root {
            --brand-navy: #0c213a;
            --brand-dark: #071221;
            --brand-accent: #3b82f6;
            --brand-gold: #b08d57;
            --brand-soft-bg: #f8fafc;
        }

        body {
            background-color: #fbfcfd;
            font-family: 'Inter', sans-serif;
            color: #1e293b;
        }

        /* Hero Section (Proportionate, Balanced Height) */
        .hero-section-compact {
            padding: calc(var(--nav-height, 80px) + 24px) 0 30px 0;
        }

        .hero-banner-container {
            background: linear-gradient(135deg, #0c213a 0%, #1e3a5f 60%, #152e4d 100%);
            border-radius: 32px;
            overflow: hidden;
            box-shadow: 0 20px 45px rgba(12, 33, 58, 0.18);
            position: relative;
        }

        .hero-content-pane {
            position: relative;
            z-index: 2;
        }

        .hero-badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 6px 16px;
            border-radius: 50px;
            color: #93c5fd;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #10b981;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulseDot 2s infinite;
        }

        @keyframes pulseDot {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }

            70% {
                transform: scale(1);
                box-shadow: 0 0 0 8px rgba(16, 185, 129, 0);
            }

            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        .hero-title-refined {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(2rem, 4.2vw, 3.4rem);
            font-weight: 800;
            color: #ffffff;
            line-height: 1.15;
            letter-spacing: -0.5px;
        }

        .hero-gradient-text {
            background: linear-gradient(135deg, #93c5fd 0%, #ffffff 80%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: clamp(0.95rem, 1.2vw, 1.1rem);
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.65;
            max-width: 580px;
        }

        .btn-hero-primary {
            background: #ffffff;
            color: #0c213a;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 12px 28px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            border: 2px solid #ffffff;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .btn-hero-primary:hover {
            background: #0c213a;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            border-color: #93c5fd;
        }

        .btn-hero-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 12px 24px;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.25s ease;
        }

        .btn-hero-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            transform: translateY(-2px);
        }

        .strip-icon-circle {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            color: #93c5fd;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Hero Visual Pane */
        .hero-visual-pane {
            height: 100%;
            min-height: 440px;
        }

        .hero-image-wrapper {
            display: flex;
            align-items: flex-end;
            justify-content: center;
            height: 100%;
        }

        .hero-student-img {
            max-height: 480px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 15px 25px rgba(0, 0, 0, 0.3));
        }

        .hero-floating-card {
            position: absolute;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-radius: 16px;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid rgba(255, 255, 255, 0.8);
            z-index: 3;
        }

        .hero-floating-card.top-badge {
            top: 40px;
            right: 25px;
        }

        .hero-floating-card.bottom-badge {
            bottom: 35px;
            left: -20px;
        }

        .badge-icon {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes floatSlow {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        .animate-float-slow {
            animation: floatSlow 4s ease-in-out infinite;
        }

        .animate-float-delayed {
            animation: floatSlow 4.5s ease-in-out 1.5s infinite;
        }

        /* Logo Marquee Section */
        .logo-marquee-section {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border-top: 1px solid rgba(226, 232, 240, 0.85);
            border-bottom: 1px solid rgba(226, 232, 240, 0.85);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
        }

        .marquee-wrapper {
            width: 100%;
            overflow: hidden;
            position: relative;
            mask-image: linear-gradient(to right, transparent 0%, black 8%, black 92%, transparent 100%);
            -webkit-mask-image: linear-gradient(to right, transparent 0%, black 8%, black 92%, transparent 100%);
        }

        .marquee-track {
            display: flex;
            align-items: center;
            width: max-content;
            animation: marqueeSlide 28s linear infinite;
        }

        .marquee-track:hover {
            animation-play-state: paused;
        }

        .marquee-item {
            flex-shrink: 0;
            padding: 8px 45px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .marquee-logo {
            height: 44px;
            max-width: 180px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.04));
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .marquee-item:hover .marquee-logo {
            transform: scale(1.12);
            filter: drop-shadow(0 6px 14px rgba(0, 0, 0, 0.1));
        }

        @keyframes marqueeSlide {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        @media (max-width: 768px) {
            .marquee-logo {
                height: 36px;
                max-width: 140px;
            }
            .marquee-item {
                padding: 6px 28px;
            }
            .marquee-track {
                animation-duration: 20s;
            }
        }

        /* Section Headings */
        .section-tag-pill {
            display: inline-block;
            background: rgba(12, 33, 58, 0.06);
            color: #0c213a;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .section-title-modern {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(1.8rem, 3.2vw, 2.5rem);
            font-weight: 800;
            color: #0c213a;
            line-height: 1.2;
        }

        .section-desc-modern {
            color: #64748b;
            font-size: 1rem;
            max-width: 600px;
            line-height: 1.6;
        }

        /* Program Cards */
        .program-card {
            transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
            overflow: hidden;
        }

        .card-accent-strip {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .program-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 35px rgba(12, 33, 58, 0.09) !important;
            border-color: rgba(59, 130, 246, 0.35) !important;
        }

        .highlighted-program {
            border-color: rgba(59, 130, 246, 0.25) !important;
            background: linear-gradient(180deg, #ffffff 0%, #f9fbff 100%) !important;
        }

        /* 3-Step Process */
        .step-number {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #0c213a;
            color: #ffffff;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: 0 4px 12px rgba(12, 33, 58, 0.2);
        }

        /* Campus Showcase */
        .campus-showcase-box {
            height: 380px;
        }

        .campus-badge-glass {
            background: rgba(12, 33, 58, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Gallery Tiles */
        .gallery-tile {
            height: 200px;
            transition: transform 0.3s ease;
        }

        .gallery-tile:hover {
            transform: scale(0.98);
        }

        .gallery-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0.65) 100%);
        }

        @media (max-width: 991px) {
            .hero-section-compact {
                padding: calc(var(--nav-height, 80px) + 18px) 0 24px 0;
            }

            .hero-banner-container {
                border-radius: 24px;
                box-shadow: 0 12px 30px rgba(12, 33, 58, 0.16);
            }

            .hero-content-pane {
                padding: 2rem 1.5rem !important;
            }

            .hero-badge-pill {
                font-size: 0.74rem;
                padding: 6px 14px;
                letter-spacing: 0.4px;
                margin-bottom: 1rem !important;
                display: inline-flex;
                max-width: 100%;
            }

            .hero-title-refined {
                font-size: clamp(1.75rem, 6vw, 2.2rem);
                line-height: 1.2;
                letter-spacing: -0.3px;
                margin-bottom: 0.85rem !important;
            }

            .hero-subtitle {
                font-size: 0.925rem;
                line-height: 1.6;
                margin-bottom: 1.5rem !important;
            }

            .hero-actions-row {
                flex-direction: column;
                align-items: stretch;
                width: 100%;
            }

            .btn-hero-primary,
            .btn-hero-secondary {
                width: 100%;
                justify-content: center;
                padding: 13px 24px;
                font-size: 0.95rem;
            }
        }

        @media (max-width: 576px) {
            .hero-section-compact {
                padding: calc(var(--nav-height, 80px) + 12px) 0 20px 0;
            }

            .hero-content-pane {
                padding: 1.6rem 1.15rem !important;
            }

            .hero-banner-container {
                border-radius: 20px;
            }

            .hero-badge-pill {
                font-size: 0.7rem;
                padding: 5px 12px;
                gap: 6px;
            }

            .hero-title-refined {
                font-size: 1.65rem;
                line-height: 1.22;
            }

            .hero-subtitle {
                font-size: 0.875rem;
                line-height: 1.55;
                margin-bottom: 1.25rem !important;
            }

            .btn-hero-primary {
                padding: 12px 20px;
                font-size: 0.92rem;
            }
        }
    </style>
@endsection