<!-- Modal Tambah Kasir -->
<div class="modal fade" id="modalTambahKasir" tabindex="-1" aria-labelledby="modalTambahKasirLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="/owner/akun-kasir" class="modal-content border-0 shadow-sm rounded-4 overflow-hidden">
            <input type="hidden" name="action" value="tambah">

            <!-- Modal Header -->
            <div class="modal-header border-bottom px-4 py-3 bg-white">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 bg-primary-subtle text-primary rounded-3">
                        <i data-lucide="user-plus" class="icon-sm"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-dark mb-0" id="modalTambahKasirLabel">Tambah Akun Kasir Baru</h6>
                        <small class="text-muted" style="font-size: 0.75rem;">Buat kredensial login untuk staf kasir</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body Form -->
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="full_name" class="form-control" placeholder="Contoh: Siti Aminah" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-muted mb-1">Username <span class="text-danger">*</span></label>
                        <input type="text" name="user_name" class="form-control" placeholder="sitikasir" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-muted mb-1">No. WhatsApp / HP</label>
                        <input type="tel" name="phone_number" class="form-control" placeholder="0812-xxxx-xxxx" required>
                    </div>
                </div>

                <div class="mb-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="siti@gmail.com">
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top bg-light px-4 py-3 d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-2 rounded-2 fw-medium" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="submit" class="btn btn-sm btn-primary px-4 py-2 rounded-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm">
                    <i data-lucide="check" class="icon-sm"></i>
                    <span>Simpan Akun</span>
                </button>
            </div>
        </form>
    </div>
</div>
