<!-- Modal Profil Warung -->
<?php if (($role ?? '') === 'owner'): ?>
<div class="modal fade" id="modalProfilWarung" tabindex="-1" aria-labelledby="modalProfilWarungLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <form method="POST" action="/owner/store" class="modal-content border-0 shadow-sm rounded-4 overflow-hidden">
            <!-- Modal Header -->
            <div class="modal-header border-bottom px-4 py-3 bg-white">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 bg-primary-subtle text-primary rounded-3">
                        <i data-lucide="store" class="icon-sm"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-dark mb-0" id="modalProfilWarungLabel">Profil Warung</h6>
                        <small class="text-muted" style="font-size: 0.75rem;">Informasi identitas usaha &amp; struk</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Nama Toko / Usaha <span class="text-danger">*</span></label>
                    <input type="text" name="store_name" class="form-control" value="<?= htmlspecialchars($store_info['StoreName'] ?? '') ?>" placeholder="Contoh: WarungKu Sembako" required>
                </div>
                <div class="mb-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Alamat Toko</label>
                    <textarea name="address" class="form-control" rows="3" placeholder="Jl. Contoh No. 123, RT 01/RW 02"><?= htmlspecialchars($store_info['Address'] ?? '') ?></textarea>
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
<?php endif; ?>
