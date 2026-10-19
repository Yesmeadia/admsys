@extends('admin.layout')

@section('title', 'Profile & Account Settings')
@section('page-title', 'Profile & Account Settings')
@section('page-subtitle', 'Manage your administrator identity, credentials, and multi-factor security')

@section('content')
    <div class="row g-4 fade-in" id="profileSettingsPage">
        <!-- Left Column: Profile Summary & Security Status (4 Cols) -->
        <div class="col-xl-4 col-lg-5">
            <!-- Profile Identity Card -->
            <div class="profile-form-card p-0 overflow-hidden position-relative mb-4"
                style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.08) 0%, rgba(79, 70, 229, 0.02) 100%); border: 1px solid rgba(99, 102, 241, 0.16);">
                <div class="p-4 p-md-5 text-center">
                    <!-- Avatar with Camera Badge -->
                    <div class="avatar-wrapper mb-3 position-relative d-inline-block">
                        <div id="mainProfileAvatarContainer" class="d-inline-block">
                            @if(Auth::user()->profile_photo_path && Storage::disk('public')->exists(Auth::user()->profile_photo_path))
                                <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}"
                                    alt="{{ Auth::user()->name }}" id="mainUserAvatar" class="avatar-circle shadow-sm" style="width: 120px; height: 120px; max-width: 120px; max-height: 120px; border-radius: 50%; object-fit: cover; display: block;">
                            @else
                                <div id="mainUserAvatar" class="avatar-circle bg-gradient-primary text-white shadow-sm" style="width: 120px; height: 120px; max-width: 120px; max-height: 120px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    {{ Auth::user()->initials }}
                                </div>
                            @endif
                        </div>
                        <button type="button" class="avatar-edit-btn shadow-sm" data-bs-toggle="modal"
                            data-bs-target="#changePhotoModal" title="Change Profile Photo">
                            <x-fas-camera style="width: 14px; height: 14px;" />
                        </button>
                    </div>

                    <h4 class="profile-name fw-bold mb-1 text-dark">{{ Auth::user()->name }}</h4>
                    <p class="profile-email text-muted small mb-3">{{ Auth::user()->email }}</p>

                    <div class="d-flex justify-content-center align-items-center gap-2 mb-4 flex-wrap">
                        @if(Auth::user()->role === 'admin')
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1.5 fw-semibold small">
                                <x-fas-user-tie class="me-1" style="width: 12px; height: 12px;" /> Administrator
                            </span>
                        @else
                            <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-1.5 fw-semibold small">
                                <x-fas-user class="me-1" style="width: 12px; height: 12px;" /> Office Staff
                            </span>
                        @endif
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1.5 fw-semibold small">
                            <x-fas-circle class="me-1 text-success" style="width: 7px; height: 7px;" /> Active Account
                        </span>
                    </div>

                    <hr class="my-3 opacity-10">

                    <!-- Profile Meta Details -->
                    <div class="profile-stats-list text-start">
                        <div class="stat-list-item d-flex align-items-center py-2.5 border-bottom border-light-subtle">
                            <div class="stat-icon-wrapper rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 38px; height: 38px;">
                                <x-fas-calendar-alt style="width: 15px; height: 15px;" />
                            </div>
                            <div class="flex-grow-1">
                                <span class="d-block text-muted small text-uppercase fw-semibold" style="font-size: 10.5px; letter-spacing: 0.5px;">MEMBER SINCE</span>
                                <span class="stat-val fw-semibold text-dark small">{{ Auth::user()->created_at->format('d M Y') }}</span>
                            </div>
                        </div>

                        <div class="stat-list-item d-flex align-items-center py-2.5 border-bottom border-light-subtle">
                            <div class="stat-icon-wrapper rounded-3 bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 38px; height: 38px;">
                                <x-fas-clock style="width: 15px; height: 15px;" />
                            </div>
                            <div class="flex-grow-1">
                                <span class="d-block text-muted small text-uppercase fw-semibold" style="font-size: 10.5px; letter-spacing: 0.5px;">LAST SIGN IN</span>
                                <span class="stat-val fw-semibold text-dark small" title="{{ Auth::user()->last_login_at ? Auth::user()->last_login_at->format('Y-m-d H:i') : '' }}">
                                    {{ Auth::user()->last_login_at ? Auth::user()->last_login_at->diffForHumans() : 'Current Session' }}
                                </span>
                            </div>
                        </div>

                        <div class="stat-list-item d-flex align-items-center py-2.5 border-bottom border-light-subtle">
                            <div class="stat-icon-wrapper rounded-3 bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 38px; height: 38px;">
                                <x-fas-shield-alt style="width: 15px; height: 15px;" />
                            </div>
                            <div class="flex-grow-1">
                                <span class="d-block text-muted small text-uppercase fw-semibold" style="font-size: 10.5px; letter-spacing: 0.5px;">TWO-FACTOR AUTH</span>
                                @if(Auth::user()->two_factor_enabled && Auth::user()->two_factor_confirmed_at)
                                    <span class="badge bg-success bg-opacity-15 text-success rounded-pill px-2.5 py-0.5 small fw-bold">Active & Enforced</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-15 text-secondary rounded-pill px-2.5 py-0.5 small fw-bold">Not Enabled</span>
                                @endif
                            </div>
                        </div>

                        <div class="stat-list-item d-flex align-items-center pt-2.5">
                            <div class="stat-icon-wrapper rounded-3 bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 38px; height: 38px;">
                                <x-fas-lock style="width: 15px; height: 15px;" />
                            </div>
                            <div class="flex-grow-1">
                                <span class="d-block text-muted small text-uppercase fw-semibold" style="font-size: 10.5px; letter-spacing: 0.5px;">PASSWORD AGE</span>
                                <span class="stat-val fw-semibold text-dark small">
                                    {{ Auth::user()->password_changed_at ? Auth::user()->password_changed_at->diffForHumans() : 'Standard' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Security Tools Card -->
            <div class="profile-form-card p-0 overflow-hidden position-relative mb-4"
                style="background: linear-gradient(135deg, rgba(255, 209, 102, 0.05) 0%, rgba(255, 209, 102, 0.02) 100%); border: 1px solid rgba(255, 209, 102, 0.18);">
                <div class="card-header-glass p-3.5 px-4 d-flex justify-content-between align-items-center border-bottom">
                    <div class="d-flex align-items-center gap-2.5">
                        <span class="rounded-circle bg-warning bg-opacity-15 text-warning d-flex align-items-center justify-content-center"
                            style="width: 34px; height: 34px;">
                            <x-fas-bolt style="width: 14px; height: 14px;" />
                        </span>
                        <h6 class="mb-0 fw-bold tool-title text-dark">Security Tools</h6>
                    </div>
                </div>

                <div class="p-3.5 p-md-4">
                    <div class="d-flex flex-column gap-2.5">
                        <!-- 2FA Tile -->
                        <div class="security-tool-item" role="button" data-bs-toggle="modal" data-bs-target="#twoFactorModal">
                            <div class="d-flex align-items-center gap-3">
                                <div class="tool-icon rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                                    <x-fas-mobile-alt style="width: 15px; height: 15px;" />
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark small mb-0 tool-title">Two-Factor Authentication</div>
                                    <small class="text-muted" style="font-size: 11px;">
                                        {{ (Auth::user()->two_factor_enabled && Auth::user()->two_factor_confirmed_at) ? 'Active security verification' : 'Add an extra security layer' }}
                                    </small>
                                </div>
                            </div>
                            <div>
                                @if(Auth::user()->two_factor_enabled && Auth::user()->two_factor_confirmed_at)
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 small fw-bold">Active</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2.5 py-1 small fw-bold">Setup</span>
                                @endif
                            </div>
                        </div>

                        <!-- Backup Codes Tile -->
                        <div class="security-tool-item" role="button" data-bs-toggle="modal" data-bs-target="#backupCodesModal">
                            <div class="d-flex align-items-center gap-3">
                                <div class="tool-icon rounded-3 bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                                    <x-fas-key style="width: 15px; height: 15px;" />
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark small mb-0 tool-title">Recovery Backup Codes</div>
                                    <small class="text-muted" style="font-size: 11px;">Emergency one-time codes</small>
                                </div>
                            </div>
                            <div>
                                <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-2.5 py-1 small fw-bold">Manage</span>
                            </div>
                        </div>

                        <!-- Audit Activity Trail Tile -->
                        <div class="security-tool-item" role="button" data-bs-toggle="modal" data-bs-target="#activityModal">
                            <div class="d-flex align-items-center gap-3">
                                <div class="tool-icon rounded-3 bg-secondary bg-opacity-10 text-secondary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                                    <x-fas-history style="width: 15px; height: 15px;" />
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark small mb-0 tool-title">Audit Activity Trail</div>
                                    <small class="text-muted" style="font-size: 11px;">Inspect session & action history</small>
                                </div>
                            </div>
                            <div>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2.5 py-1 small fw-bold">Review</span>
                            </div>
                        </div>

                        <!-- Delete Account Tile -->
                        <div class="security-tool-item danger-item mt-1" role="button" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            <div class="d-flex align-items-center gap-3">
                                <div class="tool-icon rounded-3 bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                                    <x-fas-trash-alt style="width: 15px; height: 15px;" />
                                </div>
                                <div>
                                    <div class="fw-semibold text-danger small mb-0 tool-title">Delete Account</div>
                                    <small class="text-muted" style="font-size: 11px;">Permanently remove profile data</small>
                                </div>
                            </div>
                            <div>
                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2.5 py-1 small fw-bold">Danger</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Personal Info & Password Settings (8 Cols) -->
        <div class="col-xl-8 col-lg-7">
            <!-- Card 1: Personal Information -->
            <div class="profile-form-card p-0 overflow-hidden position-relative mb-4"
                style="background: linear-gradient(135deg, rgba(67, 97, 238, 0.05) 0%, rgba(67, 97, 238, 0.01) 100%); border: 1px solid rgba(67, 97, 238, 0.12);">
                <div class="card-header-glass p-3.5 px-4 d-flex justify-content-between align-items-center border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                            style="width: 42px; height: 42px;">
                            <x-fas-user-edit style="width: 18px; height: 18px;" />
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold card-title text-dark">Personal Information</h5>
                            <small class="text-muted">Update your administrative profile details and contact information</small>
                        </div>
                    </div>
                </div>

                <div class="p-4 p-md-5">
                    <form method="POST" action="{{ route('profile.update') }}" class="needs-validation" novalidate>
                        @csrf
                        @method('PATCH')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold small required text-dark">Full Name</label>
                                <div class="profile-input-group @error('name') has-error @enderror">
                                    <span class="input-icon"><x-fas-user style="width: 14px; height: 14px;" /></span>
                                    <input type="text" class="form-control"
                                        id="name" name="name" value="{{ old('name', Auth::user()->name) }}"
                                        placeholder="Enter your full name" required autocomplete="name">
                                </div>
                                @error('name')
                                    <div class="text-danger small mt-1.5"><x-fas-exclamation-circle class="me-1" style="width: 11px; height: 11px;" /> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold small required text-dark">Official Email Address</label>
                                <div class="profile-input-group @error('email') has-error @enderror">
                                    <span class="input-icon"><x-fas-envelope style="width: 14px; height: 14px;" /></span>
                                    <input type="email" class="form-control"
                                        id="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                                        placeholder="admin@institution.edu" required autocomplete="email">
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1.5"><x-fas-exclamation-circle class="me-1" style="width: 11px; height: 11px;" /> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold small text-dark">Contact Phone Number</label>
                                <div class="profile-input-group @error('phone') has-error @enderror">
                                    <span class="input-icon"><x-fas-phone style="width: 14px; height: 14px;" /></span>
                                    <input type="tel" class="form-control"
                                        id="phone" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}"
                                        placeholder="+91 98765 43210" autocomplete="tel">
                                </div>
                                @error('phone')
                                    <div class="text-danger small mt-1.5"><x-fas-exclamation-circle class="me-1" style="width: 11px; height: 11px;" /> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="timezone" class="form-label fw-semibold small text-dark">Administrative Timezone</label>
                                <div class="profile-input-group @error('timezone') has-error @enderror">
                                    <span class="input-icon"><x-fas-globe style="width: 14px; height: 14px;" /></span>
                                    <select class="form-select" id="timezone" name="timezone">
                                        <option value="Asia/Kolkata" {{ (Auth::user()->timezone ?? 'Asia/Kolkata') == 'Asia/Kolkata' ? 'selected' : '' }}>
                                            Asia/Kolkata (IST &bull; GMT+5:30)
                                        </option>
                                        <option value="UTC" {{ (Auth::user()->timezone ?? '') == 'UTC' ? 'selected' : '' }}>
                                            Coordinated Universal Time (UTC)
                                        </option>
                                        <option value="Asia/Dubai" {{ (Auth::user()->timezone ?? '') == 'Asia/Dubai' ? 'selected' : '' }}>
                                            Asia/Dubai (GST &bull; GMT+4:00)
                                        </option>
                                        <option value="Europe/London" {{ (Auth::user()->timezone ?? '') == 'Europe/London' ? 'selected' : '' }}>
                                            Europe/London (GMT/BST)
                                        </option>
                                        <option value="America/New_York" {{ (Auth::user()->timezone ?? '') == 'America/New_York' ? 'selected' : '' }}>
                                            America/New_York (EST/EDT)
                                        </option>
                                    </select>
                                </div>
                                @error('timezone')
                                    <div class="text-danger small mt-1.5"><x-fas-exclamation-circle class="me-1" style="width: 11px; height: 11px;" /> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex justify-content-end align-items-center gap-2">
                            <button type="reset" class="btn btn-light rounded-pill px-4">
                                <x-fas-undo class="me-1" style="width: 12px; height: 12px;" /> Reset
                            </button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
                                <x-fas-save class="me-1" style="width: 12px; height: 12px;" /> Save Profile Details
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Card 2: Password & Authentication Credentials -->
            <div class="profile-form-card p-0 overflow-hidden position-relative mb-4"
                style="background: linear-gradient(135deg, rgba(239, 71, 111, 0.05) 0%, rgba(239, 71, 111, 0.01) 100%); border: 1px solid rgba(239, 71, 111, 0.14);">
                <div class="card-header-glass p-3.5 px-4 d-flex justify-content-between align-items-center border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-warning bg-opacity-15 text-warning d-flex align-items-center justify-content-center"
                            style="width: 42px; height: 42px;">
                            <x-fas-lock style="width: 18px; height: 18px;" />
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold card-title text-dark">Change Password</h5>
                            <small class="text-muted">Ensure your administrative account is secured with a strong credential</small>
                        </div>
                    </div>
                </div>

                <div class="p-4 p-md-5">
                    <form method="POST" action="{{ route('profile.password.update') }}" class="needs-validation" novalidate id="passwordForm">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="current_password" class="form-label fw-semibold small required text-dark">Current Password</label>
                                <div class="profile-input-group @error('current_password') has-error @enderror">
                                    <span class="input-icon"><x-fas-key style="width: 14px; height: 14px;" /></span>
                                    <input type="password" class="form-control"
                                        id="current_password" name="current_password" placeholder="Enter your current password" required autocomplete="current-password">
                                    <button class="toggle-password-btn" type="button" data-target="current_password" title="Toggle password visibility">
                                        <x-fas-eye style="width: 14px; height: 14px;" />
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="text-danger small mt-1.5"><x-fas-exclamation-circle class="me-1" style="width: 11px; height: 11px;" /> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold small required text-dark">New Password</label>
                                <div class="profile-input-group @error('password') has-error @enderror">
                                    <span class="input-icon"><x-fas-lock style="width: 14px; height: 14px;" /></span>
                                    <input type="password" class="form-control"
                                        id="password" name="password" placeholder="Create a new password" required autocomplete="new-password">
                                    <button class="toggle-password-btn" type="button" data-target="password" title="Toggle password visibility">
                                        <x-fas-eye style="width: 14px; height: 14px;" />
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1.5"><x-fas-exclamation-circle class="me-1" style="width: 11px; height: 11px;" /> {{ $message }}</div>
                                @enderror

                                <!-- Password Strength Meter -->
                                <div class="password-strength-container mt-2">
                                    <div class="progress" style="height: 5px; border-radius: 10px; background-color: #e2e8f0;">
                                        <div class="progress-bar" id="passwordStrengthBar" style="width: 0%; transition: width 0.3s ease;"></div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-1.5">
                                        <small class="text-muted" style="font-size: 11px;">Password strength:</small>
                                        <small class="fw-bold text-muted" id="passwordStrengthLabel" style="font-size: 11px;">Too Weak</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold small required text-dark">Confirm New Password</label>
                                <div class="profile-input-group" id="confirmPasswordGroup">
                                    <span class="input-icon"><x-fas-shield-alt style="width: 14px; height: 14px;" /></span>
                                    <input type="password" class="form-control"
                                        id="password_confirmation" name="password_confirmation" placeholder="Re-type new password" required autocomplete="new-password">
                                    <button class="toggle-password-btn" type="button" data-target="password_confirmation" title="Toggle password visibility">
                                        <x-fas-eye style="width: 14px; height: 14px;" />
                                    </button>
                                </div>

                                <!-- Password Match Feedback -->
                                <div id="passwordMatchStatus" class="mt-1.5 small d-flex align-items-center gap-1 text-muted" style="font-size: 11px;">
                                    <span class="status-icon"><x-fas-info-circle style="width: 11px; height: 11px;" /></span>
                                    <span class="status-text">Passwords must match</span>
                                </div>
                            </div>
                        </div>

                        <!-- Password Requirements Checklist -->
                        <div class="password-guidelines mt-3 p-3 rounded-4 border bg-white bg-opacity-75">
                            <h6 class="small fw-bold text-dark mb-2.5 d-flex align-items-center gap-1.5">
                                <x-fas-shield-alt class="text-primary" style="width: 12px; height: 12px;" />
                                <span>Security Complexity Checklist:</span>
                            </h6>
                            <div class="row g-2 small text-muted">
                                <div class="col-sm-6 req-item" data-rule="length">
                                    <span class="indicator-icon me-1 text-danger"><x-fas-times-circle style="width: 12px; height: 12px;" /></span>
                                    <span>At least 8 characters</span>
                                </div>
                                <div class="col-sm-6 req-item" data-rule="uppercase">
                                    <span class="indicator-icon me-1 text-danger"><x-fas-times-circle style="width: 12px; height: 12px;" /></span>
                                    <span>At least one uppercase letter (A-Z)</span>
                                </div>
                                <div class="col-sm-6 req-item" data-rule="lowercase">
                                    <span class="indicator-icon me-1 text-danger"><x-fas-times-circle style="width: 12px; height: 12px;" /></span>
                                    <span>At least one lowercase letter (a-z)</span>
                                </div>
                                <div class="col-sm-6 req-item" data-rule="number">
                                    <span class="indicator-icon me-1 text-danger"><x-fas-times-circle style="width: 12px; height: 12px;" /></span>
                                    <span>At least one number digit (0-9)</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                            <button type="submit" class="btn btn-warning text-dark fw-bold rounded-pill px-4 shadow-sm">
                                <x-fas-key class="me-1" style="width: 12px; height: 12px;" /> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Modal Partials -->
    @include('admin.profile.partials.modals.change-photo')
    @include('admin.profile.partials.modals.delete-account')
    @include('admin.profile.partials.modals.two-factor')
    @include('admin.profile.partials.modals.backup-codes')
    @include('admin.profile.partials.modals.activity-log')

    @push('scripts')
        <script>
            $(document).ready(function () {
                // Password eye toggle
                $('.toggle-password-btn, .toggle-password').click(function () {
                    const target = $(this).data('target');
                    const input = $('#' + target);
                    const isPassword = input.attr('type') === 'password';

                    if (isPassword) {
                        input.attr('type', 'text');
                        $(this).html('<i class="fas fa-eye-slash"></i>');
                    } else {
                        input.attr('type', 'password');
                        $(this).html('<i class="fas fa-eye"></i>');
                    }
                });

                // Real-time password strength and match checks
                $('#password').on('input', function () {
                    checkPasswordStrength($(this).val());
                    checkPasswordMatch();
                });

                $('#password_confirmation').on('input', function () {
                    checkPasswordMatch();
                });

                // Show/hide 2FA instructions based on selected method
                $('input[name="twoFactorMethod"]').change(function () {
                    const method = $(this).val();
                    $('#appInstructions, #smsInstructions').addClass('d-none');
                    $(`#${method}Instructions`).removeClass('d-none');
                });
            });

            // Password strength calculation
            function checkPasswordStrength(password) {
                const strengthBar = $('#passwordStrengthBar');
                const strengthLabel = $('#passwordStrengthLabel');

                if (!password) {
                    strengthBar.css('width', '0%').removeClass().addClass('progress-bar');
                    strengthLabel.text('Too Weak').removeClass().addClass('fw-bold text-muted');
                    updateRequirementsList('');
                    return;
                }

                let strength = 0;
                const rules = {
                    length: password.length >= 8,
                    uppercase: /[A-Z]/.test(password),
                    lowercase: /[a-z]/.test(password),
                    number: /[0-9]/.test(password)
                };

                Object.values(rules).forEach(met => {
                    if (met) strength += 25;
                });

                strengthBar.css('width', strength + '%');

                if (strength <= 25) {
                    strengthLabel.text('Weak').removeClass().addClass('fw-bold text-danger');
                    strengthBar.removeClass().addClass('progress-bar bg-danger');
                } else if (strength <= 50) {
                    strengthLabel.text('Fair').removeClass().addClass('fw-bold text-warning');
                    strengthBar.removeClass().addClass('progress-bar bg-warning');
                } else if (strength <= 75) {
                    strengthLabel.text('Good').removeClass().addClass('fw-bold text-info');
                    strengthBar.removeClass().addClass('progress-bar bg-info');
                } else {
                    strengthLabel.text('Strong').removeClass().addClass('fw-bold text-success');
                    strengthBar.removeClass().addClass('progress-bar bg-success');
                }

                updateRequirementsList(password);
            }

            // Real-time password confirmation match check
            function checkPasswordMatch() {
                const password = $('#password').val();
                const confirm = $('#password_confirmation').val();
                const statusContainer = $('#passwordMatchStatus');
                const group = $('#confirmPasswordGroup');

                if (!confirm) {
                    statusContainer.html('<span class="status-icon"><i class="fas fa-info-circle me-1"></i></span><span>Passwords must match</span>')
                        .removeClass('text-success text-danger').addClass('text-muted');
                    group.removeClass('has-error has-success');
                    return;
                }

                if (password === confirm) {
                    statusContainer.html('<span class="status-icon"><i class="fas fa-check-circle me-1 text-success"></i></span><span class="text-success fw-semibold">Passwords match</span>')
                        .removeClass('text-muted text-danger').addClass('text-success');
                    group.removeClass('has-error').addClass('has-success');
                } else {
                    statusContainer.html('<span class="status-icon"><i class="fas fa-times-circle me-1 text-danger"></i></span><span class="text-danger fw-semibold">Passwords do not match</span>')
                        .removeClass('text-muted text-success').addClass('text-danger');
                    group.removeClass('has-success').addClass('has-error');
                }
            }

            // Update password checklist icons
            function updateRequirementsList(password) {
                const rules = {
                    length: password.length >= 8,
                    uppercase: /[A-Z]/.test(password),
                    lowercase: /[a-z]/.test(password),
                    number: /[0-9]/.test(password)
                };

                Object.keys(rules).forEach(rule => {
                    const item = $(`.req-item[data-rule="${rule}"]`);
                    const iconSpan = item.find('.indicator-icon');

                    if (rules[rule]) {
                        iconSpan.html('<i class="fas fa-check-circle text-success"></i>');
                        item.addClass('text-success').removeClass('text-muted text-danger');
                    } else {
                        iconSpan.html('<i class="fas fa-times-circle text-danger"></i>');
                        item.addClass('text-muted').removeClass('text-success');
                    }
                });
            }

            // Image preview for profile photo modal
            function previewImage(event) {
                const input = event.target;
                const preview = document.getElementById('photoPreview');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        preview.innerHTML = `<img src="${e.target.result}" class="w-100 h-100" style="object-fit: cover;">`;
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Remove profile photo
            function removePhoto() {
                if (!confirm('Are you sure you want to remove your profile photo?')) return;

                $.ajax({
                    url: '{{ route("profile.photo.remove") }}',
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function () {
                        $('#changePhotoModal .btn').prop('disabled', true);
                    },
                    success: function (response) {
                        if (response.success) {
                            const initials = response.initials || '{{ Auth::user()->initials }}';
                            const preview = document.getElementById('photoPreview');
                            if (preview) {
                                preview.innerHTML = initials;
                            }

                            $('#mainProfileAvatarContainer').html(
                                `<div id="mainUserAvatar" class="avatar-circle bg-gradient-primary text-white shadow-sm">${initials}</div>`
                            );

                            showToast(response.message || 'Profile photo removed successfully!', 'success');
                            $('#changePhotoModal').modal('hide');
                        }
                    },
                    error: function () {
                        showToast('Error removing photo. Please try again.', 'error');
                    },
                    complete: function () {
                        $('#changePhotoModal .btn').prop('disabled', false);
                    }
                });
            }

            // Upload photo function
            function uploadPhoto() {
                const form = document.getElementById('photoForm');
                const formData = new FormData(form);
                const fileInput = document.getElementById('profile_photo');

                if (!fileInput.files.length) {
                    showToast('Please select an image file first.', 'warning');
                    return;
                }

                const file = fileInput.files[0];
                const fileSize = file.size / 1024 / 1024; // MB
                if (fileSize > 2) {
                    showToast('File size exceeds the 2MB limit.', 'error');
                    return;
                }

                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    showToast('Invalid file format. Please use JPG, PNG, GIF, or WebP.', 'error');
                    return;
                }

                $.ajax({
                    url: '{{ route("profile.photo.upload") }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        $('#changePhotoModal .btn').prop('disabled', true);
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#mainProfileAvatarContainer').html(
                                `<img src="${response.photo_url}" alt="Profile Photo" id="mainUserAvatar" class="avatar-circle shadow-sm" style="width: 120px; height: 120px; max-width: 120px; max-height: 120px; border-radius: 50%; object-fit: cover; display: block;">`
                            );
                            showToast(response.message || 'Profile photo updated successfully!', 'success');
                            $('#changePhotoModal').modal('hide');
                        }
                    },
                    error: function (xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            showToast(xhr.responseJSON.errors.profile_photo[0], 'error');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            showToast(xhr.responseJSON.message, 'error');
                        } else {
                            showToast('Error uploading photo. Please try again.', 'error');
                        }
                    },
                    complete: function () {
                        $('#changePhotoModal .btn').prop('disabled', false);
                    }
                });
            }

            // Generate QR Code with backup servers
            function generateQRCode(qrCodeUrl) {
                const container = $('#qrCodeContainer');
                container.empty();

                const size = 190;
                const encodedUrl = encodeURIComponent(qrCodeUrl);
                const qrServerUrl = `https://api.qrserver.com/v1/create-qr-code/?size=${size}x${size}&data=${encodedUrl}`;

                const img = document.createElement('img');
                img.src = qrServerUrl;
                img.alt = '2FA QR Code';
                img.width = size;
                img.height = size;
                img.style.border = '1px solid #e2e8f0';
                img.style.borderRadius = '12px';
                img.style.padding = '8px';
                img.style.background = '#ffffff';

                container.html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin me-2 text-primary"></i>Generating QR Code...</div>');

                img.onload = function () {
                    container.empty().append(img);
                };

                img.onerror = function () {
                    // Try Google Charts fallback
                    const googleUrl = `https://chart.googleapis.com/chart?chs=${size}x${size}&cht=qr&chl=${encodedUrl}&choe=UTF-8`;
                    const fallbackImg = new Image();
                    fallbackImg.src = googleUrl;
                    fallbackImg.onload = function () {
                        container.empty().append(fallbackImg);
                    };
                    fallbackImg.onerror = function () {
                        container.html(`
                            <div class="text-center p-3">
                                <div class="alert alert-info py-2 small mb-1">
                                    QR code rendering blocked. Please enter the secret key manually into your authenticator application.
                                </div>
                            </div>
                        `);
                    };
                };
            }

            // Enable 2FA
            function enableTwoFactor() {
                const method = $('input[name="twoFactorMethod"]:checked').val();
                if (!method) {
                    showToast('Please select a 2FA method.', 'warning');
                    return;
                }

                $.ajax({
                    url: '{{ route("profile.two-factor.enable") }}',
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        method: method
                    },
                    beforeSend: function () {
                        $('#twoFactorModal .btn').prop('disabled', true);
                    },
                    success: function (response) {
                        if (response.success) {
                            if (method === 'app') {
                                generateQRCode(response.qr_code_url);
                                $('#qrCodeModal #manualSecret').val(response.secret);
                                $('#twoFactorModal').modal('hide');
                                $('#qrCodeModal').modal('show');
                            } else {
                                showToast('SMS verification is not configured for this server.', 'info');
                            }
                        }
                    },
                    error: function (xhr) {
                        showToast(xhr.responseJSON?.message || 'Error configuring 2FA. Please try again.', 'error');
                    },
                    complete: function () {
                        $('#twoFactorModal .btn').prop('disabled', false);
                    }
                });
            }

            // Copy secret to clipboard
            function copySecret() {
                const secret = document.getElementById('manualSecret');
                secret.select();
                secret.setSelectionRange(0, 99999);
                navigator.clipboard.writeText(secret.value).then(function () {
                    showToast('Secret key copied to clipboard!', 'success');
                }).catch(function () {
                    showToast('Failed to copy secret.', 'error');
                });
            }

            // Verify 2FA code
            function verifyTwoFactor() {
                const code = $('#verificationCode').val();
                if (!code || code.length !== 6 || !/^\d+$/.test(code)) {
                    showToast('Please enter a valid 6-digit numeric verification code.', 'warning');
                    return;
                }

                $.ajax({
                    url: '{{ route("profile.two-factor.confirm") }}',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        code: code
                    },
                    beforeSend: function () {
                        $('#qrCodeModal .btn').prop('disabled', true);
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#qrCodeModal').modal('hide');
                            showBackupCodes(response.backup_codes);
                            showToast(response.message || 'Two-factor authentication enabled successfully!', 'success');
                            setTimeout(() => location.reload(), 2000);
                        }
                    },
                    error: function (xhr) {
                        showToast(xhr.responseJSON?.message || 'Invalid verification code. Please check your authenticator.', 'error');
                    },
                    complete: function () {
                        $('#qrCodeModal .btn').prop('disabled', false);
                    }
                });
            }

            // Show backup codes
            function showBackupCodes(codes) {
                let html = '<div class="alert alert-warning mb-3 small">';
                html += '<i class="fas fa-exclamation-triangle me-2"></i>';
                html += 'Save these backup codes in a secure location. Each code can be used once if you lose device access.';
                html += '</div>';
                html += '<div class="row g-2 mb-3">';
                codes.forEach(code => {
                    html += `<div class="col-6"><div class="p-2 bg-light rounded-3 text-center font-monospace fw-bold text-dark border small letter-spacing-1">${code}</div></div>`;
                });
                html += '</div>';

                $('#backupCodesList').html(html);
                $('#backupCodesModal').modal('show');
            }

            // Download backup codes
            function downloadBackupCodes() {
                const userEmail = '{{ Auth::user()->email }}';
                const codes = {!! Auth::user()->two_factor_backup_codes ? json_encode(Auth::user()->two_factor_backup_codes) : '[]' !!};
                const content = `BACKUP CODES FOR: ${userEmail}\nGenerated on: ${new Date().toLocaleString()}\n\nIMPORTANT:\n• Each code can only be used ONCE\n• Store these codes securely\n\nYOUR BACKUP CODES:\n${codes.join('\n')}\n`;

                const blob = new Blob([content], { type: 'text/plain' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `backup-codes-${userEmail.replace(/[^a-z0-9]/gi, '-').toLowerCase()}.txt`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
                showToast('Backup codes downloaded!', 'success');
            }

            // Disable 2FA
            function disableTwoFactor() {
                const password = $('#disablePassword').val();
                if (!password) {
                    showToast('Please enter your account password to confirm.', 'warning');
                    return;
                }

                $.ajax({
                    url: '{{ route("profile.two-factor.disable") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        password: password
                    },
                    beforeSend: function () {
                        $('#disableTwoFactorModal .btn').prop('disabled', true);
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#disableTwoFactorModal').modal('hide');
                            showToast('Two-factor authentication disabled.', 'success');
                            setTimeout(() => location.reload(), 1500);
                        }
                    },
                    error: function (xhr) {
                        showToast(xhr.responseJSON?.message || 'Invalid password. Please try again.', 'error');
                    },
                    complete: function () {
                        $('#disableTwoFactorModal .btn').prop('disabled', false);
                    }
                });
            }

            // Regenerate backup codes
            function regenerateBackupCodes() {
                if (!confirm('This will invalidate all current backup codes. Are you sure you wish to continue?')) return;

                $.ajax({
                    url: '{{ route("profile.two-factor.regenerate-backup") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function () {
                        $('#backupCodesModal .btn').prop('disabled', true);
                    },
                    success: function (response) {
                        if (response.success) {
                            showBackupCodes(response.backup_codes);
                            showToast('Backup codes regenerated successfully!', 'success');
                        }
                    },
                    error: function (xhr) {
                        showToast(xhr.responseJSON?.message || 'Error regenerating backup codes.', 'error');
                    },
                    complete: function () {
                        $('#backupCodesModal .btn').prop('disabled', false);
                    }
                });
            }

            // Confirm delete account
            function confirmDelete() {
                const confirmInput = document.getElementById('confirmDelete');
                const confirmText = confirmInput ? confirmInput.value.trim() : '';
                const expectedText = 'DELETE {{ Auth::user()->email }}';
                const checkbox = document.getElementById('understandConsequences');

                if (confirmText.toLowerCase() !== expectedText.toLowerCase()) {
                    showToast('Please type exactly: ' + expectedText, 'error');
                    return;
                }

                if (!checkbox.checked) {
                    showToast('Please confirm that you understand the consequences.', 'warning');
                    return;
                }

                if (confirm('FINAL WARNING: This action cannot be reversed! Your administrator account will be permanently erased. Proceed?')) {
                    document.getElementById('deleteAccountForm').submit();
                }
            }

            // Export activity log
            function exportActivityLog() {
                @if(Auth::user()->role === 'admin')
                    window.location.href = "{{ route('admin.activity-logs.export') }}";
                @else
                    showToast('Exporting activity logs is restricted to Administrators.', 'warning');
                @endif
            }

            // Toast notification function
            function showToast(message, type = 'info') {
                $('.toast').remove();

                const toast = document.createElement('div');
                toast.className = `toast align-items-center text-bg-${type} border-0 position-fixed bottom-0 end-0 m-3 shadow-lg`;
                toast.setAttribute('role', 'alert');

                let icon = 'info-circle';
                if (type === 'success') icon = 'check-circle';
                if (type === 'error') icon = 'exclamation-circle';
                if (type === 'warning') icon = 'exclamation-triangle';

                toast.innerHTML = `
                    <div class="d-flex">
                        <div class="toast-body small fw-semibold">
                            <i class="fas fa-${icon} me-2"></i>
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                `;

                document.body.appendChild(toast);
                const bsToast = new bootstrap.Toast(toast, { autohide: true, delay: 5000 });
                bsToast.show();

                toast.addEventListener('hidden.bs.toast', function () {
                    document.body.removeChild(toast);
                });
            }
        </script>
    @endpush

    @push('styles')
        <style>
            /* Typography & text-transform overrides for profile view */
            #profileSettingsPage .form-control,
            #profileSettingsPage .form-select,
            #profileSettingsPage .profile-name,
            #profileSettingsPage .profile-email,
            #profileSettingsPage .stat-val,
            #profileSettingsPage .tool-title,
            #profileSettingsPage .card-title,
            #profileSettingsPage .fw-semibold,
            #profileSettingsPage .fw-bold {
                text-transform: none !important;
            }

            /* Profile Form Card */
            .profile-form-card {
                background: var(--card-bg, #ffffff);
                border-radius: 22px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
                transition: box-shadow 0.25s ease, border-color 0.25s ease;
            }

            .profile-form-card:hover {
                transform: none !important;
            }

            /* Card Header Glass Effect */
            .card-header-glass {
                background: rgba(255, 255, 255, 0.6);
                backdrop-filter: blur(10px);
                border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
            }

            [data-bs-theme="dark"] .card-header-glass {
                background: rgba(30, 41, 59, 0.6);
                border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
            }

            /* Avatar Styling */
            .avatar-wrapper {
                width: 120px;
                height: 120px;
                max-width: 120px;
                max-height: 120px;
                margin: 0 auto;
                position: relative;
            }

            .avatar-circle,
            img.avatar-circle,
            #mainUserAvatar {
                width: 120px !important;
                height: 120px !important;
                max-width: 120px !important;
                max-height: 120px !important;
                border-radius: 50% !important;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 2.75rem;
                font-weight: 700;
                background: linear-gradient(135deg, var(--primary-color, #4361ee) 0%, var(--secondary-color, #3f37c9) 100%);
                color: #ffffff;
                border: 4px solid var(--card-bg, #ffffff);
                box-shadow: 0 10px 25px -5px rgba(67, 97, 238, 0.3);
                transition: transform 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
                object-fit: cover !important;
                overflow: hidden !important;
            }

            img#mainUserAvatar {
                display: block !important;
            }

            .avatar-circle:hover {
                transform: scale(1.03);
            }

            .avatar-edit-btn {
                position: absolute;
                bottom: 2px;
                right: 2px;
                width: 38px;
                height: 38px;
                border-radius: 50%;
                background: var(--primary-color, #4361ee);
                color: #ffffff;
                border: 3px solid var(--card-bg, #ffffff);
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
                transition: all 0.25s ease;
            }

            .avatar-edit-btn:hover {
                background: var(--secondary-color, #3f37c9);
                transform: scale(1.1);
            }

            /* Custom Integrated Input Group */
            .profile-input-group {
                display: flex;
                align-items: center;
                border: 1.5px solid #e2e8f0;
                border-radius: 12px;
                background-color: #ffffff;
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
                overflow: hidden;
            }

            .profile-input-group:focus-within {
                border-color: var(--primary-color, #4361ee);
                box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
            }

            .profile-input-group.has-error {
                border-color: #ef4444 !important;
                box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15) !important;
            }

            .profile-input-group.has-success {
                border-color: #10b981 !important;
            }

            .profile-input-group .input-icon {
                display: flex;
                align-items: center;
                justify-content: center;
                padding-left: 14px;
                padding-right: 4px;
                color: #94a3b8;
                background: transparent;
                border: none;
                flex-shrink: 0;
            }

            .profile-input-group .form-control,
            .profile-input-group .form-select {
                border: none !important;
                box-shadow: none !important;
                background: transparent !important;
                padding: 0.65rem 0.85rem;
                font-size: 0.925rem;
                color: var(--text-main, #0f172a);
            }

            .profile-input-group .toggle-password-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0 14px;
                background: transparent;
                border: none;
                color: #94a3b8;
                cursor: pointer;
                transition: color 0.2s ease;
                flex-shrink: 0;
            }

            .profile-input-group .toggle-password-btn:hover {
                color: var(--primary-color, #4361ee);
            }

            /* Security Tool Items */
            .security-tool-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 13px 16px;
                border-radius: 14px;
                border: 1px solid rgba(0, 0, 0, 0.06);
                background: rgba(255, 255, 255, 0.9);
                cursor: pointer;
                transition: all 0.25s cubic-bezier(0.165, 0.84, 0.44, 1);
            }

            .security-tool-item:hover {
                transform: translateY(-2px);
                background: #ffffff;
                border-color: rgba(67, 97, 238, 0.3);
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
            }

            .security-tool-item.danger-item:hover {
                border-color: rgba(239, 68, 68, 0.35);
                box-shadow: 0 6px 20px rgba(239, 68, 68, 0.08);
            }

            /* Dark Theme Adaptations */
            [data-bs-theme="dark"] .avatar-circle {
                border-color: #1e293b;
            }

            [data-bs-theme="dark"] .avatar-edit-btn {
                border-color: #1e293b;
            }

            [data-bs-theme="dark"] .profile-input-group {
                background-color: #1e293b;
                border-color: rgba(255, 255, 255, 0.12);
            }

            [data-bs-theme="dark"] .profile-input-group:focus-within {
                border-color: #6366f1;
                box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
            }

            [data-bs-theme="dark"] .profile-input-group .form-control,
            [data-bs-theme="dark"] .profile-input-group .form-select {
                color: #f8fafc;
            }

            [data-bs-theme="dark"] .profile-input-group .input-icon,
            [data-bs-theme="dark"] .profile-input-group .toggle-password-btn {
                color: #64748b;
            }

            [data-bs-theme="dark"] .profile-input-group .toggle-password-btn:hover {
                color: #a5b4fc;
            }

            [data-bs-theme="dark"] .security-tool-item {
                background: rgba(30, 41, 59, 0.7);
                border-color: rgba(255, 255, 255, 0.08);
            }

            [data-bs-theme="dark"] .security-tool-item:hover {
                background: #1e293b;
                border-color: rgba(99, 102, 241, 0.4);
            }

            [data-bs-theme="dark"] .password-guidelines {
                background: rgba(30, 41, 59, 0.8) !important;
                border-color: rgba(255, 255, 255, 0.08) !important;
            }

            [data-bs-theme="dark"] .stat-list-item {
                border-color: rgba(255, 255, 255, 0.08) !important;
            }
        </style>
    @endpush
@endsection