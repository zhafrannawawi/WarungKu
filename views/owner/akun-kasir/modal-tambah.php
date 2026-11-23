<!-- Modal Tambah Kasir -->
<div class="modal fade" id="modalTambahKasir" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="/owner/akun-kasir" class="modal-content border-0 shadow">
            <input type="hidden" name="action" value="tambah">
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-bold">Tambah Akun Kasir Baru</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="full_name" class="form-control" placeholder="Contoh: Siti Aminah" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">Username<span class="text-danger">*</span></label>
                    <input type="text" name="user_name" class="form-control" placeholder="Contoh: sitikasir" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">Email<span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="siti@gmail.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">No. WhatsApp/HP<span class="text-danger">*</span></label>
                    <input type="tel" name="phone_number" class="form-control" placeholder="08xxxxxxxxxx" required>
                </div>
            </div>
            <div class="modal-footer border-top bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan Akun</button>
            </div>
        </form>
    </div>
</div>