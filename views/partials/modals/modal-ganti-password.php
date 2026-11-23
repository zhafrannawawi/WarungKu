<!-- Modal Ganti Password -->
<div class="modal fade" id="modalGantiPassword" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <form method="POST" action="/owner/change-password" class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <i data-lucide="key" class="text-primary"></i>
                    <h6 class="modal-title fw-bold mb-0">Ganti Password</h6>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">Password Lama <span class="text-danger">*</span></label>
                    <input type="password" name="old_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">Password Baru <span class="text-danger">*</span></label>
                    <input type="password" name="new_password" class="form-control" minlength="6" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer border-top bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan Password</button>
            </div>
        </form>
    </div>
</div>
