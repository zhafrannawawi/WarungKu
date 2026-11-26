<!-- Modal Edit Kasir -->
<div class="modal fade" id="modalEditKasir" tabindex="-1" aria-labelledby="modalEditKasirLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="/owner/akun-kasir" class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="user_id" id="edit_user_id">

            <!-- Modal Header -->
            <div class="modal-header border-0 px-4 pt-4 pb-3 bg-white align-items-start">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                        <i data-lucide="user-cog" class="icon-sm"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-1" id="modalEditKasirLabel">Perbarui Akun Kasir</h5>
                        <p class="text-secondary mb-0 small">Edit data profil dan status akses kasir</p>
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
                        <input type="text" name="full_name" id="edit_fullname" class="form-control bg-light border-0 py-2" placeholder="Nama lengkap kasir" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-sm-6">
                        <label class="form-label small fw-semibold text-secondary mb-1">Username <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 text-secondary ps-3">
                                <i data-lucide="at-sign" width="16" height="16"></i>
                            </span>
                            <input type="text" name="user_name" id="edit_username" class="form-control bg-light border-0 py-2" placeholder="Username login" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label small fw-semibold text-secondary mb-1">No. WhatsApp / HP</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 text-secondary ps-3">
                                <i data-lucide="phone" width="16" height="16"></i>
                            </span>
                            <input type="tel" name="phone_number" id="edit_phone" class="form-control bg-light border-0 py-2" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary mb-1">Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 text-secondary ps-3">
                            <i data-lucide="mail" width="16" height="16"></i>
                        </span>
                        <input type="email" name="email" id="edit_email" class="form-control bg-light border-0 py-2" placeholder="nama@email.com">
                    </div>
                </div>

                <div class="mb-0" id="edit_status_group">
                    <label class="form-label small fw-semibold text-secondary mb-1">Status Akun</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 text-secondary ps-3">
                            <i data-lucide="shield-check" width="16" height="16"></i>
                        </span>
                        <select name="is_active" id="edit_is_active" class="form-select bg-light border-0 py-2 ps-2" style="padding-right: 2.25rem;">
                            <option value="1">Aktif</option>
                            <option value="0">Non-Aktif</option>
                        </select>
                    </div>
                    <small class="text-secondary d-block mt-2" style="font-size: 0.75rem;">
                        Kasir Non-Aktif tidak dapat login ke shift kasir.
                    </small>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top bg-light px-4 py-3 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary rounded-3 px-3 py-2 fw-medium" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary rounded-3 shadow-sm d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold">
                    <span class="bg-white bg-opacity-25 rounded-2 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                        <i data-lucide="check" class="icon-xs"></i>
                    </span>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>