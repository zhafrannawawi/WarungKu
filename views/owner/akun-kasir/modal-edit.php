<!-- Modal Edit Kasir -->
<div class="modal fade" id="modalEditKasir" tabindex="-1" aria-labelledby="modalEditKasirLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="/owner/akun-kasir" class="modal-content border-0 shadow-sm rounded-4 overflow-hidden">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="user_id" id="edit_user_id">

            <!-- Modal Header -->
            <div class="modal-header border-bottom px-4 py-3 bg-white">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 bg-primary-subtle text-primary rounded-3">
                        <i data-lucide="user-check" class="icon-sm"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-dark mb-0" id="modalEditKasirLabel">Perbarui Akun Kasir</h6>
                        <small class="text-muted" style="font-size: 0.75rem;">Edit data profil dan status akses kasir</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body Form -->
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="full_name" id="edit_fullname" class="form-control" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-muted mb-1">Username <span class="text-danger">*</span></label>
                        <input type="text" name="user_name" id="edit_username" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-muted mb-1">No. WhatsApp / HP</label>
                        <input type="tel" name="phone_number" id="edit_phone" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Email</label>
                    <input type="email" name="email" id="edit_email" class="form-control">
                </div>

                <div class="mb-2" id="edit_status_group">
                    <label class="form-label small fw-semibold text-muted mb-1">Status Akun</label>
                    <select name="is_active" id="edit_is_active" class="form-select">
                        <option value="1">Aktif</option>
                        <option value="0">Non-Aktif</option>
                    </select>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top bg-light px-4 py-3 d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-2 rounded-2 fw-medium" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="submit" class="btn btn-sm btn-primary px-4 py-2 rounded-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm">
                    <i data-lucide="check" class="icon-sm"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>
