<!-- Modal Profil Warung -->
<div class="modal fade" id="modalProfilWarung" tabindex="-1" aria-labelledby="modalProfilWarungLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px">
        <div class="modal-content border-0 shadow-sm rounded-4 overflow-hidden">
            <!-- Modal Header -->
            <div class="modal-header border-bottom px-4 py-3 bg-white">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 bg-primary-subtle text-primary rounded-3">
                        <i data-lucide="store" class="icon-sm"></i>
                    </div>
                    <div>
                        <h6
                            class="modal-title fw-bold text-dark mb-0"
                            id="modalProfilWarungLabel">
                            Profil Warung
                        </h6>
                        <small class="text-muted" style="font-size: 0.75rem">Informasi identitas usaha & struk</small>
                    </div>
                </div>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form method="post" action="/owner/store/update">
                    <div class="mb-3">
                        <label for="warung-name" class="form-label small fw-semibold text-muted mb-1">
                            Nama Warung / Usaha <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="warung-name" name="store_name"
                            class="form-control form-control-standard"
                            value="<?= htmlspecialchars($store_data['StoreName'] ?? '') ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="warung-address" class="form-label small fw-semibold text-muted mb-1">
                            Alamat Warung
                        </label>
                        <textarea id="warung-address" name="address" rows="2" class="form-control form-control-standard" placeholder="Jl. Merdeka No.12"><?= htmlspecialchars($store_data['Address'] ?? '') ?></textarea>
                    </div>

                    <!-- Modal Footer -->
                    <div class="d-flex justify-content-between pt-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-2 rounded-btn-sm fw-medium" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-sm btn-primary px-4 py-2 rounded-btn-sm fw-semibold d-inline-flex align-items-center gap-2 shadow-sm">
                            <i data-lucide="check" class="icon-sm"></i> Simpan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>