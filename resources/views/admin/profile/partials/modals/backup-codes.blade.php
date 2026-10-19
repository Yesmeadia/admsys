<!-- Backup Codes Modal -->
<div class="modal fade" id="backupCodesModal" tabindex="-1" aria-labelledby="backupCodesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
            <div class="modal-header border-bottom border-light-subtle bg-light bg-opacity-50 px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center"
                        style="width: 36px; height: 36px;">
                        <x-fas-key style="width: 16px; height: 16px;" />
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="backupCodesModalLabel" style="font-size: 16px;">Recovery Backup Codes</h5>
                        <small class="text-muted" style="font-size: 12px;">One-time emergency authentication passcodes</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                @if(Auth::user()->two_factor_enabled && Auth::user()->two_factor_confirmed_at)
                    <div class="alert alert-warning bg-warning bg-opacity-10 border-warning border-opacity-25 rounded-3 p-3 small mb-3 d-flex gap-2">
                        <x-fas-exclamation-triangle class="text-warning mt-0.5 flex-shrink-0" style="width: 14px; height: 14px;" />
                        <span class="text-dark">Store these secret codes in a password manager or physical vault. Each passcode can only be consumed once.</span>
                    </div>

                    <div id="backupCodesList" class="mb-4">
                        @if(Auth::user()->two_factor_backup_codes && count(Auth::user()->two_factor_backup_codes) > 0)
                            <div class="row g-2">
                                @foreach(Auth::user()->two_factor_backup_codes as $code)
                                    <div class="col-6">
                                        <div class="p-2 bg-light rounded-3 text-center font-monospace fw-bold text-dark border small letter-spacing-1">
                                            {{ $code }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-3 text-muted small">
                                <x-fas-info-circle class="me-1" /> No active backup codes generated yet.
                            </div>
                        @endif
                    </div>

                    <div class="text-center pt-2">
                        <button type="button" class="btn btn-outline-warning btn-sm rounded-pill px-3 fw-semibold text-dark" onclick="regenerateBackupCodes()">
                            <x-fas-redo class="me-1" style="width: 11px; height: 11px;" /> Regenerate New Codes
                        </button>
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="rounded-circle bg-light text-muted mx-auto mb-3 d-flex align-items-center justify-content-center shadow-xs" style="width: 64px; height: 64px;">
                            <x-fas-shield-alt style="width: 28px; height: 28px;" />
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Two-Factor Authentication Inactive</h6>
                        <p class="text-muted small mb-3">Backup recovery passcodes are only generated when 2FA is enabled.</p>
                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#twoFactorModal">
                            <x-fas-shield-alt class="me-1" style="width: 11px; height: 11px;" /> Configure 2FA Now
                        </button>
                    </div>
                @endif
            </div>
            <div class="modal-footer border-top border-light-subtle bg-light bg-opacity-25 px-4 py-3 d-flex justify-content-between">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                @if(Auth::user()->two_factor_enabled && Auth::user()->two_factor_confirmed_at && Auth::user()->two_factor_backup_codes)
                    <button type="button" class="btn btn-warning text-dark fw-bold rounded-pill px-4 shadow-sm" onclick="downloadBackupCodes()">
                        <x-fas-download class="me-1" style="width: 12px; height: 12px;" /> Download .txt
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
