<!-- Modal Buat Password Baru (Jika Terverifikasi) -->
<div class="modal fade" id="modalNewPassword" tabindex="-1" aria-labelledby="modalNewPasswordLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <form method="POST" action="/login" class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <input type="hidden" name="action" value="save_new_password">
            <input type="hidden" name="reset_user_id" id="reset_user_id" value="<?= $reset_user_id ?? '' ?>">

            <!-- Modal Header -->
            <div class="modal-header border-0 px-4 pt-4 pb-3 bg-white align-items-start">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success bg-opacity-10 text-success rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                        <i data-lucide="lock-keyhole" class="icon-sm"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-1" id="modalNewPasswordLabel">Buat Password Baru</h5>
                        <p class="text-secondary mb-0 small">Akun terverifikasi, atur kata sandi baru Anda</p>
                    </div>
                </div>
                <button type="button" class="btn-close mt-1" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body px-4 pt-2 pb-4">
                <?php if (!empty($modal_error) && !empty($reset_user_id)): ?>
                    <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3"><?= $modal_error ?></div>
                <?php endif; ?>

                <div class="py-2 px-3 bg-success bg-opacity-10 text-dark border border-success border-opacity-10 rounded-3 d-flex align-items-center gap-2 mb-3">
                    <i data-lucide="check-circle-2" class="text-success flex-shrink-0" style="width: 18px; height: 18px;"></i>
                    <span class="small text-secondary">Identitas berhasil diverifikasi! Masukkan password baru minimal 6 karakter.</span>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary mb-1">Password Baru <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <input type="password" name="new_password" class="form-control bg-light border-0 py-2 pe-5" minlength="6" placeholder="Minimal 6 karakter" required autofocus>
                        <button type="button" class="btn-toggle-pass" title="Tampilkan/Sembunyikan Password" onclick="togglePasswordVisibility(this.previousElementSibling, this)">
                            <i data-lucide="eye" class="icon-xs"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-0">
                    <label class="form-label small fw-semibold text-secondary mb-1">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <input type="password" name="confirm_new_password" class="form-control bg-light border-0 py-2 pe-5" placeholder="Ulangi password baru" required>
                        <button type="button" class="btn-toggle-pass" title="Tampilkan/Sembunyikan Password" onclick="togglePasswordVisibility(this.previousElementSibling, this)">
                            <i data-lucide="eye" class="icon-xs"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top bg-light px-4 py-3 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary rounded-3 px-3 py-2 fw-medium" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary rounded-3 shadow-sm d-inline-flex align-items-center gap-2 px-4 py-2 fw-semibold">
                    <i data-lucide="check" width="16" height="16"></i>
                    <span>Simpan Password Baru</span>
                </button>
            </div>
        </form>
    </div>
</div>
