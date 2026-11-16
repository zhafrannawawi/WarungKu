<!-- Modal Reset Password / Verifikasi Akun (Tahap 1) -->
<div class="modal fade" id="modalResetPassword" tabindex="-1" aria-labelledby="modalResetLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content border-0 shadow">
            
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="modalResetLabel">Reset Password Akun</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body pt-2">

                <?php if (empty($reset_user_id) && !empty($modal_error)): ?>
                    <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3">
                        <i data-lucide="alert-circle" class="icon-xs me-1"></i>
                        <?= htmlspecialchars($modal_error) ?>
                    </div>
                <?php endif; ?>

                <p class="text-muted small mb-3">Masukkan email dan nomor WhatsApp akun Anda untuk verifikasi identitas.</p>
                
                <form method="post" action="/login">
                    <input type="hidden" name="action" value="verify_reset">
                    
                    <div class="mb-3">
                        <label for="reset_email" class="form-label small fw-semibold text-muted mb-1">Email Terdaftar</label>
                        <input type="email" id="reset_email" name="reset_email" class="form-control form-control-standard" placeholder="nama@email.com" required>
                    </div>

                    <div class="mb-4">
                        <label for="reset_phone" class="form-label small fw-semibold text-muted mb-1">No. WhatsApp/HP</label>
                        <input type="tel" id="reset_phone" name="reset_phone" class="form-control form-control-standard" placeholder="08xxxxxxxxxx" required>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light btn-standard" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-standard">
                            <i data-lucide="check" class="icon-xs me-1"></i> Verifikasi Akun
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

<?php if (empty($reset_user_id) && !empty($modal_error)): ?>
<script>
    // Otomatis buka modal Verifikasi kembali jika verifikasi gagal / ada error
    document.addEventListener("DOMContentLoaded", function () {
        var modal_element = document.getElementById('modalResetPassword');
        if (modal_element && window.bootstrap) {
            var modal_instance = new bootstrap.Modal(modal_element);
            modal_instance.show();
        }
    });
</script>
<?php endif; ?>
