<!-- Modal Edit Akun Kasir -->
<div class="modal fade" id="modalEditAkun" tabindex="-1" aria-labelledby="modalEditAkunLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-sm rounded-4 overflow-hidden">
            <!-- Modal Header -->
            <div class="modal-header border-bottom px-4 py-3 bg-white">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 bg-warning-subtle text-warning rounded-3">
                        <i data-lucide="user-pen" class="icon-sm"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-dark mb-0" id="modalEditAkunLabel">Edit Akun Kasir</h6>
                        <small class="text-muted" style="font-size: 0.75rem;">Ubah data staf kasir</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body Form -->
            <div class="modal-body p-4">
                <form id="form-edit-akun" method="post" action="/owner/akun-kasir/update">
                    <input type="hidden" id="edit-user-id" name="user_id">

                    <div class="mb-3">
                        <label for="edit-name" class="form-label small fw-semibold text-muted mb-1">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" id="edit-name" name="full_name" class="form-control form-control-standard" placeholder="Contoh: Siti Aminah" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label for="edit-username" class="form-label small fw-semibold text-muted mb-1">Username <span class="text-danger">*</span></label>
                            <input type="text" id="edit-username" name="user_name" class="form-control form-control-standard" placeholder="sitiaminah" required>
                        </div>
                        <div class="col-6">
                            <label for="edit-phone" class="form-label small fw-semibold text-muted mb-1">No. WhatsApp / HP</label>
                            <input type="tel" id="edit-phone" name="phone_number" class="form-control form-control-standard" placeholder="0812-xxxx-xxxx">
                        </div>
                    </div>

                    <div class="mb-1">
                        <label class="form-label small fw-semibold text-muted mb-1">Status</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_active" id="edit-active" value="1">
                                <label class="form-check-label small" for="edit-active">Aktif</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_active" id="edit-inactive" value="0">
                                <label class="form-check-label small" for="edit-inactive">Non-Aktif</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top bg-light px-4 py-3 d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-2 rounded-btn-sm fw-medium" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="submit" form="form-edit-akun" class="btn btn-sm btn-warning px-4 py-2 rounded-btn-sm fw-semibold d-inline-flex align-items-center gap-2 shadow-sm">
                    <i data-lucide="save" class="icon-sm"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="modalHapusAkun" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="modal-body p-4 text-center">
                <div class="mb-3 text-danger">
                    <i data-lucide="trash-2" style="width:40px;height:40px;"></i>
                </div>
                <h6 class="fw-bold mb-1">Hapus Akun?</h6>
                <p class="text-muted small mb-3">Akun <strong id="hapus-nama"></strong> akan dihapus permanen.</p>
                <form id="form-hapus-akun" method="post" action="/owner/akun-kasir/delete">
                    <input type="hidden" id="hapus-user-id" name="user_id">
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-danger rounded-btn-sm px-3">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openEditModal(user_id, full_name, user_name, phone_number, is_active) {
    document.getElementById('edit-user-id').value   = user_id;
    document.getElementById('edit-name').value      = full_name;
    document.getElementById('edit-username').value  = user_name;
    document.getElementById('edit-phone').value     = phone_number;
    document.querySelector('input[name="is_active"][value="' + is_active + '"]').checked = true;
    new bootstrap.Modal(document.getElementById('modalEditAkun')).show();
}

function openDeleteModal(user_id, full_name) {
    document.getElementById('hapus-user-id').value = user_id;
    document.getElementById('hapus-nama').textContent = full_name;
    new bootstrap.Modal(document.getElementById('modalHapusAkun')).show();
}
</script>
