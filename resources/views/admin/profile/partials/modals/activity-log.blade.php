<!-- Activity Log Modal -->
<div class="modal fade" id="activityModal" tabindex="-1" aria-labelledby="activityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
            <div class="modal-header border-bottom border-light-subtle bg-light bg-opacity-50 px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center"
                        style="width: 36px; height: 36px;">
                        <x-fas-history style="width: 16px; height: 16px;" />
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="activityModalLabel" style="font-size: 16px;">Account Audit Trail</h5>
                        <small class="text-muted" style="font-size: 12px;">Recent security and administrative activity events</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="table-responsive rounded-3 border">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">
                            <tr>
                                <th class="ps-3 py-2.5">Security Event</th>
                                <th class="py-2.5">IP Address</th>
                                <th class="py-2.5">Timestamp</th>
                                <th class="pe-3 py-2.5 text-end">Status</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            <tr>
                                <td class="ps-3 py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-2 bg-success bg-opacity-10 text-success p-1.5 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                            <x-fas-sign-in-alt style="width: 12px; height: 12px;" />
                                        </div>
                                        <span class="fw-semibold text-dark">Administrative Sign In</span>
                                    </div>
                                </td>
                                <td>
                                    <code class="text-dark bg-light px-2 py-1 rounded small">{{ Auth::user()->last_login_ip ?? '127.0.0.1' }}</code>
                                </td>
                                <td class="text-muted">
                                    {{ Auth::user()->last_login_at ? Auth::user()->last_login_at->diffForHumans() : 'Current Session' }}
                                </td>
                                <td class="pe-3 text-end">
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 fw-bold">Success</span>
                                </td>
                            </tr>
                            @if(Auth::user()->password_changed_at)
                            <tr>
                                <td class="ps-3 py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-2 bg-warning bg-opacity-10 text-warning p-1.5 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                            <x-fas-key style="width: 12px; height: 12px;" />
                                        </div>
                                        <span class="fw-semibold text-dark">Password Updated</span>
                                    </div>
                                </td>
                                <td>
                                    <code class="text-dark bg-light px-2 py-1 rounded small">Security Event</code>
                                </td>
                                <td class="text-muted">
                                    {{ Auth::user()->password_changed_at->diffForHumans() }}
                                </td>
                                <td class="pe-3 text-end">
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 fw-bold">Verified</span>
                                </td>
                            </tr>
                            @endif
                            @if(Auth::user()->two_factor_enabled && Auth::user()->two_factor_confirmed_at)
                            <tr>
                                <td class="ps-3 py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-2 bg-primary bg-opacity-10 text-primary p-1.5 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                            <x-fas-shield-alt style="width: 12px; height: 12px;" />
                                        </div>
                                        <span class="fw-semibold text-dark">2FA Confirmed</span>
                                    </div>
                                </td>
                                <td>
                                    <code class="text-dark bg-light px-2 py-1 rounded small">Multi-Factor</code>
                                </td>
                                <td class="text-muted">
                                    {{ Auth::user()->two_factor_confirmed_at->diffForHumans() }}
                                </td>
                                <td class="pe-3 text-end">
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 fw-bold">Active</span>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top border-light-subtle bg-light bg-opacity-25 px-4 py-3 d-flex justify-content-between">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-outline-primary rounded-pill px-4" onclick="exportActivityLog()">
                    <x-fas-download class="me-1" style="width: 12px; height: 12px;" /> Export Full Log
                </button>
            </div>
        </div>
    </div>
</div>
