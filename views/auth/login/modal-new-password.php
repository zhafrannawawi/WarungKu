<!-- Modal Buat Password Baru (Jika Terverifikasi) -->
<div class="modal fade" id="modalNewPassword" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <form method="POST" action="/login" class="modal-content border-0 shadow">
            <input type="hidden" name="action" value="save_new_password">
            <input type="hidden" name="reset_user_id" id="reset_user_id" value="<?= $reset_user_id ?? '' ?>">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Buat Password Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2">
                <?php if (!empty($modal_error) && !empty($reset_user_id)): ?>
                    <div class="alert alert-danger py-2 px-3 small rounded mb-3"><?= $modal_error ?></div>
                <?php endif; ?>
                <p class="text-muted small mb-3">Akun terverifikasi! Masukkan password baru minimal 6 karakter.</p>
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">Password Baru</label>
                    <input type="password" name="new_password" class="form-control" minlength="6" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">Konfirmasi Password Baru</label>
                    <input type="password" name="confirm_new_password" class="form-control" required>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan Password Baru</button>
                </div>
            </div>
        </form>
    </div>
</div>
