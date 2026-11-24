<!-- Modal Reset Password (Verifikasi Akun) -->
<div class="modal fade" id="modalResetPassword" tabindex="-1" aria-labelledby="modalResetPasswordLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <form method="POST" action="/login" class="modal-content border-0 shadow-sm rounded-4 overflow-hidden">
            <input type="hidden" name="action" value="verify_reset">

            <!-- Modal Header -->
            <div class="modal-header border-bottom px-4 py-3 bg-white">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 bg-primary-subtle text-primary rounded-3">
                        <i data-lucide="shield-alert" class="icon-sm"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-dark mb-0" id="modalResetPasswordLabel">Atur Ulang Password</h6>
                        <small class="text-muted" style="font-size: 0.75rem;">Verifikasi email dan nomor WhatsApp</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <?php if (!empty($modal_error) && empty($reset_user_id)): ?>
                    <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3"><?= $modal_error ?></div>
                <?php endif; ?>

                <p class="small text-muted mb-3">Masukkan email dan nomor HP yang terdaftar pada akun warung Anda untuk verifikasi identitas.</p>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Email Terdaftar <span class="text-danger">*</span></label>
                    <input type="email" name="reset_email" class="form-control" placeholder="nama@email.com" required>
                </div>

                <div class="mb-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Nomor Handphone (WhatsApp) <span class="text-danger">*</span></label>
                    <input type="tel" name="reset_phone" class="form-control" placeholder="Contoh: 081234567890" required>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top bg-light px-4 py-3 d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-2 rounded-2 fw-medium" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="submit" class="btn btn-sm btn-primary px-4 py-2 rounded-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm">
                    <i data-lucide="check" class="icon-sm"></i>
                    <span>Verifikasi Identitas</span>
                </button>
            </div>
        </form>
    </div>
</div>
