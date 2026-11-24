<!-- Modal Edit Profil Akun & Password -->
<div class="modal fade" id="modalProfilAkun" tabindex="-1" aria-labelledby="modalProfilAkunLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <form method="POST" action="/profile/update" class="modal-content border-0 shadow-sm rounded-4 overflow-hidden">
            <!-- Modal Header -->
            <div class="modal-header border-bottom px-4 py-3 bg-white">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 bg-primary-subtle text-primary rounded-3">
                        <i data-lucide="user-cog" class="icon-sm"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-dark mb-0" id="modalProfilAkunLabel">Pengaturan Akun</h6>
                        <small class="text-muted" style="font-size: 0.75rem;">Perbarui profil pengguna &amp; kata sandi</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user_info['Fullname'] ?? '') ?>" required>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-muted mb-1">Username <span class="text-danger">*</span></label>
                        <input type="text" name="user_name" class="form-control" value="<?= htmlspecialchars($user_info['Username'] ?? '') ?>" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-muted mb-1">No. Telepon / WA</label>
                        <input type="text" name="phone_number" class="form-control" value="<?= htmlspecialchars($user_info['PhoneNumber'] ?? '') ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user_info['Email'] ?? '') ?>">
                </div>

                <hr class="my-3 text-muted">
                <small class="text-muted d-block mb-2" style="font-size: 0.75rem;">Kosongkan bagian di bawah jika tidak ingin mengganti password.</small>

                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-muted mb-1">Password Saat Ini</label>
                        <input type="password" name="old_password" class="form-control" placeholder="Password lama">
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-muted mb-1">Password Baru</label>
                        <input type="password" name="new_password" class="form-control" minlength="6" placeholder="Min. 6 karakter">
                    </div>
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
