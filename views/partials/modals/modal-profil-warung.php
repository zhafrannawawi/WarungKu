<!-- Modal Profil Warung -->
<?php if (($role ?? '') === 'owner'): ?>
<div class="modal fade" id="modalProfilWarung" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <form method="POST" action="/owner/store" class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <i data-lucide="store" class="text-primary"></i>
                    <h6 class="modal-title fw-bold mb-0">Profil Toko / Warung</h6>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">Nama Toko <span class="text-danger">*</span></label>
                    <input type="text" name="store_name" class="form-control" value="<?= $store_info['StoreName'] ?? '' ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">Alamat Toko</label>
                    <textarea name="address" class="form-control" rows="3" placeholder="Jl. Contoh No. 123"><?= $store_info['Address'] ?? '' ?></textarea>
                </div>
            </div>
            <div class="modal-footer border-top bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>
