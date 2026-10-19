<!-- Two-Factor Authentication Modal -->
<div class="modal fade" id="twoFactorModal" tabindex="-1" aria-labelledby="twoFactorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
            <div class="modal-header border-bottom border-light-subtle bg-light bg-opacity-50 px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                        style="width: 36px; height: 36px;">
                        <x-fas-shield-alt style="width: 16px; height: 16px;" />
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="twoFactorModalLabel" style="font-size: 16px;">
                            {{ (Auth::user()->two_factor_enabled && Auth::user()->two_factor_confirmed_at) ? 'Manage Two-Factor Authentication' : 'Enable Two-Factor Authentication' }}
                        </h5>
                        <small class="text-muted" style="font-size: 12px;">Enhanced biometric or OTP cryptographic defense</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            @if(Auth::user()->two_factor_enabled && Auth::user()->two_factor_confirmed_at)
                <!-- 2FA Enabled View -->
                <div class="modal-body p-4 text-center">
                    <div class="mb-4">
                        <div class="rounded-circle bg-success bg-opacity-10 text-success mx-auto mb-3 d-flex align-items-center justify-content-center shadow-sm"
                            style="width: 72px; height: 72px;">
                            <x-fas-shield-alt style="width: 32px; height: 32px;" />
                        </div>
                        <h5 class="fw-bold text-success mb-1">Two-Factor Authentication is Active</h5>
                        <p class="text-muted small mb-0">
                            Current verification method:
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 fw-bold text-uppercase">
                                <x-fas-mobile-alt class="me-1" style="width: 10px; height: 10px;" />
                                {{ strtoupper(Auth::user()->two_factor_method ?? 'APP') }}
                            </span>
                        </p>
                    </div>

                    <div class="alert alert-info bg-primary bg-opacity-10 border-primary border-opacity-25 rounded-4 p-3 text-start small mb-4">
                        <div class="d-flex gap-2">
                            <x-fas-info-circle class="text-primary mt-1 flex-shrink-0" style="width: 14px; height: 14px;" />
                            <div class="text-dark">
                                Your account is currently protected with two-step verification. During login, a 6-digit one-time code from your authenticator app is required alongside your password.
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-outline-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#disableTwoFactorModal">
                            <x-fas-ban class="me-1" style="width: 12px; height: 12px;" /> Disable 2FA
                        </button>
                    </div>
                </div>
                <div class="modal-footer border-top border-light-subtle bg-light bg-opacity-25 px-4 py-3">
                    <button type="button" class="btn btn-light rounded-pill px-4 ms-auto" data-bs-dismiss="modal">Close</button>
                </div>
            @else
                <!-- 2FA Setup View -->
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary mx-auto mb-3 d-flex align-items-center justify-content-center shadow-sm"
                            style="width: 64px; height: 64px;">
                            <x-fas-shield-alt style="width: 28px; height: 28px;" />
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Protect Your Account with 2FA</h5>
                        <p class="text-muted small">Choose your preferred secondary verification mechanism below</p>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-dark mb-2">Select Verification Method</label>
                        <div class="list-group gap-2 border-0">
                            <label class="list-group-item rounded-3 border d-flex align-items-center gap-3 p-3 cursor-pointer" id="methodApp">
                                <input class="form-check-input flex-shrink-0 mt-0" type="radio" name="twoFactorMethod" value="app" checked>
                                <div class="rounded-3 bg-primary bg-opacity-10 text-primary p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <x-fas-mobile-alt style="width: 14px; height: 14px;" />
                                </div>
                                <div>
                                    <strong class="d-block text-dark small">Authenticator App (Recommended)</strong>
                                    <small class="text-muted" style="font-size: 11px;">Use Google Authenticator, Microsoft Authenticator, or Authy</small>
                                </div>
                            </label>
                            <label class="list-group-item rounded-3 border d-flex align-items-center gap-3 p-3 cursor-pointer" id="methodSms">
                                <input class="form-check-input flex-shrink-0 mt-0" type="radio" name="twoFactorMethod" value="sms">
                                <div class="rounded-3 bg-info bg-opacity-10 text-info p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <x-fas-sms style="width: 14px; height: 14px;" />
                                </div>
                                <div>
                                    <strong class="d-block text-dark small">SMS One-Time Passcode</strong>
                                    <small class="text-muted" style="font-size: 11px;">Receive one-time codes on your verified mobile phone</small>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="alert alert-warning bg-warning bg-opacity-10 border-warning border-opacity-25 rounded-3 p-3 small d-flex gap-2">
                        <x-fas-exclamation-triangle class="text-warning mt-0.5 flex-shrink-0" style="width: 14px; height: 14px;" />
                        <span class="text-dark">Once enabled, emergency backup recovery codes will be generated for safe keeping.</span>
                    </div>
                </div>
                <div class="modal-footer border-top border-light-subtle bg-light bg-opacity-25 px-4 py-3 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" onclick="enableTwoFactor()">
                        <x-fas-shield-alt class="me-1" style="width: 12px; height: 12px;" /> Continue Setup
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- QR Code Modal (for Authenticator App Setup) -->
<div class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
            <div class="modal-header border-bottom border-light-subtle bg-light bg-opacity-50 px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-primary bg-opacity-10 text-primary p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <x-fas-qrcode style="width: 14px; height: 14px;" />
                    </div>
                    <h6 class="modal-title fw-bold text-dark mb-0" id="qrCodeModalLabel" style="font-size: 15px;">Scan QR Code</h6>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div id="qrCodeContainer" class="mb-3 d-flex justify-content-center"></div>
                <p class="small text-muted mb-3">Scan this QR code using your authenticator application (Google Authenticator, Authy, etc.)</p>

                <div class="mb-3 text-start">
                    <label class="form-label small fw-semibold text-muted mb-1">Manual Entry Secret Key:</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm text-center fw-bold font-monospace bg-light" id="manualSecret" readonly>
                        <button class="btn btn-outline-secondary btn-sm" type="button" onclick="copySecret()" title="Copy to clipboard">
                            <x-fas-copy style="width: 12px; height: 12px;" /> Copy
                        </button>
                    </div>
                </div>

                <form id="verifyTwoFactorForm" class="text-start mt-4">
                    @csrf
                    <div class="mb-3">
                        <label for="verificationCode" class="form-label small fw-bold text-dark">Enter 6-Digit Authenticator Code:</label>
                        <input type="text"
                               class="form-control text-center fw-bold fs-5 rounded-3 letter-spacing-2"
                               id="verificationCode"
                               name="code"
                               placeholder="000 000"
                               maxlength="6"
                               pattern="\d{6}"
                               required>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top border-light-subtle bg-light bg-opacity-25 px-4 py-3 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" onclick="verifyTwoFactor()">
                    <x-fas-check class="me-1" style="width: 12px; height: 12px;" /> Verify & Activate
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Disable 2FA Modal -->
<div class="modal fade" id="disableTwoFactorModal" tabindex="-1" aria-labelledby="disableTwoFactorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden border-top border-4 border-danger">
            <div class="modal-header border-bottom border-light-subtle bg-danger bg-opacity-10 px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-danger bg-opacity-20 text-danger p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <x-fas-exclamation-triangle style="width: 14px; height: 14px;" />
                    </div>
                    <h6 class="modal-title fw-bold text-danger mb-0" id="disableTwoFactorModalLabel" style="font-size: 15px;">Disable Two-Factor Authentication</h6>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="rounded-circle bg-danger bg-opacity-10 text-danger mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                        <x-fas-shield-alt style="width: 28px; height: 28px;" />
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Turn off 2-Step Verification?</h5>
                    <p class="text-muted small">Disabling two-factor authentication leaves your administrative account protected by only your password.</p>
                </div>

                <form id="disableTwoFactorForm">
                    @csrf
                    <div class="mb-3">
                        <label for="disablePassword" class="form-label fw-semibold small text-dark">Enter your current password to confirm:</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><x-fas-lock style="width: 12px; height: 12px;" /></span>
                            <input type="password"
                                   class="form-control border-start-0 border-end-0"
                                   id="disablePassword"
                                   name="password"
                                   placeholder="Current account password"
                                   required>
                            <button class="btn btn-outline-secondary border-start-0 bg-white toggle-password text-muted" type="button" data-target="disablePassword">
                                <x-fas-eye style="width: 12px; height: 12px;" />
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top border-light-subtle bg-light bg-opacity-25 px-4 py-3 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger rounded-pill px-4 shadow-sm" onclick="disableTwoFactor()">
                    <x-fas-ban class="me-1" style="width: 12px; height: 12px;" /> Disable 2FA
                </button>
            </div>
        </div>
    </div>
</div>
