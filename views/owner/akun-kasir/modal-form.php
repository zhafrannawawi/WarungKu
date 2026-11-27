<!-- Modal Tambah & Edit Akun Kasir (Terpadu) -->
<div class="modal fade" id="modalFormKasir" tabindex="-1" aria-labelledby="modalFormKasirLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="/owner/akun-kasir" id="form-kasir" class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <input type="hidden" name="action" id="kasir-action" value="tambah">
            <input type="hidden" name="user_id" id="kasir-id">

            <!-- Modal Header -->
            <div class="modal-header border-0 px-4 pt-4 pb-3 bg-white align-items-start">
                <div class="d-flex align-items-center gap-3">
                    <div id="kasir-modal-icon" class="bg-primary bg-opacity-10 text-primary rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                        <i data-lucide="user-plus" class="icon-sm"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-1" id="modalFormKasirLabel">Tambah Akun Kasir Baru</h5>
                        <p class="text-secondary mb-0 small" id="kasir-modal-subtitle">Buat kredensial login untuk staf kasir</p>
                    </div>
                </div>
                <button type="button" class="btn-close mt-1" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body Form -->
            <div class="modal-body px-4 pt-2 pb-4">
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary mb-1">Nama Lengkap <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 text-secondary ps-3">
                            <i data-lucide="user" width="16" height="16"></i>
                        </span>
                        <input type="text" name="full_name" id="kasir-fullname" class="form-control bg-light border-0 py-2" placeholder="Contoh: Siti Aminah" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-sm-6">
                        <label class="form-label small fw-semibold text-secondary mb-1">Username <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 text-secondary ps-3">
                                <i data-lucide="at-sign" width="16" height="16"></i>
                            </span>
                            <input type="text" name="user_name" id="kasir-username" class="form-control bg-light border-0 py-2" placeholder="sitikasir" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label small fw-semibold text-secondary mb-1">No. WhatsApp / HP <span class="text-danger" id="kasir-phone-req">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 text-secondary ps-3">
                                <i data-lucide="phone" width="16" height="16"></i>
                            </span>
                            <input type="tel" name="phone_number" id="kasir-phone" class="form-control bg-light border-0 py-2" placeholder="0812-xxxx-xxxx" required>
                        </div>
                    </div>
                </div>

                <div class="mb-0" id="kasir-email-group">
                    <label class="form-label small fw-semibold text-secondary mb-1">Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 text-secondary ps-3">
                            <i data-lucide="mail" width="16" height="16"></i>
                        </span>
                        <input type="email" name="email" id="kasir-email" class="form-control bg-light border-0 py-2" placeholder="siti@gmail.com">
                    </div>
                    <small class="text-secondary d-block mt-2" id="kasir-email-hint" style="font-size: 0.75rem;">
                        Username dipakai kasir untuk login ke shift kasir.
                    </small>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top bg-light px-4 py-3 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary rounded-3 px-3 py-2 fw-medium" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary rounded-3 shadow-sm d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold">
                    <i data-lucide="check" width="16" height="16"></i>
                    <span id="kasir-submit-text">Simpan Akun</span>
                </button>
            </div>
        </form>
    </div>
</div>
