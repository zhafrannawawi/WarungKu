<!-- Modal Edit Kasir -->
<div class="modal fade" id="modalEditKasir" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="/owner/akun-kasir" class="modal-content border-0 shadow">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="user_id" id="edit_user_id">
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-bold">Perbarui Akun Kasir</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="full_name" id="edit_fullname" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">Username <span class="text-danger">*</span></label>
                    <input type="text" name="user_name" id="edit_username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">Email</label>
                    <input type="email" name="email" id="edit_email" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">No. WhatsApp/HP</label>
                    <input type="tel" name="phone_number" id="edit_phone" class="form-control">
                </div>
                <div class="mb-3" id="edit_status_group">
                    <label class="form-label small fw-semibold text-muted">Status Akun</label>
                    <select name="is_active" id="edit_is_active" class="form-select">
                        <option value="1">Aktif</option>
                        <option value="0">Non-Aktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-top bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>