<!-- Modal Reset Password (Verifikasi Akun) -->
<div class="modal fade" id="modalResetPassword" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <form method="POST" action="/login" class="modal-content border-0 shadow">
            <input type="hidden" name="action" value="verify_reset">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Reset Password Akun</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2">
                <?php if (!empty($modal_error) && empty($reset_user_id)): ?>
                    <div class="alert alert-danger py-2 px-3 small rounded mb-3"><?= $modal_error ?></div>
                <?php endif; ?>
                <p class="text-muted small mb-3">Masukkan email dan No. WhatsApp untuk verifikasi akun.</p>
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">Email Terdaftar</label>
                    <input type="email" name="reset_email" class="form-control" placeholder="email@toko.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">No. WhatsApp/HP</label>
                    <input type="tel" name="reset_phone" class="form-control" placeholder="08xxxxxxxxxx" required>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Verifikasi Identitas</button>
                </div>
            </div>
        </form>
    </div>
</div>
