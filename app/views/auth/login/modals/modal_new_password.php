<!-- Modal Buat Password Baru (Tahap 2) -->
<div class="modal fade" id="modalNewPassword" tabindex="-1" aria-labelledby="modalNewPasswordLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content border-0 shadow">
            
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="modalNewPasswordLabel">Buat Password Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body pt-2">

                <?php if (!empty($reset_user_id) && !empty($modal_error)): ?>
                    <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3">
                        <i data-lucide="alert-circle" class="icon-xs me-1"></i>
                        <?= htmlspecialchars($modal_error) ?>
                    </div>
                <?php endif; ?>

                <p class="text-muted small mb-3">Akun terverifikasi! Masukkan password baru Anda di bawah ini.</p>

                <form method="post" action="/login">
                    <input type="hidden" name="action" value="save_new_password">
                    <input type="hidden" name="reset_user_id" value="<?= htmlspecialchars((string)($reset_user_id ?? '')) ?>">

                    <div class="mb-3">
                        <label for="new_password" class="form-label small fw-semibold text-muted mb-1">Password Baru</label>
                        <input type="password" id="new_password" name="new_password" class="form-control form-control-standard" placeholder="Min. 6 karakter" minlength="6" required autofocus>
                    </div>

                    <div class="mb-4">
                        <label for="confirm_new_password" class="form-label small fw-semibold text-muted mb-1">Konfirmasi Password Baru</label>
                        <input type="password" id="confirm_new_password" name="confirm_new_password" class="form-control form-control-standard" placeholder="Ulangi password baru" required>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light btn-standard" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-standard">
                            <i data-lucide="check-circle" class="icon-xs me-1"></i> Simpan Password Baru
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

<?php if (!empty($reset_user_id)): ?>
<script>
    // Otomatis buka modal Buat Password Baru jika user sudah terverifikasi
    document.addEventListener("DOMContentLoaded", function () {
        var modal_element = document.getElementById('modalNewPassword');
        if (modal_element && window.bootstrap) {
            var modal_instance = new bootstrap.Modal(modal_element);
            modal_instance.show();
        }
    });
</script>
<?php endif; ?>
