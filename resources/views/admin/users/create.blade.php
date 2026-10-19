@extends('admin.layout')

@section('title', 'Add New User')
@section('page-title', 'Add New User')
@section('page-subtitle', 'Create a new administrator or staff account')

@section('content')
    <div class="row fade-in">
        <div class="col-12">
            <div class="premium-card p-0 overflow-hidden position-relative mb-4"
                style="background: linear-gradient(135deg, rgba(67, 97, 238, 0.05), rgba(67, 97, 238, 0.02)); border: 1px solid rgba(67, 97, 238, 0.1);">
                <div class="p-4 d-flex justify-content-between align-items-center bg-white bg-opacity-25 border-bottom"
                    style="border-bottom: 1px solid rgba(0,0,0,0.05) !important;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                            style="width: 44px; height: 44px;">
                            <x-fas-user-plus class="fa-lg" />
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">User Information</h5>
                            <small class="text-muted">Enter credentials and assign access permissions</small>
                        </div>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-white bg-white bg-opacity-50 btn-sm rounded-pill px-3 border shadow-sm">
                        <x-fas-arrow-left class="me-1" /> Back to Users
                    </a>
                </div>

                <div class="p-4 p-lg-5">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <!-- Account Details Section -->
                        <div class="mb-4">
                            <h6 class="text-uppercase fw-bold text-primary small mb-3" style="letter-spacing: 0.5px;">
                                <x-fas-id-card class="me-2" /> Account Details
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small required">Full Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted"><x-fas-user /></span>
                                        <input type="text" class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror"
                                            name="name" value="{{ old('name') }}" required placeholder="e.g. John Doe">
                                    </div>
                                    @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small required">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted"><x-fas-envelope /></span>
                                        <input type="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email') }}" required placeholder="e.g. user@institution.edu">
                                    </div>
                                    @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small required">System Role</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted"><x-fas-user-shield /></span>
                                        <select class="form-select border-start-0 ps-0 @error('role') is-invalid @enderror" name="role" required>
                                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Office Staff (Applications, PDF, Status)</option>
                                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator (Full Access & Management)</option>
                                        </select>
                                    </div>
                                    <div class="form-text small text-muted">Administrators have full administrative control, while staff can manage applications.</div>
                                    @error('role') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted"><x-fas-phone /></span>
                                        <input type="text" class="form-control border-start-0 ps-0 @error('phone') is-invalid @enderror"
                                            name="phone" value="{{ old('phone') }}" placeholder="+91 9876543210">
                                    </div>
                                    <div class="form-text small text-muted">Optional contact number for notifications and verification.</div>
                                    @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 opacity-10">

                        <!-- Security & Passwords Section -->
                        <div class="mb-4">
                            <h6 class="text-uppercase fw-bold text-primary small mb-3" style="letter-spacing: 0.5px;">
                                <x-fas-lock class="me-2" /> Security Credentials
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small required">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted"><x-fas-key /></span>
                                        <input type="password" class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                                            name="password" required placeholder="Minimum 8 characters">
                                    </div>
                                    @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small required">Confirm Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted"><x-fas-check-circle /></span>
                                        <input type="password" class="form-control border-start-0 ps-0"
                                            name="password_confirmation" required placeholder="Re-type password">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end align-items-center gap-3 pt-3 border-top"
                            style="border-top: 1px solid rgba(0,0,0,0.05) !important;">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-light rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                                <x-fas-check class="me-2" /> Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection