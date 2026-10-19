<!-- Rules & Regulations -->
<section id="section-rules" class="official-form-section">
    <div class="official-section-header">
        <h4>Rules & Regulations</h4>
    </div>

    <div class="info-surface p-4 rounded-4 shadow-sm border mb-4"
        style="background: linear-gradient(180deg, #fffcf5 0%, #ffffff 100%); border-color: #fce7c3 !important;">
        <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
            <h6 class="fw-bold mb-0 text-dark">Institutional Policies & Code of Ethics</h6>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="p-3 bg-white rounded-3 border h-100">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="fw-bold text-dark small">Institutional Authority</span>
                    </div>
                    <p class="small text-muted mb-0">Head of Institution retains final authority to admit or refuse
                        candidates. Management reserves right to amend provisions.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-white rounded-3 border h-100">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="fw-bold text-dark small">Fee Terms</span>
                    </div>
                    <p class="small text-muted mb-0">No fee concessions apply. Fees are strictly non-refundable even
                        upon mid-session withdrawal or admission cancellation.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-white rounded-3 border h-100">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="fw-bold text-dark small">Age Criteria & NEP 2020</span>
                    </div>
                    <p class="small text-muted mb-0">Candidate minimum age criteria strictly adheres to National
                        Education Policy (NEP) 2020 and State Board norms.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-white rounded-3 border h-100">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="fw-bold text-dark small">Original Documentation</span>
                    </div>
                    <p class="small text-muted mb-0">All verified credentials, Leaving Certificate, and original records
                        must be submitted for final enrollment validation.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-white rounded-3 border h-100">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="fw-bold text-dark small">Health Disclosure</span>
                    </div>
                    <p class="small text-muted mb-0">Parents are required to disclose any pre-existing medical or
                        psychological care needs prior to formal admission.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-white rounded-3 border h-100">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="fw-bold text-dark small">Attendance & Leaves</span>
                    </div>
                    <p class="small text-muted mb-0">Prior leave applications are mandatory. Unexplained absence over 30
                        continuous days results in registration removal.</p>
                </div>
            </div>
            <div class="col-12">
                <div class="p-3 bg-white rounded-3 border">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="fw-bold text-dark small">Zero-Tolerance Code of Conduct</span>
                    </div>
                    <p class="small text-muted mb-0">Zero tolerance for misconduct, bullying, violence, or disrespect.
                        Standard prescribed uniforms and disciplined grooming are mandatory.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="custom-checkbox-wrap d-flex align-items-start gap-3 p-4 border rounded-4 bg-white shadow-sm">
        <div class="flex-shrink-0 pt-1">
            <input class="form-check-input" type="checkbox" name="agreed_to_rules" id="agree_rules_checkbox" value="1"
                required style="width: 24px; height: 24px; cursor: pointer; border: 2px solid var(--gold-accent);">
            <input type="hidden" name="agree_declaration" id="hidden_agree_declaration" value="0">
        </div>
        <label class="form-check-label fw-semibold text-dark mb-0 cursor-pointer" for="agree_rules_checkbox"
            style="font-size: 0.95rem; line-height: 1.6;">
            I have carefully read, understood, and solemnly agree to abide by all the <strong
                class="text-primary">Admission Rules, Regulations & Institutional Policies</strong> of
            {{ config('app.school_name') }}.
            I certify that the information entered is authentic and accurate to the best of my knowledge.
        </label>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkbox = document.getElementById('agree_rules_checkbox');
            const declarationInput = document.getElementById('hidden_agree_declaration');

            if (checkbox && declarationInput) {
                checkbox.addEventListener('change', function () {
                    declarationInput.value = this.checked ? '1' : '0';
                    console.log('Syncing agree_declaration to:', declarationInput.value);
                });

                // Set initial value
                declarationInput.value = checkbox.checked ? '1' : '0';
            }
        });
    </script>
</section>