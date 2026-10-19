<!-- Change Photo Modal -->
<div class="modal fade" id="changePhotoModal" tabindex="-1" aria-labelledby="changePhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
            <div class="modal-header border-bottom border-light-subtle bg-light bg-opacity-50 px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                        style="width: 36px; height: 36px;">
                        <x-fas-camera style="width: 16px; height: 16px;" />
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="changePhotoModalLabel" style="font-size: 16px;">Change Profile Photo</h5>
                        <small class="text-muted" style="font-size: 12px;">Upload a professional avatar picture</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="avatar-preview-container mb-4 position-relative d-inline-block">
                    <div id="photoPreview" class="rounded-circle shadow-sm mx-auto overflow-hidden d-flex align-items-center justify-content-center text-white fw-bold"
                        style="width: 120px; height: 120px; border: 4px solid var(--card-bg, #ffffff); box-shadow: 0 8px 24px rgba(67, 97, 238, 0.2); background: linear-gradient(135deg, #4361ee 0%, #3f37c9 100%); font-size: 2.5rem;">
                        @if(Auth::user()->profile_photo_path)
                            <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}"
                                alt="{{ Auth::user()->name }}"
                                class="w-100 h-100"
                                style="object-fit: cover;">
                        @else
                            {{ Auth::user()->initials }}
                        @endif
                    </div>
                </div>

                <form id="photoForm" enctype="multipart/form-data" class="text-start">
                    @csrf
                    <div class="mb-3">
                        <label for="profile_photo" class="form-label fw-semibold small text-dark mb-2">Select Image File</label>
                        <input class="form-control rounded-3"
                               type="file"
                               id="profile_photo"
                               name="profile_photo"
                               accept="image/jpeg,image/png,image/gif,image/webp"
                               onchange="previewImage(event)">
                        <div class="form-text small text-muted mt-2 d-flex align-items-center gap-1">
                            <x-fas-info-circle style="width: 12px; height: 12px;" />
                            <span>Max size: 2MB. Supported formats: JPG, PNG, GIF, WebP</span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top border-light-subtle bg-light bg-opacity-25 px-4 py-3 d-flex justify-content-between">
                @if(Auth::user()->profile_photo_path)
                    <button type="button" class="btn btn-outline-danger rounded-pill px-3 btn-sm" onclick="removePhoto()">
                        <x-fas-trash-alt class="me-1" style="width: 12px; height: 12px;" /> Remove Photo
                    </button>
                @else
                    <div></div>
                @endif
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" onclick="uploadPhoto()">
                        <x-fas-upload class="me-1" style="width: 12px; height: 12px;" /> Upload & Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
