<!-- Modal Ganti Password -->
<div class="modal fade" id="modalGantiPassword" tabindex="-1" aria-labelledby="modalGantiPasswordLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <form method="POST" action="/owner/change-password" class="modal-content border-0 shadow-sm rounded-4 overflow-hidden">
            <!-- Modal Header -->
            <div class="modal-header border-bottom px-4 py-3 bg-white">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 bg-primary-subtle text-primary rounded-3">
                        <i data-lucide="key" class="icon-sm"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-dark mb-0" id="modalGantiPasswordLabel">Ganti Password</h6>
                        <small class="text-muted" style="font-size: 0.75rem;">Perbarui kata sandi login akun</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Password Saat Ini <span class="text-danger">*</span></label>
                    <input type="password" name="old_password" class="form-control" placeholder="Masukkan password lama" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Password Baru <span class="text-danger">*</span></label>
                    <input type="password" name="new_password" class="form-control" minlength="6" placeholder="Minimal 6 karakter" required>
                </div>

                <div class="mb-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password baru" required>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top bg-light px-4 py-3 d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-2 rounded-2 fw-medium" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="submit" class="btn btn-sm btn-primary px-4 py-2 rounded-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm">
                    <i data-lucide="check" class="icon-sm"></i>
                    <span>Simpan Password</span>
                </button>
            </div>
        </form>
    </div>
</div>
