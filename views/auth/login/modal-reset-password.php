<!-- Modal Reset Password (Verifikasi Akun) -->
<div class="modal fade" id="modalResetPassword" tabindex="-1" aria-labelledby="modalResetPasswordLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <form method="POST" action="/login" class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <input type="hidden" name="action" value="verify_reset">

            <!-- Modal Header -->
            <div class="modal-header border-0 px-4 pt-4 pb-3 bg-white align-items-start">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                        <i data-lucide="key-round" class="icon-sm"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-1" id="modalResetPasswordLabel">Atur Ulang Password</h5>
                        <p class="text-secondary mb-0 small">Verifikasi email &amp; nomor WhatsApp terdaftar</p>
                    </div>
                </div>
                <button type="button" class="btn-close mt-1" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body px-4 pt-2 pb-4">
                <?php if (!empty($modal_error) && empty($reset_user_id)): ?>
                    <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3"><?= $modal_error ?></div>
                <?php endif; ?>

                <div class="py-2 px-3 bg-primary bg-opacity-10 text-dark border border-primary border-opacity-10 rounded-3 d-flex align-items-start gap-2 mb-3">
                    <i data-lucide="shield-check" class="text-primary flex-shrink-0 mt-0.5" style="width: 18px; height: 18px;"></i>
                    <span class="small text-secondary" style="line-height: 1.4;">Masukkan email dan nomor HP yang terdaftar pada akun warung Anda untuk verifikasi identitas.</span>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary mb-1">Email Terdaftar <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 text-secondary ps-3">
                            <i data-lucide="mail" width="16" height="16"></i>
                        </span>
                        <input type="email" name="reset_email" class="form-control bg-light border-0 py-2" placeholder="nama@email.com" required>
                    </div>
                </div>

                <div class="mb-0">
                    <label class="form-label small fw-semibold text-secondary mb-1">Nomor Handphone (WhatsApp) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 text-secondary ps-3">
                            <i data-lucide="phone" width="16" height="16"></i>
                        </span>
                        <input type="tel" name="reset_phone" class="form-control bg-light border-0 py-2" placeholder="Contoh: 081234567890" required>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top bg-light px-4 py-3 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary rounded-3 px-3 py-2 fw-medium" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary rounded-3 shadow-sm d-inline-flex align-items-center gap-2 px-4 py-2 fw-semibold">
                    <i data-lucide="check" width="16" height="16"></i>
                    <span>Verifikasi Identitas</span>
                </button>
            </div>
        </form>
    </div>
</div>
