<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden border-top border-4 border-danger">
            <div class="modal-header border-bottom border-light-subtle bg-danger bg-opacity-10 px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-danger bg-opacity-20 text-danger d-flex align-items-center justify-content-center"
                        style="width: 36px; height: 36px;">
                        <x-fas-exclamation-triangle style="width: 16px; height: 16px;" />
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-danger mb-0" id="deleteAccountModalLabel" style="font-size: 16px;">Delete Account</h5>
                        <small class="text-muted" style="font-size: 12px;">Irreversible administrative action</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-danger bg-danger bg-opacity-10 border-danger border-opacity-25 rounded-4 p-3 mb-4 d-flex gap-3 align-items-center">
                    <div class="rounded-circle bg-danger text-white p-2 d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width: 40px; height: 40px;">
                        <x-fas-trash-alt style="width: 16px; height: 16px;" />
                    </div>
                    <div>
                        <h6 class="fw-bold text-danger mb-1" style="font-size: 14px;">Danger: Permanent Account Erasure</h6>
                        <p class="small text-muted mb-0" style="font-size: 12px;">
                            This action cannot be undone. Your administrative credentials, active sessions, and personal profile data will be permanently removed.
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.destroy') }}" id="deleteAccountForm">
                    @csrf
                    @method('DELETE')

                    <div class="mb-3">
                        <label for="confirmDelete" class="form-label fw-semibold small text-dark">
                            Type <code class="px-2 py-1 bg-light text-danger rounded border fw-bold">DELETE {{ Auth::user()->email }}</code> to confirm:
                        </label>
                        <input type="text"
                               class="form-control rounded-3"
                               id="confirmDelete"
                               name="confirm"
                               placeholder="DELETE {{ Auth::user()->email }}"
                               style="text-transform: none !important;">
                    </div>

                    <div class="form-check p-3 bg-light bg-opacity-50 rounded-3 border">
                        <input class="form-check-input ms-0 me-2" type="checkbox" id="understandConsequences" required>
                        <label class="form-check-label small text-danger fw-semibold" for="understandConsequences">
                            I understand that this action is irreversible and will delete my administrator access immediately.
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top border-light-subtle bg-light bg-opacity-25 px-4 py-3 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger rounded-pill px-4 shadow-sm fw-semibold" onclick="confirmDelete()">
                    <x-fas-trash-alt class="me-1" style="width: 12px; height: 12px;" /> Permanently Delete
                </button>
            </div>
        </div>
    </div>
</div>
