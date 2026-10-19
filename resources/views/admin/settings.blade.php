@extends('admin.layout')

@section('title', 'System Settings')
@section('page-title', 'System Settings')
@section('page-subtitle', 'Configure admission portals, automated notifications, and system parameters')

@section('content')
    <div class="row g-4 fade-in">
        <!-- Left Column: Form & General Configurations (8 Cols) -->
        <div class="col-xl-8 col-lg-7">
            <!-- General & Form Portals Configuration -->
            <div class="premium-card p-0 overflow-hidden position-relative mb-4"
                style="background: linear-gradient(135deg, rgba(67, 97, 238, 0.05), rgba(67, 97, 238, 0.02)); border: 1px solid rgba(67, 97, 238, 0.1);">
                <div class="p-4 border-0 d-flex justify-content-between align-items-center bg-white bg-opacity-25 border-bottom"
                    style="border-bottom: 1px solid rgba(0,0,0,0.05) !important;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                            style="width: 44px; height: 44px;">
                            <x-fas-sliders-h class="fa-lg" />
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Admission & Portal Configuration</h5>
                            <small class="text-muted">Manage institute branding, academic year, and active forms</small>
                        </div>
                    </div>
                </div>

                <div class="p-4 p-md-5">
                    <form action="{{ route('admin.settings.application') }}" method="POST">
                        @csrf

                        <!-- Institute Details -->
                        <div class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small required">Institute / School Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted"><x-fas-building /></span>
                                        <input type="text" name="site_name" class="form-control border-start-0 ps-0 @error('site_name') is-invalid @enderror"
                                            value="{{ $settings['site_name'] ?? config('app.school_name') }}" required placeholder="e.g. {{ config('app.school_name') }}">
                                    </div>
                                    @error('site_name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Active Academic Year</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted"><x-fas-calendar-alt /></span>
                                        <input type="text" name="academic_year" class="form-control border-start-0 ps-0 @error('academic_year') is-invalid @enderror"
                                            value="{{ $settings['academic_year'] ?? '2026-2027' }}" placeholder="e.g. 2026-2027">
                                    </div>
                                    @error('academic_year') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Official Contact Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted"><x-fas-envelope /></span>
                                        <input type="email" name="contact_email" class="form-control border-start-0 ps-0 @error('contact_email') is-invalid @enderror"
                                            value="{{ $settings['contact_email'] ?? 'admissions@school.edu' }}" placeholder="contact@institution.edu">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Support Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted"><x-fas-phone /></span>
                                        <input type="text" name="contact_phone" class="form-control border-start-0 ps-0 @error('contact_phone') is-invalid @enderror"
                                            value="{{ $settings['contact_phone'] ?? '+91 98765 43210' }}" placeholder="+91 98765 43210">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 opacity-10">

                        <!-- Admission Forms Status -->
                        <div class="mb-4">
                            <p class="text-muted small mb-3">Enable or disable student application submission portals in real time.</p>

                            <div class="row g-3">
                                <!-- Form 1 -->
                                <div class="col-md-4">
                                    <div class="p-3 bg-white bg-opacity-75 rounded-4 border shadow-sm h-100 d-flex flex-column justify-content-between">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1 small">Primary</span>
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input" type="checkbox" name="form1_enabled" value="1"
                                                        {{ ($settings['form1_enabled'] ?? '1') == '1' ? 'checked' : '' }} id="form1Status">
                                                </div>
                                            </div>
                                            <h6 class="fw-bold mb-1" style="font-size: 15px;">Form 1 (Primary)</h6>
                                            <p class="text-muted small mb-2">Primary level admissions</p>
                                            <div class="mt-2 pt-2 border-top">
                                                <label class="form-label fw-semibold small mb-1" style="font-size: 12px;">Allowed Classes</label>
                                                <input type="text" name="form1_classes" class="form-control form-control-sm"
                                                    value="{{ \App\Models\SiteSetting::getFormClassesRaw('form1') }}"
                                                    placeholder="e.g. Nursery, LKG, UKG, 1ST">
                                                <span class="text-muted" style="font-size: 11px;">Comma-separated values</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form 2 -->
                                <div class="col-md-4">
                                    <div class="p-3 bg-white bg-opacity-75 rounded-4 border shadow-sm h-100 d-flex flex-column justify-content-between">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-2 py-1 small">Secondary</span>
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input" type="checkbox" name="form2_enabled" value="1"
                                                        {{ ($settings['form2_enabled'] ?? '1') == '1' ? 'checked' : '' }} id="form2Status">
                                                </div>
                                            </div>
                                            <h6 class="fw-bold mb-1" style="font-size: 15px;">Form 2 (Secondary)</h6>
                                            <p class="text-muted small mb-2">Middle & secondary level admissions</p>
                                            <div class="mt-2 pt-2 border-top">
                                                <label class="form-label fw-semibold small mb-1" style="font-size: 12px;">Allowed Classes</label>
                                                <input type="text" name="form2_classes" class="form-control form-control-sm"
                                                    value="{{ \App\Models\SiteSetting::getFormClassesRaw('form2') }}"
                                                    placeholder="e.g. 2ND, 3RD, 4TH, 5TH, 6TH, 7TH, 8TH, 9TH, 10TH">
                                                <span class="text-muted" style="font-size: 11px;">Comma-separated values</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form 3 -->
                                <div class="col-md-4">
                                    <div class="p-3 bg-white bg-opacity-75 rounded-4 border shadow-sm h-100 d-flex flex-column justify-content-between">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="badge bg-purple bg-opacity-10 text-purple rounded-pill px-2 py-1 small" style="color: #7209b7;">Higher Sec</span>
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input" type="checkbox" name="form3_enabled" value="1"
                                                        {{ ($settings['form3_enabled'] ?? '1') == '1' ? 'checked' : '' }} id="form3Status">
                                                </div>
                                            </div>
                                            <h6 class="fw-bold mb-1" style="font-size: 15px;">Form 3 (HSC)</h6>
                                            <p class="text-muted small mb-2">Higher secondary stream admissions</p>
                                            <div class="mt-2 pt-2 border-top">
                                                <label class="form-label fw-semibold small mb-1" style="font-size: 12px;">Allowed Classes</label>
                                                <input type="text" name="form3_classes" class="form-control form-control-sm"
                                                    value="{{ \App\Models\SiteSetting::getFormClassesRaw('form3') }}"
                                                    placeholder="e.g. 11TH, 12TH">
                                                <span class="text-muted" style="font-size: 11px;">Comma-separated values</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form 3 Subject Groups Configuration -->
                            <div class="mt-4 p-3 p-md-4 bg-white bg-opacity-75 rounded-4 border shadow-sm">
                                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-purple bg-opacity-10 text-purple d-flex align-items-center justify-content-center"
                                            style="width: 36px; height: 36px; color: #7209b7;">
                                            <i class="fas fa-layer-group"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0" style="font-size: 15px;">Form 3 (HSC) Subject Groups Management</h6>
                                            <small class="text-muted">Configure available Subject Groups and Subjects for Class 11th & 12th</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-light text-secondary border px-2 py-1 small" style="font-size: 11px;">
                                        Syntax: <code>Group Name | Subject 1, Subject 2, ...</code>
                                    </span>
                                </div>

                                <div class="row g-3">
                                    <!-- Science Stream Groups -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small d-flex align-items-center justify-content-between mb-1">
                                            <span><i class="fas fa-atom text-primary me-1"></i> Science Stream Subject Groups</span>
                                            <span class="text-muted" style="font-size: 11px;">One group per line</span>
                                        </label>
                                        <textarea name="form3_science_groups" class="form-control font-monospace" rows="6"
                                            style="font-size: 12.5px; line-height: 1.5;"
                                            placeholder="Group A (Medical 1) | English, Physics, Chemistry, Biology, EVS">{{ \App\Models\SiteSetting::getForm3StreamGroupsRaw('Science') }}</textarea>
                                        <div class="text-muted mt-1" style="font-size: 11px;">
                                            Example: <code>Group A (Medical 1) | English, Physics, Chemistry, Biology, EVS</code>
                                        </div>
                                    </div>

                                    <!-- Arts Stream Groups -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small d-flex align-items-center justify-content-between mb-1">
                                            <span><i class="fas fa-palette text-danger me-1"></i> Arts / Humanities Subject Groups</span>
                                            <span class="text-muted" style="font-size: 11px;">One group per line</span>
                                        </label>
                                        <textarea name="form3_arts_groups" class="form-control font-monospace" rows="6"
                                            style="font-size: 12.5px; line-height: 1.5;"
                                            placeholder="Group A (Arts 1) | English, History, Politics, Education, EVS">{{ \App\Models\SiteSetting::getForm3StreamGroupsRaw('Arts') }}</textarea>
                                        <div class="text-muted mt-1" style="font-size: 11px;">
                                            Example: <code>Group A (Arts 1) | English, History, Politics, Education, EVS</code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 opacity-10">

                        <!-- Notifications & Alerts Settings -->
                        <div class="mb-4">
                            <div class="space-y-3">
                                <div class="p-3 bg-white bg-opacity-75 rounded-4 mb-2 d-flex align-items-center justify-content-between border shadow-sm">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            <x-fas-paper-plane />
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0" style="font-size: 14px;">Student Auto-Responder Email</h6>
                                            <p class="text-muted small mb-0">Send an automated confirmation receipt to the applicant immediately after submission.</p>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch m-0">
                                        <input class="form-check-input" type="checkbox" name="auto_responder_enabled" value="1"
                                            {{ ($settings['auto_responder_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                                    </div>
                                </div>

                                <div class="p-3 bg-white bg-opacity-75 rounded-4 d-flex align-items-center justify-content-between border shadow-sm">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            <x-fas-envelope-open-text />
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0" style="font-size: 14px;">Administrator Intake Alerts</h6>
                                            <p class="text-muted small mb-0">Notify staff via email as soon as an incoming student query or application is received.</p>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch m-0">
                                        <input class="form-check-input" type="checkbox" name="admin_alerts_enabled" value="1"
                                            {{ ($settings['admin_alerts_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="d-flex justify-content-end align-items-center gap-3 pt-3 border-top"
                            style="border-top: 1px solid rgba(0,0,0,0.05) !important;">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                                <x-fas-save class="me-2" /> Save System Configuration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column: Diagnostics, Maintenance & Security (4 Cols) -->
        <div class="col-xl-4 col-lg-5">
            <!-- Platform Traffic & Stats -->
            <div class="premium-card mb-4 position-relative overflow-hidden"
                style="background: linear-gradient(135deg, rgba(67, 97, 238, 0.08), rgba(67, 97, 238, 0.02)); border: 1px solid rgba(67, 97, 238, 0.15);">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-uppercase fw-bold text-primary small" style="font-size: 11px; letter-spacing: 0.5px;">Portal Traffic</span>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1">Live Telemetry</span>
                </div>
                <div class="d-flex align-items-center gap-3 mb-2">
                    <h2 class="display-6 fw-bold mb-0 text-dark">{{ number_format($settings['visitor_count'] ?? 0) }}</h2>
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 small">
                        <x-fas-arrow-up class="me-1" /> Total Visitors
                    </span>
                </div>
                <p class="text-muted small mb-0">Total recorded unique page visits tracked through the public admission portal.</p>
                <div class="position-absolute" style="bottom: -15px; right: -15px; opacity: 0.06; pointer-events: none;">
                    <x-fas-chart-line style="font-size: 80px;" />
                </div>
            </div>

            <!-- Maintenance & Operations Card -->
            <div class="premium-card mb-4 position-relative overflow-hidden"
                style="background: linear-gradient(135deg, rgba(63, 55, 201, 0.05), rgba(63, 55, 201, 0.02)); border: 1px solid rgba(63, 55, 201, 0.15);">
                <h6 class="fw-bold mb-3 d-flex align-items-center">
                    <span class="rounded-3 bg-indigo text-white p-2 me-2 shadow-sm d-inline-flex" style="background-color: #3f37c9;">
                        <x-fas-tools />
                    </span>
                    Maintenance & System Health
                </h6>

                <div class="d-flex flex-column gap-3">
                    <!-- Export Activity Logs -->
                    <div class="p-3 bg-white bg-opacity-75 rounded-4 border shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-1" style="font-size: 14px;">Audit Activity Trail</h6>
                                <p class="text-muted small mb-0">Export logs of all admin actions</p>
                            </div>
                            <a href="{{ route('admin.activity-logs.export') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                <x-fas-download class="me-1" /> Export
                            </a>
                        </div>
                    </div>

                    <!-- Clear Application Cache -->
                    <div class="p-3 bg-white bg-opacity-75 rounded-4 border shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-1" style="font-size: 14px;">System Cache & Views</h6>
                                <p class="text-muted small mb-0">Purge cached routes and templates</p>
                            </div>
                            <form action="{{ route('admin.settings.clear-cache') }}" method="POST" data-confirm="Clear application cache now?">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                    <x-fas-broom class="me-1" /> Clear
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- System Environment Info -->
                    <div class="p-3 bg-white bg-opacity-50 rounded-4 border shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">PHP Version</span>
                            <span class="fw-semibold small">{{ phpversion() }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Framework</span>
                            <span class="fw-semibold small">Laravel {{ app()->version() }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Server Time</span>
                            <span class="fw-semibold small">{{ now()->format('H:i T') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile & Account Quick Access Card -->
            <div class="premium-card position-relative overflow-hidden border-0"
                style="background: linear-gradient(135deg, rgba(67, 97, 238, 0.12), rgba(67, 97, 238, 0.04)); border: 1px solid rgba(67, 97, 238, 0.15) !important;">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="avatar-sm rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow-sm"
                        style="width: 48px; height: 48px; font-weight: 700; font-size: 18px;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">{{ auth()->user()->name }}</h6>
                        <small class="text-muted">{{ auth()->user()->email }}</small>
                    </div>
                </div>
                <p class="text-muted small mb-3">Manage your 2FA security, credentials, and profile details.</p>
                <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm w-100">
                    <x-fas-user-cog class="me-2" /> Account Settings
                </a>
            </div>
        </div>
    </div>
@endsection