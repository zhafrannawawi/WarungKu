<!-- Modal Tambah & Edit Kategori (Terpadu) -->
<div class="modal fade" id="modalFormKategori" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <form method="POST" action="/owner/kategori" id="form-kategori" class="modal-content border-0 shadow">
            <input type="hidden" name="action" id="cat-action" value="tambah">
            <input type="hidden" name="category_id" id="cat-id">
            
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-bold" id="modalFormKategoriLabel">Tambah Kategori Baru</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4">
                <label class="form-label small fw-semibold text-muted">Nama Kategori <span class="text-danger">*</span></label>
                <input type="text" name="category_name" id="cat-name" class="form-control" placeholder="Contoh: Sembako, Minuman, Snack" required autofocus>
            </div>
            
            <div class="modal-footer border-top bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>
